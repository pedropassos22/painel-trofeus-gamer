<?php

namespace App\Models;

use PDO;

class AdminGameModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT g.id, g.nome, g.capa_url, g.external_id,
                    (SELECT COUNT(*) FROM user_games ug WHERE ug.game_id = g.id) AS total_usuarios
             FROM games g
             ORDER BY g.nome'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nome, capa_url, external_id FROM games WHERE id = :id'
        );

        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function nameExists(string $nome, int $exceptId = 0): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM games WHERE nome = :nome AND id <> :id'
        );

        $stmt->execute(['nome' => $nome, 'id' => $exceptId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(string $nome, ?string $capaUrl): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO games (nome, capa_url) VALUES (:nome, :capa)'
        );

        $stmt->execute(['nome' => $nome, 'capa' => $capaUrl]);
    }

    public function update(int $id, string $nome, ?string $capaUrl): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE games SET nome = :nome, capa_url = :capa WHERE id = :id'
        );

        $stmt->execute(['nome' => $nome, 'capa' => $capaUrl, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM games WHERE id = :id');

        $stmt->execute(['id' => $id]);
    }
}