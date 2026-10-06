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



        public function find(int $id): ?array
    {
        $sql = "
            SELECT *
            FROM jogos
            WHERE id = :id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $game = $stmt->fetch();

        return $game ?: null;
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

    public function update(int $id, array $data): void
{
    $sql = "
        UPDATE jogos
        SET
            nome = :nome,
            horas = :horas,
            avaliacao = :avaliacao,
            comentario = :comentario
        WHERE id = :id
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'id' => $id,
        'nome' => $data['nome'],
        'horas' => $data['horas'],
        'avaliacao' => $data['avaliacao'],
        'comentario' => $data['comentario'] ?? null
    ]);
}

public function delete(int $id): void
{
    $sql = "
        DELETE FROM jogos
        WHERE id = :id
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);
}

}