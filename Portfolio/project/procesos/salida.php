<?php 
// Incluir el header
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');

// Conectar a la base de datos
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

if (!$enlace) {
    die("Connection failed: " . mysqli_connect_error());
}

// Realizar la consulta para obtener los pedidos
$SQL_pedidos = mysqli_query($enlace, "SELECT * FROM pedidos WHERE estado = '0'");

if (!$SQL_pedidos) {
    die("Query failed: " . mysqli_error($enlace));
}

// Almacenar los pedidos y sus productos en un array
$pedidos = [];

while ($pedido = mysqli_fetch_assoc($SQL_pedidos)) {
    // Obtener productos asociados a este pedido
    $id_pedido = $pedido['id_pedido'];
    $SQL_productos = mysqli_query($enlace, "SELECT pp.cantidad, e.nombre, e.stock_pale 
                                            FROM pedido_productos pp 
                                            JOIN elementos e ON pp.ID_elemento = e.ID_elemento 
                                            WHERE pp.id_pedido = $id_pedido");
    
    $productos = [];
    $se_puede_enviar = true; // Asumimos que el pedido se puede enviar hasta que se demuestre lo contrario

    while ($producto = mysqli_fetch_assoc($SQL_productos)) {
        $cantidad = $producto['cantidad'];
        $stock_pale = $producto['stock_pale'];

        // Verificar si el stock_pale es suficiente para satisfacer el pedido
        if ($stock_pale < $cantidad) {
            $se_puede_enviar = false;
        }

        $productos[] = [
            'nombre' => $producto['nombre'],
            'cantidad' => $cantidad,
            'stock_pale' => $stock_pale
        ];
    }

    // Formatear las fechas a DD-MM-YYYY
    $fecha_produccion = date('d-m-Y', strtotime($pedido['fecha_produccion']));
    $fecha_entrega = date('d-m-Y', strtotime($pedido['fecha_entrega']));

    $pedidos[] = [
        'id_pedido' => $pedido['id_pedido'],
        'nombre' => $pedido['nombre'],
        'tipo' => $pedido['tipo'],
        'fecha_produccion' => $fecha_produccion,
        'fecha_entrega' => $fecha_entrega,
        'productos' => $productos,
        'se_puede_enviar' => $se_puede_enviar
    ];
}
?>

<div class="salidaPedidos">
    <h2>SALIDA</h2>
    <div class="salidaPedidosList">
        <div class="salidaPedidosDiv">

        <div class="salidaPedidosTabla">
            <?php if (!empty($pedidos)): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Nº pedido</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Fecha Entrega</th>
                            <th>Productos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td data-label="Nº">CS-<?php echo $pedido['id_pedido']; ?></td>
                                <td data-label="Nombre"><?php echo $pedido['nombre']; ?></td>
                                <td data-label="Tipo"><?php echo $pedido['tipo']; ?></td>
                                <td data-label="Fecha de entrega"><?php echo $pedido['fecha_entrega']; ?></td>
                                <td>
                                    <div>
                                        <?php foreach ($pedido['productos'] as $producto): ?>
                                            <div class="productoListaT">
                                                <span>
                                                    <h3><?php echo $producto['nombre']; ?> </h3>
                                                    <h3><?php echo $producto['cantidad']; ?> palets </h3>
                                                <?php
                                                if ($producto['stock_pale'] == 0){
                                                    echo'<p style="color:#E84353">(Stock '.  $producto['stock_pale']  .')</p>';
                                                } else {
                                                    echo'<p>(Stock '.  $producto['stock_pale']  .')</p>';
                                                }
                                                ?>
                                                </span>
                                                
                                            </div>    
                                        <?php endforeach; ?>
                                            </div>
                                </td>
                                <td>
                                    <div class="botonTablaSalida">
                                        <?php if ($pedido['se_puede_enviar']): ?>
                                            <a href="/Portfolio/project/pedidos/enviar-pedido.php?id_pedido=<?php echo urlencode($pedido['id_pedido']); ?>" class="enviar-pedido-YES">
                                                ENVIAR PEDIDO
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8.96456 18C8.72194 19.6961 7.26324 21 5.5 21C3.73676 21 2.27806 19.6961 2.03544 18H1V6C1 5.44772 1.44772 5 2 5H16C16.5523 5 17 5.44772 17 6V8H20L23 12.0557V18H20.9646C20.7219 19.6961 19.2632 21 17.5 21C15.7368 21 14.2781 19.6961 14.0354 18H8.96456ZM15 7H3V15.0505C3.63526 14.4022 4.52066 14 5.5 14C6.8962 14 8.10145 14.8175 8.66318 16H14.3368C14.5045 15.647 14.7296 15.3264 15 15.0505V7ZM17 13H21V12.715L18.9917 10H17V13ZM17.5 19C18.1531 19 18.7087 18.5826 18.9146 18C18.9699 17.8436 19 17.6753 19 17.5C19 16.6716 18.3284 16 17.5 16C16.6716 16 16 16.6716 16 17.5C16 17.6753 16.0301 17.8436 16.0854 18C16.2913 18.5826 16.8469 19 17.5 19ZM7 17.5C7 16.6716 6.32843 16 5.5 16C4.67157 16 4 16.6716 4 17.5C4 17.6753 4.03008 17.8436 4.08535 18C4.29127 18.5826 4.84689 19 5.5 19C6.15311 19 6.70873 18.5826 6.91465 18C6.96992 17.8436 7 17.6753 7 17.5Z"></path></svg>        
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="enviar-pedido-NO" disabled>
                                                STOCK INSUFICIENTE
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8659 3.00017L22.3922 19.5002C22.6684 19.9785 22.5045 20.5901 22.0262 20.8662C21.8742 20.954 21.7017 21.0002 21.5262 21.0002H2.47363C1.92135 21.0002 1.47363 20.5525 1.47363 20.0002C1.47363 19.8246 1.51984 19.6522 1.60761 19.5002L11.1339 3.00017C11.41 2.52187 12.0216 2.358 12.4999 2.63414C12.6519 2.72191 12.7782 2.84815 12.8659 3.00017ZM4.20568 19.0002H19.7941L11.9999 5.50017L4.20568 19.0002ZM10.9999 16.0002H12.9999V18.0002H10.9999V16.0002ZM10.9999 9.00017H12.9999V14.0002H10.9999V9.00017Z"></path></svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pedidos pendientes.</p>
            <?php endif; ?>
        </div>

        </div>
    </div>
</div>

<?php
// Incluir el footer
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>
