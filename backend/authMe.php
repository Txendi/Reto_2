<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['id'], $_SESSION['username'])) {
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
