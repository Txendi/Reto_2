<?php
session_start();
require_once "conexion.php";

// $input = file_get_contents('php://input');
// $data = json_decode($input, true);

// if (!$data) {
//     echo json_encode(['ok' => false, 'error' => 'No se han recibido datos JSON válidos']);
//     exit;
// }

$titulo = $_POST['titulo'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$plazas = (int) ($_POST['plazas'] ?? 0);
$descripcion = $_POST['descripcion'] ?? '';

$dir = __DIR__ . '/img/eventos'; // carpeta donde subes imágenes

if (!file_exists($dir)) {
    die("❌ La carpeta 'img' no existe");
}

if (is_writable($dir)) {
    echo "✅ La carpeta img ES escribible<br><br>";
} else {
    echo "❌ La carpeta img NO es escribible<br><br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['imagen'])) {
        die("No se envió ningún archivo");
    }

    $file = $_FILES['imagen'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Error en la subida: " . $file['error']);
    }

    // Verificar que sea imagen real
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);

    $permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    if (!in_array($mime, $permitidos)) {
        die("❌ El archivo no es una imagen válida ($mime)");
    }

    // Nombre aleatorio seguro
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nuevoNombre = bin2hex(random_bytes(8)) . "." . $ext;

    $destino = $dir . '/' . $nuevoNombre;

    if (move_uploaded_file($file['tmp_name'], $destino)) {
        echo "✅ Imagen subida correctamente<br>";
        echo "Ruta: img/$nuevoNombre<br>";
        echo "<img src='img/$nuevoNombre' style='max-width:300px;margin-top:10px;'>";
    } else {
        echo "❌ No se pudo mover el archivo a la carpeta img";
    }
}
// $nombreImagen = 'default.jpg';

// if ($imagenBase64) {
//     $partes = explode(',', $imagenBase64);

//     if (isset($partes[1])) {
//         $datosBinarios = base64_decode($partes[1]);

//         $nombreImagen = time() . ".jpg";
//         $rutaDestino = "img/events/";

//         if (!is_dir($rutaDestino)) {
//             mkdir($rutaDestino, 0777, true);
//         }

//         file_put_contents($rutaDestino . $nombreImagen, $datosBinarios);
//     }
// }

$sql = "INSERT INTO events (titulo, tipo, fecha, hora, plazasLibres, imagen, descripcion, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
$stmt = $conexion->prepare($sql);

$stmt->bind_param("ssssiss", $titulo, $tipo, $fecha, $hora, $plazas, $nuevoNombre, $descripcion);

if ($stmt->execute()) {
    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['ok' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>