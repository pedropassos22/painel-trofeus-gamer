<?php

namespace App\Actions;

use App\Core\App;
use App\Core\Auth;
use App\Core\Csrf;
use App\Services\AuthService;
use InvalidArgumentException;

class RegisterAction
{
    public function execute(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        $data = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'senha' => $_POST['senha'] ?? '',
            'senha_confirmacao' => $_POST['senha_confirmacao'] ?? ''
        ];

        try {
            $service = new AuthService(App::get('pdo'));

            $user = $service->register($data);

            Auth::login((int) $user['id'], (string) $user['nome']);

            $_SESSION['flash_success'] = 'Conta criada com sucesso. Bem-vindo!';

            header('Location: ' . BASE_URL . '/games');

            exit;
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            $_SESSION['old_input'] = [
                'nome' => (string) $data['nome'],
                'email' => (string) $data['email']
            ];

            header('Location: ' . BASE_URL . '/register');

            exit;
        }
    }
}