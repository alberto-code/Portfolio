<?php
// Iniciar output buffering para evitar errores de cabeceras
ob_start();

// Datos de conexión a la base de datos
$NombreBaseDeDatos = "creatNew";
$Servidor = "hl1225.dinaserver.com";
$usuario = "creatNew_";
$clave = "%CreatNew%";

// Crear conexión
$enlace = mysqli_connect($Servidor, $usuario, $clave, $NombreBaseDeDatos);

// Verificar la conexión
if (!$enlace) {
    die("Error de conexión (" . mysqli_connect_errno() . "): " . mysqli_connect_error());
}

// Consulta para obtener los pedidos de la base de datos
$sql = "SELECT id_pedido, nombre, tipo, estado, fecha_entrega, fecha_produccion FROM pedidos";
$result = mysqli_query($enlace, $sql);

// Especificar las cabeceras para la descarga de un archivo CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=pedidos.csv');

// Abrir un puntero de archivo a la salida PHP
$output = fopen('php://output', 'w');

// Añadir BOM al archivo para que Excel lo abra correctamente en UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Escribir la primera línea de encabezados
fputcsv($output, array('Nº pedido', 'Nombre', 'Tipo pedido', 'Estado', 'Fecha de entrega', 'Fecha de producción'), ',');

// Verificar si hay resultados y escribir los pedidos en el CSV
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Convertir el estado a texto
        $row['estado'] = $row['estado'] == 0 ? 'pendiente' : 'completado';
        
        // Escribir cada fila en el archivo CSV
        fputcsv($output, $row, ',');
    }
} else {
    // Si no hay resultados, escribir una fila con un mensaje de no encontrados
    fputcsv($output, array('No se encontraron pedidos.'), ',');
}

// Cerrar la conexión a la base de datos
mysqli_close($enlace);

// Cerrar el puntero de archivo
fclose($output);

// Finalizar output buffering y enviar la salida
ob_end_flush();
?>
