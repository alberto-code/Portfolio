<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');

// Establecer la configuración regional a español
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'spanish');

// Obtener mes y año actuales
$mesActual = date('m');
$anioActual = date('Y');

// Establecer el mes y el año desde la URL o usar el actual
$mes = isset($_GET['mes']) ? $_GET['mes'] : $mesActual; // Mes actual
$anio = isset($_GET['anio']) ? $_GET['anio'] : $anioActual; // Año actual

// Obtener el primer y el último día del mes
$primerDia = date('Y-m-01', strtotime("$anio-$mes-01"));
$ultimoDia = date('Y-m-t', strtotime("$anio-$mes-01"));

// Obtener el número del día de la semana del primer día (para ajustar el calendario)
$primerDiaSemana = date('N', strtotime($primerDia));

// Consulta SQL para obtener los registros de la tabla "registro" dentro del mes seleccionado
$sqlRegistros = "SELECT * FROM registro WHERE fecha >= '$primerDia' AND fecha <= '$ultimoDia'";
$resultadoRegistros = mysqli_query($enlace, $sqlRegistros);

// Crear un array para almacenar los registros por día
$registrosPorDia = [];
while ($registro = mysqli_fetch_assoc($resultadoRegistros)) {
    $dia = date('j', strtotime($registro['fecha'])); // Obtener el día del mes
    // Formatear la fecha en el formato DD/MM/YY HH:MM
    $registro['fecha_formateada'] = date('d/m/y H:i', strtotime($registro['fecha']));
    $registro['fecha_titulo'] = date('d/m/y', strtotime($registro['fecha'])); // Formato para el título
    if (!isset($registrosPorDia[$dia])) {
        $registrosPorDia[$dia] = [];
    }
    $registrosPorDia[$dia][] = $registro;
}

// Navegación entre meses y años
$mesAnterior = $mes - 1 > 0 ? $mes - 1 : 12;
$anioMesAnterior = $mes - 1 > 0 ? $anio : $anio - 1;
$mesSiguiente = $mes + 1 <= 12 ? $mes + 1 : 1;
$anioMesSiguiente = $mes + 1 <= 12 ? $anio : $anio + 1;

// Convertir números de mes a nombres de mes en español y poner la primera letra en mayúscula
$nombreMesAnterior = ucfirst(strftime('%B', mktime(0, 0, 0, $mesAnterior, 10))); // Nombre del mes anterior
$nombreMesActual = ucfirst(strftime('%B', mktime(0, 0, 0, $mes, 10))); // Nombre del mes actual
$nombreMesSiguiente = ucfirst(strftime('%B', mktime(0, 0, 0, $mesSiguiente, 10))); // Nombre del mes siguiente

echo '<div class="calendarioContainer">';
echo '<div class="calendarioHeader">';
echo '<div class="calendarioHeaderDiv">';
echo '<a href="?mes='.$mesAnterior.'&anio='.$anioMesAnterior.'">&laquo; '.$nombreMesAnterior.'</a>'; // Nombre del mes anterior
echo '<span>'.$nombreMesActual.' '.$anio.'</span>'; // Nombre del mes actual
echo '<a href="?mes='.$mesSiguiente.'&anio='.$anioMesSiguiente.'">'.$nombreMesSiguiente.' &raquo;</a>'; // Nombre del mes siguiente
echo '</div>';
echo '<button onclick="volverActual()">Hoy</button>';
echo '</div>';

// Diseño del calendario
echo '<div class="calendarioGrid">';

// Rellenar con días vacíos hasta el primer día de la semana (para que la cuadrícula comience correctamente)
for ($i = 1; $i < $primerDiaSemana; $i++) {
    echo '<div class="diaVacio"></div>';
}

// Mostrar los días del mes
$diasEnElMes = date('t', strtotime("$anio-$mes-01"));
for ($dia = 1; $dia <= $diasEnElMes; $dia++) {
    // Obtener el nombre del día de la semana
    $fechaCompleta = "$anio-$mes-$dia";
    $diaSemana = date('l', strtotime($fechaCompleta));
    $nombreDiaSemana = ['Sunday' => 'Domingo', 'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado'];
    $diaSemana = $nombreDiaSemana[$diaSemana];
    $tieneRegistros = isset($registrosPorDia[$dia]); // Verificar si el día tiene registros

    echo '
    <div class="diaDiv">
        <button class="diaButton" onclick="abrirModal('.$dia.')">
            <span class="numeroYDia">'.$dia.' '.$diaSemana.'  </span>
            '.($tieneRegistros ? '<span class="indicadorRegistros">📋<p> Registros</p></span>' : '').' <!-- Placeholder para días con registros -->
        </button>
    </div>';
}

// Calcular cuántos días faltan para completar la última semana
$ultimoDiaSemana = date('N', strtotime("$anio-$mes-$diasEnElMes"));
$diasRestantes = 7 - $ultimoDiaSemana;

// Rellenar con días vacíos si es necesario para completar la cuadrícula
for ($i = 1; $i <= $diasRestantes; $i++) {
    echo '<div class="diaVacio"></div>';
}

echo '</div>'; // Fin de calendarioGrid
echo '</div>'; // Fin de calendarioContainer

// Modal para mostrar los registros
echo '<div id="modalRegistros" class="modal">';
echo '<div class="modalContent">';
echo '<span class="closeButton" onclick="cerrarModal()">&times;</span>';
echo '<h2 id="modalTitle"></h2>';
echo '<table id="tablaRegistros" class="tablaRegistros">';
echo '<thead><tr><th>Fecha</th><th>Técnico</th><th>Descripción</th></tr></thead>';
echo '<tbody id="modalBody"></tbody>';
echo '</table>';
echo '</div>';
echo '</div>';

include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>

<script>
// Pasar los datos de registros a JavaScript
var registrosPorDia = <?php echo json_encode($registrosPorDia); ?>;

// Función para abrir la modal y cargar los registros
function abrirModal(dia) {
    var modal = document.getElementById('modalRegistros');
    var modalTitle = document.getElementById('modalTitle');
    var modalBody = document.getElementById('modalBody');
    var fechaTitulo = registrosPorDia[dia] ? registrosPorDia[dia][0].fecha_titulo : '';

    modalTitle.innerHTML = "Registros del " + fechaTitulo;

    // Limpiar contenido anterior
    modalBody.innerHTML = '';

    // Cargar los registros del día
    if (registrosPorDia[dia] && registrosPorDia[dia].length > 0) {
        registrosPorDia[dia].forEach(function(registro) {
            modalBody.innerHTML += `<tr>
                    <td>${registro.fecha_formateada}</td>
                    <td>${registro.tecnico}</td>
                    <td>${registro.descripcion}</td>
                </tr>`;
        });
    } else {
        modalBody.innerHTML = '<tr><td colspan="3">No hay registros para este día</td></tr>';
    }

    modal.style.display = 'block';  // Mostrar modal
}

// Función para cerrar la modal
function cerrarModal() {
    var modal = document.getElementById('modalRegistros');
    modal.style.display = 'none';  // Ocultar modal
}

// Función para volver al mes y año actuales
function volverActual() {
    window.location.href = "?mes=<?php echo $mesActual; ?>&anio=<?php echo $anioActual; ?>";
}

// Cerrar la modal cuando se hace clic fuera de ella
window.onclick = function(event) {
    var modal = document.getElementById('modalRegistros');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>
