<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');

if ($_SESSION["rol"] !== 'admin') {
    echo '<script>
    alert("Acceso denegado");
    window.location.href="/Portfolio/index.php";
    </script>';
    exit();
}
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

// Inicializamos variables para almacenar los conteos
$materias = ['crea_sustratos' => 0, 'tone' => 0];
$bobinas = ['crea_sustratos' => 0, 'tone' => 0];
$productos = ['crea_sustratos' => 0, 'tone' => 0];
$lotes = ['crea_sustratos' => 0, 'tone' => 0];
$palets = ['crea_sustratos' => 0, 'tone' => 0];
$tecnicosTotales = 0;
$tecnicosPorRol = ['admin' => 0, 'tecnico' => 0, 'administracion' => 0];
$pedidosPendientes = 0;
$pedidosCompletados = 0;

// Función para obtener los conteos por tipo y tone
function obtenerConteos($enlace, $tipo) {
    $conteos = ['crea_sustratos' => 0, 'tone' => 0, 'lotes_crea_sustratos' => 0, 'lotes_tone' => 0, 'palets_crea_sustratos' => 0, 'palets_tone' => 0];

    // Conteo de elementos
    $sql = "SELECT tone, COUNT(*) as total FROM elementos WHERE tipo = '$tipo' GROUP BY tone";
    $result = mysqli_query($enlace, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['tone'] == 1) {
            $conteos['tone'] = $row['total'];
        } else {
            $conteos['crea_sustratos'] = $row['total'];
        }
    }

    // Conteo de lotes
    $sql_lotes = "SELECT e.tone, COUNT(DISTINCT l.id_lote) as total_lotes
                  FROM elementos e
                  LEFT JOIN lote l ON e.ID_elemento = l.ID_elementos
                  WHERE e.tipo = '$tipo'
                  GROUP BY e.tone";
    $result_lotes = mysqli_query($enlace, $sql_lotes);
    while ($row_lotes = mysqli_fetch_assoc($result_lotes)) {
        if ($row_lotes['tone'] == 1) {
            $conteos['lotes_tone'] = $row_lotes['total_lotes'];
        } else {
            $conteos['lotes_crea_sustratos'] = $row_lotes['total_lotes'];
        }
    }

    // Conteo de palets
    $sql_palets = "SELECT e.tone, COUNT(p.id_pale) as total_palets
                   FROM elementos e
                   LEFT JOIN lote l ON e.ID_elemento = l.ID_elementos
                   LEFT JOIN pale p ON l.id_lote = p.Id_lote
                   WHERE e.tipo = '$tipo'
                   GROUP BY e.tone";
    $result_palets = mysqli_query($enlace, $sql_palets);
    while ($row_palets = mysqli_fetch_assoc($result_palets)) {
        if ($row_palets['tone'] == 1) {
            $conteos['palets_tone'] = $row_palets['total_palets'];
        } else {
            $conteos['palets_crea_sustratos'] = $row_palets['total_palets'];
        }
    }

    return $conteos;
}

// Obtener conteos para materias, bobinas, y productos
$materias = obtenerConteos($enlace, 'materias');
$bobinas = obtenerConteos($enlace, 'bobinas');
$productos = obtenerConteos($enlace, 'productos');

// Consultas para obtener el número total de técnicos y el número de técnicos por rol
$sqlTecnicosTotales = "SELECT COUNT(*) as total FROM tecnico";
$resultTecnicosTotales = mysqli_query($enlace, $sqlTecnicosTotales);

if ($resultTecnicosTotales) {
    $rowTotales = mysqli_fetch_assoc($resultTecnicosTotales);
    $tecnicosTotales = $rowTotales['total'];
} else {
    die('Error en la consulta SQL de técnicos totales: ' . mysqli_error($enlace));
}

$sqlTecnicosPorRol = "SELECT rol, COUNT(*) as total_por_rol FROM tecnico GROUP BY rol";
$resultTecnicosPorRol = mysqli_query($enlace, $sqlTecnicosPorRol);

if ($resultTecnicosPorRol) {
    while ($row = mysqli_fetch_assoc($resultTecnicosPorRol)) {
        $tecnicosPorRol[$row['rol']] = $row['total_por_rol'];
    }
} else {
    die('Error en la consulta SQL de técnicos por rol: ' . mysqli_error($enlace));
}

// Consultas para obtener el número de pedidos pendientes y completados
$sqlPedidosPendientes = "SELECT COUNT(*) as total FROM pedidos WHERE estado = '0'";
$resultPedidosPendientes = mysqli_query($enlace, $sqlPedidosPendientes);

if ($resultPedidosPendientes) {
    $rowPendientes = mysqli_fetch_assoc($resultPedidosPendientes);
    $pedidosPendientes = $rowPendientes['total'];
} else {
    die('Error en la consulta SQL de pedidos pendientes: ' . mysqli_error($enlace));
}

$sqlPedidosCompletados = "SELECT COUNT(*) as total FROM pedidos WHERE estado = '1'";
$resultPedidosCompletados = mysqli_query($enlace, $sqlPedidosCompletados);

if ($resultPedidosCompletados) {
    $rowCompletados = mysqli_fetch_assoc($resultPedidosCompletados);
    $pedidosCompletados = $rowCompletados['total'];
} else {
    die('Error en la consulta SQL de pedidos completados: ' . mysqli_error($enlace));
}
// Consultar los últimos 10 registros
$sql_registros = "SELECT * FROM registro ORDER BY fecha DESC LIMIT 5";
$result_registros = mysqli_query($enlace, $sql_registros);

