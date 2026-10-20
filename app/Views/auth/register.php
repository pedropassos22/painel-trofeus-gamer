<section class="auth-box">

    <h1>Criar conta</h1>

    <?php if (!empty($error)): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <div class="card">

        <form method="POST" action="<?= BASE_URL ?>/register">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= \App\Core\Csrf::token() ?>"
            >

            <div class="form-group">
                <label for="nome">Nome</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    autocomplete="name"
                    value="<?= htmlspecialchars($oldInput['nome'] ?? '') ?>"
                    required
                >
            </div>

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
                <label for="senha">Senha (mínimo 8 caracteres)</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="form-group">
                <label for="senha_confirmacao">Confirmar senha</label>
                <input
                    type="password"
                    id="senha_confirmacao"
                    name="senha_confirmacao"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button class="btn-primary" type="submit">
                Criar conta
            </button>

        </form>

        <p class="auth-link">
            Já tem conta?
            <a href="<?= BASE_URL ?>/login">Entrar</a>
        </p>

    </div>

</section>