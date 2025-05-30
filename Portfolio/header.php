<?php
      // Incluye el archivo de conexión una sola vez
      include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');
      session_start();

      // Verificar si el usuario está autenticado
      if (empty($_SESSION["usuario"])) {
        header("Location: /Portfolio/login.php");
      exit();
      }

    // Obtener el nombre del usuario desde la sesión
      $usuario = $_SESSION["usuario"];
      if (isset($_SESSION["imagen"])) {
        $imagenUsuario = htmlspecialchars($_SESSION["imagen"]);
    } else {
        $imagenUsuario = "/Portfolio/img/img_avatar.png"; // Imagen predeterminada
    }
      
      ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Prueba</title>
        <link rel="stylesheet" href="/Portfolio/fonts/fonts.css">
        <link rel="stylesheet" href="/Portfolio/styles/header.css">
        <link rel="stylesheet" href="/Portfolio/styles/index.css">
        <link rel="stylesheet" href="/Portfolio/styles/admin.css">
        <link rel="stylesheet" href="/Portfolio/styles/add-element.css">
        <link rel="stylesheet" href="/Portfolio/styles/tecnicos.css">
        <link rel="stylesheet" href="/Portfolio/styles/elementosCS.css">
        <link rel="stylesheet" href="/Portfolio/styles/pedidos.css">
        <link rel="stylesheet" href="/Portfolio//styles/confirmacion.css">
        <link rel="stylesheet" href="/Portfolio/styles/lotes.css">
        <link rel="stylesheet" href="/Portfolio/styles/almacen.css">
        <link rel="stylesheet" href="/Portfolio/styles/dashboard.css">
        <link rel="stylesheet" href="/Portfolio/styles/add-pedido.css">
        <link rel="stylesheet" href="/Portfolio/styles/enviar-pedido.css">
        <link rel="stylesheet" href="/Portfolio/styles/salida.css">
        <link rel="stylesheet" href="/Portfolio/styles/registros.css">
        <link rel="shortcut icon" href="/Portfolio/img/menu-items/favicongood.png" type="image/x-icon">
        <link rel="icon" href="/Portfolio/img/menu-items/favicongood.png" type="image/x-icon">
