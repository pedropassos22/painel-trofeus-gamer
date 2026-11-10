<?php

use App\Actions\AddGameAction;
use App\Actions\CreateSuggestionAction;
use App\Actions\DeleteGameAction;
use App\Actions\LoginAction;
use App\Actions\LogoutAction;
use App\Actions\RegisterAction;
use App\Actions\UpdateGameAction;
use App\Controllers\AuthController;
use App\Controllers\GamesController;
use App\Actions\AdminCreateGameAction;
use App\Actions\AdminUpdateGameAction;
use App\Actions\AdminDeleteGameAction;
use App\Actions\AdminApproveSuggestionAction;
use App\Actions\AdminRejectSuggestionAction;
use App\Controllers\AdminController;

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

// Minha lista de jogos
$router->get('/', [GamesController::class, 'index']);

$router->get('/games', [GamesController::class, 'index']);

// Pesquisar no catálogo e adicionar à minha lista
$router->get('/games/search', [GamesController::class, 'search']);
$router->get('/games/suggest', [GamesController::class, 'suggest']);

// Atenção: nestas duas rotas o {id} é o id do jogo no CATÁLOGO
$router->get('/games/{id}/add', [GamesController::class, 'addForm']);
$router->post('/games/{id}/add', [AddGameAction::class, 'execute']);

// Nestas, o {id} é o id do jogo na MINHA LISTA
$router->get('/games/{id}/edit', [GamesController::class, 'edit']);
$router->post('/games/{id}/edit', [UpdateGameAction::class, 'execute']);
$router->post('/games/{id}/delete', [DeleteGameAction::class, 'execute']);

// Indicação de jogos que não existem no sistema
$router->post('/suggestions', [CreateSuggestionAction::class, 'execute']);


// Administração: catálogo de jogos (só admin, o bloqueio já está no public/index.php)
$router->get('/admin', [AdminController::class, 'games']);
$router->get('/admin/games', [AdminController::class, 'games']);
$router->get('/admin/games/new', [AdminController::class, 'createForm']);
$router->post('/admin/games', [AdminCreateGameAction::class, 'execute']);
$router->get('/admin/games/{id}/edit', [AdminController::class, 'editForm']);
$router->post('/admin/games/{id}/edit', [AdminUpdateGameAction::class, 'execute']);
$router->post('/admin/games/{id}/delete', [AdminDeleteGameAction::class, 'execute']);

// Administração: indicações de jogos
$router->get('/admin/suggestions', [AdminController::class, 'suggestions']);
$router->post('/admin/suggestions/{id}/approve', [AdminApproveSuggestionAction::class, 'execute']);
$router->post('/admin/suggestions/{id}/reject', [AdminRejectSuggestionAction::class, 'execute']);