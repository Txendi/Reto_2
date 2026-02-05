<?php
session_start();

define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset("utf8mb4");


if (!isset($_SESSION['id'])) {
    echo json_encode([
        "authenticated" => false
    ]);
    exit;
}

    $stmt = $conexion->prepare("SELECT id, username, email, 'role' FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nombre, $email, $rol);
    $stmt->fetch();

    echo json_encode([
        "authenticated" => true,
        "user" => [
            "id" => $id,
            "username" => $nombre,
            "email" => $email,
            "rol" => $rol
        ]
    ]);