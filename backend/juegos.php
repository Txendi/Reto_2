<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
define('SERVIDOR', 'localhost');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', '12345');
$conexion = new mysqli(SERVIDOR, USUARIO, null, BBDD);
$conexion->set_charset('utf8mb4');

$input = file_get_contents('php://input');

$data = json_decode($input, true);

$texto = $data['texto'] ?? null;

$sql = "SELECT * FROM usuarios WHERE 1=1";
$params = [];
$types = "";

if (!empty($texto)) {
    $sql .= " AND (titulo LIKE ? OR genero LIKE ? OR plataforma LIKE ?)";
    $params[] = "%" . $_POST['titulo'] . "%";
    $params[] = "%" . $_POST['genero'] . "%";
    $params[] = "%" . $_POST['plataforma'] . "%";
    $types .= "s";
}

$stmt = $conexion->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$conexion->close();
?>