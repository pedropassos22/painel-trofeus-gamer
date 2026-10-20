<?php

namespace App\Actions;

use App\Core\App;
use App\Core\Auth;
use App\Core\Csrf;
use App\Services\AuthService;
use InvalidArgumentException;

class LoginAction
{
    public function execute(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        $email = (string) ($_POST['email'] ?? '');
        $senha = (string) ($_POST['senha'] ?? '');

        try {
            $service = new AuthService(App::get('pdo'));

            $user = $service->attempt($email, $senha);

            Auth::login((int) $user['id'], (string) $user['nome']);

            header('Location: ' . BASE_URL . '/games');

            exit;
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            $_SESSION['old_input'] = ['email' => $email];

            header('Location: ' . BASE_URL . '/login');

            exit;
        }
    }
}