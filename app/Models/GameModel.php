<?php

namespace App\Models;

use PDO;

class GameModel
{
    public function __construct(
        private PDO $pdo
    ) {
    }


    public function all(): array
    {
        $sql = "SELECT * FROM jogos ORDER BY nome";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }


    public function create(array $data): void
    {
        $sql = "
            INSERT INTO jogos
            (
                nome,
                horas,
                avaliacao,
                comentario
            )
            VALUES
            (
                :nome,
                :horas,
                :avaliacao,
                :comentario
            )
        ";


        $stmt = $this->pdo->prepare($sql);


        $stmt->execute([
            'nome' => $data['nome'],
            'horas' => $data['horas'],
            'avaliacao' => $data['avaliacao'],
            'comentario' => $data['comentario'] ?? null
        ]);
    }
}