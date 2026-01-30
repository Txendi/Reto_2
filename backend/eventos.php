<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

error_reporting(0);
ini_set('display_errors', 0);

define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

define('CURRENT_USER_ID', 2); // temporal para simular un user

/* require_once 'funciones.php'; */

$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');

$accion = $_GET['accion'] ?? 'listar';

if ($accion === 'listar') {

    $porPagina = 9;

    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) && $_GET['pagina'] > 0
        ? (int) $_GET['pagina']
        : 1;

    $offset = ($pagina - 1) * $porPagina;

    $tipo = $_GET['tipo'] ?? '';
    $fecha = $_GET['fecha'] ?? '';
    $plazas = $_GET['plazas'] ?? '';

    // Tipos para filtro
    $sqlTipos = "SELECT DISTINCT tipo FROM events ORDER BY tipo";
    $resTipos = $conexion->query($sqlTipos);
    $tipos = array_column($resTipos->fetch_all(MYSQLI_ASSOC), 'tipo');
    $resTipos->free();

    // Base SQL
    $sqlEventos = "SELECT * FROM events WHERE 1=1";
    $sqlTotal = "SELECT COUNT(*) AS total FROM events WHERE 1=1";

    if ($tipo !== '') {
        $tipo = $conexion->real_escape_string($tipo);
        $sqlEventos .= " AND tipo = '$tipo'";
        $sqlTotal .= " AND tipo = '$tipo'";
    }

    if ($fecha !== '') {
        $fecha = $conexion->real_escape_string($fecha);
        $sqlEventos .= " AND fecha = '$fecha'";
        $sqlTotal .= " AND fecha = '$fecha'";
    }

    if ($plazas === '1') {
        $sqlEventos .= " AND plazasLibres > 0";
        $sqlTotal .= " AND plazasLibres > 0";
    }

    // Total páginas
    $resTotal = $conexion->query($sqlTotal);
    $fila = $resTotal->fetch_assoc();
    $totalPaginas = ceil((int) $fila['total'] / $porPagina);
    $resTotal->free();

    // Paginación
    $sqlEventos .= " ORDER BY fecha, hora LIMIT $porPagina OFFSET $offset";
    $resEventos = $conexion->query($sqlEventos);
    $eventos = $resEventos->fetch_all(MYSQLI_ASSOC);
    $resEventos->free();

    echo json_encode([
        'eventos' => $eventos,
        'totalPaginas' => $totalPaginas,
        'tipos' => $tipos
    ]);
    exit;
}

// ==============================
// APUNTARSE A EVENTO (POST)
// ==============================
if ($accion === 'signup') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        exit;
    }

    $eventId = (int) ($_GET['id'] ?? 0);
    $userId = CURRENT_USER_ID;

    if ($eventId <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido']);
        exit;
    }

    $conexion->begin_transaction();

    // Bloquear evento
    $stmt = $conexion->prepare(
        "SELECT plazasLibres FROM events WHERE id = ? FOR UPDATE"
    );
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $evento = $stmt->get_result()->fetch_assoc();

    if (!$evento || $evento['plazasLibres'] <= 0) {
        $conexion->rollback();
        echo json_encode(['error' => 'No hay plazas']);
        exit;
    }

    // Comprobar duplicado
    $stmt = $conexion->prepare(
        "SELECT 1 FROM user_events WHERE user_id = ? AND event_id = ?"
    );
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {
        $conexion->rollback();
        echo json_encode(['error' => 'Ya inscrito']);
        exit;
    }

    // Insertar inscripción
    $stmt = $conexion->prepare(
        "INSERT INTO user_events (user_id, event_id) VALUES (?, ?)"
    );
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();

    // Reducir plazas
    $stmt = $conexion->prepare(
        "UPDATE events SET plazasLibres = plazasLibres - 1 WHERE id = ?"
    );
    $stmt->bind_param("i", $eventId);
    $stmt->execute();

    $conexion->commit();
    echo json_encode(['ok' => true]);
    exit;
}

// ==============================
// CANCELAR INSCRIPCIÓN (DELETE)
// ==============================
if ($accion === 'cancelar') {

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        exit;
    }

    $eventId = (int) ($_GET['id'] ?? 0);
    $userId = CURRENT_USER_ID;

    $conexion->begin_transaction();

    // Comprobar inscripción
    $stmt = $conexion->prepare(
        "SELECT 1 FROM user_events WHERE user_id = ? AND event_id = ?"
    );
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        $conexion->rollback();
        echo json_encode(['error' => 'No estabas inscrito']);
        exit;
    }

    // Borrar inscripción
    $stmt = $conexion->prepare(
        "DELETE FROM user_events WHERE user_id = ? AND event_id = ?"
    );
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();

    // Liberar plaza
    $stmt = $conexion->prepare(
        "UPDATE events SET plazasLibres = plazasLibres + 1 WHERE id = ?"
    );
    $stmt->bind_param("i", $eventId);
    $stmt->execute();

    $conexion->commit();
    echo json_encode(['ok' => true]);
    exit;
}

// ==============================
// MIS EVENTOS (GET)
// ==============================
if ($accion === 'mis_eventos') {

    $userId = CURRENT_USER_ID;

    $stmt = $conexion->prepare("
        SELECT e.*
        FROM events e
        JOIN user_events ue ON ue.event_id = e.id
        WHERE ue.user_id = ?
        ORDER BY e.fecha, e.hora
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

// ==============================
// ACCIÓN NO VÁLIDA
// ==============================
http_response_code(400);
echo json_encode(['error' => 'Acción no válida']);
$conexion->close();