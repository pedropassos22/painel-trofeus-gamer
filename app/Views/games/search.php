<?php
$suggestionLabels = [
    'pendente' => 'Pendente',
    'aprovada' => 'Aprovada',
    'recusada' => 'Recusada'
];
?>

<section class="page">

    <div class="page-header">

        <h1>Adicionar jogo</h1>

    </div>

    <?php if (!empty($success)): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>

    <?php if (!empty($error)): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <div class="card">

        <form method="GET" action="<?= BASE_URL ?>/games/search">

            <div class="form-group suggest-wrap">

                <label for="q">
                    Pesquise o jogo pelo nome
                </label>

                <input
                    type="text"
                    id="q"
                    name="q"
                    value="<?= htmlspecialchars($term) ?>"
                    autocomplete="off"
                    data-suggest-url="<?= BASE_URL ?>/games/suggest"
                    required
                >

            </div>

            <button class="btn-primary" type="submit">
                Pesquisar
            </button>

            <a href="<?= BASE_URL ?>/games">
                Voltar
            </a>

        </form>

    </div>

    <?php if ($term !== ''): ?>

        <div class="card section-gap">

            <div class="card-body">

                <h2>Resultados para "<?= htmlspecialchars($term) ?>"</h2>

                <?php if (empty($results)): ?>

                    <p class="muted">Nenhum jogo encontrado no sistema.</p>

                <?php else: ?>

                    <ul class="result-list">

                        <?php foreach ($results as $result): ?>

                            <li>

                                <span><?= htmlspecialchars($result['nome']) ?></span>

                                <a href="<?= BASE_URL ?>/games/<?= (int) $result['id'] ?>/add">
                                    Adicionar à minha lista
                                </a>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                <?php endif; ?>

            </div>

        </div>

        <?php if ($showSuggestion): ?>

            <div class="card section-gap">

                <div class="card-body">

                    <h2>Não encontrou o jogo?</h2>

                    <p class="muted">
                        Indique "<?= htmlspecialchars($term) ?>" para os
                        administradores adicionarem ao sistema.
                    </p>

                    <form method="POST" action="<?= BASE_URL ?>/suggestions">

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= \App\Core\Csrf::token() ?>"
                        >

                        <input
                            type="hidden"
                            name="nome"
                            value="<?= htmlspecialchars($term) ?>"
                        >

                        <button class="btn-primary" type="submit">
                            Indicar este jogo
                        </button>

                    </form>

                </div>

            </div>

        <?php elseif ($alreadySuggested): ?>

            <div class="alert alert-info section-gap">
                Você já indicou "<?= htmlspecialchars($term) ?>". Aguarde a análise dos administradores.
            </div>

        <?php endif; ?>

    <?php endif; ?>

    <?php if (!empty($suggestions)): ?>

        <div class="card section-gap">

            <div class="card-body">

                <h2>Minhas indicações</h2>

                <ul class="result-list">

                    <?php foreach ($suggestions as $suggestion): ?>

                        <li>

                            <span><?= htmlspecialchars($suggestion['nome']) ?></span>

                            <span class="badge badge-<?= htmlspecialchars($suggestion['status']) ?>">
                                <?= htmlspecialchars($suggestionLabels[$suggestion['status']] ?? $suggestion['status']) ?>
                            </span>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    <?php endif; ?>

</section>

<script src="<?= BASE_URL ?>/assets/js/game-search.js?v=1"></script>