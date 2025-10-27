<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
require_once __DIR__ . '/../db.php';

// $stmt = $pdo->query("SELECT * FROM masjid ORDER BY id");
// $data = $stmt->fetch();
// $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$id = $_GET['id'] ?? 1;
// $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$stmt = $pdo->prepare("SELECT * FROM masjid WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$data) {
    echo json_encode(['error' => 'No masjid data']);
} else {
    echo json_encode($data);
}