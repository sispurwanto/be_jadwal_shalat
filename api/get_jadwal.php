<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
require_once __DIR__ . '/../config.php';
$config = require __DIR__ . '/../config.php';

$city_or_kodeprov = $_GET['kota'] ?? null; // fleksibel: bisa prov/kota atau lat/lon
$date = $_GET['date'] ?? date('Y-m-d');

$kemenag = $config['kemenag'];
if (!empty($kemenag['token'])) {
    // contoh endpoint (dokumentasi internal menyebutkan endpoint apiv1)
    $url = $kemenag['base'] . '/getShalatJadwal';
    $post = http_build_query([
        'param_prov' => $city_or_kodeprov,
        'param_token' => $kemenag['token'],
        'param_tanggal' => $date
    ]);
    $opts = ['http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $post,
        'timeout' => 5
    ]];
    $context = stream_context_create($opts);
    $result = @file_get_contents($url, false, $context);
    if ($result !== false) {
        echo $result;
        exit;
    }
}

// fallback: gunakan myQuran API (example) — requires city id parameter
$fallback = $config['fallback_jadwal']; // e.g. https://api.myquran.com/v1/sholat/jadwal/{tahun}/{bulan}/{kotaId}
if ($city_or_kodeprov) {
    // NOTE: myQuran expects kotaId; adapt as needed. This is just example.
    $parts = explode('/', trim($city_or_kodeprov,'/'));
    // try to call fallback generically:
    $url = $fallback . "?lokasi=" . urlencode($city_or_kodeprov) . "&tanggal=" . urlencode($date);
    $result = @file_get_contents($url);
    if ($result !== false) {
        echo $result;
        exit;
    }
}

http_response_code(502);
echo json_encode(['error'=>'jadwal unavailable']);
