<?php

$env = parse_ini_file(__DIR__ . '/.env');

if ($env === false) {
    die('Arquivo .env não encontrado.');
}

require_once __DIR__ . '/app/Core/Database.php';

$database = new Database($env);

$pdo = $database->getConnection();