<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

$conexion = new mysqli('mysql', 'root', 'pass', 'gamefest');
$conexion->set_charset('utf8mb4');

$id = $_GET['id'] ?? '';

    $stmt = $conexion->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $juego = $result->fetch_assoc();

    if ($juego) {
        $juego['plataformas'] = json_decode($juego['plataformas']);
    }

    echo json_encode($juego, JSON_UNESCAPED_UNICODE);


?>