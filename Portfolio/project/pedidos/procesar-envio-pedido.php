<?php
// Incluir la conexión a la base de datos
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
session_start(); // Asegúrate de iniciar la sesión si vas a utilizarla

// Obtener los datos del formulario
$idPedido = isset($_POST['id_pedido']) ? intval($_POST['id_pedido']) : 0;
$fechaProduccion = isset($_POST['fecha-produccion-pedido']) ? $_POST['fecha-produccion-pedido'] : '';
$paleNombres = isset($_POST['pale_nombres']) ? $_POST['pale_nombres'] : [];

// Técnico (suponiendo que lo obtienes de la sesión, puedes ajustarlo según tu necesidad)
$tecnico = isset($_SESSION['tecnico']) ? $_SESSION['tecnico'] : 'Nombre del técnico por defecto'; 

if ($idPedido <= 0 || empty($fechaProduccion) || empty($paleNombres)) {
    echo "Datos insuficientes para procesar el pedido.";
    exit;
}

// Iniciar transacción
mysqli_begin_transaction($enlace);

try {
    // Procesar cada palet
    foreach ($paleNombres as $paleNombre) {
        $paleNombre = trim($paleNombre);
        echo "Buscando palet con nombre: '$paleNombre'<br>";

        // Verificar si el palet existe
        $sqlVerificarPalet = "SELECT * FROM pale WHERE nombre = ?";
        $stmt = mysqli_prepare($enlace, $sqlVerificarPalet);
        mysqli_stmt_bind_param($stmt, 's', $paleNombre);
        mysqli_stmt_execute($stmt);
        $resultadoPalet = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultadoPalet) > 0) {
            $palet = mysqli_fetch_assoc($resultadoPalet);

            // Obtener el ID del lote asociado al palet
            $idLote = $palet['ID_lote'];

            // Obtener el ID del elemento asociado al lote
            $sqlObtenerElemento = "SELECT ID_elementos FROM lote WHERE Id_lote = ?";
            $stmt = mysqli_prepare($enlace, $sqlObtenerElemento);
            mysqli_stmt_bind_param($stmt, 'i', $idLote);
            mysqli_stmt_execute($stmt);
            $resultadoLote = mysqli_stmt_get_result($stmt);
            $lote = mysqli_fetch_assoc($resultadoLote);
            $elementoId = $lote['ID_elementos'];

            // Eliminar el palet de la tabla pale
            $sqlEliminarPalet = "DELETE FROM pale WHERE nombre = ?";
            $stmt = mysqli_prepare($enlace, $sqlEliminarPalet);
            mysqli_stmt_bind_param($stmt, 's', $paleNombre);
            mysqli_stmt_execute($stmt);

            // Actualizar el stock de elementos
            $sqlActualizarStockElementos = "UPDATE elementos SET stock_pale = GREATEST(stock_pale - 1, 0) WHERE ID_elemento = ?";
            $stmt = mysqli_prepare($enlace, $sqlActualizarStockElementos);
            mysqli_stmt_bind_param($stmt, 'i', $elementoId);
            mysqli_stmt_execute($stmt);

            // Verificar si quedan palets en el lote
            $sqlContarPaletsRestantes = "SELECT COUNT(*) AS total_palets FROM pale WHERE ID_lote = ?";
            $stmt = mysqli_prepare($enlace, $sqlContarPaletsRestantes);
            mysqli_stmt_bind_param($stmt, 'i', $idLote);
            mysqli_stmt_execute($stmt);
            $resultadoContar = mysqli_stmt_get_result($stmt);
            $conteo = mysqli_fetch_assoc($resultadoContar);
            $totalPaletsRestantes = $conteo['total_palets'];

            // Si no quedan palets en el lote, actualizar el stock del lote y eliminar el lote
            if ($totalPaletsRestantes <= 0) {
                $sqlActualizarStockLote = "UPDATE lote SET stock_lote = GREATEST(stock_lote - 1, 0) WHERE Id_lote = ?";
                $stmt = mysqli_prepare($enlace, $sqlActualizarStockLote);
                mysqli_stmt_bind_param($stmt, 'i', $idLote);
                mysqli_stmt_execute($stmt);

                // Eliminar el lote si no quedan palets
                $sqlEliminarLote = "DELETE FROM lote WHERE Id_lote = ?";
                $stmt = mysqli_prepare($enlace, $sqlEliminarLote);
                mysqli_stmt_bind_param($stmt, 'i', $idLote);
                mysqli_stmt_execute($stmt);

                // Actualizar el stock de elementos solo si el lote se vació
                $sqlActualizarStockElementosLote = "UPDATE elementos SET stock_lote = GREATEST(stock_lote - 1, 0) WHERE ID_elemento = ?";
                $stmt = mysqli_prepare($enlace, $sqlActualizarStockElementosLote);
                mysqli_stmt_bind_param($stmt, 'i', $elementoId);
                mysqli_stmt_execute($stmt);
            }

        } else {
            throw new Exception("Palet con nombre '$paleNombre' no encontrado en la base de datos.");
        }
    }

    // Actualizar el estado del pedido a completado y la fecha de producción
    $sqlActualizarPedido = "UPDATE pedidos SET estado = '1', fecha_produccion = ? WHERE id_pedido = ?";
    $stmt = mysqli_prepare($enlace, $sqlActualizarPedido);
    mysqli_stmt_bind_param($stmt, 'si', $fechaProduccion, $idPedido);
    mysqli_stmt_execute($stmt);

    // Insertar en la tabla registros el pedido completado
    $descripcionRegistro = "Pedido $idPedido completado";
    $fechaActual = date('Y-m-d H:i:s'); // Fecha y hora actuales
    $sqlInsertarRegistro = "INSERT INTO registro (tecnico, fecha, descripcion) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($enlace, $sqlInsertarRegistro);
    mysqli_stmt_bind_param($stmt, 'sss', $tecnico, $fechaActual, $descripcionRegistro);
    mysqli_stmt_execute($stmt);

    // Confirmar la transacción
    mysqli_commit($enlace);
    header("Location: /Portfolio/project/confirmaciones/confirmacion-pedido-enviado.php");
} catch (Exception $e) {
    // Deshacer la transacción en caso de error
    mysqli_rollback($enlace);
    error_log($e->getMessage());
    echo "Error al procesar el pedido: " . $e->getMessage();
}

// Cerrar la conexión a la base de datos
mysqli_close($enlace);
?>
