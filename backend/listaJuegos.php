<?php
// header('Content-Type: application/json; charset=utf-8');
// header("Access-Control-Allow-Origin: *");
session_start();
$conexion = new mysqli('mysql', 'root', 'pass', 'gamefest');
$conexion->set_charset('utf8mb4');

$q = $_GET['q'] ?? '';


    $sql = "SELECT * FROM games WHERE 1=1";
    if ($q) {
        $sql .= " AND (titulo LIKE ? OR genero LIKE ? OR plataformas LIKE ?)";
        $stmt = $conexion->prepare($sql);
        $like = "%$q%";
        $stmt->bind_param("sss", $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conexion->query($sql);
    }

    $lista = [];
    while ($fila = $result->fetch_assoc()) {
        $fila['plataformas'] = json_decode($fila['plataformas']);
        $lista[] = $fila;
    }
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);


?>