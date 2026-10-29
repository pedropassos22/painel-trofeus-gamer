<?php

namespace App\Models;

use PDO;

class AdminSuggestionModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("
            SELECT s.id, s.nome, s.status, s.created_at, u.nome AS usuario
            FROM game_suggestions s
            INNER JOIN users u ON u.id = s.user_id
            ORDER BY (s.status = 'pendente') DESC, s.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nome, status FROM game_suggestions WHERE id = :id'
        );

        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function reject(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE game_suggestions SET status = 'recusada' WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);
    }

    // Marca como aprovadas todas as indicações pendentes com esse nome
    // (outros usuários podem ter indicado o mesmo jogo)
    public function approveByName(string $nome): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE game_suggestions SET status = 'aprovada'
             WHERE nome = :nome AND status = 'pendente'"
        );

        $stmt->execute(['nome' => $nome]);
    }
}