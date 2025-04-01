<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

// Inicializamos variables
$tipoSeleccionado = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$toneSeleccionado = isset($_POST['TONE']) ? 1 : 0;
$elementos = [];

// Consulta a la base de datos si hay una selección previa
if ($tipoSeleccionado !== '') {
    $stmt = $enlace->prepare("SELECT ID_elemento, nombre FROM elementos WHERE tipo = ? AND tone = ?");
    $stmt->bind_param("si", $tipoSeleccionado, $toneSeleccionado);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $elementos[] = $row;
    }

    $stmt->close();
}
?>

<div class="addForm">
    <div class="form">
        <h2 class="page-title">Añadir Lote</h2>
        <form id="msform" action="" method="POST" enctype="multipart/form-data">
            <select required name="tipo" id="tipo" onchange="this.form.submit()">
                <option value="">Selecciona Elemento</option>
                <option value="Materias" <?php echo $tipoSeleccionado == 'Materias' ? 'selected' : ''; ?>>Materias</option>
                <option value="Bobinas" <?php echo $tipoSeleccionado == 'Bobinas' ? 'selected' : ''; ?>>Bobinas</option>
            </select>

            <div class="inLine">
                <h2>T-ONE</h2>
                <input name="TONE" id="tone" type="checkbox" <?php echo $toneSeleccionado ? 'checked' : ''; ?> onchange="this.form.submit()">
            </div>

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
            
            <input onchange="toggleButton()" required id="loteEntrada" name="lote" placeholder="Código de lote" type="text">
            <input onchange="toggleButton()" required id="cantidadEntrada" min="0" name="stock_lote" placeholder="Cantidad" type="number">
            <?php
                $current_date_time = date('d-m-Y H:i:s');
                echo '<input type="hidden" value="' . $current_date_time . '" id="tiempoEntrada">';
            ?>
            <!-- Botón que envía el formulario a procesarLotes.php -->
            <button disabled onclick="generarcodigobarrasEntrada()" type="submit" formaction="/Portfolio/project/procesado-info/procesarLotes.php">REGISTRAR</button>
        </form>
    </div>
    <div style="display: none;" id="codigosEntrada"></div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>
