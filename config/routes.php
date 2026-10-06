<?php

use App\Actions\UpdateGameAction;
use App\Controllers\GamesController;
use App\Actions\CreateGameAction;
use App\Actions\DeleteGameAction;

/*
|--------------------------------------------------------------------------
| Rotas da aplicação
|--------------------------------------------------------------------------
*/

$router->get('/', [GamesController::class, 'index']);

$router->get('/games', [GamesController::class, 'index']);

$router->get('/games/create', [GamesController::class, 'create']);

$router->get('/games/{id}/edit', [GamesController::class, 'edit']);

$router->post('/games/{id}/edit', [UpdateGameAction::class, 'execute']);

$router->post('/games/{id}/delete', [DeleteGameAction::class, 'execute']);

$router->post('/games/create', [CreateGameAction::class, 'execute']);