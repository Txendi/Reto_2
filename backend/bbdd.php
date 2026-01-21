<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$conexion = new mysqli('localhost', 'root', '', 'gamefest');
$conexion->set_charset('utf8mb4');

$query = "SELECT id, titulo, genero, plataformas, imagen, descripcion FROM games";
$resultado = $conexion->query($query);

$juegos = [];

while ($fila = $resultado->fetch_assoc()) {
  /* $fila['plataformas'] = json_decode($fila['plataformas']); // JSON → array PHP */
  $juegos[] = $fila;
}

echo json_encode($juegos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$resultado->free();
$conexion->close();
