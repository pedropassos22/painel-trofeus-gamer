<?php

namespace App\Models;

use PDO;

class GameModel
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function allForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                ug.id,
                g.nome,
                ug.status,
                ug.horas,
                ug.avaliacao,
                ug.comentario
            FROM user_games ug
            INNER JOIN games g ON g.id = ug.game_id
            WHERE ug.user_id = :user_id
            ORDER BY g.nome
        ");

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function findForUser(int $userId, int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                ug.id,
                g.nome,
                ug.status,
                ug.horas,
                ug.avaliacao,
                ug.comentario
            FROM user_games ug
            INNER JOIN games g ON g.id = ug.game_id
            WHERE ug.id = :id
              AND ug.user_id = :user_id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);

        $game = $stmt->fetch();

        return $game ?: null;
    }

    public function searchCatalog(string $term): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, nome
            FROM games
            WHERE nome LIKE :term
            ORDER BY nome
            LIMIT 20
        ");

        $stmt->execute([
            'term' => '%' . addcslashes($term, '%_\\') . '%'
        ]);

        return $stmt->fetchAll();
    }

    public function findCatalogGame(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nome FROM games WHERE id = :id LIMIT 1"
        );

        $stmt->execute(['id' => $id]);

        $game = $stmt->fetch();

        return $game ?: null;
    }

    public function catalogNameExists(string $nome): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM games WHERE nome = :nome LIMIT 1"
        );

        $stmt->execute(['nome' => $nome]);

        return (bool) $stmt->fetch();
    }

    public function userHasGame(int $userId, int $gameId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT id
            FROM user_games
            WHERE user_id = :user_id
              AND game_id = :game_id
            LIMIT 1
        ");

        $stmt->execute([
            'user_id' => $userId,
            'game_id' => $gameId
        ]);

        return (bool) $stmt->fetch();
    }

    public function create(int $userId, int $gameId, array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO user_games
                (user_id, game_id, status, horas, avaliacao, comentario)
            VALUES
                (:user_id, :game_id, :status, :horas, :avaliacao, :comentario)
        ");

        $stmt->execute([
            'user_id' => $userId,
            'game_id' => $gameId,
            'status' => $data['status'],
            'horas' => $data['horas'],
            'avaliacao' => $data['avaliacao'],
            'comentario' => $data['comentario']
        ]);
    }

    public function update(int $userId, int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE user_games
            SET
                status = :status,
                horas = :horas,
                avaliacao = :avaliacao,
                comentario = :comentario
            WHERE id = :id
              AND user_id = :user_id
        ");

        $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
            'status' => $data['status'],
            'horas' => $data['horas'],
            'avaliacao' => $data['avaliacao'],
            'comentario' => $data['comentario']
        ]);
    }

    public function delete(int $userId, int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM user_games
            WHERE id = :id
              AND user_id = :user_id
        ");

        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
    }
}