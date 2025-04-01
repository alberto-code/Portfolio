<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');

if ($_SESSION["rol"] !== 'admin') {
    echo '<script>
    alert("Acceso denegado");
    window.location.href="/Portfolio/index.php";
    </script>';
    exit();
}
?>
<div class="tecnicos">
    <div class="volverAtras">
        <span onclick="irALaUltimaPagina()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M5.82843 6.99955L8.36396 9.53509L6.94975 10.9493L2 5.99955L6.94975 1.0498L8.36396 2.46402L5.82843 4.99955H13C17.4183 4.99955 21 8.58127 21 12.9996C21 17.4178 17.4183 20.9996 13 20.9996H4V18.9996H13C16.3137 18.9996 19 16.3133 19 12.9996C19 9.68584 16.3137 6.99955 13 6.99955H5.82843Z"></path>
            </svg>
            ATRÁS
        </span>
    </div>
    <h2>Técnicos</h2>
    <div class="menuTecnicos">
        <div class="filtroBusqueda">
            <select id="buscadorTecnicos" onchange="BuscadorTecnicos()">
                <option value="TODOS">TODOS</option>
                <option value="admin">Admin</option>
                <option value="administracion">Administración</option>
                <option value="tecnico">Técnico</option>
            </select>
        </div>
        <a href="#" id="abrirModalAgregarElemento">
            <button>
                Añadir técnico
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 3C3.44772 3 3 3.44772 3 4V10C3 10.5523 3.44772 11 4 11H10C10.5523 11 11 10.5523 11 10V4C11 3.44772 10.5523 3 10 3H4ZM4 13C3.44772 13 3 13.4477 3 14V20C3 20.5523 3.44772 21 4 21H10C10.5523 21 11 20.5523 11 20V14C11 13.4477 10.5523 13 10 13H4ZM14 13C13.4477 13 13 13.4477 13 14V20C13 20.5523 13.4477 21 14 21H20C20.5523 21 21 20.5523 21 20V14C21 13.4477 20.5523 13 20 13H14ZM15 19V15H19V19H15ZM5 9V5H9V9H5ZM5 19V15H9V19H5ZM16 11V8H13V6H16V3H18V6H21V8H18V11H16Z"></path>
                </svg>
            </button>
        </a>

<!-- Modal -->
<div id="modalAgregarElemento" class="modalAddForm">
<div class="addForm">
    <div class="form">
        <h2 class="page-title">Añadir Técnico<h2>
            <form id="formAgregarTec" method="POST" enctype="multipart/form-data">
                <input required placeholder="Usuario" name="usuario" type="text">
                <input required placeholder="Email" name="email" type="text">
                <input required placeholder="Contraseña" name="contrasena" type="password">
                <select required name="rol">
                    <option value="admin">Admin</option>
                    <option value="administracion">Administración</option>
                    <option value="tecnico">Técnico</option>
                </select>
                <label id="subidaImagen" for="file-upload" class="custom-file-upload">
                Añadir imagen
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M14.8287 7.75737L9.1718 13.4142C8.78127 13.8047 8.78127 14.4379 9.1718 14.8284C9.56232 15.219 10.1955 15.219 10.586 14.8284L16.2429 9.17158C17.4144 8.00001 17.4144 6.10052 16.2429 4.92894C15.0713 3.75737 13.1718 3.75737 12.0002 4.92894L6.34337 10.5858C4.39075 12.5384 4.39075 15.7042 6.34337 17.6569C8.29599 19.6095 11.4618 19.6095 13.4144 17.6569L19.0713 12L20.4855 13.4142L14.8287 19.0711C12.095 21.8047 7.66283 21.8047 4.92916 19.0711C2.19549 16.3374 2.19549 11.9053 4.92916 9.17158L10.586 3.51473C12.5386 1.56211 15.7045 1.56211 17.6571 3.51473C19.6097 5.46735 19.6097 8.63317 17.6571 10.5858L12.0002 16.2427C10.8287 17.4142 8.92916 17.4142 7.75759 16.2427C6.58601 15.0711 6.58601 13.1716 7.75759 12L13.4144 6.34316L14.8287 7.75737Z"></path></svg>
                </label>
                <input id="file-upload" placeholder="Imagen" name="imagen" type="file" accept="image/*">
                <div style="display:none" class="textoImagen">
                    <h2>Imagen</h2>
                    <svg onclick="borrador()" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                </div>
                <button type="submit">REGISTRAR</button>
            </form>
    </div>
