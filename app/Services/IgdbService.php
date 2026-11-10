<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * Conversa com a IGDB (via Twitch).
 * Só BUSCA dados. Não grava nada no banco.
 */
class IgdbService
{
    private const TOKEN_URL = 'https://id.twitch.tv/oauth2/token';
    private const API_URL = 'https://api.igdb.com/v4/games';
    private const IMAGE_URL = 'https://images.igdb.com/igdb/image/upload/t_cover_big/';

    private const FIELDS = 'fields name, cover.image_id, first_release_date, '
        . 'platforms.name, involved_companies.developer, '
        . 'involved_companies.company.name, '
        . 'total_rating_count, follows, hypes; ';

    // Jogo principal, expansão independente, remake, remaster, versão expandida e port
    private const TYPES = 'game_type = (0,4,8,9,10,11)';

    // Renova o token 1 dia antes de ele vencer
    private const RENEW_MARGIN = 86400;

    // Limite da IGDB: 4 pedidos por segundo
    private const MIN_INTERVAL = 0.3;

    private static float $lastRequestAt = 0.0;

    private string $clientId;
    private string $clientSecret;
    private string $cacheFile;

    public function __construct()
    {
        $env = parse_ini_file(ROOT_PATH . '/.env');

        if (
            $env === false
            || empty($env['IGDB_CLIENT_ID'])
            || empty($env['IGDB_CLIENT_SECRET'])
        ) {
            throw new RuntimeException('Chaves da IGDB não encontradas no .env.');
        }

        $this->clientId = (string) $env['IGDB_CLIENT_ID'];
        $this->clientSecret = (string) $env['IGDB_CLIENT_SECRET'];
        $this->cacheFile = STORAGE_PATH . '/cache/igdb_token.json';
    }

    /**
     * Busca jogos pelo nome.
     * Trata "2" = "II", ignora acentos, junta os resultados
     * e ordena por relevância.
     */
    public function search(string $term, int $limit = 30): array
    {
        $term = trim($term);

        if (mb_strlen($term) < 2) {
            return [];
        }

        $variants = $this->termVariants($term);
        $found = [];

        // 1) Busca por parte do nome (uma vez para cada variação)
        foreach ($variants as $variant) {
            $safe = str_replace(['\\', '"'], '', $variant);

            $body = self::FIELDS
                . 'where name ~ *"' . $safe . '"* & ' . self::TYPES . '; '
                . 'sort total_rating_count desc; '
                . 'limit 50;';

            foreach ($this->query($body) as $row) {
                $found[$row['id']] = $row;
            }
        }

        // 2) Busca aproximada da IGDB (ajuda com acentos, como Ragnarök)
        $safeTerm = str_replace(['\\', '"'], '', $term);

        $body = 'search "' . $safeTerm . '"; '
            . self::FIELDS
            . 'where ' . self::TYPES . '; '
            . 'limit 50;';

        foreach ($this->query($body) as $row) {
            $found[$row['id']] = $row;
        }

        // 3) Só fica o que realmente casa com o que foi digitado
        $found = array_filter(
            $found,
            fn(array $row) => $this->matchesTerm((string) $row['name'], $variants)
        );

        $games = array_map(
            fn(array $row) => $this->normalize($row, $term),
            array_values($found)
        );

        usort($games, function (array $a, array $b) {
            if ($a['_exato'] !== $b['_exato']) {
                return $b['_exato'] <=> $a['_exato'];
            }
            return $b['_popularidade'] <=> $a['_popularidade'];
        });

        $games = array_slice($games, 0, $limit);

        foreach ($games as &$game) {
            unset($game['_exato'], $game['_popularidade']);
        }
        unset($game);

        return $games;
    }

    /* ------------------------------------------------------------
     | Token da Twitch (com cache)
     ------------------------------------------------------------ */

    private function getToken(bool $forceNew = false): string
    {
        if (!$forceNew && is_file($this->cacheFile)) {
            $cache = json_decode((string) file_get_contents($this->cacheFile), true);

            if (
                is_array($cache)
                && !empty($cache['access_token'])
                && ($cache['expires_at'] ?? 0) > time() + self::RENEW_MARGIN
            ) {
                return (string) $cache['access_token'];
            }
        }

        [$status, $raw] = $this->http(
            self::TOKEN_URL,
            http_build_query([
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
            ])
        );

        $data = json_decode($raw, true);

        if ($status !== 200 || !is_array($data) || empty($data['access_token'])) {
            throw new RuntimeException('Não foi possível obter o token da Twitch (erro ' . $status . ').');
        }

        $dir = dirname($this->cacheFile);

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        file_put_contents(
            $this->cacheFile,
            json_encode([
                'access_token' => $data['access_token'],
                'expires_at' => time() + (int) ($data['expires_in'] ?? 0),
            ]),
            LOCK_EX
        );

        return (string) $data['access_token'];
    }

    /* ------------------------------------------------------------
     | Pedido à IGDB
     ------------------------------------------------------------ */

    private function query(string $body): array
    {
        $forceNew = false;

        for ($attempt = 0; $attempt < 3; $attempt++) {
            $token = $this->getToken($forceNew);
            $forceNew = false;

            $this->throttle();

            [$status, $raw] = $this->http(self::API_URL, $body, [
                'Client-ID: ' . $this->clientId,
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
            ]);

            if ($status === 200) {
                $data = json_decode($raw, true);

                if (!is_array($data)) {
                    throw new RuntimeException('Resposta inválida da IGDB.');
                }

                return $data;
            }

            // Token recusado: pede um novo e tenta de novo
            if ($status === 401) {
                $forceNew = true;
                continue;
            }

            // Pedidos demais: espera 1 segundo e tenta de novo
            if ($status === 429) {
                sleep(1);
                continue;
            }

            throw new RuntimeException('A IGDB respondeu com erro ' . $status . ': ' . $raw);
        }

        throw new RuntimeException('A IGDB não respondeu depois de 3 tentativas.');
    }

