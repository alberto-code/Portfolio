<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/header.php');
?>

<div class="pedidos">
    <div class="filtrosPedidos">
        <div class="dataPedidos">
            <h2>Pedidos</h2>
            <button onclick="window.location.href='exportar_pedidos.php'">
                Exportar
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M1 14.5C1 12.1716 2.22429 10.1291 4.06426 8.9812C4.56469 5.044 7.92686 2 12 2C16.0731 2 19.4353 5.044 19.9357 8.9812C21.7757 10.1291 23 12.1716 23 14.5C23 17.9216 20.3562 20.7257 17 20.9811L7 21C3.64378 20.7257 1 17.9216 1 14.5ZM16.8483 18.9868C19.1817 18.8093 21 16.8561 21 14.5C21 12.927 20.1884 11.4962 18.8771 10.6781L18.0714 10.1754L17.9517 9.23338C17.5735 6.25803 15.0288 4 12 4C8.97116 4 6.42647 6.25803 6.0483 9.23338L5.92856 10.1754L5.12288 10.6781C3.81156 11.4962 3 12.927 3 14.5C3 16.8561 4.81833 18.8093 7.1517 18.9868L7.325 19H16.675L16.8483 18.9868ZM13 12H16L12 17L8 12H11V8H13V12Z"></path></svg>
            </button>
        </div>

        <div class="filterPedidos">
            <div class="iconoFiltros">
                <div onclick="mostrarFiltrosPedidos()" class="iconoFiltros">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10 18H14V16H10V18ZM3 6V8H21V6H3ZM6 13H18V11H6V13Z"></path></svg>
                    <h4>Filtros</h4>
                </div>
                <button onclick="resetFiltros()">Resetear</button>
            </div>
            <div class="filtrosSi">
                <div class="iconoFiltros">
                    <h4>Fecha de entrega</h4>
                    <input onchange="filtroFechaPedidos()" type="date">
                </div>
                <div class="iconoFiltros">
                <h4>Tipo</h4>
                        <select class="iconoFiltrosTipo" onchange="filtroTipoPedidos()">
                            <option>TODOS</option>
                            <option>Trailer</option>
                            <option>Agencia</option>
                            <option>Granada/Almeria</option>
                        </select>
                </div>
                <div class="iconoFiltros">
                <h4>Estado</h4>
                        <select class="iconoFiltrosEstado" onchange="filtroEstadoPedidos()">
                            <option>TODOS</option>
                            <option>Pendiente</option>
                            <option>Completado</option>
                        </select>
                </div>
                <input id="buscadorNombrePedidos" type="search" placeholder="Buscar por nombre..">
                <span>
                    <h2>Mostrar</h2>
                    <select onchange="filtroMostrarPedidos()">
                        <option>TODOS</option>
                        <option>10</option>
                        <option>20</option>
                        <option>30</option>
                        <option>40</option>
                    </select>
                </span>
                <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11 2C15.968 2 20 6.032 20 11C20 15.968 15.968 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2ZM11 18C14.8675 18 18 14.8675 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18ZM19.4853 18.0711L22.3137 20.8995L20.8995 22.3137L18.0711 19.4853L19.4853 18.0711Z"></path></svg> -->
            </div>
        </div>
    </div>

    
    <div class="listaPedidos">

        <div class="loading">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C12.5523 2 13 2.44772 13 3V6C13 6.55228 12.5523 7 12 7C11.4477 7 11 6.55228 11 6V3C11 2.44772 11.4477 2 12 2ZM12 17C12.5523 17 13 17.4477 13 18V21C13 21.5523 12.5523 22 12 22C11.4477 22 11 21.5523 11 21V18C11 17.4477 11.4477 17 12 17ZM22 12C22 12.5523 21.5523 13 21 13H18C17.4477 13 17 12.5523 17 12C17 11.4477 17.4477 11 18 11H21C21.5523 11 22 11.4477 22 12ZM7 12C7 12.5523 6.55228 13 6 13H3C2.44772 13 2 12.5523 2 12C2 11.4477 2.44772 11 3 11H6C6.55228 11 7 11.4477 7 12ZM19.0711 19.0711C18.6805 19.4616 18.0474 19.4616 17.6569 19.0711L15.5355 16.9497C15.145 16.5592 15.145 15.9261 15.5355 15.5355C15.9261 15.145 16.5592 15.145 16.9497 15.5355L19.0711 17.6569C19.4616 18.0474 19.4616 18.6805 19.0711 19.0711ZM8.46447 8.46447C8.07394 8.85499 7.44078 8.85499 7.05025 8.46447L4.92893 6.34315C4.53841 5.95262 4.53841 5.31946 4.92893 4.92893C5.31946 4.53841 5.95262 4.53841 6.34315 4.92893L8.46447 7.05025C8.85499 7.44078 8.85499 8.07394 8.46447 8.46447ZM4.92893 19.0711C4.53841 18.6805 4.53841 18.0474 4.92893 17.6569L7.05025 15.5355C7.44078 15.145 8.07394 15.145 8.46447 15.5355C8.85499 15.9261 8.85499 16.5592 8.46447 16.9497L6.34315 19.0711C5.95262 19.4616 5.31946 19.4616 4.92893 19.0711ZM15.5355 8.46447C15.145 8.07394 15.145 7.44078 15.5355 7.05025L17.6569 4.92893C18.0474 4.53841 18.6805 4.53841 19.0711 4.92893C19.4616 5.31946 19.4616 5.95262 19.0711 6.34315L16.9497 8.46447C16.5592 8.85499 15.9261 8.85499 15.5355 8.46447Z"></path></svg>
        </div>

        <div class="gridPedidos">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Nº pedido</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipo pedido</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
        // Consulta SQL para obtener los registros de pedidos
        $SQL = mysqli_query($enlace, "SELECT * FROM pedidos");
        if (mysqli_num_rows($SQL) > 0) {
            while ($tabla = mysqli_fetch_array($SQL)) {
                $originalFechaEntrega = $tabla['fecha_entrega'];
                $newFechaEntrega = date("d/m/Y", strtotime($originalFechaEntrega));

                $originalFechaProduccion = $tabla['fecha_produccion'];
                $newFechaProduccion = date("d/m/Y", strtotime($originalFechaProduccion));
                echo '
                    <tr id="pedido-'.$tabla['id_pedido'].'">
                        <td data-label="Nº pedido" class="nPedido">
                            <h4 class="nPedidoN">CS-'.$tabla['id_pedido'].'</h4>
                            <h4 class="nPedidoFecha">'.$newFechaEntrega.'</h4>
                        </td>
                        <td class="nombrePedidoDato" data-label="Nombre">'.$tabla['nombre'].'</td>
                        <td class="tipoPedidoDato" data-label="Tipo pedido">'.$tabla['tipo'].'</td>
                        <td data-label="Estado" class="ePedido">';
                            if ($tabla['estado']==0){
                                echo'  
                                    <button style="background: lightcoral">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM13 12H17V14H11V7H13V12Z"></path></svg>
                                        Pendiente
                                    </button>';
                              } else {
                                  echo'  
                                   <button style="background: #3CB371">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 3H20C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3ZM5 5V19H19V5H5ZM11.0026 16L6.75999 11.7574L8.17421 10.3431L11.0026 13.1716L16.6595 7.51472L18.0737 8.92893L11.0026 16Z"></path></svg>
                                        Completado
                                    </button>';
                              }
                        echo '
                        </td>
                        <td data-label="Acciones" class="aPedido">
                            <button id="'.$tabla['id_pedido'].'" onclick="mostrarInfoPedido('.$tabla['id_pedido'].')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.0003 3C17.3924 3 21.8784 6.87976 22.8189 12C21.8784 17.1202 17.3924 21 12.0003 21C6.60812 21 2.12215 17.1202 1.18164 12C2.12215 6.87976 6.60812 3 12.0003 3ZM12.0003 19C16.2359 19 19.8603 16.052 20.7777 12C19.8603 7.94803 16.2359 5 12.0003 5C7.7646 5 4.14022 7.94803 3.22278 12C4.14022 16.052 7.7646 19 12.0003 19ZM12.0003 16.5C9.51498 16.5 7.50026 14.4853 7.50026 12C7.50026 9.51472 9.51498 7.5 12.0003 7.5C14.4855 7.5 16.5003 9.51472 16.5003 12C16.5003 14.4853 14.4855 16.5 12.0003 16.5ZM12.0003 14.5C13.381 14.5 14.5003 13.3807 14.5003 12C14.5003 10.6193 13.381 9.5 12.0003 9.5C10.6196 9.5 9.50026 10.6193 9.50026 12C9.50026 13.3807 10.6196 14.5 12.0003 14.5Z"></path></svg>
                                Ver
                            </button>
                        </td>
                        <div id="infopedido-'.$tabla['id_pedido'].'" class="infoPedido ocultandoPedido">
                            <button onclick="mostrarInfoPedido('.$tabla['id_pedido'].')">Cerrar</button>
                            <div class="infoPedidoGrid">
                                    <div class="nombrePedido">
                                        <h2>'.$tabla['nombre'].'</h2>
                                        <h4>CS-'.$tabla['id_pedido'].'</h4>
                                    </div>
                                    <div class="tipoPedido">
                                        <h2>'.$tabla['tipo'].'</h2>
                                    </div>
                                    <div class="fechaEntregaPedido">
                                        <h2>Fecha de entrega</h2>
                                        <h4>'.$newFechaEntrega.'</h4>
                                    </div>
                                    <div class="listaProductosPedido">';
                                    // Consulta para obtener los productos relacionados con este pedido
                                        $productosSQL = mysqli_query($enlace, "SELECT p.nombre, pp.cantidad 
                                                        FROM pedido_productos pp 
                                                        JOIN elementos p ON pp.id_elemento = p.id_elemento 
                                                        WHERE pp.id_pedido = ".$tabla['id_pedido']);
                                        if (mysqli_num_rows($productosSQL) > 0) {
                                        while ($producto = mysqli_fetch_array($productosSQL)) {
                                        echo '
                                            <article>
                                                <h2>'.$producto['nombre'].'</h2>
                                                <h2>'.$producto['cantidad'].' Palets</h2>
                                            </article>';
                                        }
                                        } else {
                                            echo '
                                            <article>
                                                <h2>No hay productos asociados a este pedido</h2>
                                            </article>';
                                        }
                                        echo '
                                    </div>';
                                        if ($tabla['estado']==0){
                                          echo'  
                                            <div style="background: lightcoral" class="estadoPedido">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM13 12H17V14H11V7H13V12Z"></path></svg>
                                                Pendiente
                                            </div>';
                                        } else {
                                            echo'  
                                            <div style="background: #3CB371" class="estadoPedido">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 3H20C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3ZM5 5V19H19V5H5ZM11.0026 16L6.75999 11.7574L8.17421 10.3431L11.0026 13.1716L16.6595 7.51472L18.0737 8.92893L11.0026 16Z"></path></svg>
                                                Completado
                                            </div>';
                                        }
                                    echo'
                                    <div class="fechaProduccionPedido">
                                        <h2>Fecha de produccion</h2>
                                        ';
                                        if ($newFechaProduccion == '31/12/9999') {
                                            echo '<h4>DD-MM-YYYY</h4>';  // Placeholder mostrado al usuario
                                        } else {
                                            echo '<h4>' . $newFechaProduccion . '</h4>';  // Mostrar la fecha real
                                        }
                                        echo '

                                    </div>';
                                     if ($tabla['estado']==0){
                                          echo'  
                                            <div class="botonesPedido">
                                                <a onclick="mostrarConfirmacionBorrado('.$tabla['id_pedido'].')">
                                                    <button class="borradoPedido">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM13.4142 13.9997L15.182 15.7675L13.7678 17.1817L12 15.4139L10.2322 17.1817L8.81802 15.7675L10.5858 13.9997L8.81802 12.232L10.2322 10.8178L12 12.5855L13.7678 10.8178L15.182 12.232L13.4142 13.9997ZM9 4V6H15V4H9Z"></path></svg>
                                                        Borrar
                                                    </button>
                                                </a>
                                                <a href="/Portfolio/project/pedidos/editar-pedido.php?id_pedido='.$tabla['id_pedido'].'"">
                                                    <button>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 6.75736L19 8.75736V4H10V9H5V20H19V17.2426L21 15.2426V21.0082C21 21.556 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5501 3 20.9932V8L9.00319 2H19.9978C20.5513 2 21 2.45531 21 2.9918V6.75736ZM21.7782 8.80761L23.1924 10.2218L15.4142 18L13.9979 17.9979L14 16.5858L21.7782 8.80761Z"></path></svg>
                                                        Editar
                                                    </button>
                                                </a>
                                                <a href="/Portfolio/project/pedidos/enviar-pedido.php?id_pedido='.$tabla['id_pedido'].'"">
                                                    <button class="enviadoPedido">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8.96456 18C8.72194 19.6961 7.26324 21 5.5 21C3.73676 21 2.27806 19.6961 2.03544 18H1V6C1 5.44772 1.44772 5 2 5H16C16.5523 5 17 5.44772 17 6V8H20L23 12.0557V18H20.9646C20.7219 19.6961 19.2632 21 17.5 21C15.7368 21 14.2781 19.6961 14.0354 18H8.96456ZM15 7H3V15.0505C3.63526 14.4022 4.52066 14 5.5 14C6.8962 14 8.10145 14.8175 8.66318 16H14.3368C14.5045 15.647 14.7296 15.3264 15 15.0505V7ZM17 13H21V12.715L18.9917 10H17V13ZM17.5 19C18.1531 19 18.7087 18.5826 18.9146 18C18.9699 17.8436 19 17.6753 19 17.5C19 16.6716 18.3284 16 17.5 16C16.6716 16 16 16.6716 16 17.5C16 17.6753 16.0301 17.8436 16.0854 18C16.2913 18.5826 16.8469 19 17.5 19ZM7 17.5C7 16.6716 6.32843 16 5.5 16C4.67157 16 4 16.6716 4 17.5C4 17.6753 4.03008 17.8436 4.08535 18C4.29127 18.5826 4.84689 19 5.5 19C6.15311 19 6.70873 18.5826 6.91465 18C6.96992 17.8436 7 17.6753 7 17.5Z"></path></svg>
                                                        Enviar
                                                    </button>
                                                </a>
                                            </div>
                                          ';
                                        } else {
                                            echo'  
                                            <div class="botonesPedido">
                                                <a onclick="mostrarConfirmacionBorrado('.$tabla['id_pedido'].')">
                                                    <button class="borradoPedido">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM13.4142 13.9997L15.182 15.7675L13.7678 17.1817L12 15.4139L10.2322 17.1817L8.81802 15.7675L10.5858 13.9997L8.81802 12.232L10.2322 10.8178L12 12.5855L13.7678 10.8178L15.182 12.232L13.4142 13.9997ZM9 4V6H15V4H9Z"></path></svg>
                                                        Borrar
                                                    </button>
                                                </a>
                                            </div>
                                           ';
                                        }
                                   echo'

                                     <div id="confirmacion-'.$tabla['id_pedido'].'" class="confirmacionBorrado ocultandoConfirmacionBorrado">
                                        <div class="confirmacionBorradoDiv">
                                            <svg class="alert" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8659 3.00017L22.3922 19.5002C22.6684 19.9785 22.5045 20.5901 22.0262 20.8662C21.8742 20.954 21.7017 21.0002 21.5262 21.0002H2.47363C1.92135 21.0002 1.47363 20.5525 1.47363 20.0002C1.47363 19.8246 1.51984 19.6522 1.60761 19.5002L11.1339 3.00017C11.41 2.52187 12.0216 2.358 12.4999 2.63414C12.6519 2.72191 12.7782 2.84815 12.8659 3.00017ZM10.9999 16.0002V18.0002H12.9999V16.0002H10.9999ZM10.9999 9.00017V14.0002H12.9999V9.00017H10.9999Z"></path></svg>
                                            <h2>¿Esta seguro de querer eliminar?</h2>
                                            <span>
                                                <button onclick="mostrarConfirmacionBorrado('.$tabla['id_pedido'].')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.82843 6.99955L8.36396 9.53509L6.94975 10.9493L2 5.99955L6.94975 1.0498L8.36396 2.46402L5.82843 4.99955H13C17.4183 4.99955 21 8.58127 21 12.9996C21 17.4178 17.4183 20.9996 13 20.9996H4V18.9996H13C16.3137 18.9996 19 16.3133 19 12.9996C19 9.68584 16.3137 6.99955 13 6.99955H5.82843Z"></path></svg>
                                                    Atrás
                                                </button>
                                                <a onclick="borrarElemento(' . htmlspecialchars($tabla['id_pedido'], ENT_QUOTES, 'UTF-8') . ', \'pedido\')">
                                                    <button>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM13.4142 13.9997L15.182 15.7675L13.7678 17.1817L12 15.4139L10.2322 17.1817L8.81802 15.7675L10.5858 13.9997L8.81802 12.232L10.2322 10.8178L12 12.5855L13.7678 10.8178L15.182 12.232L13.4142 13.9997ZM9 4V6H15V4H9Z"></path></svg>
                                                        Continuar
                                                    </button>
                                                </a>
                                            </span>
                                        </div>
                                    </div>


                            </div>
                        </div>
                    </tr>';
            }
        }
                    ?>
            </table>
        </div>
    </div>

</div>



<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/footer.php');
?>