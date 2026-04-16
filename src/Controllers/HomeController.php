<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

final class HomeController extends Controller
{
    public function index(): void
    {
        $productModel = new Product();

        $this->view('home/index', [
            'featuredProducts' => $productModel->featured(),
            'hostProducts' => $productModel->byCategory('host'),
            'vpsProducts' => $productModel->byCategory('vps'),
            'cpanelProducts' => $productModel->byCategory('cpanel'),
        ]);
    }
}