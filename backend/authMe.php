<?php
session_start();


/* ===== CORS correcto para cookies ===== */
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=utf-8");




if (!isset($_SESSION['id'])) {
    echo json_encode([
        "authenticated" => false
    ]);
    exit;
}

echo json_encode([
    "authenticated" => true,
    "user" => [
        "id" => $_SESSION['id'],
        "username" => $_SESSION['username']
    ]
]);
