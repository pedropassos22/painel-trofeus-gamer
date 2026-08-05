<?php

namespace App\Actions;

use App\Core\App;
use App\Services\GameService;

class CreateGameAction
{
    public function execute(): void
    {
        $service = new GameService(
            App::get('pdo')
        );


        $service->create([

            'nome' => $_POST['nome'],

            'horas' => $_POST['horas'],

            'avaliacao' => $_POST['avaliacao'],

            'comentario' => $_POST['comentario'] ?? null

        ]);


        header('Location: /games');

        exit;
    }
}