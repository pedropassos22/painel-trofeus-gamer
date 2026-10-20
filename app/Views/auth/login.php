<section class="auth-box">

    <h1>Entrar</h1>

    <?php if (!empty($error)): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <div class="card">

        <form method="POST" action="<?= BASE_URL ?>/login">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= \App\Core\Csrf::token() ?>"
            >

            <div class="form-group">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button class="btn-primary" type="submit">
                Entrar
            </button>

        </form>

        <p class="auth-link">
            Ainda não tem conta?
            <a href="<?= BASE_URL ?>/register">Criar conta</a>
        </p>

    </div>

</section>