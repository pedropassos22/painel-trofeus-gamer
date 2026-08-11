<?php

namespace App\Actions;


use App\Core\App;
use App\Core\Csrf;
use App\Services\GameService;
use InvalidArgumentException;

class CreateGameAction
{
    public function execute(): void
    {

    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        http_response_code(403);

        exit('Token CSRF inválido.');
    }


        $data = [
            'nome' => $_POST['nome'] ?? '',
            'horas' => $_POST['horas'] ?? null,
            'avaliacao' => $_POST['avaliacao'] ?? null,
            'comentario' => $_POST['comentario'] ?? null
        ];

        try {
            $service = new GameService(
                App::get('pdo')
            );

            $service->create($data);

            $_SESSION['flash_success'] = 'Jogo cadastrado com sucesso.';

            header('Location: ' . BASE_URL . '/games');

            exit;
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            $_SESSION['old_input'] = $data;

            header('Location: ' . BASE_URL . '/games/create');

            exit;
        }
    }
}