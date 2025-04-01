<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

// Verificar que el usuario tenga permisos de admin
if ($_SESSION["rol"] !== 'admin') {
    echo json_encode(array('status' => 'error', 'message' => 'Acceso denegado'));
    exit();
}

// Verificar si el tipo de entidad y el ID han sido enviados
if (isset($_POST['tipo']) && isset($_POST['id'])) {
    $tipo = $_POST['tipo']; // Tipo de entidad (tecnico, elemento, pedido)
    $id = $_POST['id'];
    $current_date_time = date('Y-m-d H:i:s');
    $usuario = $_SESSION['usuario'];

    // Switch para manejar diferentes tipos de eliminaciones
    switch ($tipo) {
        case 'tecnico':
            // Obtener el nombre del técnico antes de eliminar
            $sql_nombre = "SELECT usuario FROM tecnico WHERE ID = ?";
            $stmt = $enlace->prepare($sql_nombre);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $resultado_nombre = $stmt->get_result();
            $tecnico = $resultado_nombre->fetch_assoc();
            $nombre_tecnico = $tecnico['usuario'];

            // Eliminar el técnico
            $sql_delete = "DELETE FROM tecnico WHERE ID = ?";
            $stmt = $enlace->prepare($sql_delete);
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                // Registrar la eliminación
                $descripcion = "Eliminado técnico $nombre_tecnico";
                $sql_registro = "INSERT INTO registro (fecha, tecnico, descripcion) VALUES (?, ?, ?)";
                $stmt_registro = $enlace->prepare($sql_registro);
                $stmt_registro->bind_param('sss', $current_date_time, $usuario, $descripcion);
                $stmt_registro->execute();
                echo json_encode(array('status' => 'success', 'message' => 'Técnico eliminado'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'No se pudo eliminar el técnico'));
            }
            break;

        case 'elemento':
            // Obtener el nombre del elemento antes de eliminar
            $sql_nombre = "SELECT nombre FROM elementos WHERE ID_elemento = ?";
            $stmt = $enlace->prepare($sql_nombre);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $resultado_nombre = $stmt->get_result();
            $elemento = $resultado_nombre->fetch_assoc();
            $nombre_elemento = $elemento['nombre'];

            // Eliminar el elemento
            $sql_delete = "DELETE FROM elementos WHERE ID_elemento = ?";
            $stmt = $enlace->prepare($sql_delete);
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                // Registrar la eliminación
                $descripcion = "Borrado elemento $nombre_elemento";
                $sql_registro = "INSERT INTO registro (fecha, tecnico, descripcion) VALUES (?, ?, ?)";
                $stmt_registro = $enlace->prepare($sql_registro);
                $stmt_registro->bind_param('sss', $current_date_time, $usuario, $descripcion);
                $stmt_registro->execute();
                echo json_encode(array('status' => 'success', 'message' => 'Elemento eliminado'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'No se pudo eliminar el elemento'));
            }
            break;

        case 'pedido':
            // Obtener el nombre del pedido antes de eliminar
            $sql_nombre = "SELECT nombre FROM pedidos WHERE id_pedido = ?";
            $stmt = $enlace->prepare($sql_nombre);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $resultado_nombre = $stmt->get_result();
            $pedido = $resultado_nombre->fetch_assoc();
            $nombre_pedido = $pedido['nombre'];

            // Eliminar el pedido
            $sql_delete = "DELETE FROM pedidos WHERE id_pedido = ?";
            $stmt = $enlace->prepare($sql_delete);
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                // Registrar la eliminación
                $descripcion = "Borrado pedido $nombre_pedido";
                $sql_registro = "INSERT INTO registro (fecha, tecnico, descripcion) VALUES (?, ?, ?)";
                $stmt_registro = $enlace->prepare($sql_registro);
                $stmt_registro->bind_param('sss', $current_date_time, $usuario, $descripcion);
                $stmt_registro->execute();
                echo json_encode(array('status' => 'success', 'message' => 'Pedido eliminado'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'No se pudo eliminar el pedido'));
            }
            break;

        default:
            echo json_encode(array('status' => 'error', 'message' => 'Tipo de entidad no válido'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'ID o tipo no proporcionado'));
}
?>
