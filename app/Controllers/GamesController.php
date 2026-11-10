<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Auth;
use App\Core\View;
use App\Services\GameService;
use App\Services\SuggestionService;
use App\Services\IgdbService;

class GamesController
{
    private function service(): GameService
    {
        return new GameService(App::get('pdo'));
    }

    public function index(): void
    {
        $games = $this->service()->getGames((int) Auth::id());

        $success = $_SESSION['flash_success'] ?? null;
        $error = $_SESSION['flash_error'] ?? null;

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        View::render('games/index', [
            'title' => 'Jogos',
            'games' => $games,
            'success' => $success,
            'error' => $error
        ]);
    }

    public function search(): void
    {
        $term = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';

        $results = $this->service()->search($term);

        $suggestions = (new SuggestionService(App::get('pdo')))
            ->getForUser((int) Auth::id());

        $needle = mb_strtolower($term);

        $exactMatch = false;

        foreach ($results as $result) {
            if (mb_strtolower($result['nome']) === $needle) {
                $exactMatch = true;
            }
        }

        $alreadySuggested = false;

        foreach ($suggestions as $suggestion) {
            if (mb_strtolower($suggestion['nome']) === $needle) {
                $alreadySuggested = true;
            }
        }

        $success = $_SESSION['flash_success'] ?? null;
        $error = $_SESSION['flash_error'] ?? null;

        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        View::render('games/search', [
            'title' => 'Adicionar jogo',
            'term' => $term,
            'results' => $results,
            'suggestions' => $suggestions,
            'showSuggestion' => $term !== '' && !$exactMatch && !$alreadySuggested,
            'alreadySuggested' => $alreadySuggested && !$exactMatch,
            'success' => $success,
            'error' => $error
        ]);
    }

        public function suggest(): void
    {
        // Libera a sessão para não travar as próximas buscas enquanto esta espera a IGDB
        session_write_close();

        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');

        $term = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';

        if (mb_strlen($term) < 3) {
            echo json_encode(['ok' => true, 'tooMany' => false, 'items' => []]);

            return;
        }

        try {
            $found = (new IgdbService())->search($term);
        } catch (\Throwable $e) {
            http_response_code(502);

            echo json_encode(['ok' => false, 'tooMany' => false, 'items' => []]);

            return;
        }

        // Regra combinada: só mostra sugestões se houver 10 títulos ou menos
        if (count($found) > 10) {
            echo json_encode(['ok' => true, 'tooMany' => true, 'items' => []]);

            return;
        }

        $items = [];

        foreach ($found as $game) {
            $items[] = [
                'external_id' => $game['external_id'],
                'nome' => $game['nome'],
                'ano_lancamento' => $game['ano_lancamento'],
                'lancamento_status' => $game['lancamento_status']
            ];
        }

        echo json_encode(
            ['ok' => true, 'tooMany' => false, 'items' => $items],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function addForm(int $id): void
    {
        $game = $this->service()->getCatalogGame($id);

        if (!$game) {
            http_response_code(404);

            echo 'Jogo não encontrado.';

            return;
        }

        $error = $_SESSION['flash_error'] ?? null;
        $oldInput = $_SESSION['old_input'] ?? [];

        unset($_SESSION['flash_error'], $_SESSION['old_input']);

        View::render('games/add', [
            'title' => 'Adicionar jogo',
            'game' => $game,
            'error' => $error,
            'oldInput' => $oldInput
        ]);
    }

    public function edit(int $id): void
    {
        $game = $this->service()->getGame((int) Auth::id(), $id);

        if (!$game) {
            http_response_code(404);

            echo 'Jogo não encontrado.';

            return;
        }

        $error = $_SESSION['flash_error'] ?? null;
        $oldInput = $_SESSION['old_input'] ?? [];

        unset($_SESSION['flash_error'], $_SESSION['old_input']);

        $editable = array_flip(['status', 'horas', 'avaliacao', 'comentario']);

        $game = array_merge($game, array_intersect_key($oldInput, $editable));

        View::render('games/edit', [
            'title' => 'Editar Jogo',
            'game' => $game,
            'error' => $error
        ]);
    }
}