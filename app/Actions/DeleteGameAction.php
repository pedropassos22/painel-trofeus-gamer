<?php

namespace App\Actions;

use App\Core\App;
use App\Core\Auth;
use App\Core\Csrf;
use App\Services\GameService;
use InvalidArgumentException;

class DeleteGameAction
{
    public function execute(int $id): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        try {
            $service = new GameService(App::get('pdo'));

            $service->delete((int) Auth::id(), $id);

            $_SESSION['flash_success'] = 'Jogo removido da sua lista.';
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/games');

        exit;
    }
}