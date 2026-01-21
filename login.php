<?php
    // Empezamos la sesion
    session_start();

    // Incluimos el fichero que configura la base de datos
    require_once 'configuracion.php';

    $error = '';

    // Se procesa el formulario cuando se envia
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $correo = $_POST['correo'] ?? '';
        $contrasenia = $_POST['contrasenia'] ?? '';

        // Si no se ha enviado nada
        if(empty($correo) || empty($contrasenia)) {
            $error = 'Debes introducir o un correo o una contraseña';

        } else {
            if($conexion) {
                // Prevenimos sql injections
                $correo = mysqli_real_escape_string($conexion, $correo);
                $contrasenia = mysqli_real_escape_string($conexion, $contrasenia);

                // Variables para ver si se han encontrado el usuario
                $usuario_encontrado = false;
                $datos_usuario = null;

                // Buscar en administrador
                $consulta = "
                    SELECT A.CORREO_A, A.NICK_A, A.CONTRASEÑA_A, 'ADMINISTRADOR' AS TIPO
                    FROM ADMINISTRADOR A
                    WHERE A.CORREO_A = '$correo'
                ";

                $resultado = mysqli_query($conexion, $consulta);

                // Compruebas si la consulta da alguna fila como resultado
                if($resultado && mysqli_num_rows($resultado) > 0) {
                    $datos_usuario = mysqli_fetch_assoc($resultado);
                    $usuario_encontrado = true;
                }

                // Si no se encontro un administrador se busca dentro de los usuarios normales
                if(!$usuario_encontrado) {
                    $consulta = "
                        SELECT U.CORREO_U AS CORREO_A, U.NICK_U AS NICK_A, U.CONTRASEÑA_U AS CONTRASEÑA_A, 'USUARIO' AS TIPO
                        FROM USUARIO U
                        WHERE U.CORREO_U = '$correo'
                    ";

                    $resultado = mysqli_query($conexion, $consulta);
                    if($resultado && mysqli_num_rows($resultado) > 0) {
                        $datos_usuario = mysqli_fetch_assoc($resultado);
                        $usuario_encontrado = true;
                    }
                }

                // Verificamos que la contraseña exista
                if($usuario_encontrado && $datos_usuario['CONTRASEÑA_A'] === $contrasenia) {
                    $_SESSION['nick'] = $datos_usuario['NICK_A'];
                    $_SESSION['correo'] = $datos_usuario['CORREO_A'];
                    $_SESSION['contrasenia'] = $datos_usuario['CONTRASEÑA_A'];
                    $_SESSION['tipo'] = $datos_usuario['TIPO'];

                    // Se redirige segun el tipo de usuario que sea
                    switch($datos_usuario['TIPO']) {
                        case 'USUARIO':
                            header('Location: usuario-normal.html');
                            exit();
                        
                        case 'ADMINISTRADOR':
                            header('Location: admin.html');
                            exit();
                    }

                } else {
                    $error = 'Contraseña incorrecta.';
                }

                if($resultado) {
                    mysqli_free_result($resultado);
                }

            } else {
                $error = 'Conexion fallida';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Login - DevSwap.</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <meta name="description" content="Plataforma para el intercambio entre desarrolladores.">
        <meta name="author" content="Hugo García San Pedro">
        <link rel="stylesheet" href="estilo/style.css">
    </head>
    <body>
        <header>
            <div class="barra-busqueda">
                <h1>DevSwap</h1>
                <a href="index.html">Inicio</a>
                <a href="index.html#Como-Funciona">Como Funciona</a>
                <a href="index.html#Ofertas">Ofertas</a>
                <a href="registro.html">Registrarse</a>
            </div>
        </header>

        <main>
            <div class="registro">
                <?php if($error): ?>
                    <div class="mensaje-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <h2>Iniciar Sesión</h2>

                <p>Introduce tus credenciales:</p>
                <form method="POST" action="login.php">
                    <label for="correo">Intrduce tu correo electronico:</label><br>
                    <input type="email" id="correo" name="correo" placeholder="email@"><br>
                    <label for="contrasenia">Introduce tu contraseña:</label><br>
                    <input type="password" id="contrasenia" name="contrasenia" placeholder="***********"><br>
                    <input type="submit" value="Iniciar Sesión">
                </form>

                <p>¿No tienes cuenta?</p>
                <a href="registro.html">Registrate Aqui</a>
            </div>
        </main>

        <footer>
            <strong>DevSwap</strong><br>
            <hr>
            <strong>Plataforma de intercambios entre desarrolladores</strong><br>
            <hr>
            <strong>2026 - DevSwap</strong>
        </footer>
    </body>
</html>