<section class="page">

    <div class="page-header">

        <h1><?= htmlspecialchars($heading) ?></h1>

    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">

        <form method="POST" action="<?= htmlspecialchars($action) ?>">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= \App\Core\Csrf::token() ?>"
            >

            <div class="form-group">

                <label for="nome">Nome do jogo</label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    maxlength="150"
                    value="<?= htmlspecialchars($game['nome'] ?? '') ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label for="capa_url">Link da capa (opcional)</label>

                <input
                    type="text"
                    id="capa_url"
                    name="capa_url"
                    maxlength="255"
                    value="<?= htmlspecialchars($game['capa_url'] ?? '') ?>"
                    placeholder="https://..."
                >

            </div>

            <button class="btn-primary" type="submit">
                Salvar
            </button>

            <a href="<?= BASE_URL ?>/admin/games">
                Cancelar
            </a>

        </form>

    </div>

</section>