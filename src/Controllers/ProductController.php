<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

final class ProductController extends Controller
{
    private Product $products;

    public function __construct()
    {
        $this->products = new Product();
    }

    public function cpanel(): void
    {
        $this->viewCategory('cpanel', 'cPanel Brasil', 'Hospedagem com cPanel oficial no Brasil para sites, e-mails e projetos comerciais.');
    }

    public function hosting(): void
    {
        $this->viewCategory('host', 'Hospedagem Web', 'Planos de hospedagem para sites com presença no Brasil e Canadá, SSL incluso e ativação rápida.');
    }

    public function vps(): void
    {
        $this->viewCategory('vps', 'VPS Premium', 'Servidores virtuais com root total, discos NVMe e opções no Brasil e Canadá.');
    }

    private function viewCategory(string $category, string $title, string $description): void
    {
        $this->view('products/list', [
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'products' => $this->products->byCategory($category),
        ]);
    }
}