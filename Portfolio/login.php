<?php
//INFO Iniciar la sesión
session_start();

//INFO Límite de intentos fallidos y tiempo de bloqueo en segundos
$max_attempts = 5;
$block_time = 900; //PD 15 minutos (900 segundos)

//INFO Obtener la dirección IP del usuario
$ip_address = $_SERVER['REMOTE_ADDR'];

//INFO Asegurarse de que los arrays de intentos de login y direcciones IP bloqueadas estén inicializados como arrays
if (!isset($_SESSION['login_attempts']) || !is_array($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = []; //PD Inicializar como array si no lo es
}

if (!isset($_SESSION['blocked_ips']) || !is_array($_SESSION['blocked_ips'])) {
    $_SESSION['blocked_ips'] = []; //PD Inicializar como array si no lo es
}

//INFO Inicializar la variable de mensaje de error
$mensaje_error = '';

//INFO Verificar si la IP está bloqueada y el tiempo de bloqueo no ha expirado
if (isset($_SESSION['blocked_ips'][$ip_address]) && time() < $_SESSION['blocked_ips'][$ip_address]['blocked_until']) {
    $tiempo_restante = ($_SESSION['blocked_ips'][$ip_address]['blocked_until'] - time()) / 60;
    $mensaje_error = "Has sido bloqueado. Intenta nuevamente en " . ceil($tiempo_restante) . " minutos.";
} else {
    //INFO Resetear el bloqueo si el tiempo ha pasado
    if (isset($_SESSION['blocked_ips'][$ip_address]) && time() >= $_SESSION['blocked_ips'][$ip_address]['blocked_until']) {
        unset($_SESSION['blocked_ips'][$ip_address]);
        unset($_SESSION['login_attempts'][$ip_address]); //PD También reiniciar los intentos de login
    }

    //INFO Verificar si el formulario fue enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //INFO Verificar si el token CSRF está definido y es válido
        if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $mensaje_error = 'Token CSRF inválido.';
        } else {
            //INFO Destruir el token CSRF tras el uso solo si es válido
            unset($_SESSION['csrf_token']);

            include_once($_SERVER['DOCUMENT_ROOT'] . '/Portfolio/server/conexion.php');

            //INFO Limpiar los datos del usuario
            $usuario = htmlspecialchars(trim($_POST["usuario"]));
            $pass = $_POST["palabra_secreta"];

            //INFO Preparar y ejecutar la consulta para obtener el hash de la contraseña
            $stmt = $enlace->prepare("SELECT id, contrasena, rol, imagen, email FROM tecnico WHERE usuario = ?");
            if (!$stmt) {
                die("Error en la preparación de la consulta: " . $enlace->error);
            }
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $registro = $resultado->fetch_assoc();
            $stmt->close();

            if ($registro) {
                //INFO Verificar la contraseña con el hash almacenado
                if (password_verify($pass, $registro["contrasena"])) {
                    //INFO Verificar si la IP está bloqueada, en cuyo caso ignorar el acceso
                    if (isset($_SESSION['blocked_ips'][$ip_address]) && time() < $_SESSION['blocked_ips'][$ip_address]['blocked_until']) {
                        $tiempo_restante = ($_SESSION['blocked_ips'][$ip_address]['blocked_until'] - time()) / 60;
                        $mensaje_error = "Estás bloqueado. Intenta nuevamente en " . ceil($tiempo_restante) . " minutos.";
                    } else {
                        //INFO Resetear el contador de intentos fallidos para esta IP
                        unset($_SESSION['login_attempts'][$ip_address]);

                        session_regenerate_id(true); //PD Regenera el ID de la sesión para mayor seguridad

                        //INFO Almacenar el user, el ID y el permission en la sesión
                        $_SESSION["usuario"] = $usuario;
                        $_SESSION["rol"] = $registro["rol"];
                        $_SESSION["imagen"] = $registro["imagen"];
                        
                         // Registrar el inicio de sesión en la tabla `registro`
                        $current_date_time = date('Y-m-d H:i:s');
                        $descripcion = "Inicio de sesión: ".$usuario."";
                        $stmt_registro = $enlace->prepare("INSERT INTO registro (fecha, tecnico, descripcion) VALUES (?, ?, ?)");
                        if ($stmt_registro) {
                            $stmt_registro->bind_param("sss", $current_date_time, $usuario, $descripcion);
                            $stmt_registro->execute();
                            $stmt_registro->close();
                        } else {
                            die("Error en la preparación de la consulta de registro: " . $enlace->error);
                        }

                        //INFO Redirigir según el permiso del user
                        header("Location: /Portfolio/index.php");
                        exit();
                    }
                } else {
                    //INFO Manejo de intentos fallidos de login
                    if (!isset($_SESSION['login_attempts'][$ip_address])) {
                        $_SESSION['login_attempts'][$ip_address] = 0;
                    }

                    $_SESSION['login_attempts'][$ip_address]++;
                    $intentos_restantes = $max_attempts - $_SESSION['login_attempts'][$ip_address];

                    if ($_SESSION['login_attempts'][$ip_address] >= $max_attempts) {
                        //INFO Bloquear la IP por 15 minutos
                        $_SESSION['blocked_ips'][$ip_address] = [
                            'blocked_until' => time() + $block_time
                        ];
                        $mensaje_error = "Demasiados intentos fallidos. Te bloqueado por 15 minutos.";
                    } else {
                        $mensaje_error = "Contraseña Incorrecta. Te quedan $intentos_restantes intentos.";
                    }
                }
            } else {
                $mensaje_error = "Usuario no encontrado.";
            }
        }
    }
}

