<?php
// Verificar si la solicitud es POST para manejar los datos enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados en la solicitud POST
    $nombrePedido = isset($_POST['nombrePedido']) ? htmlspecialchars($_POST['nombrePedido']) : 'No definido';
    $tipoPedido = isset($_POST['tipoPedido']) ? htmlspecialchars($_POST['tipoPedido']) : 'No definido';
    $fechaEntregaPedido = isset($_POST['fechaEntregaPedido']) ? htmlspecialchars($_POST['fechaEntregaPedido']) : 'No definido';

    // Crear un array de respuesta
    $response = array(
        'nombrePedido' => $nombrePedido,
        'tipoPedido' => $tipoPedido,
        'fechaEntregaPedido' => $fechaEntregaPedido
    );

    // Enviar la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    exit; // Asegúrate de que el script termine después de enviar la respuesta
}

// Incluye encabezado si es necesario
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
?>

<div class="nuevoPedido">
    <div class="nuevoPedidoDiv">
        <div class="nuevoPedidoDivForm">
            <form id="nuevoPedido1">
                <h2>Nuevo pedido</h2>
                <input required type="text" name="nombre-pedido" id="nombre-pedido" placeholder="Nombre">
                <select required name="tipo-pedido" id="tipo-pedido">
                    <option value="Trailer">Trailer</option>
                    <option value="Granada/Almeria">Granada/Almeria</option>
                    <option value="Agencia">Agencia</option>
                </select>
                <label class="label-fecha-pedido" for="fecha-entrega-pedido">
                   Fecha de entrega
                </label>
                <input required min="<?php echo date("Y-m-d"); ?>" type="date" name="fecha-entrega-pedido" id="fecha-entrega-pedido">
                <button type="submit">Siguiente</button>
            </form>
        </div>
    </div>
    <div style="display:none" class="nuevoPedidoDiv2"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector("#nuevoPedido1").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita el envío tradicional del formulario

            const nombrePedido = document.querySelector("#nombre-pedido").value;
            const tipoPedido = document.querySelector("#tipo-pedido").value;
            const fechaEntregaPedido = document.querySelector("#fecha-entrega-pedido").value;

            // Usar Fetch API para enviar los datos del formulario
            fetch(window.location.href, { // Enviar la solicitud a la misma página
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    nombrePedido: nombrePedido,
                    tipoPedido: tipoPedido,
                    fechaEntregaPedido: fechaEntregaPedido
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json(); // Obtener la respuesta en formato JSON
            })
            .then(data => {
                // Procesar y mostrar los datos recibidos
                document.querySelector(".nuevoPedidoDiv").style.display='none';
                document.querySelector(".nuevoPedidoDiv2").style.display='flex';
                document.querySelector(".nuevoPedidoDiv2").innerHTML = `
                <div style="display:none" class="nuevoPedidoDivForm2">
                    <form id="nuevoPedido2">
                        <input disabled type="text" value="${data.nombrePedido}" name="nombre-pedido-recibido" id="nombre-pedido-recibido">
                        <select disabled name="tipo-pedido-recibido" id="tipo-pedido-recibido">
                        <option value="${data.tipoPedido}">${data.tipoPedido}</option>
                        </select>
                        <label class="label-fecha-pedido" for="fecha-entrega-pedido">
                        Fecha de entrega
                        </label>
                        <input disabled value="${data.fechaEntregaPedido}" type="date" name="fecha-entrega-pedido-recibido" id="fecha-entrega-pedido-recibido">
                    </form>
                </div>
                 <div class="nuevoPedidoDivForm3">
                    <form id="nuevoPedido3" action="/Portfolio/project/procesado-info/procesar-nuevo-pedido.php" method="POST">
                        <input type="hidden" value="${data.nombrePedido}" name="nombre-pedido-recibido" id="nombre-pedido-recibido">
                        <select hidden name="tipo-pedido-recibido" id="tipo-pedido-recibido">
                        <option value="${data.tipoPedido}">${data.tipoPedido}</option>
                        </select>
                        <input value="${data.fechaEntregaPedido}" type="hidden" name="fecha-entrega-pedido-recibido" id="fecha-entrega-pedido-recibido">
                        <span class="addedProduct" id="divProductoPedido0">
                        <?php 
                            include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
                            if (!$enlace) {
                                die("Connection failed: " . mysqli_connect_error());
                            }
                            // Realizar la consulta
                            $SQL = mysqli_query($enlace, "SELECT * FROM elementos WHERE tipo = 'Productos'");
                            // Verificar la consulta
                            if (!$SQL) {
                                die("Query failed: " . mysqli_error($enlace));
                            }
                            echo '<select class="producto-select" name="producto[]">';
                                while ($fila = mysqli_fetch_assoc($SQL)) {
                                    echo '<option value="' . htmlspecialchars($fila['ID_elemento']) . '">' . htmlspecialchars($fila['nombre']) . '</option>';
                            }
                            echo '</select>';
                        ?>
                        Cant
                        <input name="cantidad[]" type="number">
                        <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" onclick="eliminarCampo(this)"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 10.5858L9.17157 7.75736L7.75736 9.17157L10.5858 12L7.75736 14.8284L9.17157 16.2426L12 13.4142L14.8284 16.2426L16.2426 14.8284L13.4142 12L16.2426 9.17157L14.8284 7.75736L12 10.5858Z"></path></svg>
                        </span>
                        
                        <span id="agregadorProductos" onclick="agregarCampo()">
                            Añadir otro producto
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V7H13V11H17V13H13V17H11V13H7V11H11ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20Z"></path></svg>
                        </span>
                        <button onclick="enviarNuevoPedido()" type="button">Registrar</button>
                    </form>
                </div>
                `;
                updateProductOptions(); // Actualizar opciones al cargar los productos
            })
            .catch(error => {
                console.error("Error:", error);
                document.querySelector("#resultado").innerHTML = "Error al enviar el pedido.";
            });
        });
    });
</script>

<?php
// Incluir pie de página si es necesario
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>
