<?php
$statusLabels = [
    'pendente' => 'Pendente',
    'aprovada' => 'Aprovada',
    'recusada' => 'Recusada'
];
?>

<section class="page">

    <div class="page-header">

        <h1>Administração - Indicações</h1>

        <a href="<?= BASE_URL ?>/admin/games">
            Voltar ao catálogo
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
                    <th>Jogo indicado</th>
                    <th>Indicado por</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php if (empty($suggestions)): ?>

                <tr>
                    <td colspan="5" class="empty">
                        Nenhuma indicação recebida.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($suggestions as $suggestion): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($suggestion['nome']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($suggestion['usuario']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(date('d/m/Y H:i', strtotime((string) $suggestion['created_at']))) ?>
                        </td>

                        <td>
                            <span class="badge badge-<?= htmlspecialchars($suggestion['status']) ?>">
                                <?= htmlspecialchars($statusLabels[$suggestion['status']] ?? $suggestion['status']) ?>
                            </span>
                        </td>

                        <td>

                            <?php if ($suggestion['status'] === 'pendente'): ?>

                                <form
                                    method="POST"
                                    action="<?= BASE_URL ?>/admin/suggestions/<?= (int) $suggestion['id'] ?>/approve"
                                    style="display: inline;"
                                >
                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= \App\Core\Csrf::token() ?>"
                                    >

                                    <button type="submit">
                                        Aprovar
                                    </button>
                                </form>

                                <form
                                    method="POST"
                                    action="<?= BASE_URL ?>/admin/suggestions/<?= (int) $suggestion['id'] ?>/reject"
                                    style="display: inline;"
                                >
                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= \App\Core\Csrf::token() ?>"
                                    >

                                    <button type="submit">
                                        Recusar
                                    </button>
                                </form>

                            <?php else: ?>

                                —

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>