//TODO -> BOTON DE ABRIR OCULTAR MENU */

const sidebar= document.querySelector('header .sidebar')
const menuIcon= document.querySelector('#menuIcon')
const overview= document.querySelector('header .overview')
const main= document.querySelector('.main')
const barra1 = document.querySelector('.iconoMenu .barra1');
const barra2 = document.querySelector('.iconoMenu .barra2');
const barra3 = document.querySelector('.iconoMenu .barra3');
var temporal;
let Tone;

menuIcon.addEventListener('click', function () {
  if (sidebar.classList.contains('oculto')){
    sidebar.classList.remove('oculto');
    overview.classList.add('posicionAbierta')
    main.classList.add('posicionAbierta')
    barra1.style.width = '100%';
    barra1.style.transform = 'translateX(0%) translateY(360%) rotate(45deg)';
    barra2.style.opacity = '0';
    barra2.style.transform = 'rotate(90deg)';
    barra3.style.width = '100%';
    barra3.style.transform = 'translateX(0%) translateY(-360%) rotate(-45deg)';
    
  } else {
    sidebar.classList.add('oculto');
    overview.classList.remove('posicionAbierta')
    main.classList.remove('posicionAbierta')
    barra1.style.width = '60%';
    barra1.style.transform = 'translateX(20%) translateY(0%) rotate(0deg)';
    barra2.style.opacity = '1';
    barra2.style.transform = 'rotate(0deg)';
    barra3.style.width = '60%';
    barra3.style.transform = 'translateX(-20%) translateY(0%) rotate(0deg)';

  }
});

//TODO -> ENVIO DE FORMULARIO AUTOMATICO EN ESCANER*/
function beep1(){
  let audio1= document.getElementById('audio')
  audio1.play()
}
function submitform(){
  document.forms["myForm"].submit();
}

// TODO -> FUNCION DE MOSTRAR PALETS EN LOTES */
function mostrarPalets(elemento) {
  const ancla = document.querySelector(`#palets-${elemento}`);
  ancla.classList.toggle('noSeVe');
  ancla.classList.toggle('siSeVe');
}


// *TODO -> ESTO ES PARA QUE APAREZCA LA INFO DEL PEDIDO
function mostrarInfoPedido(elemento) {
  const pedido = document.querySelector(`#infopedido-${elemento}`);
  pedido.classList.toggle('ocultandoPedido');
  pedido.classList.toggle('mostrandoPedido');
}


// *TODO -> ESTO ES PARA QUE APAREZCA LA ALERTA DE BORRADO
function mostrarConfirmacionBorrado(elemento) {
  const confirmacionElemento = document.querySelector(`#confirmacion-${elemento}`);
  const body = document.body;

  if (confirmacionElemento) {
    const esOcultado = confirmacionElemento.classList.toggle('ocultandoConfirmacionBorrado');
    body.style.overflow = esOcultado ? '' : 'hidden';
  } else {
    console.warn('Elemento de confirmación no encontrado');
  }
}



// *TODO -> ESTO ES PARA FILTRAR POR TIPO EN ALMACÉN
const elementosAlmacenText = document.querySelectorAll('.almacenDivButton > h2');
const buscadorAlmacen = document.querySelector('#buscadorAlmacen');
const elementosAlmacen = document.querySelectorAll('.almacenDiv');

function BuscadorAlmacen() {
  loading();
  elementosAlmacen.forEach((elemento, index) => {
    elemento.style.display = 'none';
    if (elementosAlmacenText[index].textContent === buscadorAlmacen.value || buscadorAlmacen.value === 'TODOS') {
      elemento.style.display = 'flex';
    }
  });
}

// *TODO -> ESTO ES PARA FILTRAR POR ROL EN TÉCNICOS
const elementosTecnicosText = document.querySelectorAll('.tecnicosGrid .tecnizaco .tecnicoInfo p');
const buscadorTecnicos = document.querySelector('#buscadorTecnicos');
const elementosTecnicos = document.querySelectorAll('.tecnicosGrid .tecnizaco');

