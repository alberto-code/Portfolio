<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
?>
<div class="lotes">
       <?php
            $acum = 0;
            // Incluye el archivo de conexión una sola vez
            $id = $_GET['id'];
            // Consulta SQL para obtener los registros de materias primas
            $SQL = mysqli_query($enlace, "SELECT * FROM lote WHERE ID_elementos = '$id' ");
            $SQL1 = mysqli_query($enlace,"SELECT * FROM elementos WHERE ID_elemento = '$id'");
            if (mysqli_num_rows($SQL1) > 0) {
                while ($tabla1 = mysqli_fetch_array($SQL1)) {
                    echo '
                     <div class="volverAtras">
                        <span onclick="irALaUltimaPagina()">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.82843 6.99955L8.36396 9.53509L6.94975 10.9493L2 5.99955L6.94975 1.0498L8.36396 2.46402L5.82843 4.99955H13C17.4183 4.99955 21 8.58127 21 12.9996C21 17.4178 17.4183 20.9996 13 20.9996H4V18.9996H13C16.3137 18.9996 19 16.3133 19 12.9996C19 9.68584 16.3137 6.99955 13 6.99955H5.82843Z"></path></svg>
                            ATRÁS
                        </span>
                    </div>
                    <h2>LOTES de  '.$tabla1['nombre'].'</h2>
                     ';
                }
            }
            echo '<div class="lotesGrid">';
            if (mysqli_num_rows($SQL) > 0) {
                while ($tabla = mysqli_fetch_array($SQL)) {
                    $acum++;
                    $id_lote = $tabla['Id_lote'];
                    echo '
                        <div class="loteDiv">
                            <div class="loteButton">
                                <a id="#'.$acum.'" onclick="mostrarPalets('.$acum.')">
                                    Ver palets
                                    <div id="palets-'.$acum.'" class="palets noSeVe">
                                        <button>Cerrar</button>
                                        <div class="paletGrid">';
                                        $SQL2 = mysqli_query($enlace, "SELECT * FROM pale WHERE Id_lote = '$id_lote'");
                                        if (mysqli_num_rows($SQL2) > 0) {
                                        while ($tabla2 = mysqli_fetch_array($SQL2)) {
                                        echo'<div class="palet">'.$tabla2['nombre'].'</div> ';
                                        }
                                        } else {
                                        echo '<h2>No hay pales asociados a este lote</h2>';
                                        }
                                    echo'
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="loteName">
                                <h2>'.$tabla['lote'].'</h2>
                                <p>Nº de palets: '.$tabla['stock_lote'].'</p>
                            </div>
                        </div>
                ';
                }
            } else {
                // echo '<h2>No hay lotes disponibles.</h2>';
                echo '
                <script>
                  function irAPenultimaPagina() {
                    // Redirige al usuario dos páginas atrás en el historial
                    window.history.go(-1);
                    }
                alert("No hay lotes disponibles")
                irAPenultimaPagina()
                </script>
                ';
            }
        ?>
    </div>
</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>