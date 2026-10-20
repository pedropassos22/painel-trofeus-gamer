<?php

use App\Actions\CreateGameAction;
use App\Actions\DeleteGameAction;
use App\Actions\LoginAction;
use App\Actions\LogoutAction;
use App\Actions\RegisterAction;
use App\Actions\UpdateGameAction;
use App\Controllers\AuthController;
use App\Controllers\GamesController;

/*
|--------------------------------------------------------------------------
| Rotas da aplicação
|--------------------------------------------------------------------------
*/

// Login e cadastro
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [LoginAction::class, 'execute']);

$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [RegisterAction::class, 'execute']);

$router->post('/logout', [LogoutAction::class, 'execute']);

// Jogos
$router->get('/', [GamesController::class, 'index']);

$router->get('/games', [GamesController::class, 'index']);

$router->get('/games/create', [GamesController::class, 'create']);

$router->get('/games/{id}/edit', [GamesController::class, 'edit']);

$router->post('/games/{id}/edit', [UpdateGameAction::class, 'execute']);

$router->post('/games/{id}/delete', [DeleteGameAction::class, 'execute']);

$router->post('/games/create', [CreateGameAction::class, 'execute']);