<?php

use App\Controllers\GamesController;
use App\Actions\CreateGameAction;

/*
|--------------------------------------------------------------------------
| Rotas da aplicação
|--------------------------------------------------------------------------
*/

$router->get('/', [GamesController::class, 'index']);

$router->get('/games', [GamesController::class, 'index']);

$router->get('/games/create', [GamesController::class, 'create']);

$router->post('/games/create', [CreateGameAction::class, 'execute']);