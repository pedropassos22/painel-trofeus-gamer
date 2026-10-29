<section class="page">

    <div class="page-header">

        <h1>Administração - Jogos</h1>

        <div>
            <a href="<?= BASE_URL ?>/admin/suggestions">
                Ver indicações
            </a>

            <a href="<?= BASE_URL ?>/admin/games/new" class="btn-primary">
                + Novo jogo
            </a>
        </div>

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

        <table>

            <thead>

                <tr>
                    <th>Nome</th>
                    <th>Capa</th>
                    <th>Nas listas de</th>
                    <th>Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php if (empty($games)): ?>

                <tr>
                    <td colspan="4" class="empty">
                        Nenhum jogo no catálogo ainda.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($games as $game): ?>

                    <?php
                    $total = (int) $game['total_usuarios'];

                    $confirmMsg = 'Remover "' . $game['nome'] . '" do catálogo? '
                        . 'Ele também será removido da lista de ' . $total . ' usuário(s). '
                        . 'Isso não pode ser desfeito.';
                    ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($game['nome']) ?>
                        </td>

                        <td>
                            <?= !empty($game['capa_url']) ? 'Sim' : '—' ?>
                        </td>

                        <td>
                            <?= $total ?> usuário(s)
                        </td>

                        <td>

                            <a href="<?= BASE_URL ?>/admin/games/<?= (int) $game['id'] ?>/edit">
                                Editar
                            </a>

                            <form
                                method="POST"
                                action="<?= BASE_URL ?>/admin/games/<?= (int) $game['id'] ?>/delete"
                                style="display: inline;"
                                onsubmit="return confirm(<?= htmlspecialchars(json_encode($confirmMsg, JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>);"
                            >

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= \App\Core\Csrf::token() ?>"
                                >

                                <button type="submit">
                                    Excluir
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>