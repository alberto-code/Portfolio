<?php
// Incluir la conexión a la base de datos
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

// Verificar si se ha recibido el ID del pedido para editar
$idPedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;

if ($idPedido > 0) {
    // Obtener los detalles del pedido existente
    $sqlPedido = "SELECT * FROM pedidos WHERE id_pedido = ?";
    $stmt = mysqli_prepare($enlace, $sqlPedido);
    mysqli_stmt_bind_param($stmt, 'i', $idPedido);
    mysqli_stmt_execute($stmt);
    $resultadoPedido = mysqli_stmt_get_result($stmt);

    if ($resultadoPedido && mysqli_num_rows($resultadoPedido) > 0) {
        $pedido = mysqli_fetch_assoc($resultadoPedido);

        // Obtener los productos asociados al pedido
        $sqlProductosPedido = "SELECT pp.*, e.nombre AS producto_nombre 
                               FROM pedido_productos pp
                               JOIN elementos e ON pp.ID_elemento = e.ID_elemento
                               WHERE pp.id_pedido = ?";
        $stmt = mysqli_prepare($enlace, $sqlProductosPedido);
        mysqli_stmt_bind_param($stmt, 'i', $idPedido);
        mysqli_stmt_execute($stmt);
        $resultadoProductosPedido = mysqli_stmt_get_result($stmt);
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

// Obtener los palets asociados a cada producto
$paletsPorProducto = [];
foreach ($productosPedido as $producto) {
    $productoId = $producto['ID_elemento'];
    $sqlPalets = "SELECT * FROM pale WHERE ID_lote IN (SELECT Id_lote FROM lote WHERE ID_elementos = ?)";
    $stmt = mysqli_prepare($enlace, $sqlPalets);
    mysqli_stmt_bind_param($stmt, 'i', $productoId);
    mysqli_stmt_execute($stmt);
    $resultadoPalets = mysqli_stmt_get_result($stmt);
    $palets = [];
    
    while ($palet = mysqli_fetch_assoc($resultadoPalets)) {
        $palets[] = $palet['nombre']; // Asegúrate de que 'nombre' es el nombre correcto de la columna
    }

    $paletsPorProducto[$productoId] = $palets;
}
?>

<?php
// Incluir encabezado si es necesario
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
?>

<div class="enviarPedido">
    <div class="enviarPedidoDiv">
        <div class="enviarPedidoDivForm">
            <h2>Enviar pedido:<br> <?php echo htmlspecialchars($pedido['nombre']); ?></h2>
            <form id="enviarPedidoForm" method="POST" action="/Portfolio/project/pedidos/procesar-envio-pedido.php">
                <div class="dosColumnas">

                    <div class="dosColumnasProducts">
                        <label class="label-fecha-pedido" for="fecha-entrega-pedido">
                            Fecha de producción
                        </label>
                        <input required min="<?php echo date("Y-m-d"); ?>" type="date" name="fecha-produccion-pedido" id="fecha-produccion-pedido" value="">
                    </div>

                    <div class="dosColumnasPalets">
                        <?php foreach ($productosPedido as $index => $producto): ?>
                            <h4><?php echo htmlspecialchars($producto['producto_nombre']); ?> | <?php echo htmlspecialchars($producto['cantidad']); ?> Palets</h4>
                            <div id="listaPalets<?php echo $index; ?>" class="dosColumnasPaletsGrid">
                                <?php
                                for ($i = 0; $i < htmlspecialchars($producto['cantidad']); $i++) {
                                    echo '
                                    <div onclick="enviarPedido(this, ' . htmlspecialchars($producto['ID_elemento']) . ')" id="dosColumnasPaletsDiv'.($i + 1).'_'.$index.'" class="dosColumnasPaletsDiv">
                                        <h4>Palet '.($i + 1).'</h4>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15 3H21V8H19V5H15V3ZM9 3V5H5V8H3V3H9ZM15 21V19H19V16H21V21H15ZM9 21H3V16H5V19H9V21ZM3 11H21V13H3V11Z"></path></svg>
                                        <input type="hidden" name="pale_nombres[]" value="">
                                    </div>
                                    ';
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
                <!-- Campo oculto para indicar que se desea completar el pedido -->
                <input type="hidden" name="id_pedido" value="<?php echo $idPedido; ?>">
                <button onclick="enviarCorreo()" type="submit">Enviar</button>
            </form>
            <div id="lectorPedidosSalida"></div>
            <audio style="display:none" id="beep">
                <source src="/Portfolio/audio/beep.mp3" type="audio/mp3">
            </audio>
        </div>
    </div>
</div>

<?php
// Incluir pie de página si es necesario
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>

<script>
function beep() {
    let audio2 = document.getElementById('beep');
    audio2.play();
}

var paletTemporal;
var paletsPermitidos = <?php echo json_encode($paletsPorProducto); ?>;
var scanning = false;  // Bandera para controlar el estado del escaneo
var paletsEscaneados = new Set();  // Conjunto para almacenar los códigos de palets ya escaneados

function enviarPedido(palet, productoId) {
    if (scanning) return;  // Evita iniciar un nuevo escaneo si ya se está escaneando

    paletTemporal = palet;
    document.querySelector("#lectorPedidosSalida").style.display = "";
    escanerPedidos(productoId);
}

function escanerPedidos(productoId) {
    // Detener Quagga si está en ejecución
    if (scanning) {
        Quagga.stop();
        Quagga.offDetected(handleDetected);
        scanning = false;
    }

    // Configurar Quagga
    Quagga.init({
        inputStream: {
            target: document.querySelector("#lectorPedidosSalida"),
            type: "LiveStream",
            constraints: {
                width: { min: 640 },
                height: { min: 480 },
                facingMode: "environment",
                aspectRatio: { min: 1, max: 2 }
            }
        },
        decoder: {
            readers: ['code_128_reader']
        },
    }, function(err) {
        if (err) {
            console.log(err);
            return;
        }
        Quagga.start();
        scanning = true;  // Establece la bandera de escaneo en verdadero
    });

    // Definir el manejador de detección
    function handleDetected(result) {
        beep();
        const code = result.codeResult.code.trim();

        // Verificar si el código ya ha sido escaneado
        if (paletsEscaneados.has(code)) {
            alert("Este palet ya ha sido escaneado.");
            Quagga.stop();
            document.querySelector("#lectorPedidosSalida").style.display = "none";
            scanning = false;
            Quagga.offDetected(handleDetected);
            return;
        }

        if (paletTemporal && paletsPermitidos[productoId] && paletsPermitidos[productoId].includes(code)) {
            if (!paletTemporal.querySelector('input[type="hidden"][value="' + code + '"]')) {
                paletTemporal.style.background = '#3CB371';
                paletTemporal.style.opacity = '1';
                paletTemporal.style.color = '#fdfdfd';
                paletTemporal.style.pointerEvents = 'none';
                paletTemporal.style.cursorPointer = 'none';
                paletTemporal.innerHTML = '<h4 id="' + code + '">' + code + '</h4>' +
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">' +
                    '<path d="M4 3H20C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3ZM11.0026 16L18.0737 8.92893L16.6595 7.51472L11.0026 13.1716L8.17421 10.3431L6.75999 11.7574L11.0026 16Z"></path></svg>' +
                    '<input type="hidden" name="pale_nombres[]" value="' + code + '">';
                
                // Marcar el código como escaneado
                paletsEscaneados.add(code);
            }
            // Resetear estado de escaneo
            scanning = false;
            document.querySelector("#lectorPedidosSalida").style.display = "none";
            Quagga.stop();
            Quagga.offDetected(handleDetected);
            paletTemporal = null;  // Resetear paletTemporal después de escanear
        } else {
            alert("Palet no válido para el producto seleccionado.");
            // Detener escaneo después de un código inválido
            Quagga.stop();
            document.querySelector("#lectorPedidosSalida").style.display = "none";
            scanning = false;  // Resetear la bandera de escaneo para permitir futuros intentos
            Quagga.offDetected(handleDetected);
        }
    }

    // Vincular el manejador de detección
    Quagga.onDetected(handleDetected);
}
</script>
