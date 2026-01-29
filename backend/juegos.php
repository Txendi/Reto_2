<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');
$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');

if ($conexion->connect_error) {
    echo json_encode(['error' => 'Error conexión BD']);
    exit;
}

$conexion->set_charset('utf8mb4');

$q = $_GET['q'] ?? '';

if ($q === '') {
    $sql = "SELECT * FROM games";
    $stmt = $conexion->prepare($sql);
} else {
    $sql = "SELECT * FROM games WHERE
                titulo LIKE ? OR
                genero LIKE ? OR
                plataforma LIKE ?";
    $stmt = $conexion->prepare($sql);
    $like = "%$q%";
    $stmt->bind_param("sss", $like, $like, $like);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conexion->close();

/* $input = file_get_contents('php://input');

$data = json_decode($input, true);

$texto = $data['texto'] ?? null;

$sql = "SELECT * FROM games WHERE 1=1";
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

$conexion->close(); */
?>