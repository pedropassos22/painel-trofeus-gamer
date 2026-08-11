<?php

/**
 * ==========================================================
 * PAINEL DE TROFÉUS GAMER
 * Bootstrap da aplicação
 * ==========================================================
 */

declare(strict_types=1);

error_reporting(E_ALL);

use App\Core\App;
use App\Core\Database;

/*
|--------------------------------------------------------------------------
| Carrega o arquivo .env
|--------------------------------------------------------------------------
*/

$env = parse_ini_file(__DIR__ . '/.env');

if ($env === false) {
    die('Erro: arquivo .env não encontrado.');
}

/*
|--------------------------------------------------------------------------
| Constantes da aplicação
|--------------------------------------------------------------------------
*/

define('APP_NAME', $env['APP_NAME']);
define('BASE_URL', 'http://painel-trofeus-gamer.test');

define('ROOT_PATH', __DIR__);

define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATABASE_PATH', ROOT_PATH . '/database');
define('STORAGE_PATH', ROOT_PATH . '/storage');

define('UPLOADS_PATH', STORAGE_PATH . '/uploads');
define('SAVES_PATH', UPLOADS_PATH . '/saves');

/*
|--------------------------------------------------------------------------
| Autoload das classes da aplicação
|--------------------------------------------------------------------------
*/

spl_autoload_register(function (string $class) {

    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));

    $file = APP_PATH . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

/*
|--------------------------------------------------------------------------
| Timezone
|--------------------------------------------------------------------------
*/

date_default_timezone_set('America/Bahia');

/*
|--------------------------------------------------------------------------
| Sessão
|--------------------------------------------------------------------------
*/

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Conexão com o banco
|--------------------------------------------------------------------------
*/

$database = new Database($env);

$pdo = $database->getConnection();

App::set('pdo', $pdo);