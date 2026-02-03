<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

$conexion = new mysqli('mysql', 'root', 'pass', 'gamefest');
$conexion->set_charset('utf8mb4');

$q = $_GET['q'] ?? '';
$id = $_GET['id'] ?? '';

if (($id && is_numeric($id)) || (is_numeric($q))) {
    $realId = !empty($id) ? $id : $q;

    $stmt = $conexion->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->bind_param("i", $realId);
    $stmt->execute();
    $result = $stmt->get_result();
    $juego = $result->fetch_assoc();

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

    $lista = [];
    while ($fila = $result->fetch_assoc()) {
        $fila['plataformas'] = json_decode($fila['plataformas']);
        $lista[] = $fila;
    }
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
}
?>