</div>
</div>
    </div>

    <div class="tecnicosGrid">
        <div class="loading">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C12.5523 2 13 2.44772 13 3V6C13 6.55228 12.5523 7 12 7C11.4477 7 11 6.55228 11 6V3C11 2.44772 11.4477 2 12 2ZM12 17C12.5523 17 13 17.4477 13 18V21C13 21.5523 12.5523 22 12 22C11.4477 22 11 21.5523 11 21V18C11 17.4477 11.4477 17 12 17ZM22 12C22 12.5523 21.5523 13 21 13H18C17.4477 13 17 12.5523 17 12C17 11.4477 17.4477 11 18 11H21C21.5523 11 22 11.4477 22 12ZM7 12C7 12.5523 6.55228 13 6 13H3C2.44772 13 2 12.5523 2 12C2 11.4477 2.44772 11 3 11H6C6.55228 11 7 11.4477 7 12ZM19.0711 19.0711C18.6805 19.4616 18.0474 19.4616 17.6569 19.0711L15.5355 16.9497C15.145 16.5592 15.145 15.9261 15.5355 15.5355C15.9261 15.145 16.5592 15.145 16.9497 15.5355L19.0711 17.6569C19.4616 18.0474 19.4616 18.6805 19.0711 19.0711ZM8.46447 8.46447C8.07394 8.85499 7.44078 8.85499 7.05025 8.46447L4.92893 6.34315C4.53841 5.95262 4.53841 5.31946 4.92893 4.92893C5.31946 4.53841 5.95262 4.53841 6.34315 4.92893L8.46447 7.05025C8.85499 7.44078 8.85499 8.07394 8.46447 8.46447ZM4.92893 19.0711C4.53841 18.6805 4.53841 18.0474 4.92893 17.6569L7.05025 15.5355C7.44078 15.145 8.07394 15.145 8.46447 15.5355C8.85499 15.9261 8.85499 16.5592 8.46447 16.9497L6.34315 19.0711C5.95262 19.4616 5.31946 19.4616 4.92893 19.0711ZM15.5355 8.46447C15.145 8.07394 15.145 7.44078 15.5355 7.05025L17.6569 4.92893C18.0474 4.53841 18.6805 4.53841 19.0711 4.92893C19.4616 5.31946 19.4616 5.95262 19.0711 6.34315L16.9497 8.46447C16.5592 8.85499 15.9261 8.85499 15.5355 8.46447Z"></path></svg>
        </div>
        <?php
        // Consulta SQL para obtener los registros de técnicos
        $SQL = mysqli_query($enlace, "SELECT * FROM tecnico");
        if (mysqli_num_rows($SQL) > 0) {
            while ($tabla = mysqli_fetch_array($SQL)) {
                echo '
                <div id="tecnico-' . htmlspecialchars($tabla['ID'], ENT_QUOTES, 'UTF-8') . '" class="tecnizaco">
                        <div class="tecnicoInfo">
                            <img src="' . htmlspecialchars($tabla['imagen'], ENT_QUOTES, 'UTF-8') . '">
                            <h2>' . htmlspecialchars($tabla['usuario'], ENT_QUOTES, 'UTF-8') . '</h2>
                            <p>' . htmlspecialchars($tabla['rol'], ENT_QUOTES, 'UTF-8') . '</p>
                        </div>
                        <div class="tecnicoData">
                            <div class="tecniIcons">
                            <a onclick="mostrarConfirmacionBorrado(' . htmlspecialchars($tabla['ID'], ENT_QUOTES, 'UTF-8') . ')">
                                <svg class="deleteTecnico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 4V2H17V4H22V6H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V6H2V4H7ZM6 6V20H18V6H6ZM9 9H11V17H9V9ZM13 9H15V17H13V9Z"></path></svg>
                            </a>
                            </div>
                            <div class="mail">
                                Mail:
                                <input value="' . htmlspecialchars($tabla['email'], ENT_QUOTES, 'UTF-8') . '" disabled type="text">
                            </div>
                            <div class="pass">
                                Modificar datos:
                                <a href="#" onclick="abrirModalModificarTecnico(\'' . htmlspecialchars($tabla['ID'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($tabla['usuario'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($tabla['email'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($tabla['rol'], ENT_QUOTES, 'UTF-8') . '\')">
                                    <button>Cambiar</button>
                                </a>
                            </div>
                        </div>
                            <div id="confirmacion-' . htmlspecialchars($tabla['ID'], ENT_QUOTES, 'UTF-8') . '" class="confirmacionBorrado ocultandoConfirmacionBorrado">
                                <div class="confirmacionBorradoDiv">
                                    <svg class="alert" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8659 3.00017L22.3922 19.5002C22.6684 19.9785 22.5045 20.5901 22.0262 20.8662C21.8742 20.954 21.7017 21.0002 21.5262 21.0002H2.47363C1.92135 21.0002 1.47363 20.5525 1.47363 20.0002C1.47363 19.8246 1.51984 19.6522 1.60761 19.5002L11.1339 3.00017C11.41 2.52187 12.0216 2.358 12.4999 2.63414C12.6519 2.72191 12.7782 2.84815 12.8659 3.00017ZM10.9999 16.0002V18.0002H12.9999V16.0002H10.9999ZM10.9999 9.00017V14.0002H12.9999V9.00017H10.9999Z"></path></svg>
                                    <h2>¿Está seguro de querer eliminar?</h2>
                                    <span>
                                        <button onclick="mostrarConfirmacionBorrado(' . htmlspecialchars($tabla['ID'], ENT_QUOTES, 'UTF-8') . ')">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.82843 6.99955L8.36396 9.53509L6.94975 10.9493L2 5.99955L6.94975 1.0498L8.36396 2.46402L5.82843 4.99955H13C17.4183 4.99955 21 8.58127 21 12.9996C21 17.4178 17.4183 20.9996 13 20.9996H4V18.9996H13C16.3137 18.9996 19 16.3133 19 12.9996C19 9.68584 16.3137 6.99955 13 6.99955H5.82843Z"></path></svg>
                                            Atrás
                                        </button>
                                        <a onclick="borrarElemento(' . htmlspecialchars($tabla['ID'], ENT_QUOTES, 'UTF-8') . ', \'tecnico\')">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM13.4142 13.9997L15.182 15.7675L13.7678 17.1817L12 15.4139L10.2322 17.1817L8.81802 15.7675L10.5858 13.9997L8.81802 12.232L10.2322 10.8178L12 12.5855L13.7678 10.8178L15.182 12.232L13.4142 13.9997ZM9 4V6H15V4H9Z"></path></svg>
                                            Continuar
                                        </button>
                                        </a>
                                    </span>
                                </div>
                            </div>
                    </div>
                    ';
            }
        }
        ?>
    </div>
    
