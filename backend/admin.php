<?php
// Permitimos que Vue (Vite) se comunique con PHP
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");
define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
    exit;

// Conexión
$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');

if ($conexion->connect_error) {
    echo json_encode(['ok' => false, 'error' => 'Error de BD']);
    exit;
}

// Recibimos los datos
$titulo = $_POST['titulo'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$plazas = (int) ($_POST['plazas'] ?? 0);
$descripcion = $_POST['descripcion'] ?? '';

// Gestión de imagen
$nombreImagen = 'default.jpg';
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $nombreImagen = time() . "_" . $_FILES['imagen']['name'];

    // DEFINIMOS LA RUTA REAL (Sin el 'public/' que te está fallando)
    $rutaDestino = "img/events/";

    if (!is_dir($rutaDestino)) {
        mkdir($rutaDestino, 0777, true);
    }

    // Movemos el archivo a la ruta que acabamos de asegurar
    move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino . $nombreImagen);
}

// Inserción
$sql = "INSERT INTO events (titulo, tipo, fecha, hora, plazasLibres, imagen, descripcion, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssiss", $titulo, $tipo, $fecha, $hora, $plazas, $nombreImagen, $descripcion);

if ($stmt->execute()) {
    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['ok' => false, 'error' => $conexion->error]);
}
?>