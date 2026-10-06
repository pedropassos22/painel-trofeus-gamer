<?php

namespace App\Actions;

use App\Core\App;
use App\Core\Csrf;
use App\Services\GameService;

class DeleteGameAction
{
    public function execute(int $id): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        $service = new GameService(
            App::get('pdo')
        );

        $service->delete($id);

        $_SESSION['flash_success'] = 'Jogo excluído com sucesso.';

        header('Location: ' . BASE_URL . '/games');

        exit;
    }
}