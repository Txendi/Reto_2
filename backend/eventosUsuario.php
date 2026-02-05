<?php
    session_start();

require_once "conexion.php";

    if (!isset($_SESSION['id'])) {
        http_response_code(401);
        echo json_encode([]);
        exit;
    }


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