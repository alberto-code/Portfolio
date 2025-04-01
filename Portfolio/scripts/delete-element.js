// Función para borrar el pedido o cualquier otro tipo de elemento
function borrarElemento(idElemento, tipo) {
  console.log(`Intentando eliminar el ${tipo} con ID:`, idElemento);

  // Construye el ID dinámico según el tipo
  var elementId = `${tipo}-${idElemento}`;
  var infoPedidoId = `infopedido-${idElemento}`; // ID del div que contiene la información del pedido

  // Verifica si el elemento existe en el DOM antes de hacer la petición
  var element = document.getElementById(elementId);
  var infoPedidoElement = document.getElementById(infoPedidoId); // Selecciona el div infoPedido

  if (!element) {
      console.warn(`Elemento ${tipo} con ID ${idElemento} no encontrado en el DOM antes de la eliminación`);
      return;
  }

  // Si existe el elemento, continúa con la solicitud de eliminación
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "/Portfolio/project/borrado/borrar_elemento.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.status === 'success') {
              // Eliminar la fila del pedido
              if (element) {
                  element.remove(); // Elimina el pedido del DOM
                  console.log(`Pedido con ID ${idElemento} eliminado exitosamente del DOM`);
              }

              // Eliminar el div con la información del pedido
              if (infoPedidoElement) {
                  infoPedidoElement.remove(); // Elimina el div de la información del pedido
                  console.log(`Info del pedido con ID ${idElemento} eliminada del DOM`);
              }

              // Mostrar mensaje de confirmación con animación
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
          } else {
              console.error("Error del servidor:", response.message);
          }
      }
  };

  // Envía la solicitud AJAX
  xhr.send(`id=${idElemento}&tipo=${tipo}`);
}
