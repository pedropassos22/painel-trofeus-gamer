<?php

namespace App\Services;

use App\Models\GameModel;
use PDO;

class GameService
{
    private GameModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new GameModel($pdo);
    }


    public function getGames(): array
    {
        return $this->model->all();
    }


    public function create(array $data): void
    {
        $this->model->create($data);
    }
}