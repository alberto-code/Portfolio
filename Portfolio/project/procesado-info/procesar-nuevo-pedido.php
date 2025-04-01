<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
session_start();

if (empty($_SESSION["usuario"])) {
    header("Location: /Portfolio/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre-pedido-recibido'];
    $tipo = $_POST['tipo-pedido-recibido'];
    $fecha_entrega = $_POST['fecha-entrega-pedido-recibido'];
    $productos = $_POST['producto'];  // Array de ID de productos
    $cantidades = $_POST['cantidad'];  // Array de cantidades
    $fecha_produccion = '9999-12-31';
    $usuario = $_SESSION['usuario'];  // Obtener el usuario que realiza el registro
    $current_date_time = date('Y-m-d H:i:s');  // Obtener la fecha y hora actual

    // Empezar la transacción
    $enlace->begin_transaction();

    try {
        // Insertar el pedido en la tabla `pedidos`
        $sql = "INSERT INTO pedidos (nombre, tipo, estado, fecha_produccion, fecha_entrega) VALUES (?, ?, 0, ?, ?)";
        $stmt = $enlace->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $tipo, $fecha_produccion, $fecha_entrega);

        if (!$stmt->execute()) {
            throw new Exception("Error al insertar el pedido: " . $stmt->error);
        }

        // Obtener el ID del pedido recién insertado
        $id_pedido = $stmt->insert_id;

        // Cerrar la declaración del primer insert
        $stmt->close();

        // Preparar la consulta para insertar productos en `pedido_productos`
        $sql_productos = "INSERT INTO pedido_productos (id_pedido, cantidad, ID_elemento) VALUES (?, ?, ?)";
        $stmt_productos = $enlace->prepare($sql_productos);

        // Insertar cada producto en la tabla `pedido_productos`
        for ($i = 0; $i < count($productos); $i++) {
            $producto_id = $productos[$i];
            $cantidad = $cantidades[$i];
            $stmt_productos->bind_param("iii", $id_pedido, $cantidad, $producto_id);

            if (!$stmt_productos->execute()) {
                throw new Exception("Error al insertar productos: " . $stmt_productos->error);
            }
        }

        // Cerrar la declaración del segundo insert
        $stmt_productos->close();

        // Insertar en la tabla `registro` la operación realizada
        $sql_registro = "INSERT INTO registro (fecha, tecnico, descripcion) VALUES (?, ?, ?)";
        $descripcion = "Pedido registrado: ".$nombre."";
        $stmt_registro = $enlace->prepare($sql_registro);
        $stmt_registro->bind_param("sss", $current_date_time, $usuario, $descripcion);

        if (!$stmt_registro->execute()) {
            throw new Exception("Error al registrar la operación: " . $stmt_registro->error);
        }

        // Cerrar la declaración del registro
        $stmt_registro->close();

        // Si todo salió bien, confirmar la transacción
        $enlace->commit();

        header("Location: /Portfolio/project/confirmaciones/confirmacion-nuevo-pedido.php");

    } catch (Exception $e) {
        // Si hubo un error, revertir la transacción
        $enlace->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Cerrar la conexión
    $enlace->close();
}
?>
