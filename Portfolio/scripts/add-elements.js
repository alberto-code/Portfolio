const formAddElement = document.getElementById('formAgregarElemento')
if (formAddElement){
 document.getElementById('formAgregarElemento').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar el envío normal del formulario

    var formData = new FormData(this);

    // Realizar la solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/Portfolio/project/procesado-info/procesarElementos.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                // Cerrar el modal
                document.getElementById('modalAgregarElemento').style.display = 'none';

                const messageConfirmation = document.querySelector('.confirmChangePrices');
                if (messageConfirmation) {
                  messageConfirmation.style.display = 'flex';
                  setTimeout(() => {
                    messageConfirmation.style.opacity = '1';
                    setTimeout(() => {
                      messageConfirmation.style.opacity = '0';
                      setTimeout(() => {
                        messageConfirmation.style.display = 'none';
                      }, 1200);
                    }, 3000);
                  }, 100);
                }

                // Crear un nuevo div con el elemento y agregarlo al DOM
                var almacenGrid = document.querySelector('.almacenGrid');
                var nuevoElemento = document.createElement('div');
                nuevoElemento.classList.add('almacenDiv');

                // Verificar si estamos en la página de almacenTo o almacenCS
                var isTonePage = window.location.href.includes('almacenTO'); // Cambia según la URL
                var isToneElement = formData.get('TONE') === '1';

                // Si el elemento es TONE y estamos en almacenTo, o si no es TONE y estamos en almacenCS, lo añadimos
                if ((isToneElement && isTonePage) || (!isToneElement && !isTonePage)) {
                    // Contenido del nuevo elemento (usando el ID del elemento recién insertado)
                    nuevoElemento.innerHTML = `
                        <div class="almacenDivButton">
                            <h2>${formData.get('tipo')}</h2>
                            <a onclick="mostrarConfirmacionBorrado(${response.data.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM13.4142 13.9997L15.182 15.7675L13.7678 17.1817L12 15.4139L10.2322 17.1817L8.81802 15.7675L10.5858 13.9997L8.81802 12.232L10.2322 10.8178L12 12.5855L13.7678 10.8178L15.182 12.232L13.4142 13.9997ZM9 4V6H15V4H9Z"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="almacenDivData">
                            <h2>${formData.get('nombre')}</h2>
                        </div>
                    `;

                    // Insertar el nuevo elemento al final de la lista
                    almacenGrid.appendChild(nuevoElemento);
                }

                // Reiniciar el formulario
                document.getElementById('formAgregarElemento').reset();
            } else {
                alert('Error: ' + response.message);
            }
        }
    };
    xhr.send(formData);
});

// Lógica para manejar la subida de imagen y modal
const subidaImagen = document.querySelector('.textoImagen');
const textoSubidaImagen = document.querySelector('.textoImagen h2');
const imagen = document.querySelector('#file-upload');
const labelImagen = document.querySelector('.custom-file-upload');

function borrador() {
  imagen.value = '';
  textoSubidaImagen.innerHTML = '';
  subidaImagen.style.display = 'none';
  labelImagen.style.display = '';
}

imagen.addEventListener('change', function () {
  subidaImagen.style.display = 'flex';
  textoSubidaImagen.innerHTML = imagen.value.replace('C:\\fakepath\\', ' ');
  labelImagen.style.display = 'none';
});

// Abrir modal
document.getElementById('abrirModalAgregarElemento').addEventListener('click', function(event) {
    event.preventDefault();
    document.querySelector('.modalAddForm').style.display = 'flex';
});

// Cerrar modal al hacer clic fuera del formulario
window.onclick = function(event) {
    var modal = document.querySelector('.modalAddForm');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};

}    


