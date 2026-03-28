<?php

namespace App\Core;

abstract class Controller
{
    protected function render($view, $data = [], $layout = 'main')
    {
        extract($data);
        
        $viewPath = __DIR__ . "/../../interface_frontend/pages/" . $view . ".html";
        
        if (!file_exists($viewPath)) {
            die("View $view not found at $viewPath");
        }

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        $layoutPath = __DIR__ . "/../../interface_frontend/pages/layouts/" . $layout . ".html";
        
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $content;
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}
