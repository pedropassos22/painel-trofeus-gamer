<?php

namespace App\Actions;

use App\Core\Auth;
use App\Core\Csrf;

class LogoutAction
{
    public function execute(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        Auth::logout();

        header('Location: ' . BASE_URL . '/login');

        exit;
    }
}