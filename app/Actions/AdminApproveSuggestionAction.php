<?php

namespace App\Actions;

use App\Core\App;
use App\Core\Csrf;
use App\Services\AdminSuggestionService;
use InvalidArgumentException;

class AdminApproveSuggestionAction
{
    public function execute(int $id): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        try {
            (new AdminSuggestionService(App::get('pdo')))->approve($id);

            $_SESSION['flash_success'] = 'Indicação aprovada: o jogo foi adicionado ao catálogo.';
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/admin/suggestions');

        exit;
    }
}