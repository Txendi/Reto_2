<?php
// header('Content-Type: application/json; charset=utf-8');
// header("Access-Control-Allow-Origin: *");
session_start();
error_reporting(0);
ini_set('display_errors', 0);

/* error_reporting(E_ALL);
ini_set('display_errors', 1); */


define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

/* require_once 'funciones.php'; */

$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');
$conexion->query("SET NAMES utf8mb4");


$sql = "SELECT DISTINCT tipo FROM events ORDER BY tipo";
$res = $conexion-> query($sql);

$tipos = array_column($res->fetch_all(MYSQLI_ASSOC), 'tipo');

echo json_encode($tipos, JSON_UNESCAPED_UNICODE);
$conexion->close();

?>