function BuscadorTecnicos() {
  loading();
  elementosTecnicos.forEach((elemento, index) => {
    elemento.style.display = 'none';
    if (elementosTecnicosText[index].textContent === buscadorTecnicos.value || buscadorTecnicos.value === 'TODOS') {
      elemento.style.display = 'flex';
    }
  });
}

// *TODO -> ESTO ES PARA FILTRAR POR NOMBRE EN MATERIAS,BOBINAS Y PRODUCTOS CS Y TO

function filtrarElementos() {
  loading()
  const buscador = document.getElementById('buscadorNombredeElementos').value.toLowerCase();
    const elementos = document.querySelectorAll('.elementosGrid .elementosGridDiv');

    elementos.forEach(elemento => {
        const nombre = elemento.querySelector('.nameElement').textContent.toLowerCase();
        if (nombre.includes(buscador)) {
            elemento.style.display = 'flex'; // Mostrar el elemento si coincide
        } else {
            elemento.style.display = 'none'; // Ocultar el elemento si no coincide
        }
    });
}


const buscadorNombredeElementos = document.getElementById('buscadorNombredeElementos')
if (buscadorNombredeElementos){
  document.getElementById('buscadorNombredeElementos').addEventListener('input', filtrarElementos);
}



// *TODO -> ESTO ES PARA QUE APAREZCAN LOS FILTROS EN PEDIDOS EN MOVIL

const filtrosPedidos = document.querySelector('.filterPedidos .filtrosSi');
const iconoFiltro = document.querySelector('.filterPedidos svg');
let clear1, clear2;

function mostrarFiltrosPedidos() {
  clearTimeout(clear1);
  clearTimeout(clear2);

  if (filtrosPedidos.style.height === '0px' || filtrosPedidos.style.height === '') {
    // Mostrar filtros
    filtrosPedidos.style.display = 'flex';
    filtrosPedidos.style.zIndex = '';
    
    clear1 = setTimeout(() => {
      filtrosPedidos.style.height = '285px';
      filtrosPedidos.style.opacity = '1';
    }, 1);
    
    iconoFiltro.innerHTML = `
      <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 10.5858L14.8284 7.75736L16.2426 9.17157L13.4142 12L16.2426 14.8284L14.8284 16.2426L12 13.4142L9.17157 16.2426L7.75736 14.8284L10.5858 12L7.75736 9.17157L9.17157 7.75736L12 10.5858Z"></path>`;
  } else {
    // Ocultar filtros
    filtrosPedidos.style.height = '0';
    filtrosPedidos.style.opacity = '0';
    filtrosPedidos.style.zIndex = '-5';

    clear1 = setTimeout(() => {
      filtrosPedidos.style.display = 'none';
    }, 2000);
    
    iconoFiltro.innerHTML = `
      <path d="M10 18H14V16H10V18ZM3 6V8H21V6H3ZM6 13H18V11H6V13Z"></path>`;
  }
}


// //*TODO -> ESTO ES PARA FILTROS EN PEDIDOS
const filtroMostrar = document.querySelector('.filterPedidos .filtrosSi span select')
const filtroFechas = document.querySelector('.filterPedidos .filtrosSi input[type="date"]')
const filtroTipos = document.querySelector('.filterPedidos .filtrosSi .iconoFiltrosTipo')
const filtroNombres = document.querySelector('.filterPedidos input[type="search"]')
const filtroEstado = document.querySelector('.filterPedidos .filtrosSi .iconoFiltrosEstado')

const pedidosTotales = document.querySelectorAll('.gridPedidos table tr')
const loader = document.querySelector('.loading')

// ! RESETEAR FILTROS
function resetFiltros() {
  loading()
  filtroFechas.value = ''; 
  filtroMostrar.value = 'TODOS';
  filtroTipos.value='TODOS'
  filtroNombres.value=''
  filtroEstado.value='TODOS'
  pedidosTotales.forEach(pedido => {
      pedido.style.display = '';
  });
}

// ! LOADER DE BUSQUEDA
function loading() {
  loader.style.display='flex'
  setTimeout(() => {
  loader.style.display='none'
  }, 500);
}

