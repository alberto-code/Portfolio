<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
session_start();

header('Content-Type: application/json'); // Para que el servidor responda con JSON

// Verificar si el usuario está logueado
if (empty($_SESSION["usuario"])) {
    echo json_encode(['status' => 'error', 'message' => 'No ha iniciado sesión']);
    exit();
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$tipo = $_POST['tipo'];
$codigo = $_POST['codigo'];
$TONE = isset($_POST['TONE']) ? 1 : 0;
$usuario = $_SESSION['usuario'];
$fecha_registro = date("Y-m-d H:i:s");

// Procesar la imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imagen_temp = $_FILES['imagen']['tmp_name'];
    $nombre_imagen = mysqli_real_escape_string($enlace, $_FILES['imagen']['name']);
    $ruta_destino = '../../img/Elementos/' . $nombre_imagen;

    // Mover la imagen a la ubicación deseada
    if (move_uploaded_file($imagen_temp, $ruta_destino)) {
        // Inserción en la base de datos con imagen
        $sql = "INSERT INTO elementos (nombre, codigo, tipo, TONE, imagen) 
                VALUES ('$nombre', '$codigo', '$tipo', '$TONE', '$ruta_destino')";

        if (mysqli_query($enlace, $sql)) {
            $elementoId = mysqli_insert_id($enlace); // Obtener el ID del elemento recién insertado
            
            // Inserción en la tabla registro
            $sql_registro = "INSERT INTO registro (tecnico, fecha, descripcion) 
                             VALUES ('$usuario', '$fecha_registro', 'Elemento Registrado con imagen')";
            mysqli_query($enlace, $sql_registro);
            
            echo json_encode(['status' => 'success', 'message' => 'Elemento registrado exitosamente con imagen', 'data' => ['id' => $elementoId]]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar el elemento: ' . mysqli_error($enlace)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al mover la imagen a la ubicación deseada']);
    }
} else {
    // Inserción en la base de datos sin imagen
    $sql = "INSERT INTO elementos (nombre, codigo, tipo, TONE, imagen) 
            VALUES ('$nombre', '$codigo', '$tipo', '$TONE', '')";

    if (mysqli_query($enlace, $sql)) {
        $elementoId = mysqli_insert_id($enlace); // Obtener el ID del elemento recién insertado
        
        // Inserción en la tabla registro
        $sql_registro = "INSERT INTO registro (tecnico, fecha, descripcion) 
                         VALUES ('$usuario', '$fecha_registro', 'Elemento Registrado sin imagen')";
        mysqli_query($enlace, $sql_registro);
        
        echo json_encode(['status' => 'success', 'message' => 'Elemento registrado exitosamente sin imagen', 'data' => ['id' => $elementoId]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el elemento: ' . mysqli_error($enlace)]);
    }
}
?>
