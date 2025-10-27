<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
require_once __DIR__ . '/../db.php';


$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Validasi input
$required = ['id', 'name', 'location', 'latitude', 'longitude', 'timezone'];
foreach ($required as $key) {
    if (!isset($data[$key])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Field '$key' wajib diisi"]);
        exit;
    }
}

$id   = (int)$data['id'];
$name = (string)$data['name'];
$location = (string)$data['location'];
$latitude = (float)$data['latitude'];
$longitude = (float)$data['longitude'];
$timezone = (string)$data['timezone'];

try {
    $sql = "
        INSERT INTO masjid (id, name, location, latitude, longitude, timezone)
        VALUES (:id, :name, :location, :latitude, :longitude, :timezone)
        ON DUPLICATE KEY UPDATE
            name = VALUES(name),
            location = VALUES(location),
            latitude = VALUES(latitude),
            longitude = VALUES(longitude),
            timezone = VALUES(timezone),
            created_at = CURRENT_TIMESTAMP      
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id'   => $id,
        ':name' => $name,
        ':location' => $location,
        ':latitude' => $latitude,
        ':longitude' => $longitude,
        ':timezone' => $timezone
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Data berhasil disimpan (insert/update)',
        'id' => $id
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Exception: " . $e->getMessage()]);
}
?>