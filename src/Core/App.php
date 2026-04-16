<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CommerceController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;

final class App
{
    private Router $router;

    public function __construct(private readonly string $basePath)
    {
        require_once $this->basePath . '/src/Core/helpers.php';

        Session::start();

        $this->router = new Router();
        $this->registerRoutes();
    }

    public function run(): void
    {
        $this->router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
    }

    private function registerRoutes(): void
    {
        $home = new HomeController();
        $products = new ProductController();
        $auth = new AuthController();
        $admin = new AdminController();
        $commerce = new CommerceController();

        $this->router->get('/', [$home, 'index']);
        $this->router->get('/cpanel', [$products, 'cpanel']);
        $this->router->get('/host', [$products, 'hosting']);
        $this->router->get('/vps', [$products, 'vps']);
        $this->router->get('/login', [$auth, 'showLogin']);
        $this->router->post('/login', [$auth, 'login']);
        $this->router->get('/registro', [$auth, 'showRegister']);
        $this->router->post('/registro', [$auth, 'register']);
        $this->router->post('/logout', [$auth, 'logout']);
        $this->router->get('/conta', [$auth, 'account']);

        $this->router->get('/carrinho', [$commerce, 'cart']);
        $this->router->post('/carrinho/adicionar', [$commerce, 'addToCart']);
        $this->router->post('/carrinho/remover', [$commerce, 'removeFromCart']);
        $this->router->post('/checkout', [$commerce, 'checkout']);
        $this->router->get('/checkout/sucesso', [$commerce, 'checkoutSuccess']);
        $this->router->get('/checkout/pendente', [$commerce, 'checkoutPending']);
        $this->router->get('/checkout/falha', [$commerce, 'checkoutFailure']);
        $this->router->post('/webhook/mercadopago', [$commerce, 'mercadoPagoWebhook']);
        $this->router->get('/webhook/mercadopago', [$commerce, 'mercadoPagoWebhook']);
        $this->router->get('/servicos/acessar', [$commerce, 'accessService']);

        $this->router->get('/admin', [$admin, 'dashboard']);
        $this->router->post('/admin/products/create', [$admin, 'storeProduct']);
        $this->router->post('/admin/products/update', [$admin, 'updateProduct']);
        $this->router->post('/admin/products/delete', [$admin, 'deleteProduct']);
    }
}