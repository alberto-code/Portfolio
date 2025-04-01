<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
?>

<div class="addForm">
    <div id="camera"></div>
    <div id="myFormDiv" style="display:none" class="form">
        <form name="myForm" id="myForm" action="/Portfolio/project/borrado/borrado.php" method="POST">
            <h2>Lectura correcta<br>Por favor, espere</h2>
            <input type="text" id="lectura" name="nombre">
            <!-- <button type="submit">CONFIRMAR</button> -->
        </form>  
    </div>
</div>
<audio style="display:none" id="audio">
        <source src="/Portfolio/audio/beep.mp3" type="audio/mp3">
</audio>
<script>
document.addEventListener('DOMContentLoaded', () => {
        procesado()
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>