// ! FILTRO FECHA ENTREGA
function filtroFechaPedidos() {
  loading()
  let fechaOriginal = filtroFechas.value;
  if (!fechaOriginal) {
      pedidosTotales.forEach(pedido => {
          pedido.style.display = '';
      });
      return;
  }
  let fechaNueva = new Date(fechaOriginal);
  let dia = fechaNueva.getDate();
  let mes = fechaNueva.getMonth() + 1;
  let anio = fechaNueva.getFullYear();
  if (dia < 10) dia = '0' + dia;
  if (mes < 10) mes = '0' + mes;
  let fechaFormateada = dia + '/' + mes + '/' + anio;

  pedidosTotales.forEach(pedido => {
      pedido.style.display = ''; 
  });

  pedidosTotales.forEach(pedido => {
      let fechaElemento = pedido.querySelector('.nPedidoFecha');
      if (fechaElemento) {
          let fechaTexto = fechaElemento.textContent.trim();
          if (fechaTexto === fechaFormateada) {
              pedido.style.display = '';
          } else {
              pedido.style.display = 'none';
          }
      } 
  });
}

// ! FILTRO TIPO PEDIDO
function filtroTipoPedidos() {
  loading();
  pedidosTotales.forEach(pedido => {
    pedido.style.display = '';
  });
  const tipoSeleccionado = filtroTipos.value;
  pedidosTotales.forEach(pedido => {
    let tipoElemento = pedido.querySelector('.tipoPedidoDato');
    if (tipoElemento) {
      let tipoTexto = tipoElemento.textContent.trim();
      
      if (tipoSeleccionado === 'TODOS' || tipoTexto === tipoSeleccionado) {
        pedido.style.display = '';
      } else {
        pedido.style.display = 'none';
      }
    }
  });
}

// ! FILTRO ESTADO PEDIDO
function filtroEstadoPedidos() {
  loading()
  const estadoSeleccionado = filtroEstado.value;
  
  pedidosTotales.forEach(pedido => {
    let estadoElemento = pedido.querySelector('.ePedido button');
    
    if (estadoElemento) {
      let estadoTexto = estadoElemento.textContent.trim();
      
      if (estadoSeleccionado === 'TODOS' || estadoTexto === estadoSeleccionado) {
        pedido.style.display = '';
      } else {
        pedido.style.display = 'none';
      }
    }
  });
}


// ! FILTRO NOMBRE PEDIDO
function filtroNombrePedidos() {
  loading()
  const nombreBusqueda = filtroNombres.value.toLowerCase().trim();
  
  pedidosTotales.forEach(pedido => {
    let nombreElemento = pedido.querySelector('.nombrePedidoDato');
    if (nombreElemento) {
      let nombreTexto = nombreElemento.textContent.toLowerCase().trim();
      if (nombreBusqueda === '' || nombreTexto.includes(nombreBusqueda)) {
        pedido.style.display = '';
      } else {
        pedido.style.display = 'none';
      }
    }
  });
}

const buscadorNombrePedidos = document.getElementById('buscadorNombrePedidos')
if (buscadorNombrePedidos){
  document.getElementById('buscadorNombrePedidos').addEventListener('input', filtroNombrePedidos);
}


// ! FILTRO MOSTRAR PEDIDOS
function filtroMostrarPedidos() {
  loading()
    pedidosTotales.forEach(pedido => {
          pedido.style.display=''
    });

    for (let index = pedidosTotales.length-1; index >= filtroMostrar.value; index--) {
          pedidosTotales[index].style.display='none'
    }
};


// //*TODO -> ESTO ES PARA DESHABILITAR EL BOTON DE ENTRADA, FABRICACION Y ADDLOTES HASTA NO RELLENAR

const botonForm = document.querySelector('.addForm .form form button')
const camposNumero = document.querySelectorAll('.addForm .form form input[type="number"]')
const camposTexto = document.querySelectorAll('.addForm .form form input[type="text"]')
const losLotes = document.querySelectorAll('.addForm .form form .losLotes')
const codigoLote = document.querySelector('.addForm .form form .entrada_codigo')

function toggleButton(){
losLotes.forEach(lote => {
  if(codigoLote.value == lote.value){
    alert ('Este lote ya existe')
    window.href="Portfolio/project/add-elements/add-lote.php?id="+lote.id+""
  }

});

  for (var i=0 ; i< camposTexto.length; i++){
    if (camposTexto[i].value) {
      if(camposNumero[i].value) {
        botonForm.disabled = false;
      }
    } else {
      botonForm.disabled = true;
    }
  }
}

