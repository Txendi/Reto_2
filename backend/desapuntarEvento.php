 <?php
    define('SERVIDOR', 'mysql');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', 'pass');

    require_once 'funciones.php';

    $idUsuario = $_GET['idUsuario'] ?? '';
    $idEvento = $_GET['idEvento'] ?? '';

    $conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
    $conexion->set_charset('utf8mb4');

    $sql = "DELETE FROM user_events WHERE user_id = ? AND event_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("dd", $idUsuario, $idEvento);

    $stmt->execute();
    $result = $stmt->get_result();
    $eventos = $result->fetch_all(MYSQLI_ASSOC);
    print json_encode($eventos);

    $conexion->close();
?>