<section class="page">

    <div class="page-header">

        <h1>Meus jogos</h1>

        <a href="<?= BASE_URL ?>/games/search" class="btn-primary">
            + Adicionar jogo
        </a>

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
                    <th>Status</th>
                    <th>Horas</th>
                    <th>Avaliação</th>
                    <th>Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php if (empty($games)): ?>

                <tr>
                    <td colspan="5" class="empty">
                        Nenhum jogo na sua lista ainda.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($games as $game): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($game['nome']) ?>
                        </td>

                        <td>
                            <span class="badge badge-<?= htmlspecialchars($game['status']) ?>">
                                <?= htmlspecialchars(
                                    \App\Services\GameService::STATUS[$game['status']] ?? $game['status']
                                ) ?>
                            </span>
                        </td>

                        <td>
                            <?= (int) $game['horas'] ?>h
                        </td>

                        <td>
                            <?= (int) $game['avaliacao'] ?>/5
                        </td>

                        <td>

                            <a href="<?= BASE_URL ?>/games/<?= (int) $game['id'] ?>/edit">
                                Editar
                            </a>

                            <form
                                method="POST"
                                action="<?= BASE_URL ?>/games/<?= (int) $game['id'] ?>/delete"
                                style="display: inline;"
                                onsubmit="return confirm('Tem certeza que deseja remover este jogo da sua lista?');"
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