// //*TODO -> ESTO ES PARA GENERAR PRODUCTOS EN NUEVO PEDIDO

var AcumuladorProductosPedidos = 0
function agregarCampo() {
  if (AcumuladorProductosPedidos >= document.querySelector(".producto-select").length-1) {
    alert("No hay más productos.");
    return;
  }

  var divProductos = document.getElementById("divProductoPedido"+AcumuladorProductosPedidos); // Corregido el uso de # en el ID
  if (divProductos) {
    var nuevaProducto = divProductos.cloneNode(true);
    AcumuladorProductosPedidos++
    nuevaProducto.setAttribute('id', 'divProductoPedido'+AcumuladorProductosPedidos+'')
    divProductos.parentNode.insertBefore(nuevaProducto, divProductos.nextSibling);
    updateProductOptions()
  } else {
    console.error("No se encontró el elemento con ID divProductoPedido"+AcumuladorProductosPedidos);
  }
};

// //*TODO -> ESTO ES PARA ELIMINAR PRODUCTOS EN NUEVO PEDIDO

function eliminarCampo(svgElement){
  if (document.querySelectorAll(".addedProduct").length==1){
    alert('Tiene que haber un producto como mínimo')
    return
  }
  var campo = svgElement.closest('.addedProduct');
  campo.parentNode.removeChild(campo);

  var campos = document.querySelectorAll(".addedProduct");
  AcumuladorProductosPedidos=0
  for (var k = 0; k<campos.length;k++){
    campos[k].setAttribute('id', 'divProductoPedido'+AcumuladorProductosPedidos+'')
    AcumuladorProductosPedidos++
  }
  AcumuladorProductosPedidos--
  updateProductOptions()
}

// //*TODO -> ESTO ES PARA ACTUALIZAR EL VALOR DE LOS SELECTS


function updateProductOptions(){
  const selects = document.querySelectorAll('.producto-select')
  const options = document.querySelectorAll('.producto-select option')
  let selectedProducts = [];
  selects.forEach(select => {
      selectedProducts.push(select.value);
  });

  selects.forEach(select => {
    const options = select.querySelectorAll("option");
    options.forEach(option => {
      if (selectedProducts.includes(option.value) && option.value !== select.value) {
        option.style.display = "none";
      } else {
        option.style.display = "";
      }
    });
  });

  selects.forEach(select => {
    select.addEventListener('change', updateProductOptions);
  });
}

//TODO -> BOTON DE ENVIO FORMULARIO DE NUEVO PEDIDO */

function enviarNuevoPedido(){
  document.forms["nuevoPedido3"].submit();
}

//TODO -> BOTON DE ENVIO FORMULARIO DE NUEVO PEDIDO */

