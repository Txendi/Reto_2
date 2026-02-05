 <?php
    session_start();
    define('SERVIDOR', 'mysql');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', 'pass');

    require_once 'funciones.php';
    $data = json_decode(file_get_contents('php://input'), true);

    $idUsuario = $data['idUsuario'] ?? '';
    $idEvento = $data['idEvento'] ?? '';

    $conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
    $conexion->set_charset('utf8mb4');

    $sql = "DELETE FROM user_events WHERE user_id = ? AND event_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("dd", $idUsuario, $idEvento);

    $stmt->execute();
    
    $conexion->close();
?>