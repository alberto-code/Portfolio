<?php
$NombreBaseDeDatos = "portfolio";
$Servidor = "localhost";
$usuario = "root";
$clave = "";
$enlace = mysqli_connect($Servidor,$usuario,$clave);
if (!$enlace){
    die("Error de conexion (".mysqli_connect_errno().")".mysqli_connect_error());
}
$db_select = mysqli_select_db($enlace,$NombreBaseDeDatos);
if (!$db_select) {
    error_log("Conexion a base de datos fallida".mysqli_error($enlace));
    die ("Error interno");
}

?>

