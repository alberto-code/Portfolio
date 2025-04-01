<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
session_start();

header('Content-Type: application/json'); // Asegurarse de enviar JSON
$response = []; // Inicializar la respuesta como un array

if (empty($_SESSION["usuario"])) {
    echo json_encode(['status' => 'error', 'message' => 'No ha iniciado sesión']);
    exit();
}

$ID = $_POST['ID'];
$email = $_POST['email'];
$contrasena = $_POST['contrasena'];
$imagen = $_FILES['imagen'];
$rol = $_POST['rol'];

// Actualizar el email
if (!empty($email)) {
    $stmt = $enlace->prepare("UPDATE tecnico SET email = ? WHERE ID = ?");
    $stmt->bind_param("si", $email, $ID);
    $stmt->execute();
    $stmt->close();
}

// Actualizar el rol
if (!empty($rol)) {
    $stmt = $enlace->prepare("UPDATE tecnico SET rol = ? WHERE ID = ?");
    $stmt->bind_param("si", $rol, $ID);
    $stmt->execute();
    $stmt->close();
}

// Actualizar la contraseña
if (!empty($contrasena)) {
    $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $enlace->prepare("UPDATE tecnico SET contrasena = ? WHERE ID = ?");
    $stmt->bind_param("si", $hash_contrasena, $ID);
    $stmt->execute();
    $stmt->close();
}

if ($imagen['error'] === UPLOAD_ERR_OK) {
    $imagen_temp = $imagen['tmp_name'];
    $nombre_imagen = time() . '_' . basename($imagen['name']);
    $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . '/Portfolio/img/Tecnicos/' . $nombre_imagen;

    // Verifica que el directorio de destino exista antes de mover el archivo
    if (!file_exists(dirname($ruta_destino))) {
        mkdir(dirname($ruta_destino), 0755, true); // Crea el directorio si no existe
    }

    if (move_uploaded_file($imagen_temp, $ruta_destino)) {
        $stmt = $enlace->prepare("UPDATE tecnico SET imagen = ? WHERE ID = ?");
        $stmt->bind_param("si", $nombre_imagen, $ID);
        $stmt->execute();
        $stmt->close();
        $response['imagen'] = '/Portfolio/img/Tecnicos/' . $nombre_imagen;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al mover la imagen']);
        exit();
    }
}


$response['status'] = 'success';
$response['message'] = 'Actualización completada exitosamente';

// Enviar respuesta JSON al cliente
echo json_encode($response);
exit();

?>
