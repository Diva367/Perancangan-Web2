<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include '../koneksi.php';

$data = [];
$query = $conn->query("SELECT id, original_name, filename FROM images ORDER BY id DESC");

while ($row = $query->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "data" => $data
]);
