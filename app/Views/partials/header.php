<header class="header">

    <div class="logo">
        🎮 Painel de Troféus Gamer
    </div>

    <?php if (\App\Core\Auth::check()): ?>

        <div class="user">

            <span><?= htmlspecialchars(\App\Core\Auth::name() ?? '') ?></span>

            <form method="POST" action="<?= BASE_URL ?>/logout">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= \App\Core\Csrf::token() ?>"
                >

                <button type="submit" class="btn-logout">
                    Sair
                </button>

            </form>

        </div>

    <?php endif; ?>

</header>