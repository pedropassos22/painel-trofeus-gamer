<?php

use App\Services\IgdbService;

require __DIR__ . '/../bootstrap.php';

header('Content-Type: text/html; charset=utf-8');

$q = trim($_GET['q'] ?? '');

echo '<h3>Teste da busca na IGDB (temporário)</h3>';
echo '<form method="get"><input name="q" value="' . htmlspecialchars($q) . '" size="40"> ';
echo '<button type="submit">Buscar</button></form>';

if ($q !== '') {
    try {
        $service = new IgdbService();

        $inicio = microtime(true);
        $jogos = $service->search($q);
        $tempo = round(microtime(true) - $inicio, 2);

        echo '<p>' . count($jogos) . ' resultado(s) em ' . $tempo . ' segundos</p>';
        echo '<table border="1" cellpadding="6" style="border-collapse:collapse">';
        echo '<tr><th>Capa</th><th>Nome</th><th>Ano</th><th>Status</th><th>Desenvolvedoras</th><th>Plataformas</th></tr>';

        foreach ($jogos as $jogo) {
            $devs = implode(', ', array_column($jogo['desenvolvedoras'], 'nome'));
            $plats = implode(', ', array_column($jogo['plataformas'], 'nome'));

            echo '<tr>';
            echo '<td>' . ($jogo['capa_url'] ? '<img src="' . htmlspecialchars($jogo['capa_url']) . '" height="60">' : '-') . '</td>';
            echo '<td>' . htmlspecialchars($jogo['nome']) . '</td>';
            echo '<td>' . ($jogo['ano_lancamento'] ?? '-') . '</td>';
            echo '<td>' . $jogo['lancamento_status'] . '</td>';
            echo '<td>' . htmlspecialchars($devs) . '</td>';
            echo '<td>' . htmlspecialchars($plats) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } catch (Throwable $e) {
        echo '<pre style="color:red">' . htmlspecialchars($e->getMessage()) . '</pre>';
    }
}