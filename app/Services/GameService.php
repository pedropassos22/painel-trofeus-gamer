<?php

namespace App\Services;

use App\Models\GameModel;
use InvalidArgumentException;
use PDO;

class GameService
{
    public const STATUS = [
        'zerado' => 'Zerado',
        'jogando' => 'Jogando',
        'abandonado' => 'Abandonado'
    ];

    private GameModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new GameModel($pdo);
    }

    public function getGames(int $userId): array
    {
        return $this->model->allForUser($userId);
    }

    public function getGame(int $userId, int $id): ?array
    {
        return $this->model->findForUser($userId, $id);
    }

    public function search(string $term): array
    {
        $term = trim($term);

        if ($term === '') {
            return [];
        }

        return $this->model->searchCatalog(mb_substr($term, 0, 150));
    }

    public function getCatalogGame(int $id): ?array
    {
        return $this->model->findCatalogGame($id);
    }

    public function add(int $userId, int $gameId, array $data): void
    {
        if (!$this->model->findCatalogGame($gameId)) {
            throw new InvalidArgumentException(
                'Jogo não encontrado no sistema.'
            );
        }

        $progress = $this->validateProgress($data);

        if ($this->model->userHasGame($userId, $gameId)) {
            throw new InvalidArgumentException(
                'Este jogo já está na sua lista.'
            );
        }

        $this->model->create($userId, $gameId, $progress);
    }

    public function update(int $userId, int $id, array $data): void
    {
        if (!$this->model->findForUser($userId, $id)) {
            throw new InvalidArgumentException('Jogo não encontrado.');
        }

        $progress = $this->validateProgress($data);

        $this->model->update($userId, $id, $progress);
    }

    public function delete(int $userId, int $id): void
    {
        if (!$this->model->findForUser($userId, $id)) {
            throw new InvalidArgumentException('Jogo não encontrado.');
        }

        $this->model->delete($userId, $id);
    }

    private function validateProgress(array $data): array
    {
        $status = (string) ($data['status'] ?? '');
        $horas = $data['horas'] ?? null;
        $avaliacao = $data['avaliacao'] ?? null;
        $comentario = trim((string) ($data['comentario'] ?? ''));

        if (!array_key_exists($status, self::STATUS)) {
            throw new InvalidArgumentException('Escolha um status válido.');
        }

        if (
            $horas === null ||
            filter_var($horas, FILTER_VALIDATE_INT) === false
        ) {
            throw new InvalidArgumentException(
                'Informe uma quantidade válida de horas.'
            );
        }

        $horas = (int) $horas;

        if ($horas < 0) {
            throw new InvalidArgumentException(
                'As horas jogadas não podem ser negativas.'
            );
        }

        if (
            $avaliacao === null ||
            filter_var($avaliacao, FILTER_VALIDATE_INT) === false
        ) {
            throw new InvalidArgumentException(
                'Informe uma avaliação válida.'
            );
        }

        $avaliacao = (int) $avaliacao;

        if ($avaliacao < 0 || $avaliacao > 5) {
            throw new InvalidArgumentException(
                'A avaliação deve estar entre 0 e 5.'
            );
        }

        return [
            'status' => $status,
            'horas' => $horas,
            'avaliacao' => $avaliacao,
            'comentario' => $comentario !== '' ? $comentario : null
        ];
    }
}