<?php
session_start();

require_once "conexion.php";

// if (!isset($_SESSION['id'])) {
//     echo json_encode([
//         "authenticated" => false
//     ]);
//     exit;
// }

echo json_encode([
    "authenticated" => true,
    "user" => [
        "id" => $_SESSION['id'],
        "username" => $_SESSION['username']
    ]
]);
