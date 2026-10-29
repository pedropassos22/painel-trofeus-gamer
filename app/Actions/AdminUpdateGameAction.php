<?php

namespace App\Actions;

use App\Core\App;
use App\Core\Csrf;
use App\Services\AdminGameService;
use InvalidArgumentException;

class AdminUpdateGameAction
{
    public function execute(int $id): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        $nome = is_string($_POST['nome'] ?? null) ? $_POST['nome'] : '';
        $capa = is_string($_POST['capa_url'] ?? null) ? $_POST['capa_url'] : '';

        try {
            (new AdminGameService(App::get('pdo')))->update($id, $nome, $capa);

            $_SESSION['flash_success'] = 'Jogo atualizado.';

            header('Location: ' . BASE_URL . '/admin/games');

            exit;
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            $_SESSION['old_input'] = ['nome' => $nome, 'capa_url' => $capa];

            header('Location: ' . BASE_URL . '/admin/games/' . $id . '/edit');

            exit;
        }
    }
}