</head>
<body>
<header>
  <div class="sidebar oculto">
    <div class="logoDiv">
      <h1 id="logo1">Prueba</h1>
    </div>

    <div class="menuPrincipal">
      <h2>Menu principal</h2>
      <?php
      if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
        // Mostrar el enlace solo si el usuario es admin
        echo '
        <a href="/Portfolio/project/admin/admin.php">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 14V16C8.68629 16 6 18.6863 6 22H4C4 17.5817 7.58172 14 12 14ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11ZM14.5946 18.8115C14.5327 18.5511 14.5 18.2794 14.5 18C14.5 17.7207 14.5327 17.449 14.5945 17.1886L13.6029 16.6161L14.6029 14.884L15.5952 15.4569C15.9883 15.0851 16.4676 14.8034 17 14.6449V13.5H19V14.6449C19.5324 14.8034 20.0116 15.0851 20.4047 15.4569L21.3971 14.8839L22.3972 16.616L21.4055 17.1885C21.4673 17.449 21.5 17.7207 21.5 18C21.5 18.2793 21.4673 18.551 21.4055 18.8114L22.3972 19.3839L21.3972 21.116L20.4048 20.543C20.0117 20.9149 19.5325 21.1966 19.0001 21.355V22.5H17.0001V21.3551C16.4677 21.1967 15.9884 20.915 15.5953 20.5431L14.603 21.1161L13.6029 19.384L14.5946 18.8115ZM18 19.5C18.8284 19.5 19.5 18.8284 19.5 18C19.5 17.1716 18.8284 16.5 18 16.5C17.1716 16.5 16.5 17.1716 16.5 18C16.5 18.8284 17.1716 19.5 18 19.5Z"></path></svg>
          <h2>Administrador</h2>
        </a>
        ';
      }
      if (isset($_SESSION['rol']) && $_SESSION['rol'] != 'administracion') {
        // Mostrar el enlace solo si el usuario es admin
        echo '
        <a href="/Portfolio/index.php">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V9.48907C3 9.18048 3.14247 8.88917 3.38606 8.69972L11.3861 2.47749C11.7472 2.19663 12.2528 2.19663 12.6139 2.47749L20.6139 8.69972C20.8575 8.88917 21 9.18048 21 9.48907V20ZM19 19V9.97815L12 4.53371L5 9.97815V19H19Z"></path></svg>
          <h2>Inicio</h2>
        </a>   
        ';
      }
      ?>
      <a href="/Portfolio/project/pedidos/pedidos.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8 4H21V6H8V4ZM5 3V6H6V7H3V6H4V4H3V3H5ZM3 14V11.5H5V11H3V10H6V12.5H4V13H6V14H3ZM5 19.5H3V18.5H5V18H3V17H6V21H3V20H5V19.5ZM8 11H21V13H8V11ZM8 18H21V20H8V18Z"></path></svg>        
        <h2>Pedidos</h2>
      </a>

      <a class="anclasCS" href="/Portfolio/project/main/materiasCS.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 7.65311V16.3469L12 20.689L19.5 16.3469V7.65311L12 3.311L4.5 7.65311ZM12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM6.49896 9.97065L11 12.5765V17.625H13V12.5765L17.501 9.97066L16.499 8.2398L12 10.8445L7.50104 8.2398L6.49896 9.97065Z"></path></svg>        
        <h2>Materias primas</h2>
      </a>
      <a class="anclasTone" href="/Portfolio/project/main/materiasTO.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 7.65311V16.3469L12 20.689L19.5 16.3469V7.65311L12 3.311L4.5 7.65311ZM12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM6.49896 9.97065L11 12.5765V17.625H13V12.5765L17.501 9.97066L16.499 8.2398L12 10.8445L7.50104 8.2398L6.49896 9.97065Z"></path></svg>        
        <h2>Materias TO</h2>
      </a>
      <a class="anclasCS" href="/Portfolio/project/main/bobinasCS.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 7.65311V16.3469L12 20.689L19.5 16.3469V7.65311L12 3.311L4.5 7.65311ZM12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM6.49896 9.97065L11 12.5765V17.625H13V12.5765L17.501 9.97066L16.499 8.2398L12 10.8445L7.50104 8.2398L6.49896 9.97065Z"></path></svg>        
        <h2>Bobinas</h2>
      </a>
      <a class="anclasTone" href="/Portfolio/project/main/bobinasTO.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 7.65311V16.3469L12 20.689L19.5 16.3469V7.65311L12 3.311L4.5 7.65311ZM12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM6.49896 9.97065L11 12.5765V17.625H13V12.5765L17.501 9.97066L16.499 8.2398L12 10.8445L7.50104 8.2398L6.49896 9.97065Z"></path></svg>        
        <h2>Bobinas TO</h2>
      </a>
      <a class="anclasCS" href="/Portfolio/project/main/productosCS.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 7.65311V16.3469L12 20.689L19.5 16.3469V7.65311L12 3.311L4.5 7.65311ZM12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM6.49896 9.97065L11 12.5765V17.625H13V12.5765L17.501 9.97066L16.499 8.2398L12 10.8445L7.50104 8.2398L6.49896 9.97065Z"></path></svg>        
        <h2>Productos</h2>
      </a>
      <a class="anclasTone" href="/Portfolio/project/main/productosTO.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 7.65311V16.3469L12 20.689L19.5 16.3469V7.65311L12 3.311L4.5 7.65311ZM12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM6.49896 9.97065L11 12.5765V17.625H13V12.5765L17.501 9.97066L16.499 8.2398L12 10.8445L7.50104 8.2398L6.49896 9.97065Z"></path></svg>        
        <h2>Productos TO</h2>
      </a>
      <a href="/Portfolio/project/main/registros.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17 2V4H20.0066C20.5552 4 21 4.44495 21 4.9934V21.0066C21 21.5552 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5551 3 21.0066V4.9934C3 4.44476 3.44495 4 3.9934 4H7V2H17ZM7 6H5V20H19V6H17V8H7V6ZM9 16V18H7V16H9ZM9 13V15H7V13H9ZM9 10V12H7V10H9ZM15 4H9V6H15V4Z"></path></svg>        
        <h2>Registros</h2>
      </a>
    </div>

    <div class="procesos">
      <h2>Procesos</h2>
      <?php
      if (isset($_SESSION['rol']) && $_SESSION['rol'] != 'administracion') {
        // Mostrar el enlace solo si el usuario es admin
        echo '
          <a href="/Portfolio/project/procesos/entrada.php">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M6.99979 7V3C6.99979 2.44772 7.4475 2 7.99979 2H20.9998C21.5521 2 21.9998 2.44772 21.9998 3V16C21.9998 16.5523 21.5521 17 20.9998 17H17V20.9925C17 21.5489 16.551 22 15.9925 22H3.00728C2.45086 22 2 21.5511 2 20.9925L2.00276 8.00748C2.00288 7.45107 2.4518 7 3.01025 7H6.99979ZM8.99979 7H15.9927C16.549 7 17 7.44892 17 8.00748V15H19.9998V4H8.99979V7ZM15 9H4.00255L4.00021 20H15V9ZM8.50242 18L4.96689 14.4645L6.3811 13.0503L8.50242 15.1716L12.7451 10.9289L14.1593 12.3431L8.50242 18Z"></path></svg>        
            <h2>Entrada</h2>
          </a>
          <a href="/Portfolio/project/procesos/procesado.php">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 5V19H20V5H4ZM3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM6 7H9V17H6V7ZM10 7H12V17H10V7ZM13 7H14V17H13V7ZM15 7H18V17H15V7Z"></path></svg>        
            <h2>Procesado</h2>
          </a>
          <a href="/Portfolio/project/procesos/fabricacion.php">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.21232 14.0601C1.91928 12.6755 1.93115 11.2743 2.21316 9.94038C3.32308 10.0711 4.29187 9.7035 4.60865 8.93871C4.92544 8.17392 4.50032 7.22896 3.62307 6.53655C4.3669 5.3939 5.34931 4.39471 6.53554 3.62289C7.228 4.50059 8.17324 4.92601 8.93822 4.60914C9.7032 4.29227 10.0708 3.32308 9.93979 2.21281C11.3243 1.91977 12.7255 1.93164 14.0595 2.21364C13.9288 3.32356 14.2964 4.29235 15.0612 4.60914C15.8259 4.92593 16.7709 4.5008 17.4633 3.62356C18.606 4.36739 19.6052 5.3498 20.377 6.53602C19.4993 7.22849 19.0739 8.17373 19.3907 8.93871C19.7076 9.70369 20.6768 10.0713 21.7871 9.94028C22.0801 11.3248 22.0682 12.726 21.7862 14.06C20.6763 13.9293 19.7075 14.2969 19.3907 15.0616C19.0739 15.8264 19.4991 16.7714 20.3763 17.4638C19.6325 18.6064 18.6501 19.6056 17.4638 20.3775C16.7714 19.4998 15.8261 19.0743 15.0612 19.3912C14.2962 19.7081 13.9286 20.6773 14.0596 21.7875C12.675 22.0806 11.2738 22.0687 9.93989 21.7867C10.0706 20.6768 9.70301 19.708 8.93822 19.3912C8.17343 19.0744 7.22848 19.4995 6.53606 20.3768C5.39341 19.633 4.39422 18.6506 3.62241 17.4643C4.5001 16.7719 4.92552 15.8266 4.60865 15.0616C4.29179 14.2967 3.32259 13.9291 2.21232 14.0601ZM3.99975 12.2104C5.09956 12.5148 6.00718 13.2117 6.45641 14.2963C6.90564 15.3808 6.75667 16.5154 6.19421 17.5083C6.29077 17.61 6.38998 17.7092 6.49173 17.8056C7.4846 17.2432 8.61912 17.0943 9.70359 17.5435C10.7881 17.9927 11.485 18.9002 11.7894 19.9999C11.9295 20.0037 12.0697 20.0038 12.2099 20.0001C12.5143 18.9003 13.2112 17.9927 14.2958 17.5435C15.3803 17.0942 16.5149 17.2432 17.5078 17.8057C17.6096 17.7091 17.7087 17.6099 17.8051 17.5081C17.2427 16.5153 17.0938 15.3807 17.543 14.2963C17.9922 13.2118 18.8997 12.5149 19.9994 12.2105C20.0032 12.0704 20.0033 11.9301 19.9996 11.7899C18.8998 11.4856 17.9922 10.7886 17.543 9.70407C17.0937 8.61953 17.2427 7.48494 17.8052 6.49204C17.7086 6.39031 17.6094 6.2912 17.5076 6.19479C16.5148 6.75717 15.3803 6.9061 14.2958 6.4569C13.2113 6.0077 12.5144 5.10016 12.21 4.00044C12.0699 3.99666 11.9297 3.99659 11.7894 4.00024C11.4851 5.10005 10.7881 6.00767 9.70359 6.4569C8.61904 6.90613 7.48446 6.75715 6.49155 6.1947C6.38982 6.29126 6.29071 6.39047 6.19431 6.49222C6.75668 7.48509 6.90561 8.61961 6.45641 9.70407C6.00721 10.7885 5.09967 11.4855 3.99995 11.7899C3.99617 11.93 3.9961 12.0702 3.99975 12.2104ZM11.9997 15.0002C10.3428 15.0002 8.99969 13.657 8.99969 12.0002C8.99969 10.3433 10.3428 9.00018 11.9997 9.00018C13.6565 9.00018 14.9997 10.3433 14.9997 12.0002C14.9997 13.657 13.6565 15.0002 11.9997 15.0002ZM11.9997 13.0002C12.552 13.0002 12.9997 12.5525 12.9997 12.0002C12.9997 11.4479 12.552 11.0002 11.9997 11.0002C11.4474 11.0002 10.9997 11.4479 10.9997 12.0002C10.9997 12.5525 11.4474 13.0002 11.9997 13.0002Z"></path></svg>
            <h2>Fabricación</h2>
          </a>
        ';
      }
      if (isset($_SESSION['rol']) && $_SESSION['rol'] != 'tecnico') {
        // Mostrar el enlace solo si el usuario es admin
        echo '
          <a href="/Portfolio/project/add-elements/add-pedido.php">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 1V4H1V6H4V9H6V6H9V4H6V1H4ZM3 20.0066V11H5V19H13V14C13 13.45 13.45 13 14 13L19 12.999V5H11V3H20.0066C20.5552 3 21 3.45576 21 4.00247V15L15 20.996L4.00221 21C3.4487 21 3 20.5551 3 20.0066ZM18.171 14.999L15 15V18.169L18.171 14.999Z"></path></svg>
            <h2>Nuevo pedido</h2>
          </a>
        ';
      }
      if (isset($_SESSION['rol']) && $_SESSION['rol'] != 'administracion') {
        // Mostrar el enlace solo si el usuario es admin
        echo '
         <a  href="/Portfolio/project/procesos/salida.php">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8.96456 18C8.72194 19.6961 7.26324 21 5.5 21C3.73676 21 2.27806 19.6961 2.03544 18H1V6C1 5.44772 1.44772 5 2 5H16C16.5523 5 17 5.44772 17 6V8H20L23 12.0557V18H20.9646C20.7219 19.6961 19.2632 21 17.5 21C15.7368 21 14.2781 19.6961 14.0354 18H8.96456ZM15 7H3V15.0505C3.63526 14.4022 4.52066 14 5.5 14C6.8962 14 8.10145 14.8175 8.66318 16H14.3368C14.5045 15.647 14.7296 15.3264 15 15.0505V7ZM17 13H21V12.715L18.9917 10H17V13ZM17.5 19C18.1531 19 18.7087 18.5826 18.9146 18C18.9699 17.8436 19 17.6753 19 17.5C19 16.6716 18.3284 16 17.5 16C16.6716 16 16 16.6716 16 17.5C16 17.6753 16.0301 17.8436 16.0854 18C16.2913 18.5826 16.8469 19 17.5 19ZM7 17.5C7 16.6716 6.32843 16 5.5 16C4.67157 16 4 16.6716 4 17.5C4 17.6753 4.03008 17.8436 4.08535 18C4.29127 18.5826 4.84689 19 5.5 19C6.15311 19 6.70873 18.5826 6.91465 18C6.96992 17.8436 7 17.6753 7 17.5Z"></path></svg>        
          <h2>Salida</h2>
        </a>
        ';
      }
      ?>
    </div>
    <div class="user">
      <img src="<?php echo htmlspecialchars($imagenUsuario); ?>">
      <div class="userInfo">
        <h3><?php echo htmlspecialchars($usuario); ?></h3>
        <a href="/Portfolio/logout.php"><button>Cerrar sesión</button></a>
      </div>
    </div>
  </div>

  <div class="overview">
  <div id="menuIcon" class="iconoMenu">
                <span class="barra1"></span>
                <span class="barra2"></span>
                <span class="barra3"></span>
    </div>
    <div class="overviewDiv">
      <h2>Vista general</h2>
      <div class="csTo">
        <h3>CS</h3>
        <span id="selectorCSTO">
          <div class="creaSustratos" id="botonCSTO"></div>
        </span>
        <h3>TO</h3>
      </div>
      <!-- <input type="search" placeholder="Buscar..."> -->
    </div>
    <div class="timeLogo">
      <div class="interiorTimeLogo">
        <div>
          <a href="/Portfolio/index.php"></a>
          
        </div>
        <p>
          <?php
          echo date("d-m-Y H:i:s");
          ?>
      </p>
      </div>
    </div>
  </div>
  </header>
  <div style="display:none;opacity:0" class="confirmChangePrices">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" class="checkmark" width="100" height="100">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                    <path class="checkmark-check" fill="none" d="M14 27 L20 33 L38 15" />
                </svg>
                <h2>Actualizado correctamente</h2>
  </div>

<!-- MAIN -->
  
  <div class="main">
 
