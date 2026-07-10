<?php

/**
 * Bootstrap da aplicação
 * Carrega as configurações do arquivo .env
 */

$env = parse_ini_file(__DIR__ . '/.env');

if ($env === false) {
    die('Erro: não foi possível carregar o arquivo .env');
}