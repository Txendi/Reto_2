<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);

if ($conexion->connect_error) {
    echo json_encode(['error' => 'Error conexión BD']);
    exit;
}

$conexion->set_charset("utf8mb4");


$q = $_GET['q'] ?? '';
$id = $_GET['id'] ?? '';

if ($id && is_numeric($id)) {
    $sql = "SELECT * FROM games WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id); // i para que sea un entero (integer)  
    $stmt->execute();
    $juego = $stmt->get_result()->fetch_assoc(); // devuelve solo el juego esperado, para eso el fetch assoc

    if ($juego) {
        $juego['plataformas'] = json_decode($juego['plataformas']);
    }
    echo json_encode($juego, JSON_UNESCAPED_UNICODE);

} else {
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

    $lista = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($lista as &$j) {
        $j['plataformas'] = json_decode($j['plataformas']);
    }

    echo json_encode($lista, JSON_UNESCAPED_UNICODE);

}
?>