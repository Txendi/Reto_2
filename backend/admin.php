<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// ⬅️ PRE-FLIGHT PRIMERO
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ⬅️ LUEGO validas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');


$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'JSON inválido']);
    exit;
}

$titulo = $data['titulo'] ?? '';
$descripcion = $data['descripcion'] ?? '';
$fecha = $data['fecha'] ?? '';
$hora = $data['hora'] ?? '';
$plazas = (int) ($data['plazas'] ?? 0);
$tipo = $data['tipo'] ?? '';
$imagen = $data['imagen'] ?? null;

// Validación mínima
if ($titulo === '' || $fecha === '' || $hora === '' || $plazas <= 0 || $tipo === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$stmt = $conexion->prepare("
    INSERT INTO events
    (titulo, tipo, fecha, hora, plazasLibres, imagen, descripcion, created_by)
    VALUES (?, ?, ?, ?, ?, ?, ?, 1)
");

$stmt->bind_param(
    "ssssiss",
    $titulo,
    $tipo,
    $fecha,
    $hora,
    $plazas,
    $imagen,
    $descripcion
);

if ($stmt->execute()) {
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear evento']);
}

$conexion->close();

?>