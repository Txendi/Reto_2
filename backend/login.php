<?php
// 1. Cabeceras CORS completas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');
define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

// 2. Manejo mejorado del preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "debug" => "Error conexión BD"
    ]);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$usuario  = trim($data['usuario'] ?? '');
$contrasena    = trim($data['password'] ?? '');

$errores = [];
// Validaciones
if ($usuario === '' || $email === '') {
    $errores[] = "Campos obligatorios vacíos";
}


// Comprobar usuario
$stmt = $conexion->prepare("SELECT username,password_hash FROM users WHERE username = ?");
$stmt->bind_param("s", $usuario);
$stmt->bind_result($nombreBD, $hashBD);
$stmt->execute();
$stmt->store_result();

if ($contrasena != $hashBD) {
    echo "La contraseña no coincide";
}
