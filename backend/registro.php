<?php
session_start();
// Cabeceras CORS completas

define('SERVIDOR', 'mysql');
define('BBDD', 'gamefest');
define('USUARIO', 'root');
define('CLAVE', 'pass');

// Manejo del preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

$conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
$conexion->set_charset('utf8mb4');

if ($conexion->connect_error) {
  http_response_code(500);
  echo json_encode([
    "status" => "error",
    "debug" => "Error conexion BD"
  ]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$usuario  = trim($data['usuario'] ?? '');
$email    = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

$errores = [];

// Validaciones
if ($usuario === '' || $email === '' || $password === '') {
  $errores[] = "Campos obligatorios vacios";
}

if (!preg_match('/^[a-z0-9]{3,50}$/i', $usuario)) {
  $errores[] = "Formato de usuario incorrecto";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errores[] = "Formato de email incorrecto";
}

if (strlen($password) < 6) {
  $errores[] = "Contrasena demasiado corta";
}

if (!empty($errores)) {
  http_response_code(400);
  echo json_encode([
    "status" => "error",
    "debug" => "Validacion fallida",
    "errors" => $errores
  ]);
  exit;
}

// Comprobar duplicados
$stmt = $conexion->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $usuario, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
  http_response_code(409);
  echo json_encode([
    "status" => "error",
    "debug" => "Usuario o email ya existen"
  ]);
  $stmt->close();
  exit;
}
$stmt->close();

// Hash
$hash = password_hash($password, PASSWORD_BCRYPT);

// Insert
$stmt = $conexion->prepare("
  INSERT INTO users (username, email, password_hash, role, created_at)
  VALUES (?, ?, ?, 'USER', NOW())");
$stmt->bind_param("sss", $usuario, $email, $hash);

if ($stmt->execute()) {
  $_SESSION["id"]       = $stmt->insert_id;
  $_SESSION["username"] = $usuario;
  $_SESSION["email"]    = $email;
  $_SESSION["role"]     = "USER";
  $_SESSION["autenticado"] = true;

  $respuesta = [
    "status" => "success",
    "nuevoUsuario" => [
      "id"       => $_SESSION["id"],
      "username" => $_SESSION["username"],
      "email"    => $_SESSION["email"],
      "role"     => $_SESSION["role"],
      "autenticado" => $_SESSION["autenticado"]
    ],
    "listaUsuarios" => []
  ];


  $result = $conexion->query("SELECT id, username, email, password_hash as contra, role FROM users ORDER BY id");
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $respuesta["listaUsuarios"][] = $row;
    }
  }

  echo json_encode($respuesta);
} else {
  http_response_code(500);
  echo json_encode([
    "status" => "error",
    "debug" => "Fallo en INSERT"
  ]);
}

$stmt->close();
$conexion->close();
