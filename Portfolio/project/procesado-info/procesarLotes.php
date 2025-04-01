<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
session_start();

if (empty($_SESSION["usuario"])) {
    header("Location: /Portfolio/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $lote = $_POST['lote'];
    $stock_lote = $_POST['stock_lote'];
    $ID_elemento = $_POST['ID_elemento'];
    $usuario = $_SESSION['usuario'];  // Obtener el usuario de la sesión

    // Insertar datos en la tabla 'lote'
    $sql = "INSERT INTO lote (lote, stock_lote, ID_elementos) VALUES ('$lote', '$stock_lote', '$ID_elemento')";
    if (mysqli_query($enlace, $sql)) {
        // Obtener el ID del lote recién insertado
        $id_lote = mysqli_insert_id($enlace);

        // Insertar datos en la tabla 'pale'
        $all_inserts_successful = true;
        for ($i = 1; $i <= $stock_lote; $i++) {
            $nombre_pale = $lote . "-" . $i;
            $sql2 = "INSERT INTO pale (nombre, Id_lote) VALUES ('$nombre_pale', '$id_lote')";
            if (!mysqli_query($enlace, $sql2)) {
                $all_inserts_successful = false;
                break;
            }
        }

        // Insertar datos en la tabla 'registro'
        if ($all_inserts_successful) {
            $current_date_time = date('Y-m-d H:i:s');
            $sql3 = "INSERT INTO registro (fecha, tecnico, descripcion) 
                     VALUES ('$current_date_time', '$usuario', 'Lote $lote registrado. Cantidad añadida: $stock_lote')";
            if (mysqli_query($enlace, $sql3)) {
                header("Location: /Portfolio/project/confirmaciones/confirmacion.php");
            } else {
                header("Location: /Portfolio/project/confirmaciones/error.php");
            }
        } else {
            header("Location: /Portfolio/project/confirmaciones/error.php");
        }
        exit();
    } else {
        // Redirigir a la página de error si falla la inserción en 'lote'
        header("Location: /Portfolio/project/confirmaciones/error.php");
        exit();
    }
}
?>
