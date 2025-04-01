<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
session_start();

header('Content-Type: application/json'); // Para que la respuesta sea en formato JSON

// Verificar si el usuario está autenticado
if (empty($_SESSION["usuario"])) {
    echo json_encode(['status' => 'error', 'message' => 'No ha iniciado sesión']);
    exit();
}

// Obtener los datos del formulario
$usuario = $_POST['usuario'];
$email = $_POST['email'];
$contrasena = $_POST['contrasena'];
$rol = $_POST['rol'];
$usuario_registrador = $_SESSION['usuario'];  // Obtener el usuario que realiza el registro
$current_date_time = date('Y-m-d H:i:s');  // Obtener la fecha y hora actual

// Hashear la contraseña
$hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Obtener la información de la imagen subida
$imagen = $_FILES['imagen'];

if ($imagen['error'] === UPLOAD_ERR_OK) {
    $imagen_temp = $imagen['tmp_name'];
    $nombre_imagen = mysqli_real_escape_string($enlace, $imagen['name']);
    $ruta_destino = '../../img/Tecnicos/' . $nombre_imagen;

    if (move_uploaded_file($imagen_temp, $ruta_destino)) {
        // Preparar la consulta con el campo rol e imagen
        $stmt = $enlace->prepare("INSERT INTO tecnico (usuario, email, contrasena, imagen, rol) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $usuario, $email, $hash, $ruta_destino, $rol);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al mover la imagen']);
        exit();
    }
} else {
    // Preparar la consulta sin el campo imagen
    $stmt = $enlace->prepare("INSERT INTO tecnico (usuario, email, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $usuario, $email, $hash, $rol);
}

// Ejecutar la consulta
if ($stmt->execute()) {
    // Obtener el ID del técnico recién insertado
    $tecnico_id = $stmt->insert_id;
    
    // Insertar en la tabla 'registro' la operación realizada
    $stmt_registro = $enlace->prepare("INSERT INTO registro (fecha, tecnico, descripcion) VALUES (?, ?, ?)");
    $descripcion = "Técnico $usuario registrado";
    $stmt_registro->bind_param("sss", $current_date_time, $usuario_registrador, $descripcion);

    if ($stmt_registro->execute()) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Técnico registrado exitosamente',
            'data' => [
                'id' => $tecnico_id,
                'usuario' => $usuario,
                'email' => $email,
                'rol' => $rol,
                'imagen' => $imagen['error'] === UPLOAD_ERR_OK ? $ruta_destino : '' // Si no hay imagen, se envía vacío
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar la operación: ' . $stmt_registro->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
}

// Cerrar las sentencias
$stmt->close();
$stmt_registro->close();
?>