    private function throttle(): void
    {
        $elapsed = microtime(true) - self::$lastRequestAt;

        if ($elapsed < self::MIN_INTERVAL) {
            usleep((int) ((self::MIN_INTERVAL - $elapsed) * 1000000));
        }

        self::$lastRequestAt = microtime(true);
    }

    private function http(string $url, string $body, array $headers = []): array
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            throw new RuntimeException('Falha de conexão: ' . curl_error($ch));
        }

        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return [$status, (string) $result];
    }

    /* ------------------------------------------------------------
     | "2" = "II"
     ------------------------------------------------------------ */

    private function termVariants(string $term): array
    {
        $variants = [$term];

        // números -> romanos (ex.: "Final Fantasy 2" -> "Final Fantasy II")
        $variants[] = (string) preg_replace_callback(
            '/\b(\d{1,2})\b/',
            fn(array $m) => $this->toRoman((int) $m[1]) ?? $m[1],
            $term
        );

        // romanos -> números (ex.: "Final Fantasy II" -> "Final Fantasy 2")
        $variants[] = (string) preg_replace_callback(
            '/\b([IVXivx]+)\b/',
            fn(array $m) => (string) ($this->fromRoman(strtoupper($m[1])) ?? $m[1]),
            $term
        );

        return array_values(array_unique($variants));
    }

    private function toRoman(int $number): ?string
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
            6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X',
            11 => 'XI', 12 => 'XII', 13 => 'XIII', 14 => 'XIV', 15 => 'XV',
            16 => 'XVI', 17 => 'XVII', 18 => 'XVIII', 19 => 'XIX', 20 => 'XX',
        ];

        return $map[$number] ?? null;
    }

    private function fromRoman(string $roman): ?int
    {
        for ($n = 1; $n <= 20; $n++) {
            if ($this->toRoman($n) === $roman) {
                return $n;
            }
        }

        return null;
    }

    /* ------------------------------------------------------------
     | Acentos e conferência do nome
     ------------------------------------------------------------ */

    // Minúsculas e sem acentos (Ragnarök -> ragnarok)
    private function plain(string $text): string
    {
        return strtr(mb_strtolower($text), [
            'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n', 'ý' => 'y', 'ÿ' => 'y',
            'æ' => 'ae', 'œ' => 'oe', 'ß' => 'ss',
        ]);
    }

    // Confere se o nome do jogo contém o que foi digitado.
    // Se termina em número ou romano, exige o número inteiro
    // (assim "II" não casa com "III").
    private function matchesTerm(string $name, array $variants): bool
    {
        $haystack = $this->plain($name);

        foreach ($variants as $variant) {
            $needle = $this->plain($variant);

            if ($needle === '') {
                continue;
            }

            $needsBoundary = (bool) preg_match('/(?:^|\s)(\d+|[ivx]+)$/', $needle);
            $pos = 0;

            while (($pos = strpos($haystack, $needle, $pos)) !== false) {
                $end = $pos + strlen($needle);

                if (
                    !$needsBoundary
                    || $end >= strlen($haystack)
                                           || !(ctype_alnum($haystack[$end]) || (in_array($haystack[$end], ['.', ','], true) && isset($haystack[$end + 1]) && ctype_digit($haystack[$end + 1])))
                ) {
                    return true;
                }

                $pos++;
            }
        }

        return false;
    }

    /* ------------------------------------------------------------
     | Organiza o resultado no formato do nosso sistema
     ------------------------------------------------------------ */

    private function normalize(array $row, string $term): array
    {
        $timestamp = isset($row['first_release_date']) ? (int) $row['first_release_date'] : null;

        $developers = [];

        foreach ($row['involved_companies'] ?? [] as $involved) {
            if (!empty($involved['developer']) && !empty($involved['company']['name'])) {
                $developers[] = [
                    'igdb_id' => (int) $involved['company']['id'],
                    'nome' => (string) $involved['company']['name'],
                ];
            }
        }

        $platforms = [];

        foreach ($row['platforms'] ?? [] as $platform) {
            $platforms[] = [
                'igdb_id' => (int) $platform['id'],
                'nome' => (string) $platform['name'],
            ];
        }

        $capa = null;

        if (!empty($row['cover']['image_id'])) {
            $capa = self::IMAGE_URL . $row['cover']['image_id'] . '.jpg';
        }

        $anunciado = $timestamp === null || $timestamp > time();

        return [
            'external_id' => (int) $row['id'],
            'nome' => (string) $row['name'],
            'capa_url' => $capa,
            'ano_lancamento' => $timestamp !== null ? (int) date('Y', $timestamp) : null,
            'data_lancamento' => $timestamp !== null ? date('Y-m-d', $timestamp) : null,
            'lancamento_status' => $anunciado ? 'anunciado' : 'lancado',
            'desenvolvedoras' => $developers,
            'plataformas' => $platforms,
            '_exato' => $this->plain((string) $row['name']) === $this->plain($term) ? 1 : 0,
            '_popularidade' => (int) ($row['total_rating_count'] ?? 0)
                + (int) ($row['follows'] ?? 0)
                + (int) ($row['hypes'] ?? 0),
        ];
    }
}