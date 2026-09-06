<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

abstract class Controller
{
    protected function requireLogin(): void
    {
        if (!is_logged_in()) {
            set_flash('error', 'Faça login para acessar esta página.');
            redirect('/login');
        }
    }

    protected function render(string $view, array $data = [], ?string $layout = 'main'): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        $viewPath = dirname(__DIR__) . '/views/' . $view . '.php';

        if (!is_file($viewPath)) {
            throw new RuntimeException("View not found: $view");
        }

        require $viewPath;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutPath = dirname(__DIR__) . '/views/layouts/' . $layout . '.php';

        if (!is_file($layoutPath)) {
            echo $content;
            return;
        }

        require $layoutPath;
    }
}