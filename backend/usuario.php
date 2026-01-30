 <?php
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    define('SERVIDOR', 'localhost');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', '12345');

    require_once 'funciones.php';

    $conexion = new mysqli(SERVIDOR, USUARIO, null, BBDD);
    $conexion->set_charset('utf8mb4');

    if(isset($_COOKIE['usuario']) && !preg_match('/^\s*$/',$_COOKIE['usuario'])){
        setcookie('usuario',$_COOKIE['usuario'],strtotime('+ 1 month'));
    }
?>