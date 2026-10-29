<section class="page">

    <div class="page-header">

        <h1>Adicionar: <?= htmlspecialchars($game['nome']) ?></h1>

    </div>

    <?php if (!empty($error)): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <div class="card">

        <form method="POST" action="<?= BASE_URL ?>/games/<?= (int) $game['id'] ?>/add">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= \App\Core\Csrf::token() ?>"
            >

            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select id="status" name="status" required>

                    <?php foreach (\App\Services\GameService::STATUS as $value => $label): ?>

                        <option
                            value="<?= $value ?>"
                            <?= ($oldInput['status'] ?? 'zerado') === $value ? 'selected' : '' ?>
                        >
                            <?= $label ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label for="horas">
                    Horas jogadas
                </label>

                <input
                    type="number"
                    id="horas"
                    name="horas"
                    min="0"
                    value="<?= htmlspecialchars((string) ($oldInput['horas'] ?? '')) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label for="avaliacao">
                    Avaliação (0 a 5)
                </label>

                <input
                    type="number"
                    id="avaliacao"
                    name="avaliacao"
                    min="0"
                    max="5"
                    value="<?= htmlspecialchars((string) ($oldInput['avaliacao'] ?? '')) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label for="comentario">
                    Comentário
                </label>

                <textarea
                    id="comentario"
                    name="comentario"
                ><?= htmlspecialchars((string) ($oldInput['comentario'] ?? '')) ?></textarea>

            </div>

            <button class="btn-primary" type="submit">
                Adicionar à minha lista
            </button>

            <a href="<?= BASE_URL ?>/games/search">
                Cancelar
            </a>

        </form>

    </div>

</section>