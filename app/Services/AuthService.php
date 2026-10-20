<?php

namespace App\Services;

use App\Models\UserModel;
use InvalidArgumentException;
use PDO;

class AuthService
{
    private UserModel $users;

    public function __construct(PDO $pdo)
    {
        $this->users = new UserModel($pdo);
    }

    public function register(array $data): array
    {
        $nome = trim((string) ($data['nome'] ?? ''));
        $email = mb_strtolower(trim((string) ($data['email'] ?? '')));
        $senha = (string) ($data['senha'] ?? '');
        $confirmacao = (string) ($data['senha_confirmacao'] ?? '');

        if ($nome === '') {
            throw new InvalidArgumentException('O nome é obrigatório.');
        }

        if (mb_strlen($nome) > 100) {
            throw new InvalidArgumentException('O nome não pode ter mais de 100 caracteres.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
            throw new InvalidArgumentException('Informe um e-mail válido.');
        }

        if (mb_strlen($senha) < 8) {
            throw new InvalidArgumentException('A senha deve ter pelo menos 8 caracteres.');
        }

        if ($senha !== $confirmacao) {
            throw new InvalidArgumentException('A confirmação da senha não confere.');
        }

        if ($this->users->findByEmail($email)) {
            throw new InvalidArgumentException('Este e-mail já está cadastrado.');
        }

        $id = $this->users->create(
            $nome,
            $email,
            password_hash($senha, PASSWORD_DEFAULT)
        );

        return ['id' => $id, 'nome' => $nome];
    }

    public function attempt(string $email, string $senha): array
    {
        $email = mb_strtolower(trim($email));

        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($senha, $user['senha_hash'])) {
            throw new InvalidArgumentException('E-mail ou senha inválidos.');
        }

        return $user;
    }
}