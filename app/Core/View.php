<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);

        ob_start();

        require APP_PATH . '/Views/' . $view . '.php';

        $content = ob_get_clean();

        require APP_PATH . '/Views/layouts/app.php';
    }
}