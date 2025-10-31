<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
require_once __DIR__ . '/../db.php';


$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Validasi input
$required = ['id_masjid', 'saldo_awal', 'saldo_akhir', 'masuk', 'keluar'];
foreach ($required as $key) {
    if (!isset($data[$key])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Field '$key' wajib diisi"]);
        exit;
    }
}

$id_masjid   = (int)$data['id_masjid'];
$saldo_awal  = (float)$data['saldo_awal'];
$saldo_akhir = (float)$data['saldo_akhir'];
$masuk       = (float)$data['masuk'];
$keluar      = (float)$data['keluar'];
$keterangan  = isset($data['keterangan']) ? (string)$data['keterangan'] : null;

try {
    $sql = "
        INSERT INTO laporan (id_masjid, saldo_awal, saldo_akhir, masuk, keluar, keterangan)
        VALUES (:id_masjid, :saldo_awal, :saldo_akhir, :masuk, :keluar, :keterangan)
        ON DUPLICATE KEY UPDATE
            saldo_awal  = VALUES(saldo_awal),
            saldo_akhir = VALUES(saldo_akhir),
            masuk       = VALUES(masuk),
            keluar      = VALUES(keluar),
            keterangan  = VALUES(keterangan),
            updated_at  = CURRENT_TIMESTAMP
            
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_masjid'   => $id_masjid,
        ':saldo_awal'  => $saldo_awal,
        ':saldo_akhir' => $saldo_akhir,
        ':masuk'       => $masuk,
        ':keluar'      => $keluar,
        ':keterangan'  => $keterangan
    ]);

    $sql_his = "
        INSERT INTO history_laporan (id_masjid, saldo_awal, saldo_akhir, masuk, keluar, keterangan)
        VALUES (:id_masjid, :saldo_awal, :saldo_akhir, :masuk, :keluar, :keterangan)";

    $stmt_his = $pdo->prepare($sql_his);
    $stmt_his->execute([
        ':id_masjid'   => $id_masjid,
        ':saldo_awal'  => $saldo_awal,
        ':saldo_akhir' => $saldo_akhir,
        ':masuk'       => $masuk,
        ':keluar'      => $keluar,
        ':keterangan'  => $keterangan
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Data berhasil disimpan (insert/update)',
        'id_masjid' => $id_masjid
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Exception: " . $e->getMessage()]);
}
?>