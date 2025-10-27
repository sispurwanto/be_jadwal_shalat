<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
require_once __DIR__ . '/../db.php';

$id = $_GET['id_masjid'] ?? 1;
$stmt = $pdo->prepare("SELECT * FROM laporan WHERE id_masjid = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) {
    echo json_encode(['error' => 'No Laporan data']);
} else {
    echo json_encode($data);
}
