<?php

namespace App\Models;

use PDO;

class UserModel
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM users WHERE email = :email LIMIT 1'
        );

        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function create(string $nome, string $email, string $senhaHash): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (nome, email, senha_hash)
             VALUES (:nome, :email, :senha_hash)'
        );

        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha_hash' => $senhaHash
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}