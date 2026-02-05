<?php
    session_start();
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
?>