<!-- Modal para modificar el técnico -->
<div id="modalModificarTecnico" class="modalAddForm" style="display:none;">
    <div class="addForm">
        <div class="form">
            <h2 class="page-title">Modificar Técnico</h2>
            <form id="formModificarTec" enctype="multipart/form-data">
                <!-- Campo oculto para enviar el ID del usuario -->
                <input type="hidden" name="ID" id="modificarTecID">

                <!-- Campo para el nombre de usuario prellenado con el nombre del técnico -->
                <input placeholder="Usuario" id="modificarTecUsuario" name="usuario" type="text" readonly>

                <!-- Campo para seleccionar el rol -->
                <select required name="rol" id="modificarTecRol">
                    <option value="admin">Admin</option>
                    <option value="administracion">Administración</option>
                    <option value="tecnico">Técnico</option>
                </select>

                <!-- Campos para el email y la nueva contraseña -->
                <input placeholder="Email" id="modificarTecEmail" name="email" type="text">
                <input placeholder="Nueva Contraseña" name="contrasena" type="password">

                <!-- Campo para añadir imagen -->
                <label for="file-upload2" class="custom-file-upload2">
                    Añadir imagen
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14.8287 7.75737L9.1718 13.4142C8.78127 13.8047 8.78127 14.4379 9.1718 14.8284C9.56232 15.219 10.1955 15.219 10.586 14.8284L16.2429 9.17158C17.4144 8.00001 17.4144 6.10052 16.2429 4.92894C15.0713 3.75737 13.1718 3.75737 12.0002 4.92894L6.34337 10.5858C4.39075 12.5384 4.39075 15.7042 6.34337 17.6569C8.29599 19.6095 11.4618 19.6095 13.4144 17.6569L19.0713 12L20.4855 13.4142L14.8287 19.0711C12.095 21.8047 7.66283 21.8047 4.92916 19.0711C2.19549 16.3374 2.19549 11.9053 4.92916 9.17158L10.586 3.51473C12.5386 1.56211 15.7045 1.56211 17.6571 3.51473C19.6097 5.46735 19.6097 8.63317 17.6571 10.5858L12.0002 16.2427C10.8287 17.4142 8.92916 17.4142 7.75759 16.2427C6.58601 15.0711 6.58601 13.1716 7.75759 12L13.4144 6.34316L14.8287 7.75737Z"></path>
                    </svg>
                </label>
                <input id="file-upload2" name="imagen" type="file" accept="image/*">
                <div style="display:none" class="textoImagen2">
                    <h2>Imagen</h2>
                    <svg onclick="borrador()" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                    </svg>
                </div>
                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>
