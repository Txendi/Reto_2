<?php
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    define('SERVIDOR', 'mysql');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', 'pass');

    require_once 'funciones.php';

    $conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
    $conexion->set_charset('utf8mb4');

    // Leer el body
    $input = file_get_contents('php://input');

    // Convertir JSON → array PHP
    $data = json_decode($input, true);

    // Usar los datos
    $tipo = $data['tipo'] ?? null;
    $fecha   = $data['fecha'] ?? null; 
    $plazasLibres   = $data['plazasLibres'] ?? null; 

    $sql = "SELECT * FROM usuarios WHERE 1=1";
    $params = [];
    $types  = "";

    // Filtro tipo
    if (!empty($tipo)) {
    $sql .= " AND nombre LIKE ";
    $params[] = "%" . $_POST['tipo'] . "%";
    $types .= "s";
    }

    // Filtro email
    if (!empty($_POST['fecha'])) {
    $sql .= " AND fecha = ?";
    $params[] = $_POST['fecha'];
    $types .= "s";
    }

    // Filtro edad
    if (!empty($_POST['plazasLibres'])) {
    $sql .= " AND plazasLibres = ?";
    $params[] = $_POST['plazasLibres'];
    $types .= "i";
    }

    $stmt = $conn->prepare($sql);

    if ($params) {
    $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $eventos = $result->fetch_all(MYSQLI_ASSOC);
    $array = filter_eventos( $eventos,  $_GET["tipo"] ?? "",  $_GET["fecha"] ?? "",  $_GET["plazas"] ?? "");
    print json_encode([ceil(count($array)/9), array_slice($array, $_GET["pagina"] *9, 9)]);
    $resultado->free();

    $conexion->close();
?>