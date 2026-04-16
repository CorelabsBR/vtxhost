<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;
use App\Models\User;

final class AdminController extends Controller
{
    private Product $products;

    public function __construct()
    {
        $this->products = new Product();
    }

    public function dashboard(): void
    {
        Auth::ensureRoot();

        $this->view('admin/dashboard', [
            'products' => $this->products->all(),
            'users' => (new User())->all(),
        ]);
    }

    public function storeProduct(): void
    {
        Auth::ensureRoot();
        $this->guardRequest();

        $this->products->create($this->productPayload());

        Session::flash('success', 'Produto criado com sucesso.');
        $this->redirect('/admin');
    }

    public function updateProduct(): void
    {
        Auth::ensureRoot();
        $this->guardRequest();

        $payload = $this->productPayload();
        $payload['id'] = (int) ($_POST['id'] ?? 0);

        $this->products->update($payload);

        Session::flash('success', 'Produto atualizado com sucesso.');
        $this->redirect('/admin');
    }

    public function deleteProduct(): void
    {
        Auth::ensureRoot();
        $this->guardRequest();

        $this->products->delete((int) ($_POST['id'] ?? 0));

        Session::flash('success', 'Produto removido com sucesso.');
        $this->redirect('/admin');
    }

    private function guardRequest(): void
    {
        if (! Session::validateCsrf($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Token inválido.');
            $this->redirect('/admin');
        }
    }

    private function productPayload(): array
    {
        $category = trim((string) ($_POST['category'] ?? 'host'));
        $location = trim((string) ($_POST['location'] ?? 'brasil'));

        if ($category === 'cpanel') {
            $location = 'brasil';
        }

        return [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'category' => $category,
            'location' => $location,
            'cpu' => trim((string) ($_POST['cpu'] ?? '')),
            'ram' => trim((string) ($_POST['ram'] ?? '')),
            'storage' => trim((string) ($_POST['storage'] ?? '')),
            'bandwidth' => trim((string) ($_POST['bandwidth'] ?? '')),
            'ddos_protection' => trim((string) ($_POST['ddos_protection'] ?? 'Inclusa')),
            'price_monthly' => (float) ($_POST['price_monthly'] ?? 0),
            'highlight' => trim((string) ($_POST['highlight'] ?? 'Ativação imediata')),
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];
    }
}