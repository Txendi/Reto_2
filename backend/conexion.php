<?php
error_reporting(0);
ini_set('display_errors', 0);

define('SERVIDOR', 'localhost');
define('BBDD', 'dw2t_jon_ismael');
define('USUARIO', 'dw2t_jon_ismael');
define('CLAVE', '12345');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');


if ($conexion->connect_error) {
    echo json_encode(['ok' => false, 'error' => 'Error de conexión a la base de datos']);
    exit;
}
?>