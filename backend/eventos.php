<?php
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    define('SERVIDOR', 'localhost');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', '12345');

    require_once 'funciones.php';

    $conexion = new mysqli(SERVIDOR, USUARIO, null, BBDD);
    $conexion->set_charset('utf8mb4');
    
    
    
    $query = "SELECT * FROM events";
    $resultado = $conexion->query($query);
    $eventos = $resultado->fetch_all(MYSQLI_ASSOC );
    $array = filter_eventos( $eventos,  $_GET["tipo"] ?? "",  $_GET["fecha"] ?? "",  $_GET["plazas"] ?? "");
    print json_encode([ceil(count($array)/9), array_slice($array, $_GET["pagina"] *9, 9)]);
    $resultado->free();





    $conexion->close();
?>