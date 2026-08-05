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

        View::render('games/index', [
            'title' => 'Jogos',
            'games' => $games
        ]);
    }

    public function create(): void
{
    View::render('games/create', [
        'title' => 'Novo Jogo'
    ]);
}

}

