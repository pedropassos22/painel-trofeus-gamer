<?php

require_once __DIR__ . '/../bootstrap.php';

$stmt = $pdo->query("SELECT NOW() AS data");

$dados = $stmt->fetch();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<title><?= $env['APP_NAME'] ?></title>

</head>

<body>

<h1><?= $env['APP_NAME'] ?></h1>

<p>Banco conectado com sucesso.</p>

<p>

<?= $dados['data']; ?>

</p>

</body>

</html>