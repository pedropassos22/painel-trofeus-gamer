<section class="page">

    <div class="page-header">

        <h1>Editar Jogo</h1>

    </div>

    <div class="card">

        <form method="POST" action="<?= BASE_URL ?>/games/<?= $game['id'] ?>/edit">
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
                    value="<?= htmlspecialchars($game['nome']) ?>"
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
                    value="<?= htmlspecialchars($game['horas']) ?>"
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
                    value="<?= htmlspecialchars($game['avaliacao']) ?>"
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
                ><?= htmlspecialchars($game['comentario'] ?? '') ?></textarea>

            </div>

            <button class="btn-primary">
                Salvar alterações
            </button>

            <a href="<?= BASE_URL ?>/games">
                Cancelar
            </a>

        </form>

    </div>

</section>