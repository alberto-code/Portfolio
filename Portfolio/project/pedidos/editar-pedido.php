<?php
// Incluir la conexión a la base de datos
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

// Verificar si se ha recibido el ID del pedido para editar
$idPedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;

if ($idPedido > 0) {
    // Obtener los detalles del pedido existente
    $sqlPedido = "SELECT * FROM pedidos WHERE id_pedido = '$idPedido'";
    $resultadoPedido = mysqli_query($enlace, $sqlPedido);

    if ($resultadoPedido && mysqli_num_rows($resultadoPedido) > 0) {
        $pedido = mysqli_fetch_assoc($resultadoPedido);

        // Obtener los productos asociados al pedido
        $sqlProductosPedido = "SELECT pp.*, e.nombre 
                               FROM pedido_productos pp
                               JOIN elementos e ON pp.ID_elemento = e.ID_elemento
                               WHERE pp.id_pedido = '$idPedido'";
        $resultadoProductosPedido = mysqli_query($enlace, $sqlProductosPedido);
        $productosPedido = [];

        while ($productoPedido = mysqli_fetch_assoc($resultadoProductosPedido)) {
            $productosPedido[] = $productoPedido;
        }

    } else {
        echo "Pedido no encontrado.";
        exit;
    }
} else {
    echo "ID de pedido no proporcionado.";
    exit;
}

// Si la solicitud es POST, procesar la actualización del pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoPedido = isset($_POST['tipo-pedido-recibido']) ? htmlspecialchars($_POST['tipo-pedido-recibido']) : '';
    $fechaEntregaPedido = isset($_POST['fecha-entrega-pedido-recibido']) ? htmlspecialchars($_POST['fecha-entrega-pedido-recibido']) : '';
    $productos = isset($_POST['producto']) ? $_POST['producto'] : [];
    $cantidades = isset($_POST['cantidad']) ? $_POST['cantidad'] : [];

    if (!empty($tipoPedido) && !empty($fechaEntregaPedido) && !empty($productos) && !empty($cantidades)) {
        // Iniciar una transacción
        mysqli_begin_transaction($enlace);

        try {
            // Actualizar los detalles del pedido
            $sqlUpdatePedido = "UPDATE pedidos SET tipo = '$tipoPedido', fecha_entrega = '$fechaEntregaPedido' WHERE id_pedido = '$idPedido'";
            if (!mysqli_query($enlace, $sqlUpdatePedido)) {
                throw new Exception("Error al actualizar el pedido: " . mysqli_error($enlace));
            }

            // Eliminar los productos existentes asociados al pedido
            $sqlDeleteProductos = "DELETE FROM pedido_productos WHERE id_pedido = '$idPedido'";
            if (!mysqli_query($enlace, $sqlDeleteProductos)) {
                throw new Exception("Error al eliminar los productos existentes: " . mysqli_error($enlace));
            }

            // Insertar los nuevos productos asociados con el pedido
            foreach ($productos as $index => $productoID) {
                $cantidad = intval($cantidades[$index]);
                $sqlInsertProducto = "INSERT INTO pedido_productos (id_pedido, ID_elemento, cantidad) VALUES ('$idPedido', '$productoID', '$cantidad')";
                if (!mysqli_query($enlace, $sqlInsertProducto)) {
                    throw new Exception("Error al insertar el producto: " . mysqli_error($enlace));
                }
            }

            // Confirmar la transacción
            mysqli_commit($enlace);
            // Redirigir a la página de pedidos con un mensaje de éxito
            header("Location: /Portfolio/project/pedidos/pedidos.php?status=edit-success");
            exit;

        } catch (Exception $e) {
            // En caso de error, deshacer la transacción
            mysqli_rollback($enlace);
            // Redirigir con un mensaje de error
            header("Location: /Portfolio/project/pedidos/pedidos.php?status=edit-error&message=" . urlencode($e->getMessage()));
            exit;
        }
    } else {
        // Si faltan datos requeridos, redirigir con un mensaje de error
        header("Location: /Portfolio/project/pedidos/pedidos.php?status=edit-error&message=Missing+required+fields");
        exit;
    }
}
?>

<?php
// Incluir encabezado si es necesario
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
?>

<div class="editarPedido">
    <div class="editarPedidoDiv">
        <div class="editarPedidoDivForm">
            <form id="editarPedidoForm" method="POST" action="">
                <h2>Editar pedido: <?php echo htmlspecialchars($pedido['nombre']); ?></h2>
                <!-- <input disabled type="text" value="<?php echo htmlspecialchars($pedido['nombre']); ?>" name="nombre-pedido-recibido" id="nombre-pedido-recibido"> -->
                <select required name="tipo-pedido-recibido" id="tipo-pedido-recibido">
                    <option value="Trailer" <?php echo $pedido['tipo'] == 'Trailer' ? 'selected' : ''; ?>>Trailer</option>
                    <option value="Granada/Almeria" <?php echo $pedido['tipo'] == 'Granada/Almeria' ? 'selected' : ''; ?>>Granada/Almeria</option>
                    <option value="Agencia" <?php echo $pedido['tipo'] == 'Agencia' ? 'selected' : ''; ?>>Agencia</option>
                </select>
                <label class="label-fecha-pedido" for="fecha-entrega-pedido">
                   Fecha de entrega
                </label>
                <input required min="<?php echo date("Y-m-d"); ?>" type="date" name="fecha-entrega-pedido-recibido" id="fecha-entrega-pedido-recibido" value="<?php echo htmlspecialchars($pedido['fecha_entrega']); ?>">

                <div id="productosContainer">
                    <?php foreach ($productosPedido as $index => $producto): ?>
                        <span class="addedProduct" id="divProductoPedido<?php echo $index; ?>">
                            <select class="producto-select" name="producto[]">
                                <?php
                                $SQL = mysqli_query($enlace, "SELECT * FROM elementos WHERE tipo = 'Productos'");
                                while ($fila = mysqli_fetch_assoc($SQL)) {
                                    $selected = $producto['ID_elemento'] == $fila['ID_elemento'] ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($fila['ID_elemento']) . '" ' . $selected . '>' . htmlspecialchars($fila['nombre']) . '</option>';
                                }
                                ?>
                            </select>
                            <input name="cantidad[]" type="number" value="<?php echo htmlspecialchars($producto['cantidad']); ?>">
                            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" onclick="eliminarCampo(this)"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 10.5858L9.17157 7.75736L7.75736 9.17157L10.5858 12L7.75736 14.8284L9.17157 16.2426L12 13.4142L14.8284 16.2426L16.2426 14.8284L13.4142 12L16.2426 9.17157L14.8284 7.75736L12 10.5858Z"></path></svg>
                        </span>
                    <?php endforeach; ?>
                </div>
                
                <span id="editadorProductos" onclick="agregarCampo()">
                    Añadir otro producto
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V7H13V11H17V13H13V17H11V13H7V11H11ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20Z"></path></svg>
                </span>
                <button type="submit">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>
<?php
// Incluir pie de página si es necesario
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>
