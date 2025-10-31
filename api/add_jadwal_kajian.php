<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
require_once __DIR__ . '/../db.php';


$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data) || !isset($data['id_masjid']) || !isset($data['body'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Format input tidak valid. Contoh: { "id_masjid":1, "body":{} }']);
    exit;
}

$id_masjid = (int) $data['id_masjid'];
$body_json = json_encode($data['body'], JSON_UNESCAPED_UNICODE);
$durasi_json = json_encode($data['durasi'], JSON_UNESCAPED_UNICODE);
if ($body_json === false) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Gagal encode body ke JSON: ' . json_last_error_msg()]);
    exit;
}

try {
    $sql = "
        INSERT INTO jadwal_kajian (id_masjid, title, body, durasi)
        VALUES (:id_masjid, :title, :body, :durasi)
        ON DUPLICATE KEY UPDATE
            title = VALUES(title),
            body = VALUES(body),
            durasi = VALUES(durasi),
            created_at = CURRENT_TIMESTAMP
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_masjid' => $id_masjid, ':title' => $data['title'], ':body' => $body_json, ':durasi' => $durasi_json]);

    $affected = $stmt->rowCount(); // untuk MySQL: 1 = insert, 2 = update (change), 0 = update no-change
    echo json_encode([
        'status' => 'success',
        'message' => 'Data disimpan (insert/update).',
        'id_masjid' => $id_masjid,
        'affected_rows' => $affected
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Exception: " . $e->getMessage()]);
}
?>