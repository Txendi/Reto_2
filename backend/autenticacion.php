<?php
// Permitir el origen de tu frontend en desarrollo
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Manejo inmediato del preflight (petición OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents("php://input"), true);

$usuario  = trim($data['usuario'] ?? '');
$email    = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

$errores = [];

// Validaciones
if ($usuario === '' || $email === '' || $password === '') {
    $errores[] = "Campos obligatorios vacíos";
}

if (!preg_match('/^[a-z0-9]{3,50}$/i', $usuario)) {
    $errores[] = "Formato de usuario incorrecto";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Formato de email incorrecto";
}

if (strlen($password) < 6) {
    $errores[] = "Contraseña demasiado corta";
}

if (!empty($errores)) {
    http_response_code(400);
    echo json_encode([
      "status" => "error",
      "debug" => "Validación fallida",
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
  INSERT INTO users (username, email, password_hash, role)
  VALUES (?, ?, ?, 'USER')
");
$stmt->bind_param("sss", $usuario, $email, $hash);

if ($stmt->execute()) {
    echo json_encode([
      "status" => "ok",
      "debug" => "Usuario insertado correctamente",
      "data" => [
        "id" => $stmt->insert_id,
        "username" => $usuario,
        "email" => $email
      ]
    ]);
} else {
    http_response_code(500);
    echo json_encode([
      "status" => "error",
      "debug" => "Fallo en INSERT"
    ]);
}

$stmt->close();
$conexion->close();
