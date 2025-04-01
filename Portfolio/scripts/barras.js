var codigo = document.getElementById('codigos');
var cant = document.getElementById('cantidad');
var nombreElemento = document.getElementById('nombreElemento');
var tiempo = document.getElementById('tiempo');

function generarcodigobarras() {
    codigo.innerHTML = '';  // Limpiar contenido previo
    for (var i = 1; i <= cant.value; i++) {
        var x = document.getElementById("entrada_codigo").value + '-' + i;
        codigo.innerHTML += `
            <div class="codigos">
                <div class="barCode">
                    <svg id="codigobarras${i}"></svg>
                    <h3>${nombreElemento.value}</h3>
                    <h5>${tiempo.value}</h5>
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
                    size: 21cm 29.7cm; /* Tamaño de la hoja A4 */
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
                    overflow: hidden; /* Evita el desbordamiento del contenido */
                }
                .barCode {
                    height: 100%;
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-direction: column;
                    gap: 5px; /* Espacio entre el código de barras y los textos */
                }
                .barCode svg {
                    max-width: 90%; /* Limita el ancho máximo del código de barras */
                    max-height: 70%; /* Limita la altura máxima del código de barras */
                    width: auto; /* Mantiene la proporción del ancho */
                    height: auto; /* Mantiene la proporción de la altura */
                }
                .barCode h3, .barCode h5 {
                    font-family: 'Poppins', sans-serif;
                    font-size: 14px;
                    margin: 0;
                }
            </style>
        </head>
        <body>
            <div id="codigos">${codigo.innerHTML}</div>
        </body>
        </html>
    `);

    mywindow.document.close();
    mywindow.focus();
    mywindow.print();
    mywindow.close();

    return true;
}
