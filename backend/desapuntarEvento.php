<?php
session_start();

require_once "conexion.php";

$data = json_decode(file_get_contents('php://input'), true);

$idUsuario = $_SESSION['id'] ?? '';
$idEvento = $data["idEvento"] ?? '';

$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');

$sql = "DELETE FROM user_events WHERE user_id = ? AND event_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idUsuario, $idEvento);
$stmt->execute();
print $idUsuario;


$conexion->close();
