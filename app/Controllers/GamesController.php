<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\View;
use App\Services\GameService;

class GamesController
{
public function index(): void
{

    $service = new GameService(
        App::get('pdo')
    );

    $games = $service->getGames();

    $success = $_SESSION['flash_success'] ?? null;

    unset($_SESSION['flash_success']);

    View::render('games/index', [
        'title' => 'Jogos',
        'games' => $games,
        'success' => $success
    ]);
}

    public function create(): void
{
    $error = $_SESSION['flash_error'] ?? null;
    $oldInput = $_SESSION['old_input'] ?? [];

    unset($_SESSION['flash_error'], $_SESSION['old_input']);

    View::render('games/create', [
        'title' => 'Novo Jogo',
        'error' => $error,
        'oldInput' => $oldInput
    ]);
}

}

