<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

// Verificar si el usuario está autenticado
if (!empty($_SESSION["usuario"])) {
    $tecnico = $_SESSION["usuario"]; // Obtener el usuario de la sesión actual
    $fecha = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
    $descripcion = "El técnico $tecnico ha cerrado sesión."; // Descripción del evento de logout

    // Registrar el logout en la tabla 'registro'
    $sqlRegistroLogout = "INSERT INTO registro (tecnico, fecha, descripcion) VALUES ('$tecnico', '$fecha', '$descripcion')";
    if (mysqli_query($enlace, $sqlRegistroLogout)) {
        // Si se registró correctamente el logout, destruir la sesión
        session_unset(); // Elimina todas las variables de sesión
        session_destroy(); // Destruye la sesión

        // Redirigir al usuario a la página de inicio de sesión
        header("Location: login.php"); // Redirige a la página de inicio de sesión
        exit();
    } else {
        // Mostrar el error en caso de fallo en la inserción
        echo "Error al registrar el logout: " . mysqli_error($enlace);
    }
} else {
    // Si no hay una sesión activa, redirigir al inicio de sesión
    header("Location: login.php");
    exit();
}
?>
