<aside class="sidebar">

    <nav>

        <a href="/" class="active">
            🏠 Dashboard
        </a>

        <a href="/games">
            🎮 Jogos
        </a>

        <a href="#">
            ⭐ Platinas
        </a>

        <a href="#">
            ⚙ Configurações
        </a>

        <?php if (\App\Core\Auth::isAdmin()): ?>

            <a href="<?= BASE_URL ?>/admin">
                🛠 Administração
            </a>

        <?php endif; ?>

    </nav>

</aside>