<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

// Inicializamos variables
$toneSeleccionado = isset($_POST['TONE']) ? (int)$_POST['TONE'] : 0;
$elementos = [];

// Consulta a la base de datos si hay una selección previa
$stmt = $enlace->prepare("SELECT ID_elemento, nombre FROM elementos WHERE tipo = 'Productos' AND tone = ?");
$stmt->bind_param("i", $toneSeleccionado);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    echo "Error en la consulta: " . $enlace->error;
}

while ($row = $result->fetch_assoc()) {
    $elementos[] = $row;
}

$stmt->close();
?>

<div class="addForm">
    <div class="form">
        <h2 class="page-title">Añadir Lote</h2>
        
        <!-- Formulario oculto para actualizar el estado del checkbox -->
        <form id="toneForm" action="" method="POST" style="display: none;">
            <input name="TONE" id="toneHidden" type="hidden" value="<?php echo htmlspecialchars($toneSeleccionado); ?>">
        </form>

        <!-- Formulario principal -->
        <form id="msform" action="/Portfolio/project/procesado-info/procesarLotes.php" method="POST" enctype="multipart/form-data">
            
            <!-- Selección de elementos -->
            <select required name="ID_elemento" id="elementoEntrada">
                <?php
                if (!empty($elementos)) {
                    foreach ($elementos as $elemento) {
                        echo '<option value="' . htmlspecialchars($elemento['ID_elemento']) . '">' . htmlspecialchars($elemento['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay elementos disponibles</option>';
                }
                ?>
            </select>
            
            <!-- Checkbox TONE -->
            <div class="inLine">
                <h2>T-ONE</h2>
                <input name="TONE" id="tone" type="checkbox" <?php echo $toneSeleccionado ? 'checked' : ''; ?> onchange="updateTone()">
            </div>
            
            <input onchange="toggleButton()" required id="loteEntrada" name="lote" placeholder="Código de lote" type="text">
            <input onchange="toggleButton()" required id="cantidadEntrada" min="0" name="stock_lote" placeholder="Cantidad" type="number">
            <?php
                $current_date_time = date('d-m-Y H:i:s');
                echo '<input type="hidden" value="' . $current_date_time . '" id="tiempoEntrada">';
            ?>
            <!-- Botón de registro -->
            <button disabled onclick="generarcodigobarrasEntrada()" type="submit">REGISTRAR</button>
        </form>
    </div>
    <div style="display: none;" id="codigosEntrada"></div>
</div>

<script>
    function updateTone() {
        // Actualizar el formulario oculto con el estado del checkbox
        var toneCheckbox = document.getElementById('tone');
        document.getElementById('toneHidden').value = toneCheckbox.checked ? 1 : 0;
        
        // Enviar el formulario oculto para recargar la página con el nuevo estado
        document.getElementById('toneForm').submit();
    }
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>
