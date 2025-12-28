<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$q = $conn->prepare("SELECT * FROM users WHERE email=?");
$q->bind_param("s", $email);
$q->execute();
$user = $q->get_result()->fetch_assoc();

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => false,
        "message" => "Email atau password salah"
    ]);
    exit;
}

echo json_encode([
    "status" => true,
    "message" => "Login berhasil",
    "data" => [
        "email" => $user['email'],
        "username" => $user['username']
    ]
]);