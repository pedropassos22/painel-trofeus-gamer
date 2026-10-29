<?php

namespace App\Actions;

use App\Core\App;
use App\Core\Auth;
use App\Core\Csrf;
use App\Services\SuggestionService;
use InvalidArgumentException;

class CreateSuggestionAction
{
    public function execute(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            exit('Token CSRF inválido.');
        }

        $nome = is_string($_POST['nome'] ?? null) ? $_POST['nome'] : '';

        try {
            $service = new SuggestionService(App::get('pdo'));

            $service->create((int) Auth::id(), $nome);

            $_SESSION['flash_success'] = 'Indicação enviada! Os administradores vão analisar.';
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/games/search?q=' . urlencode($nome));

        exit;
    }
}