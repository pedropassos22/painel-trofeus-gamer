<section class="page">

    <div class="page-header">

        <h1>Novo Jogo</h1>

    </div>


    <div class="card">

        <form method="POST" action="<?= BASE_URL ?>/games/create">

            <div class="form-group">

                <label for="nome">
                    Nome do jogo
                </label>

                <input 
                    type="text"
                    id="nome"
                    name="nome"
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
                ></textarea>

            </div>


            <button class="btn-primary">

                Salvar jogo

            </button>

        </form>

    </div>

</section>