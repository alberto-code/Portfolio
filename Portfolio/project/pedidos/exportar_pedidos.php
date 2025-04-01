<?php
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

// Definir la ruta absoluta y el nombre del archivo CSV
$directorio = __DIR__ . '/'; // Obtiene el directorio del script actual
$carpetaPedidos = 'exports/';
$archivo = 'pedidos.csv';
$rutaCompleta = $directorio . $carpetaPedidos . $archivo;

// Asegurarse de que el directorio exista
if (!is_dir($directorio . $carpetaPedidos)) {
    mkdir($directorio . $carpetaPedidos, 0755, true);
}

// Intentar abrir el archivo para escritura
$output = @fopen($rutaCompleta, 'w');
if ($output === false) {
    die("No se pudo abrir el archivo para escritura en la ruta: " . $rutaCompleta);
}

// Añadir BOM al archivo para que Excel lo abra correctamente en UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Escribir la primera línea de encabezados
fputcsv($output, array('Nº pedido', 'Nombre', 'Tipo pedido', 'Estado', 'Fecha de entrega', 'Fecha de producción'), ',');

// Verificar si hay resultados y escribir los pedidos en el CSV
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Convertir el estado a texto
        $row['estado'] = $row['estado'] == 0 ? 'pendiente' : 'completado';

        // Formatear las fechas en el formato DD-MM-YYYY
        $fechaEntrega = DateTime::createFromFormat('Y-m-d', $row['fecha_entrega']);
        $fechaProduccion = DateTime::createFromFormat('Y-m-d', $row['fecha_produccion']);
        
        // Asegurarse de que la conversión fue exitosa antes de formatear
        $row['fecha_entrega'] = $fechaEntrega ? $fechaEntrega->format('d-m-Y') : 'Formato de fecha inválido';
        $row['fecha_produccion'] = $fechaProduccion ? $fechaProduccion->format('d-m-Y') : 'Formato de fecha inválido';

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

// Mensaje de confirmación
// echo "El archivo CSV ha sido guardado en: " . $rutaCompleta;
echo'
<script>
alert("Archivo exportado correctamente")
window.location.href="pedidos.php"
</script>
'
?>
