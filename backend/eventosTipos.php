<?php
session_start();

require_once "conexion.php";

$sql = "SELECT DISTINCT tipo FROM events ORDER BY tipo";
$res = $conexion-> query($sql);

$tipos = array_column($res->fetch_all(MYSQLI_ASSOC), 'tipo');

echo json_encode($tipos, JSON_UNESCAPED_UNICODE);
$conexion->close();

?>