const formAddTec = document.getElementById('formAgregarTec')
if (formAddTec){
    document.getElementById('formAgregarTec').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario
    
        var formData = new FormData(this);
    
        // Realizar la solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/Portfolio/project/procesado-info/procesarTecnico.php', true); 
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Cerrar el modal
                    document.getElementById('modalAgregarElemento').style.display = 'none';
    
                    // Mostrar confirmación de registro exitoso
                    const messageConfirmation = document.querySelector('.confirmChangePrices');
                    if (messageConfirmation) {
                        messageConfirmation.style.display = 'flex';
                        setTimeout(() => {
                            messageConfirmation.style.opacity = '1';
                            setTimeout(() => {
                                messageConfirmation.style.opacity = '0';
                                setTimeout(() => {
                                    messageConfirmation.style.display = 'none';
                                }, 1200);
                            }, 3000);
                        }, 100);
                    }
    
                    // Crear un nuevo div con el técnico y agregarlo al DOM
                    var tecnicosGrid = document.querySelector('.tecnicosGrid');
                    var nuevoTecnico = document.createElement('div');
                    nuevoTecnico.classList.add('tecnizaco');
                    nuevoTecnico.id = `tecnico-${response.data.id}`;
    
                    // Contenido del nuevo técnico
                    nuevoTecnico.innerHTML = `
                        <div class="tecnicoInfo">
                            <img src="${response.data.imagen || '/default/path/to/default_image.jpg'}" alt="Foto Técnico">
                            <h2>${response.data.usuario}</h2>
                            <p>${response.data.rol}</p>
                        </div>
                        <div class="tecnicoData">
                            <div class="tecniIcons">
                            <a onclick="mostrarConfirmacionBorrado(${response.data.id})">
                                <svg class="deleteTecnico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 4V2H17V4H22V6H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V6H2V4H7ZM6 6V20H18V6H6ZM9 9H11V17H9V9ZM13 9H15V17H13V9Z"></path>
                                </svg>
                            </a>
                            </div>
                            <div class="mail">
                                Mail:
                                <input value="${response.data.email}" disabled type="text">
                            </div>
                            <div class="pass">
                                Modificar datos:
                                <a href="/Portfolio/project/procesado-info/change-password.php?id=${response.data.id}"><button>Cambiar</button></a>
                            </div>
                        </div>
                        <div id="confirmacion-${response.data.id}" class="confirmacionBorrado ocultandoConfirmacionBorrado">
                            <div class="confirmacionBorradoDiv">
                                <svg class="alert" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8659 3.00017L22.3922 19.5002C22.6684 19.9785 22.5045 20.5901 22.0262 20.8662C21.8742 20.954 21.7017 21.0002 21.5262 21.0002H2.47363C1.92135 21.0002 1.47363 20.5525 1.47363 20.0002C1.47363 19.8246 1.51984 19.6522 1.60761 19.5002L11.1339 3.00017C11.41 2.52187 12.0216 2.358 12.4999 2.63414C12.6519 2.72191 12.7782 2.84815 12.8659 3.00017ZM10.9999 16.0002V18.0002H12.9999V16.0002H10.9999ZM10.9999 9.00017V14.0002H12.9999V9.00017H10.9999Z"></path></svg>
                                <h2>¿Está seguro de querer eliminar?</h2>
                                <span>
                                    <button onclick="mostrarConfirmacionBorrado(${response.data.id})">
                                        Atrás
                                    </button>
                                    <a onclick="borrarElemento(${response.data.id}, 'tecnico')">
                                        <button>Continuar</button>
                                    </a>
                                </span>
                            </div>
                        </div>
                    `;
    
                    // Agregar el nuevo técnico al DOM
                    tecnicosGrid.appendChild(nuevoTecnico);
    
                    // Reiniciar el formulario
                    document.getElementById('formAgregarTec').reset();
                } else {
                    alert('Error: ' + response.message);
                }
            }
        };
        xhr.send(formData);
    });
    
    
   // Función para manejar la subida de imagen
const subidaImagen = document.querySelector('.textoImagen');
const textoSubidaImagen = document.querySelector('.textoImagen h2');
const imagen = document.querySelector('#file-upload');
const labelImagen = document.querySelector('.custom-file-upload');

const subidaImagen2 = document.querySelector('.textoImagen2');
const textoSubidaImagen2 = document.querySelector('.textoImagen2 h2');
const imagen2 = document.querySelector('#file-upload2');
const labelImagen2 = document.querySelector('.custom-file-upload2');

// Función para resetear la selección de imagen
function borrador() {
  imagen.value = '';
  imagen2.value = '';
  textoSubidaImagen.innerHTML = '';
  textoSubidaImagen2.innerHTML = '';
  subidaImagen.style.display = 'none';
  subidaImagen2.style.display = 'none';
  labelImagen.style.display = '';
  labelImagen2.style.display = '';
}

// Listener para la imagen del formulario de añadir
imagen.addEventListener('change', function () {
  subidaImagen.style.display = 'flex';
  textoSubidaImagen.innerHTML = imagen.value.replace('C:\\fakepath\\', ' ');
  labelImagen.style.display = 'none';
});

imagen2.addEventListener('change', function () {
    subidaImagen2.style.display = 'flex';
    textoSubidaImagen2.innerHTML = imagen2.value.replace('C:\\fakepath\\', ' ');
    labelImagen2.style.display = 'none';
});
    
    // Abrir modal
    document.getElementById('abrirModalAgregarElemento').addEventListener('click', function(event) {
        event.preventDefault();
        document.querySelector('.modalAddForm').style.display = 'flex';
    });
    
    // Cerrar modal al hacer clic fuera del formulario
    window.onclick = function(event) {
        var modal = document.querySelector('.modalAddForm');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
    

}    