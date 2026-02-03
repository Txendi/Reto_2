<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

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

//Leer datos
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina'])
    ? (int) $_GET['pagina']
    : 0;

$tipo = $_GET['tipo'] ?? '';
$fecha = $_GET['fecha'] ?? '';
$plazas = $_GET['plazas'] ?? '';

$porPagina = 9;
$eventosPagina = $pagina * $porPagina;


//SQL eventos

$sqlEventos = "SELECT * FROM events WHERE 1=1";
$sqlTotal   = "SELECT COUNT(*) AS total FROM events WHERE 1=1";

//Filtros de tipos, fecha y plazas

if ($tipo !== '') {
    $tipoEleg = $conexion->real_escape_string($tipo);
    $sqlEventos .= " AND tipo = '$tipoEleg'";
    $sqlTotal .= " AND tipo = '$tipoEleg'";
}

if ($fecha !== '') {
    $fechaEleg = $conexion->real_escape_string($fecha);
    $sqlEventos .= " AND fecha = '$fechaEleg'";
    $sqlTotal .= " AND fecha = '$fechaEleg'";
}

if ($plazas === '1') {
    $sqlEventos .= " AND plazasLibres > 0";
    $sqlTotal .= " AND plazasLibres > 0";
}


//Total de paginas existentes

$resTotal = $conexion->query($sqlTotal);
$fila = $resTotal->fetch_assoc();
$totalEventos = (int) $fila['total'];
$totalPaginas = ceil($totalEventos / $porPagina);
$resTotal->free();

// ==============================
//  Paginación
// ==============================
$sqlEventos .= " LIMIT $porPagina OFFSET $eventosPagina";
$resEventos = $conexion->query($sqlEventos);
$eventos = $resEventos->fetch_all(MYSQLI_ASSOC);
$resEventos->free();

// ==============================
//  Respuesta final
// ==============================
echo json_encode([
    "eventos" => $eventos,
    "totalPaginas" => $totalPaginas,
], JSON_UNESCAPED_UNICODE);





$conexion-> close();
