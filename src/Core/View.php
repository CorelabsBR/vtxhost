<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = []): void
    {
        $viewPath = dirname(__DIR__, 2) . '/views/' . $view . '.php';

        if (! file_exists($viewPath)) {
            throw new \RuntimeException('View nao encontrada: ' . $view);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = (string) ob_get_clean();

        require dirname(__DIR__, 2) . '/views/layouts/header.php';
        echo $content;
        require dirname(__DIR__, 2) . '/views/layouts/footer.php';
    }
}