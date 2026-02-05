<?php
session_start();
require_once "conexion.php";


/* Comprobación de sesión */
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["error" => "No autenticado"]);
    exit;
}

/* ===== LÓGICA ===== */

$data = json_decode(file_get_contents("php://input"), true);

$idUsuario = $_SESSION['id'];          // 👈 viene de la sesión
$idEvento  = $data['idEvento'] ?? null;

if (!$idEvento) {
    http_response_code(400);
    echo json_encode(["error" => "Evento no válido"]);
    exit;
}

$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset("utf8mb4");

/* (Opcional pero recomendable) evitar duplicados */
$stmt = $conexion->prepare(
    "SELECT 1 FROM user_events WHERE user_id = ? AND event_id = ?"
);
$stmt->bind_param("ii", $idUsuario, $idEvento);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        "status" => "ok",
        "message" => "Ya estaba apuntado"
    ]);
    exit;
}

/* Insert */
$stmt = $conexion->prepare(
    "INSERT INTO user_events (user_id, event_id) VALUES (?, ?)"
);
$stmt->bind_param("ii", $idUsuario, $idEvento);
$stmt->execute();

echo json_encode([
    "status" => "ok",
    "idUsuario" => $idUsuario,
    "idEvento" => $idEvento
]);

$conexion->close();
