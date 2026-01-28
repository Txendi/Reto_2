<?php
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    define('SERVIDOR', 'mysql');
    define('BBDD', 'gamefest');
    define('USUARIO', 'root');
    define('CLAVE', 'pass');
    $conexion = new mysqli(SERVIDOR, USUARIO, CLAVE, BBDD);
    $conexion->set_charset('utf8mb4');
    

    function filter_juegos(array $juegos, string $query): array {
        $result = [];
        $query = trim($query);

        foreach ($juegos as $j) {
            $j["plataformas"] = json_decode( $j["plataformas"]);
            if ($query !== "") {
                $inTitulo  = stripos($j["titulo"], $query);
                $inGenero = stripos($j["genero"], $query);
                $inPlataforma = false;
                foreach($j["plataformas"] as $plat){
                    $inPlataforma = stripos($plat, $query);
                    if($inPlataforma !== false){
                        break;
                    }
                }
                if ($inTitulo === false && $inGenero === false && $inPlataforma === false) {
                    continue;
                }
            }
            $result[] = $j;
        }
        return $result;
    }
    function filter_eventos(array $eventos, string $tipo, string $fecha, string $plazas): array {
        $result = [];
        foreach ($eventos as $e) {
            if ($tipo !== "" && $e['tipo'] !== $tipo) {
                continue;
            }
            if ($fecha !== "" && $e['fecha'] !== $fecha) {
                continue;
            }
            if ($plazas !== "" && $e['plazas'] === 0) {
                continue;
            }
            $result[] = $e;
        }
        return $result;
    }


    $action = $_GET["action"] ?? "";

    if ($action === "listaJuegos") {

        $query = "SELECT * FROM games";
        $resultado = $conexion->query($query);
        $canciones = $resultado->fetch_all(MYSQLI_ASSOC );
        print  json_encode(filter_juegos($canciones, $_GET["q"] ?? ""));
        $resultado->free();

    }else if ($action === "listaEventos"){
        $query = "SELECT * FROM events";
        $resultado = $conexion->query($query);
        $eventos = $resultado->fetch_all(MYSQLI_ASSOC );
        // $array = filter_eventos( $eventos,  $_GET["tipo"] ?? "",  $_GET["fecha"] ?? "",  $_GET["plazas"] ?? "");
        // print json_encode([ceil(count($array)/9), array_slice($array, $_GET["pagina"] *9, 9)]);
        print json_encode($eventos);
        $resultado->free();

        //filter_eventos();
    }else if($action === "numeroPaginas"){
        $query = "SELECT COUNT(*) FROM events";
        $resultado = $conexion->query($query);
        print $resultado->fetch_all(MYSQLI_ASSOC )/9;
        $resultado->free();

    }else if($action === "registrar"){
        $query = "INSERT INTO users VALUES ({$_GET['usuario']}, {$_GET['email']}, {$_GET['contrasena']}, USER, now())";
         $conexion->query($query);
    }else if($action === "logearse"){
    }
   
    // $query = "SELECT * FROM games";
    // $resultado = $conexion->query($query);
    // $dato = $resultado->fetch_all(MYSQLI_ASSOC);
    // print var_dump($dato);
    // print json_encode($dato, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


    $conexion->close();
?>