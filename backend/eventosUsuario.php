<?php
    session_start();

    // header("Access-Control-Allow-Origin: http://localhost:5173");
    // header("Access-Control-Allow-Credentials: true");
    // header("Access-Control-Allow-Methods: GET, OPTIONS");
    // header("Access-Control-Allow-Headers: Content-Type");
    // header("Content-Type: application/json; charset=utf-8");

    if (!isset($_SESSION['id'])) {
        http_response_code(401);
        echo json_encode([]);
        exit;
    }

    define('SERVIDOR', 'mysql');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', 'pass');

    $conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
    $conexion->set_charset('utf8mb4');

    $idUsuario = $_SESSION['id'];

    $sql = "SELECT * FROM events as e JOIN user_events as ue ON e.id = ue.event_id WHERE user_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("d", $idUsuario);

    $stmt->execute();
    $result = $stmt->get_result();
    $eventos = $result->fetch_all(MYSQLI_ASSOC);
    print json_encode($eventos, JSON_UNESCAPED_UNICODE);

    $conexion->close();
    ?>