</div>



</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>

<script>
document.getElementById('formModificarTec').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevenir el comportamiento por defecto del formulario

    var formData = new FormData(this); // Crear el FormData con los datos del formulario

    fetch('/Portfolio/project/updates/update-user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.text();  // Obtener el texto de la respuesta
    })
    .then(text => {
        console.log('Respuesta del servidor:', text); // Mostrar la respuesta del servidor
        let data;
        try {
            data = JSON.parse(text);  // Intentar parsear como JSON
        } catch (error) {
            console.error('Error al parsear JSON:', error);
            throw new Error('Respuesta no es JSON válido');
        }

        // Procesar la respuesta si es JSON válido
        if (data.status === 'success') {
            // alert('Técnico actualizado exitosamente');
            cerrarModal();
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

            // Actualizar el DOM con los nuevos datos del técnico
            var tecnicoID = document.getElementById('modificarTecID').value;
            var nuevoEmail = document.getElementById('modificarTecEmail').value;
            var nuevoRol = document.getElementById('modificarTecRol').value;

            // Actualizar el email y rol en el DOM
            document.querySelector(`#tecnico-${tecnicoID} .mail input`).value = nuevoEmail;
            document.querySelector(`#tecnico-${tecnicoID} .tecnicoInfo p`).textContent = nuevoRol;

            // Actualizar la imagen si se ha subido una nueva
            if (data.imagen) {
                var timestamp = new Date().getTime();
                var imagenElement = document.querySelector(`#tecnico-${tecnicoID} .tecnicoInfo img`);
                imagenElement.src = `${data.imagen}?t=${timestamp}`;
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud');
    });
});


// Función para abrir la modal con los datos del técnico
function abrirModalModificarTecnico(id, usuario, email, rol) {
    document.getElementById('modificarTecID').value = id;
    document.getElementById('modificarTecUsuario').value = usuario;
    document.getElementById('modificarTecEmail').value = email;
    document.getElementById('modificarTecRol').value = rol;
    document.getElementById('modalModificarTecnico').style.display = 'flex';
}

// Función para cerrar la modal
function cerrarModal() {
    document.getElementById('modalModificarTecnico').style.display = 'none';
}



</script>
