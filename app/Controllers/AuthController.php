<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;

class AuthController
{
    public function showLogin(): void
    {
        $this->redirectIfLogged();

        View::render('auth/login', $this->flashData('Entrar'));
    }

    public function showRegister(): void
    {
        $this->redirectIfLogged();

        View::render('auth/register', $this->flashData('Criar conta'));
    }

    private function redirectIfLogged(): void
    {
        if (Auth::check()) {
            header('Location: ' . BASE_URL . '/games');
            exit;
        }
    }

    private function flashData(string $title): array
    {
        $data = [
            'title' => $title,
            'error' => $_SESSION['flash_error'] ?? null,
            'oldInput' => $_SESSION['old_input'] ?? []
        ];

        unset($_SESSION['flash_error'], $_SESSION['old_input']);

        return $data;
    }
}