<?php

declare(strict_types=1);

namespace App\Core;

class BaseController
{
    protected function renderHTML(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewFile = VIEWS_PATH . '/' . ltrim($view, '/') . '.php';
        if (!is_file($viewFile)) {
            throw new HttpException(500, 'View not found: ' . $view);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $viewFile;
        $content = (string) ob_get_clean();

        $layoutFile = VIEWS_PATH . '/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            throw new HttpException(500, 'Layout not found: ' . $layout);
        }

        include $layoutFile;
    }

    protected function redirect(string $url, int $statusCode = 302): void
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    public function showError(string $message, int $statusCode = 500): void
    {
        http_response_code($statusCode);

        $errorView = VIEWS_PATH . '/errors/' . $statusCode . '.php';
        if (!is_file($errorView)) {
            $errorView = VIEWS_PATH . '/errors/error.php';
        }

        $title = sprintf('%d Error', $statusCode);

        ob_start();
        include $errorView;
        $content = (string) ob_get_clean();

        $layoutFile = VIEWS_PATH . '/layouts/main.php';
        if (is_file($layoutFile)) {
            include $layoutFile;
            return;
        }

        echo $content;
    }

    protected function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}
