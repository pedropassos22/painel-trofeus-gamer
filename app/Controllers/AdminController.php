<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\View;
use App\Services\AdminGameService;
use App\Services\AdminSuggestionService;

class AdminController
{
    private function service(): AdminGameService
    {
        return new AdminGameService(App::get('pdo'));
    }

    public function games(): void
    {
        $success = $_SESSION['flash_success'] ?? null;
        $error = $_SESSION['flash_error'] ?? null;

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        View::render('admin/games/index', [
            'title' => 'Administração - Jogos',
            'games' => $this->service()->all(),
            'success' => $success,
            'error' => $error
        ]);
    }

    public function createForm(): void
    {
        $error = $_SESSION['flash_error'] ?? null;
        $oldInput = $_SESSION['old_input'] ?? [];

        unset($_SESSION['flash_error'], $_SESSION['old_input']);

        View::render('admin/games/form', [
            'title' => 'Novo jogo',
            'heading' => 'Cadastrar jogo no catálogo',
            'action' => BASE_URL . '/admin/games',
            'game' => $oldInput,
            'error' => $error
        ]);
    }

    public function editForm(int $id): void
    {
        $game = $this->service()->find($id);

        if (!$game) {
            http_response_code(404);

            echo 'Jogo não encontrado.';

            return;
        }

        $error = $_SESSION['flash_error'] ?? null;
        $oldInput = $_SESSION['old_input'] ?? [];

        unset($_SESSION['flash_error'], $_SESSION['old_input']);

        $game = array_merge($game, array_intersect_key($oldInput, array_flip(['nome', 'capa_url'])));

        View::render('admin/games/form', [
            'title' => 'Editar jogo',
            'heading' => 'Editar jogo do catálogo',
            'action' => BASE_URL . '/admin/games/' . $id . '/edit',
            'game' => $game,
            'error' => $error
        ]);
    }

    public function suggestions(): void
    {
        $success = $_SESSION['flash_success'] ?? null;
        $error = $_SESSION['flash_error'] ?? null;

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        View::render('admin/suggestions/index', [
            'title' => 'Administração - Indicações',
            'suggestions' => (new AdminSuggestionService(App::get('pdo')))->all(),
            'success' => $success,
            'error' => $error
        ]);
    }
}