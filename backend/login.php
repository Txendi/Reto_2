<?php
session_start();

require_once "conexion.php";

// Usar bloque try-catch para capturar errores de mysqli sin que impriman texto
    $json_input = file_get_contents("php://input");
    $data = json_decode($json_input, true);

    $usuario    = trim($data['usuario'] ?? '');
    $contrasena = $data['password'] ?? ''; // No hagas trim a contraseñas, los espacios cuentan
    $errores    = [];

    if ($usuario === '' || $contrasena === '') {
        $errores[] = "Campos obligatorios vacíos";
    } else {
        $stmt = $conexion->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        // Importante: Vincular antes del fetch
        $stmt->bind_result($idBD, $nombreBD, $hashBD);

        if ($stmt->fetch()) {
            // Verificamos si el hash existe y es válido
            if (empty($hashBD) || !password_verify($contrasena, $hashBD)) {
                $errores[] = "La contraseña no es correcta";
            } else {
                // Login exitoso - crear sesión
                $_SESSION['id'] = $idBD;
                $_SESSION['username'] = $nombreBD;

                echo json_encode([
                    "status" => "ok",
                    "debug" => "Login exitoso",
                    "usuarioLogeado" => [
                        "id" => $idBD,
                        "username" => $nombreBD
                    ]
                ]);
                $stmt->close();
                $conexion->close();
                exit;
            }
        } else {
            $errores[] = "Usuario no encontrado";
        }
        $stmt->close();
    }

    if (!empty($errores)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "debug" => "Login fallido",
            "errors" => $errores
        ]);
    }


$conexion->close();