if (!$result_registros) {
    die('Error en la consulta de los registros: ' . mysqli_error($enlace));
}

mysqli_close($enlace); // Cerramos la conexión a la base de datos
?>


<div class="dashboard">
    <div class="dashboardGrid">

        <!-- Materias -->
        <div class="materiasBOX">
            <div class="dashboardInfoTitle">Materias</div>
            <div class="dashboardInfoData">
                <span>
                    <h2>Prueba</h2>
                    <article>
                        <h3>Elementos</h3>
                        <h3><?php echo $materias['crea_sustratos']; ?></h3>
                    </article>
                    <article>
                        <h3>Lotes</h3>
                        <h3><?php echo $materias['lotes_crea_sustratos']; ?></h3>
                    </article>
                    <article>
                        <h3>Palets</h3>
                        <h3><?php echo $materias['palets_crea_sustratos']; ?></h3>
                    </article>
                </span>
                <span>
                    <h2>PRUEBA</h2>
                    <article>
                        <h3>Elementos</h3>
                        <h3><?php echo $materias['tone']; ?></h3>
                    </article>
                    <article>
                        <h3>Lotes</h3>
                        <h3><?php echo $materias['lotes_tone']; ?></h3>
                    </article>
                    <article>
                        <h3>Palets</h3>
                        <h3><?php echo $materias['palets_tone']; ?></h3>
                    </article>
                </span>
            </div>
        </div>

        <!-- Bobinas -->
        <div class="bobinasBOX">
            <div class="dashboardInfoTitle">Bobinas</div>
            <div class="dashboardInfoData">
                <span>
                    <h2>Prueba</h2>
                    <article>
                        <h3>Elementos</h3>
                        <h3><?php echo $bobinas['crea_sustratos']; ?></h3>
                    </article>
                    <article>
                        <h3>Lotes</h3>
                        <h3><?php echo $bobinas['lotes_crea_sustratos']; ?></h3>
                    </article>
                    <article>
                        <h3>Palets</h3>
                        <h3><?php echo $bobinas['palets_crea_sustratos']; ?></h3>
                    </article>
                </span>
                <span>
                    <h2>Prueba</h2>
                    <article>
                        <h3>Elementos</h3>
                        <h3><?php echo $bobinas['tone']; ?></h3>
                    </article>
                    <article>
                        <h3>Lotes</h3>
                        <h3><?php echo $bobinas['lotes_tone']; ?></h3>
                    </article>
                    <article>
                        <h3>Palets</h3>
                        <h3><?php echo $bobinas['palets_tone']; ?></h3>
                    </article>
                </span>
            </div>
        </div>

        <!-- Productos -->
        <div class="productosBOX">
            <div class="dashboardInfoTitle">Productos</div>
            <div class="dashboardInfoData">
                <span>
                    <h2>Prueba</h2>
                    <article>
                        <h3>Elementos</h3>
                        <h3><?php echo $productos['crea_sustratos']; ?></h3>
                    </article>
                    <article>
                        <h3>Lotes</h3>
                        <h3><?php echo $productos['lotes_crea_sustratos']; ?></h3>
                    </article>
                    <article>
                        <h3>Palets</h3>
                        <h3><?php echo $productos['palets_crea_sustratos']; ?></h3>
                    </article>
                </span>
                <span>
                    <h2>Prueba</h2>
                    <article>
                        <h3>Elementos</h3>
                        <h3><?php echo $productos['tone']; ?></h3>
                    </article>
                    <article>
                        <h3>Lotes</h3>
                        <h3><?php echo $productos['lotes_tone']; ?></h3>
                    </article>
                    <article>
                        <h3>Palets</h3>
                        <h3><?php echo $productos['palets_tone']; ?></h3>
                    </article>
                </span>
            </div>
        </div>

        <!-- Técnicos -->
        <div class="tecnicosBOX">
            <div class="tecnicosBoxTotal">
                <h2>Usuarios</h2>
                <h3><?php echo $tecnicosTotales; ?></h3>
            </div>
            <div class="tecnicosBoxRol">
                <article>
                    <h5>Admin:</h5>
                    <h5><?php echo $tecnicosPorRol['admin']; ?></h5>
                </article>
                <article>
                    <h5> Administración:</h5>
                    <h5><?php echo $tecnicosPorRol['administracion']; ?></h5>
                </article>
                <article>
                <h5>Técnicos:</h5>
                <h5><?php echo $tecnicosPorRol['tecnico']; ?></h5>
                </article>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="pedidosBOX">
            <h2>Pedidos</h2>
            <div>
                <span>
                    <h3>Pendientes</h3>
                    <h2><?php echo $pedidosPendientes; ?></h2>
                </span>
                <span>
                    <h3>Completados</h3>
                    <h2><?php echo $pedidosCompletados; ?></h2>
                </span>
            </div>
        </div>

        <div class="registrosBOX">
            <h2>Registros</h2>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($registro = mysqli_fetch_assoc($result_registros)): ?>
                        <tr>
                            <td>
                            <?php 
                            // Convertir la fecha a un objeto DateTime y luego formatearla
                            $fecha_formateada = date('d/m/y H:i', strtotime($registro['fecha'])); 
                            echo $fecha_formateada;
                            ?>
                            <td><?php echo $registro['tecnico']; ?></td>
                            <td><?php echo $registro['descripcion']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php'); ?>
