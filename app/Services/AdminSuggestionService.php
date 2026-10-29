<?php

namespace App\Services;

use App\Models\AdminGameModel;
use App\Models\AdminSuggestionModel;
use InvalidArgumentException;
use PDO;
use PDOException;
use Throwable;

class AdminSuggestionService
{
    private PDO $pdo;
    private AdminSuggestionModel $suggestions;
    private AdminGameModel $games;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->suggestions = new AdminSuggestionModel($pdo);
        $this->games = new AdminGameModel($pdo);
    }

    public function all(): array
    {
        return $this->suggestions->all();
    }

    public function approve(int $id): void
    {
        $suggestion = $this->findPending($id);

        $this->pdo->beginTransaction();

        try {
            if (!$this->games->nameExists($suggestion['nome'])) {
                $this->games->create($suggestion['nome'], null);
            }

            $this->suggestions->approveByName($suggestion['nome']);

            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            if ($e instanceof PDOException && $e->getCode() === '23000') {
                throw new InvalidArgumentException('Já existe um jogo com este nome no catálogo.');
            }

            throw $e;
        }
    }

    public function reject(int $id): void
    {
        $this->findPending($id);

        $this->suggestions->reject($id);
    }

    private function findPending(int $id): array
    {
        $suggestion = $this->suggestions->find($id);

        if (!$suggestion) {
            throw new InvalidArgumentException('Indicação não encontrada.');
        }

        if ($suggestion['status'] !== 'pendente') {
            throw new InvalidArgumentException('Esta indicação já foi analisada.');
        }

        return $suggestion;
    }
}