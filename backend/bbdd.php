<?php
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    define('SERVIDOR', 'localhost');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', '12345');
    $conexion = new mysqli(SERVIDOR, USUARIO, null, BBDD);
    $conexion->set_charset('utf8mb4');
    $query="SELECT * FROM games";
    $resultado = $conexion->query($query);

    $dato=$resultado->fetch_all();
    print json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $resultado->free();
    $conexion->close();
?>