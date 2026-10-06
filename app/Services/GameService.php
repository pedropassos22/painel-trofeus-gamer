<?php

namespace App\Services;

use App\Models\GameModel;
use PDO;
use InvalidArgumentException;

class GameService
{
    private GameModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new GameModel($pdo);
    }

    public function getGames(): array
    {
        return $this->model->all();
    }

    public function getGame(int $id): ?array
    {
        return $this->model->find($id);
    }

public function update(int $id, array $data): void
{
    $nome = trim((string) ($data['nome'] ?? ''));
    $horas = $data['horas'] ?? null;
    $avaliacao = $data['avaliacao'] ?? null;
    $comentario = trim((string) ($data['comentario'] ?? ''));

    if ($nome === '') {
        throw new InvalidArgumentException('O nome do jogo é obrigatório.');
    }

    if (mb_strlen($nome) > 150) {
        throw new InvalidArgumentException(
            'O nome do jogo não pode ter mais de 150 caracteres.'
        );
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

    $comentario = $comentario !== '' ? $comentario : null;

    $this->model->update($id, [
        'nome' => $nome,
        'horas' => $horas,
        'avaliacao' => $avaliacao,
        'comentario' => $comentario
    ]);
}

public function delete(int $id): void
{
    $game = $this->model->find($id);

    if (!$game) {
        throw new InvalidArgumentException(
            'Jogo não encontrado.'
        );
    }

    $this->model->delete($id);
}

    public function create(array $data): void
    {
        $nome = trim((string) ($data['nome'] ?? ''));
        $horas = $data['horas'] ?? null;
        $avaliacao = $data['avaliacao'] ?? null;
        $comentario = trim((string) ($data['comentario'] ?? ''));

        // Nome
        if ($nome === '') {
            throw new InvalidArgumentException('O nome do jogo é obrigatório.');
        }

        if (mb_strlen($nome) > 150) {
            throw new InvalidArgumentException('O nome do jogo não pode ter mais de 150 caracteres.');
        }

        // Horas
        if (
            $horas === null ||
            filter_var($horas, FILTER_VALIDATE_INT) === false
        ) {
            throw new InvalidArgumentException('Informe uma quantidade válida de horas.');
        }

        $horas = (int) $horas;

        if ($horas < 0) {
            throw new InvalidArgumentException('As horas jogadas não podem ser negativas.');
        }

        // Avaliação
        if (
            $avaliacao === null ||
            filter_var($avaliacao, FILTER_VALIDATE_INT) === false
        ) {
            throw new InvalidArgumentException('Informe uma avaliação válida.');
        }

        $avaliacao = (int) $avaliacao;

        if ($avaliacao < 0 || $avaliacao > 5) {
            throw new InvalidArgumentException('A avaliação deve estar entre 0 e 5.');
        }

        // Comentário
        $comentario = $comentario !== '' ? $comentario : null;

        $this->model->create([
            'nome' => $nome,
            'horas' => $horas,
            'avaliacao' => $avaliacao,
            'comentario' => $comentario
        ]);
    }
}