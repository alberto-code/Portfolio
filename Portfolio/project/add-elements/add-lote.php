<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
?>

    <div class="addForm">
    <div class="form">
        <h2 class="page-title">Añadir lote</h2>
        <form action="/Portfolio/project/procesado-info/procesarLotes.php" method="POST">
        
            <input onchange="toggleButton()" required type="text" class="entrada_codigo" id="entrada_codigo" name="lote" placeholder="Código de lote" required>
            <input onchange="toggleButton()" required id="cantidad" type="number" min="0" name="stock_lote" placeholder="Cantidad" required>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $id = $_GET['id'];
                $current_date_time = date('d-m-Y H:i:s');
                echo '<input type="hidden" value="'.$id.'" name="ID_elemento">';
                $SQL1 = mysqli_query($enlace, "SELECT * FROM elementos WHERE ID_elemento = '$id'");
                if (mysqli_num_rows($SQL1) > 0) {
                    while ($tabla1 = mysqli_fetch_array($SQL1)) {
                        echo '<input type="hidden" value="'.$tabla1['nombre'].'" id="nombreElemento">';
                        echo '<input type="hidden" value="'.$current_date_time.'" id="tiempo">';
                    }
                }
            }
            ?>
            <?php 
                            include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
                            if (!$enlace) {
                                die("Connection failed: " . mysqli_connect_error());
                            }
                            // Realizar la consulta
                            $SQL = mysqli_query($enlace, "SELECT * FROM lote WHERE ID_elementos = '$id'");
                            // Verificar la consulta
                            if (!$SQL) {
                                die("Query failed: " . mysqli_error($enlace));
                            }
                                while ($fila = mysqli_fetch_assoc($SQL)) {
                                    echo '<input class="losLotes" id="'.$id.'" type="hidden" value="' . htmlspecialchars($fila['lote']) . '" >';
                            }
            ?>
            <button disabled type="submit" onclick="generarcodigobarras()">REGISTRAR</button>
            <?php
            if (isset($mensaje)) {
                echo "<p>$mensaje</p>";
            }
            ?>
        </div>
    </form>
</div>
<div style="display: none;" id="codigos"></div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>
