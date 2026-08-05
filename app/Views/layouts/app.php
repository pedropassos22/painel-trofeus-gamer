<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Painel de Troféus Gamer' ?></title>

    <link rel="stylesheet" href="/assets/css/style.css">

</head>

<body>

    <?php require APP_PATH . '/Views/partials/header.php'; ?>

    <div class="app">

        <?php require APP_PATH . '/Views/partials/sidebar.php'; ?>

        <main class="content">

            <?= $content ?? '' ?>

        </main>

    </div>

    <?php require APP_PATH . '/Views/partials/footer.php'; ?>

</body>

</html>