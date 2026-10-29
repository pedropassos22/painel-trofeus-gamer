<?php

namespace App\Services;

use App\Models\AdminGameModel;
use InvalidArgumentException;
use PDO;
use PDOException;

class AdminGameService
{
    private AdminGameModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new AdminGameModel($pdo);
    }

    public function all(): array
    {
        return $this->model->all();
    }

    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function create(string $nome, string $capaUrl): void
    {
        [$nome, $capa] = $this->validate($nome, $capaUrl, 0);

        try {
            $this->model->create($nome, $capa);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new InvalidArgumentException('Já existe um jogo com este nome no catálogo.');
            }

            throw $e;
        }
    }

    public function update(int $id, string $nome, string $capaUrl): void
    {
        if (!$this->model->find($id)) {
            throw new InvalidArgumentException('Jogo não encontrado.');
        }

        [$nome, $capa] = $this->validate($nome, $capaUrl, $id);

        try {
            $this->model->update($id, $nome, $capa);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new InvalidArgumentException('Já existe um jogo com este nome no catálogo.');
            }

            throw $e;
        }
    }

    public function delete(int $id): void
    {
        if (!$this->model->find($id)) {
            throw new InvalidArgumentException('Jogo não encontrado.');
        }

        $this->model->delete($id);
    }

    private function validate(string $nome, string $capaUrl, int $exceptId): array
    {
        $nome = trim((string) preg_replace('/\s+/u', ' ', $nome));
        $capaUrl = trim($capaUrl);

        if ($nome === '') {
            throw new InvalidArgumentException('Informe o nome do jogo.');
        }

        if (mb_strlen($nome) > 150) {
            throw new InvalidArgumentException('O nome do jogo não pode ter mais de 150 caracteres.');
        }

        if ($this->model->nameExists($nome, $exceptId)) {
            throw new InvalidArgumentException('Já existe um jogo com este nome no catálogo.');
        }

        if ($capaUrl !== '') {
            if (
                mb_strlen($capaUrl) > 255 ||
                filter_var($capaUrl, FILTER_VALIDATE_URL) === false ||
                !preg_match('#^https?://#i', $capaUrl)
            ) {
                throw new InvalidArgumentException(
                    'A capa deve ser um link válido (começando com http:// ou https://).'
                );
            }
        }

        return [$nome, $capaUrl !== '' ? $capaUrl : null];
    }
}