<section class="page">

    <div class="page-header">

        <h1>Jogos</h1>

        <a href="/games/create" class="btn-primary">
            + Novo jogo
        </a>

    </div>

    <?php if (!empty($success)): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>

    <div class="card">

        <table>

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Nome</th>
                    <th>Horas</th>
                    <th>Avaliação</th>
                    <th>Ações</th>

                </tr>

            </thead>


            <tbody>

            <?php if (empty($games)): ?>

                <tr>

                    <td colspan="5" class="empty">

                        Nenhum jogo cadastrado.

                    </td>

                </tr>


            <?php else: ?>


                <?php foreach ($games as $game): ?>

                    <tr>

                        <td>
                            <?= $game['id'] ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($game['nome']) ?>
                        </td>


                        <td>
                            <?= $game['horas'] ?>h
                        </td>


                        <td>
                            <?= $game['avaliacao'] ?>/5
                        </td>


                        <td>

                            <a href="#">
                                Editar
                            </a>

                        </td>

                    </tr>


                <?php endforeach; ?>


            <?php endif; ?>


            </tbody>


        </table>

    </div>

</section>