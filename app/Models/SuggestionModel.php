<?php

namespace App\Models;

use PDO;

class SuggestionModel
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function exists(int $userId, string $nome): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT id
            FROM game_suggestions
            WHERE user_id = :user_id
              AND nome = :nome
            LIMIT 1
        ");

        $stmt->execute([
            'user_id' => $userId,
            'nome' => $nome
        ]);

        return (bool) $stmt->fetch();
    }

    public function create(int $userId, string $nome): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO game_suggestions (user_id, nome)
            VALUES (:user_id, :nome)
        ");

        $stmt->execute([
            'user_id' => $userId,
            'nome' => $nome
        ]);
    }

    public function allForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT nome, status, created_at
            FROM game_suggestions
            WHERE user_id = :user_id
            ORDER BY id DESC
        ");

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }
}