<section class="page">

    <div class="page-header">

        <h1>Novo Jogo</h1>

    </div>

    <?php if (!empty($error)): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <div class="card">

        <form method="POST" action="<?= BASE_URL ?>/games/create">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= \App\Core\Csrf::token() ?>"
            >

            <div class="form-group">

                <label for="nome">
                    Nome do jogo
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?= htmlspecialchars($oldInput['nome'] ?? '') ?>"
                    required
                >

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
                    value="<?= htmlspecialchars($oldInput['horas'] ?? '') ?>"
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
                    value="<?= htmlspecialchars($oldInput['avaliacao'] ?? '') ?>"
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
                ><?= htmlspecialchars($oldInput['comentario'] ?? '') ?></textarea>

            </div>

            <button class="btn-primary">
                Salvar jogo
            </button>

        </form>

    </div>

</section>