 <?php
    session_start();
    define('SERVIDOR', 'mysql');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', 'pass');

    require_once 'funciones.php';

    $conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
    $conexion->set_charset('utf8mb4');

    $sql = "SELECT * FROM events as e JOIN user_events as ue ON e.id = ue.event_id WHERE user_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("d", $_GET["id"]);

    $stmt->execute();
    $result = $stmt->get_result();
    $eventos = $result->fetch_all(MYSQLI_ASSOC);
    print json_encode($eventos);

    $conexion->close();
?>