<?php
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    define('SERVIDOR', 'localhost');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', '12345');
    $conexion = new mysqli(SERVIDOR, USUARIO, null, BBDD);

    $conexion->set_charset('utf8mb4');
    $query="SELECT * FROM games";
    $resultado = $conexion->query($query);
    while($fila=$resultado->fetch_assoc()){
      print "<p>$fila[titulo]</p>\n";
    }
    $resultado->free();
    $conexion->close();
?>