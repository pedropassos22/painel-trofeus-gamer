<?php

namespace App\Core;

use PDO;
use PDOException;
class Database
{
    private PDO $connection;

    public function __construct(array $env)
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $env['DB_HOST'],
            $env['DB_PORT'],
            $env['DB_NAME']
        );

        try {

            $this->connection = new PDO(
                $dsn,
                $env['DB_USER'],
                $env['DB_PASS']
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->connection->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

        } catch (PDOException $e) {

            die('Erro ao conectar com o banco: ' . $e->getMessage());

        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}