//INFO Generar un nuevo token CSRF después de cada intento (fallido o exitoso)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); //PD Token CSRF seguro
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Crea T-one</title>
    <link rel="stylesheet" href="fonts/fonts.css">
    <link rel="stylesheet" href="styles/login.css">
    <link rel="shortcut icon" href="img/menu-items/favicongood.png" type="image/x-icon">
    <link rel="icon" href="img/menu-items/favicongood.png" type="image/x-icon">
</head>
<body>
<main>

<div class="loginForm">
    <div class="loginHeader">
        <img src="img/menu-items/favicongood.png">
        <h2>Prueba</h2>
    </div>

    <div class="loginData">
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="usuario">Usuario</label>
            <input type="text" placeholder="Introduce tu usuario" name="usuario">
            <label for="palabra_secreta">Contraseña</label>
            <input id="pass" type="password" placeholder="Introduce tu contraseña" name="palabra_secreta">
            <svg onclick="mostrarPassword()" id="ojo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M1.18164 12C2.12215 6.87976 6.60812 3 12.0003 3C17.3924 3 21.8784 6.87976 22.8189 12C21.8784 17.1202 17.3924 21 12.0003 21C6.60812 21 2.12215 17.1202 1.18164 12ZM12.0003 17C14.7617 17 17.0003 14.7614 17.0003 12C17.0003 9.23858 14.7617 7 12.0003 7C9.23884 7 7.00026 9.23858 7.00026 12C7.00026 14.7614 9.23884 17 12.0003 17ZM12.0003 15C10.3434 15 9.00026 13.6569 9.00026 12C9.00026 10.3431 10.3434 9 12.0003 9C13.6571 9 15.0003 10.3431 15.0003 12C15.0003 13.6569 13.6571 15 12.0003 15Z"></path></svg>
            <button type="submit">INICIAR SESIÓN</button>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <?php if (!empty($mensaje_error)): ?>
                        <p class="errorLogin"><?php echo $mensaje_error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>



</main>
</body>
<script>
   const ojo= document.querySelector('#ojo')
const pass= document.querySelector('#pass')

function mostrarPassword(){
  if(pass.type=='password'){
    pass.type='text'
    ojo.innerHTML='<path d="M4.52047 5.93457L1.39366 2.80777L2.80788 1.39355L22.6069 21.1925L21.1927 22.6068L17.8827 19.2968C16.1814 20.3755 14.1638 21.0002 12.0003 21.0002C6.60812 21.0002 2.12215 17.1204 1.18164 12.0002C1.61832 9.62282 2.81932 7.5129 4.52047 5.93457ZM14.7577 16.1718L13.2937 14.7078C12.902 14.8952 12.4634 15.0002 12.0003 15.0002C10.3434 15.0002 9.00026 13.657 9.00026 12.0002C9.00026 11.537 9.10522 11.0984 9.29263 10.7067L7.82866 9.24277C7.30514 10.0332 7.00026 10.9811 7.00026 12.0002C7.00026 14.7616 9.23884 17.0002 12.0003 17.0002C13.0193 17.0002 13.9672 16.6953 14.7577 16.1718ZM7.97446 3.76015C9.22127 3.26959 10.5793 3.00016 12.0003 3.00016C17.3924 3.00016 21.8784 6.87992 22.8189 12.0002C22.5067 13.6998 21.8038 15.2628 20.8068 16.5925L16.947 12.7327C16.9821 12.4936 17.0003 12.249 17.0003 12.0002C17.0003 9.23873 14.7617 7.00016 12.0003 7.00016C11.7514 7.00016 11.5068 7.01833 11.2677 7.05343L7.97446 3.76015Z"></path>'
    pass.focus()
  } else {
    pass.type='password'
    ojo.innerHTML='<path d="M1.18164 12C2.12215 6.87976 6.60812 3 12.0003 3C17.3924 3 21.8784 6.87976 22.8189 12C21.8784 17.1202 17.3924 21 12.0003 21C6.60812 21 2.12215 17.1202 1.18164 12ZM12.0003 17C14.7617 17 17.0003 14.7614 17.0003 12C17.0003 9.23858 14.7617 7 12.0003 7C9.23884 7 7.00026 9.23858 7.00026 12C7.00026 14.7614 9.23884 17 12.0003 17ZM12.0003 15C10.3434 15 9.00026 13.6569 9.00026 12C9.00026 10.3431 10.3434 9 12.0003 9C13.6571 9 15.0003 10.3431 15.0003 12C15.0003 13.6569 13.6571 15 12.0003 15Z"></path>'
    pass.focus()
}
}

</script>
</html>
