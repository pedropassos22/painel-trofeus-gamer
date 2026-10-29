<?php

namespace App\Services;

use App\Models\GameModel;
use App\Models\SuggestionModel;
use InvalidArgumentException;
use PDO;
use PDOException;

class SuggestionService
{
    private SuggestionModel $suggestions;
    private GameModel $games;

    public function __construct(PDO $pdo)
    {
        $this->suggestions = new SuggestionModel($pdo);
        $this->games = new GameModel($pdo);
    }

    public function create(int $userId, string $nome): void
    {
        $nome = trim((string) preg_replace('/\s+/u', ' ', $nome));

        if ($nome === '') {
            throw new InvalidArgumentException('Informe o nome do jogo.');
        }

        if (mb_strlen($nome) > 150) {
            throw new InvalidArgumentException(
                'O nome do jogo não pode ter mais de 150 caracteres.'
            );
        }

        if ($this->games->catalogNameExists($nome)) {
            throw new InvalidArgumentException(
                'Este jogo já está disponível no sistema. Use a pesquisa para adicioná-lo.'
            );
        }

        if ($this->suggestions->exists($userId, $nome)) {
            throw new InvalidArgumentException('Você já indicou este jogo.');
        }

        try {
            $this->suggestions->create($userId, $nome);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new InvalidArgumentException('Você já indicou este jogo.');
            }

            throw $e;
        }
    }

    public function getForUser(int $userId): array
    {
        return $this->suggestions->allForUser($userId);
    }
}