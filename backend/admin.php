<?php
session_start();
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json; charset=utf-8");
define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');

if ($conexion->connect_error) {
    echo json_encode(['ok' => false, 'error' => 'Error de conexión a la base de datos']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['ok' => false, 'error' => 'No se han recibido datos JSON válidos']);
    exit;
}

$titulo = $data['titulo'] ?? '';
$tipo = $data['tipo'] ?? '';
$fecha = $data['fecha'] ?? '';
$hora = $data['hora'] ?? '';
$plazas = (int) ($data['plazas'] ?? 0);
$descripcion = $data['descripcion'] ?? '';
$imagenBase64 = $data['imagen'] ?? null;

$nombreImagen = 'default.jpg';

if ($imagenBase64) {
    $partes = explode(',', $imagenBase64);

    if (isset($partes[1])) {
        $datosBinarios = base64_decode($partes[1]);

        $nombreImagen = time() . ".jpg";
        $rutaDestino = "img/events/";

        if (!is_dir($rutaDestino)) {
            mkdir($rutaDestino, 0777, true);
        }

        file_put_contents($rutaDestino . $nombreImagen, $datosBinarios);
    }
}

$sql = "INSERT INTO events (titulo, tipo, fecha, hora, plazasLibres, imagen, descripcion, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
$stmt = $conexion->prepare($sql);

$stmt->bind_param("ssssiss", $titulo, $tipo, $fecha, $hora, $plazas, $nombreImagen, $descripcion);

if ($stmt->execute()) {
    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['ok' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>