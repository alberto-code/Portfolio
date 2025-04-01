<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtén el nombre del palet a eliminar
    $nombre_pale = mysqli_real_escape_string($enlace, $_POST['nombre']);

    // 1. Obtener el ID_pale, ID_lote, nombre del lote, y ID_elemento basados en el nombre del palet
    $sql_get_lote_elemento = "SELECT p.ID_pale, p.ID_lote, l.ID_elementos, l.nombre_lote 
                              FROM pale p 
                              INNER JOIN lote l ON p.ID_lote = l.Id_lote 
                              WHERE p.nombre = '$nombre_pale'";
    $result = mysqli_query($enlace, $sql_get_lote_elemento);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $ID_pale = $row['ID_pale'];
        $ID_lote = $row['ID_lote'];
        $nombre_lote = $row['nombre_lote'];
        $ID_elemento = $row['ID_elementos'];

        // 2. Eliminar el palet usando el ID obtenido
        $sql_delete_pale = "DELETE FROM pale WHERE ID_pale = '$ID_pale'";
        if (mysqli_query($enlace, $sql_delete_pale)) {
            // 3. Actualizar el `stock_pale` en la tabla `elementos`
            $sql_update_stock_pale = "UPDATE elementos SET stock_pale = stock_pale - 1 WHERE ID_elemento = '$ID_elemento'";
            mysqli_query($enlace, $sql_update_stock_pale);

            // 4. Registrar la eliminación en la tabla `registro`
            session_start();
            $usuario = $_SESSION['usuario']; // Obtener el usuario de la sesión
            $fecha = date('Y-m-d H:i:s');
            $descripcion = "El palet '$nombre_pale' ha sido eliminado.";
            $sql_registro = "INSERT INTO registro (fecha, tecnico, descripcion) VALUES ('$fecha', '$usuario', '$descripcion')";
            mysqli_query($enlace, $sql_registro);

            // 5. Comprobar si quedan palets en el lote
            $sql_count_pales = "SELECT COUNT(*) as total FROM pale WHERE ID_lote = '$ID_lote'";
            $result_count = mysqli_query($enlace, $sql_count_pales);
            $row_count = mysqli_fetch_assoc($result_count);

            if ($row_count['total'] == 0) {
                // 6. Eliminar el lote si no quedan palets
                $sql_delete_lote = "DELETE FROM lote WHERE Id_lote = '$ID_lote'";
                if (mysqli_query($enlace, $sql_delete_lote)) {
                    // 7. Actualizar el `stock_lote` en la tabla `elementos`
                    $sql_update_stock_lote = "UPDATE elementos SET stock_lote = stock_lote - 1 WHERE ID_elemento = '$ID_elemento'";
                    mysqli_query($enlace, $sql_update_stock_lote);

                    // Registrar la eliminación del lote en la tabla `registro`
                    $descripcion_lote = "El lote '$nombre_lote' ha sido eliminado junto con todos sus palets.";
                    $sql_registro_lote = "INSERT INTO registro (fecha, tecnico, descripcion) VALUES ('$fecha', '$usuario', '$descripcion_lote')";
                    mysqli_query($enlace, $sql_registro_lote);

                    $mensaje = "Palet y lote eliminados correctamente.";
                    header("Location: /Portfolio/project/confirmaciones/confirmacion-borrado-pale-lote.php");
                } else {
                    $mensaje = "Error al eliminar el lote: " . mysqli_error($enlace);
                    header("Location: /Portfolio/project/confirmaciones/error.php");
                }
            } else {
                $mensaje = "Palet eliminado correctamente.";
                header("Location: /Portfolio/project/confirmaciones/confirmacion-borrado-pale.php");
            }
        } else {
            $mensaje = "Error al eliminar el palet: " . mysqli_error($enlace);
            header("Location: /Portfolio/project/confirmaciones/error.php");
        }
    } else {
        $mensaje = "Palet no encontrado.";
        header("Location: /Portfolio/project/confirmaciones/ya-borrado.php");
    }
} else {
    $mensaje = "Método de solicitud no válido.";
}

// Mostrar el mensaje en la página
echo "<p>$mensaje</p>";

// Cerrar la conexión a la base de datos
mysqli_close($enlace);
?>
