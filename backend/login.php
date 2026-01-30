<?php
// Desactivar visualización de errores de texto para no romper el JSON
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');

// Configuración de BD
define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Usar bloque try-catch para capturar errores de mysqli sin que impriman texto
try {
    $conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
    $conexion->set_charset('utf8mb4');

    if ($conexion->connect_error) {
        throw new Exception("Error conexión BD");
    }

    $json_input = file_get_contents("php://input");
    $data = json_decode($json_input, true);

    $usuario    = trim($data['usuario'] ?? '');
    $contrasena = $data['password'] ?? ''; // No hagas trim a contraseñas, los espacios cuentan
    $errores    = [];

    if ($usuario === '' || $contrasena === '') {
        $errores[] = "Campos obligatorios vacíos";
    } else {
        $stmt = $conexion->prepare("SELECT username, password_hash FROM users WHERE username = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();
        
        // Importante: Vincular antes del fetch
        $stmt->bind_result($nombreBD, $hashBD);

        if ($stmt->fetch()) {
            // Verificamos si el hash existe y es válido
            if (empty($hashBD) || !password_verify($contrasena, $hashBD)) {
                $errores[] = "La contraseña no es correcta";
            } else {
                echo json_encode([
                    "status" => "ok",
                    "debug" => "Login exitoso",
                    "usuario" => $nombreBD
                ]);
                $stmt->close();
                $conexion->close();
                exit; 
            }
        } else {
            $errores[] = "Usuario no encontrado";
        }
        $stmt->close();
    }

    if (!empty($errores)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "debug" => "Login fallido",
            "errors" => $errores
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "debug" => $e->getMessage()
    ]);
}

$conexion->close();
?>