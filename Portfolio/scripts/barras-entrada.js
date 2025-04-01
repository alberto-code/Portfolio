var codigoEntrada = document.getElementById('codigosEntrada');
var cantEntrada = document.getElementById('cantidadEntrada');
var nombreElementoEntrada = document.getElementById('elementoEntrada');
var tiempoEntrada = document.getElementById('tiempoEntrada');

function generarcodigobarrasEntrada() {
    console.log(nombreElementoEntrada.options[nombreElementoEntrada.selectedIndex].text);
    codigoEntrada.innerHTML = '';  // Limpiar contenido previo

    for (var i = 1; i <= cantEntrada.value; i++) {
        var x = document.getElementById("loteEntrada").value + '-' + i;
        codigoEntrada.innerHTML += `
            <div class="codigos">
                <div class="barCode">
                    <svg id="codigobarras${i}"></svg>
                    <h3>${nombreElementoEntrada.options[nombreElementoEntrada.selectedIndex].text}</h3>
                    <h5>${tiempoEntrada.value}</h5>
                </div>
            </div>
        `;
        JsBarcode(`#codigobarras${i}`, x);
    }

    var mywindow = window.open('', 'PRINT', 'height=800,width=1200');
    mywindow.document.write(`
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8" />
            <style>
                @page {
                    size: A4; /* Tamaño de la hoja A4 */
                    margin: 0; /* Sin márgenes */
                }
                body {
                    margin: 0;
                    padding: 0;
                }
                #codigos {
                    display: flex;
                    flex-wrap: wrap; /* Permite que las etiquetas se envuelvan en varias filas si es necesario */
                    justify-content: flex-start; /* Ajusta las etiquetas desde el inicio */
                    align-items: flex-start; /* Alinea las etiquetas desde la parte superior */
                    width: 100%; /* Ajusta al ancho completo de la página */
                    height: 100%; /* Ajusta al alto completo de la página */
                    box-sizing: border-box;
                    overflow: hidden; /* Evita el desbordamiento */
                }
                .codigos {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    width: 10cm; /* Tamaño de la etiqueta */
                    height: 7cm; /* Tamaño de la etiqueta */
                    box-sizing: border-box;
                    margin: 0.2cm; /* Margen para evitar el pegado al borde */
                    padding: 0;
                }
                .barCode {
                    height: 100%;
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-direction: column;
                    gap: 10px; /* Espacio entre el código de barras y los textos */
                }
                .barCode svg {
                    max-width: 90%; /* Limita el ancho máximo del código de barras */
                    max-height: 60%; /* Limita la altura máxima del código de barras */
                    width: auto; /* Mantiene la proporción del ancho */
                    height: auto; /* Mantiene la proporción de la altura */
                }
                .barCode h3, .barCode h5 {
                    font-family: 'Poppins', sans-serif;
                    font-size: 16px; /* Ajusta el tamaño de fuente */
                    margin: 0;
                    line-height: 1; /* Ajuste para evitar espacios extra */
                }
            </style>
        </head>
        <body>
            <div id="codigos">${codigoEntrada.innerHTML}</div>
        </body>
        <script src="script/barras.js"></script>
        </html>
    `);

    mywindow.document.close();
    mywindow.focus();
    mywindow.print();
    mywindow.close();

    return true;
}