async function enviarCorreo() {
  // Obtener el contenedor de productos y palets
  const contenedor = document.querySelector('.dosColumnasPalets');

  // Inicializar una lista para almacenar los productos y sus palets
  const productos = [];

  // Iterar sobre los elementos h4 en el contenedor
  let i = 0;
  while (i < contenedor.children.length) {
      const h4Producto = contenedor.children[i];
      if (h4Producto.tagName === 'H4') {
          // Extraer nombre del producto y cantidad de palets
          const productoNombre = h4Producto.innerText.split('|')[0].trim();
          const cantidadPalets = parseInt(h4Producto.innerText.split('|')[1].trim().split(' ')[0], 10);

          // Inicializar una lista para los palets del producto
          const palets = [];
          i++; // Pasar al div de palets siguiente

          // Obtener los palets del siguiente div
          if (i < contenedor.children.length && contenedor.children[i].classList.contains('dosColumnasPaletsGrid')) {
              const divPalets = contenedor.children[i];
              palets.push(...Array.from(divPalets.querySelectorAll('input[name="pale_nombres[]"]'))
                  .map(input => input.value)
                  .filter(value => value.trim() !== '')
              );
              i++; // Pasar al siguiente h4 o fin del contenedor
          }

          // Asegurar que no se exceda la cantidad esperada de palets
          if (palets.length > cantidadPalets) {
              palets.length = cantidadPalets; // Ajustar la longitud si hay más palets
          }

          // Agregar el producto y sus palets a la lista
          productos.push({
              nombre: productoNombre,
              palets: palets
          });
      } else {
          i++;
      }
  }

  // Construir el mensaje HTML
  const mensajeHTML = `
      <html>
      <body style="font-family: Arial, sans-serif;">
          <h2>Detalles del Pedido</h2>
          <p>A continuación se listan los productos y palets escaneados:</p>
          ${productos.map(producto => `
              <h3>Producto: ${producto.nombre} | Cantidad: ${producto.palets.length} Palets</h3>
              <ul>
                  ${producto.palets.map(palet => `<li>${palet}</li>`).join('')}
              </ul>
          `).join('')}
      </body>
      </html>
  `;

  try {
      const response = await emailjs.send('service_nn9yvjs', 'template_ajude39', {
          to_email: 'recipient@example.com', // Reemplaza con la dirección de correo del destinatario
          subject: 'Informe de palets escaneados',
          message: mensajeHTML // Aquí se pasa el HTML
      }, 'jpu08gqYoKggTzxqz'); // Reemplaza con tu ID de usuario de EmailJS

      console.log('Correo enviado con éxito:', response);
  } catch (error) {
      console.error('Error al enviar el correo:', error);
  }
}

//TODO -> BOTON DE CAMBIO CREA SUSTRATOS - T ONE */
const botonCSTO = document.querySelector('#botonCSTO');
const selectorCSTO = document.querySelector('#selectorCSTO');
const body = document.querySelector('html');
const logo1 = document.querySelector('#logo1');
const logo2 = document.querySelector('#logo2');

const urlsCS = {
  'materias': '/Portfolio/project/main/materiasCS.php',
  'bobinas': '/Portfolio/project/main/bobinasCS.php',
  'productos': '/Portfolio/project/main/productosCS.php',
  'almacen': '/Portfolio/project/admin/almacenCS.php'
};

const urlsTO = {
  'materias': '/Portfolio/project/main/materiasTO.php',
  'bobinas': '/Portfolio/project/main/bobinasTO.php',
  'productos': '/Portfolio/project/main/productosTO.php',
  'almacen': '/Portfolio/project/admin/almacenTO.php'
};

botonCSTO.addEventListener('click', () => {
  const isTone = botonCSTO.classList.toggle('creaSustratos');
  localStorage.setItem("Tone", !isTone);
  cambioMarca();
});

document.addEventListener('DOMContentLoaded', () => {
  cambioMarca();
  buclePrincipal.iterar();
  emailjs.init('jpu08gqYoKggTzxqz'); 
});

function cambioMarca() {
  const isTone = localStorage.getItem("Tone") === "true";
  botonCSTO.classList.toggle('creaSustratos', !isTone);
  botonCSTO.classList.toggle('tOne', isTone);
  selectorCSTO.style.background = isTone ? '#212121' : 'var(--primary)';
  body.style.background = isTone ? 'darkslategray' : '#9BBB1D';
  logo1.textContent = logo2.textContent = isTone ? 'T-ONE' : 'Crea Sustratos';

  document.documentElement.style.setProperty('--black', isTone ? '#9BBB1D' : '#212121');
  document.documentElement.style.setProperty('--primary', isTone ? '#212121' : '#9BBB1D');
  document.documentElement.style.setProperty('--CS', isTone ? 'none' : 'flex');
  document.documentElement.style.setProperty('--TO', isTone ? 'flex' : 'none');

  redireccionarPagina(isTone);
}

function redireccionarPagina(isTone) {
  const currentUrl = window.location.pathname;
  const pageKey = Object.keys(urlsCS).find(key => currentUrl.includes(key));

  if (pageKey) {
    const expectedUrl = isTone ? urlsTO[pageKey] : urlsCS[pageKey];
    if (currentUrl !== expectedUrl) {
      window.location.href = expectedUrl;
    }
  }
}



function irALaUltimaPagina() {
  // Redirige al usuario dos páginas atrás en el historial
  window.history.go(-1);
  }



