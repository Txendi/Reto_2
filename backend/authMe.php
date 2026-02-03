<?php
session_start();

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

if (!isset($_SESSION['id'])) {
  echo json_encode([
    "isLogged" => false
  ]);
  exit;
}

echo json_encode([
  "isLogged" => true,
  "user" => [
    "id" => $_SESSION['id'],
    "username" => $_SESSION['username'] ?? null,
    "email" => $_SESSION['email'] ?? null,
    "role" => $_SESSION['role'] ?? 'USER'
  ]
], JSON_UNESCAPED_UNICODE);
