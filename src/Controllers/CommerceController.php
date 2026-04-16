<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Crypto;
use App\Core\Session;
use App\Models\Cart;
use App\Models\HostedService;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\MercadoPagoService;
use App\Services\ProvisioningService;

final class CommerceController extends Controller
{
    private Cart $carts;
    private Product $products;
    private Order $orders;

    public function __construct()
    {
        $this->carts = new Cart();
        $this->products = new Product();
        $this->orders = new Order();
    }

    public function addToCart(): void
    {
        Auth::ensureAuthenticated();

        if (! Session::validateCsrf($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Token invalido.');
            $this->redirect('/host');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = max(1, (int) ($_POST['quantity'] ?? 1));
        $product = $this->products->find($productId);

        if (! $product) {
            Session::flash('error', 'Plano nao encontrado.');
            $this->redirect('/host');
        }

        $user = Auth::user();
        if (! $user) {
            $this->redirect('/login');
        }

        $cart = $this->carts->getOrCreateOpen((int) $user['id']);
        $this->carts->addItem((int) $cart['id'], $productId, (float) $product['price_monthly'], $qty);

        Session::flash('success', 'Plano adicionado ao carrinho.');
        $this->redirect('/carrinho');
    }

    public function cart(): void
    {
        Auth::ensureAuthenticated();

        $user = Auth::user();
        if (! $user) {
            $this->redirect('/login');
        }

        $cart = $this->carts->getOrCreateOpen((int) $user['id']);
        $items = $this->carts->items((int) $cart['id']);

        $this->view('commerce/cart', [
            'cart' => $cart,
            'items' => $items,
            'total' => $this->carts->total((int) $cart['id']),
        ]);
    }

    public function removeFromCart(): void
    {
        Auth::ensureAuthenticated();

        if (! Session::validateCsrf($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Token invalido.');
            $this->redirect('/carrinho');
        }

        $itemId = (int) ($_POST['item_id'] ?? 0);
        $user = Auth::user();
        if (! $user) {
            $this->redirect('/login');
        }

        $cart = $this->carts->getOrCreateOpen((int) $user['id']);
        $this->carts->removeItem((int) $cart['id'], $itemId);

        Session::flash('success', 'Item removido do carrinho.');
        $this->redirect('/carrinho');
    }

    public function checkout(): void
    {
        Auth::ensureAuthenticated();

        if (! Session::validateCsrf($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Token invalido.');
            $this->redirect('/carrinho');
        }

        $user = Auth::user();
        if (! $user) {
            $this->redirect('/login');
        }

        $sitePassword = (string) ($_POST['account_password'] ?? '');
        if ($sitePassword === '' || ! (new User())->verifyPassword((string) $user['email'], $sitePassword)) {
            Session::flash('error', 'Confirme sua senha da conta para provisionar no painel automaticamente.');
            $this->redirect('/carrinho');
        }

        $cart = $this->carts->getOrCreateOpen((int) $user['id']);
        $items = $this->carts->items((int) $cart['id']);

        if ($items === []) {
            Session::flash('error', 'Seu carrinho esta vazio.');
            $this->redirect('/carrinho');
        }

        $orderId = $this->orders->createFromCart(
            (int) $user['id'],
            (int) $cart['id'],
            $items,
            Crypto::encrypt($sitePassword)
        );
        $order = $this->orders->findById($orderId);

        if (! $order) {
            Session::flash('error', 'Falha ao criar pedido.');
            $this->redirect('/carrinho');
        }

        $mp = new MercadoPagoService();
        if (! $mp->enabled()) {
            Session::flash('error', 'Mercado Pago nao configurado (MP_ACCESS_TOKEN).');
            $this->redirect('/carrinho');
        }

        try {
            $pref = $mp->createPreference($order, $this->orders->items($orderId));
            $this->orders->setMercadoPagoPreference($orderId, (string) ($pref['id'] ?? ''));
            $this->carts->markConverted((int) $cart['id']);

            $initPoint = (string) ($pref['init_point'] ?? '');
            if ($initPoint === '') {
                throw new \RuntimeException('Checkout sem init_point.');
            }

            header('Location: ' . $initPoint);
            exit;
        } catch (\Throwable $e) {
            Session::flash('error', 'Falha ao iniciar pagamento: ' . $e->getMessage());
            $this->redirect('/carrinho');
        }
    }

    public function checkoutSuccess(): void
    {
        Auth::ensureAuthenticated();
        Session::flash('success', 'Pagamento aprovado. Seu servico sera provisionado automaticamente em instantes.');
        $this->redirect('/conta');
    }

    public function checkoutPending(): void
    {
        Auth::ensureAuthenticated();
        Session::flash('success', 'Pagamento pendente. Assim que confirmado, o servico sera provisionado.');
        $this->redirect('/conta');
    }

    public function checkoutFailure(): void
    {
        Auth::ensureAuthenticated();
        Session::flash('error', 'Pagamento nao concluido. Tente novamente.');
        $this->redirect('/carrinho');
    }

    public function mercadoPagoWebhook(): void
    {
        $raw = file_get_contents('php://input');
        $body = json_decode((string) $raw, true);

        $topic = $_GET['topic'] ?? $_GET['type'] ?? ($body['type'] ?? '');
        $paymentId = (string) ($_GET['id'] ?? ($body['data']['id'] ?? ''));

        if ($topic !== 'payment' || $paymentId === '') {
            http_response_code(200);
            echo 'ignored';
            return;
        }

        try {
            $mp = new MercadoPagoService();
            $payment = $mp->getPayment($paymentId);

            $status = (string) ($payment['status'] ?? '');
            $externalRef = (string) ($payment['external_reference'] ?? '');

            if ($status !== 'approved' || $externalRef === '') {
                http_response_code(200);
                echo 'not-approved';
                return;
            }

            $order = $this->orders->markPaidByExternalReference($externalRef, $paymentId);
            if (! $order) {
                http_response_code(200);
                echo 'order-not-found';
                return;
            }

            $password = Crypto::decrypt((string) ($order['provision_password_enc'] ?? ''));
            if ($password === '') {
                $password = (string) ($_ENV['PTERODACTYL_FALLBACK_PASSWORD'] ?? 'ChangeMe123!');
            }

            (new ProvisioningService())->provisionOrder((int) $order['id'], $password);

            http_response_code(200);
            echo 'ok';
        } catch (\Throwable $e) {
            http_response_code(500);
            echo 'error';
        }
    }

    public function accessService(): void
    {
        Auth::ensureAuthenticated();

        $serviceId = (int) ($_GET['service'] ?? 0);
        $user = Auth::user();
        if (! $user) {
            $this->redirect('/login');
        }

        $service = (new HostedService())->findByIdAndUser($serviceId, (int) $user['id']);
        if (! $service) {
            Session::flash('error', 'Servico nao encontrado.');
            $this->redirect('/conta');
        }

        $panelBase = rtrim((string) ($_ENV['PTERODACTYL_PANEL_URL'] ?? ''), '/');
        if ($panelBase === '') {
            Session::flash('error', 'Pterodactyl nao configurado.');
            $this->redirect('/conta');
        }

        $this->view('commerce/pterodactyl-redirect', [
            'service' => $service,
            'panelLoginUrl' => $panelBase . '/auth/login',
            'panelPassword' => Crypto::decrypt($service['panel_password_enc'] ?? null),
        ]);
    }
}
