<?php

namespace App\Actions;

use App\Core\App;
use App\Core\Csrf;
use App\Services\AdminGameService;
use InvalidArgumentException;

class AdminDeleteGameAction
{
    public function execute(int $id): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        try {
            (new AdminGameService(App::get('pdo')))->delete($id);

            $_SESSION['flash_success'] = 'Jogo removido do catálogo.';
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/admin/games');

        exit;
    }
}