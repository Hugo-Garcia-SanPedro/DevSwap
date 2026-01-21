<?php
    // Empezamos la sesion
    session_start();

    // Implementamos el archivo de configuracion de la bas de datos
    require_once 'configuracion.php';

    $error = '';
    $exito = '';

    // Comprobamos que los parametros del formulario se hayan pasado de forma correcta
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $nick = $_POST['nick'] ?? '';
        $apellido1 = $_POST['apellido1'] ?? '';
        $apellido2 = $_POST['apellido2'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $contrasenia = $_POST['contrasenia'] ?? '';

        // Vemos que se hayan enviado todos los campos
        if(empty($nombre) || empty($nick) || empty($apellido1) || empty($apellido2) || empty($correo) || empty($telefono) || empty($ciudad) || empty($contrasenia)) {
            $error = 'Algun campo no se ha completado.';

        } else {
            // Comprobamos que se haya establecido la conexion con la base de datos
            if($conexion) {
                // Evitamos SQL injections
                $nombre = mysqli_real_escape_string($conexion, $nombre);
                $nick = mysqli_real_escape_string($conexion, $nick);
                $apellido1 = mysqli_real_escape_string($conexion, $apellido1);
                $apellido2 = mysqli_real_escape_string($conexion, $apellido2);
                $correo = mysqli_real_escape_string($conexion, $correo);
                $telefono = mysqli_real_escape_string($conexion, $telefono);
                $ciudad = mysqli_real_escape_string($conexion, $ciudad);
                $contrasenia = mysqli_real_escape_string($conexion, $contrasenia);
                $contrasenia2 = $_POST['contrasenia2'] ?? '';

                // Validacion de contraseñas
                if($contrasenia !== $contrasenia2) {
                    $error = 'Las contraseñas no coinciden.';
                }

                $consulta = "
                    INSERT INTO USUARIO(CORREO_U, NICK_U, APELLIDO1, APELLIDO2, NOMBRE_U, TELEFONO, CIUDAD, CONTRASEÑA_U)
                    VALUES
                    ('$correo', '$nick', '$apellido1', '$apellido2', '$nombre', '$telefono', '$ciudad', '$contrasenia')
                ";

                $resultado = mysqli_query($conexion, $consulta);

                if($resultado) {
                    $exito = 'El usuario se creo satisfactoriamente.';

                } else {
                    $error = 'Error al crear el usuario: ' . mysqli_error($conexion);
                }

            } else {
                $error = 'Conexion fallida con la base de datos.';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Registro - DevSwap.</title>
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
                <a href="index.php">Inicio</a>
                <a href="index.php#Como-Funciona">Como Funciona</a>
                <a href="index.php#Ofertas">Ofertas</a>
                <a href="login.php">Iniciar Sesión</a>
            </div>
        </header>

        <main>
            <div class="registro">
                <?php if($exito): ?>
                    <div class="mensaje-exito">
                        <?php echo htmlspecialchars($exito); ?>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="mensaje-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <h2>Crear Cuenta</h2>
                <p>Unete a la comunidad de intercambio entre desarrolladores!!!!!!</p>
                
                <form method="POST" action="registro.php">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre de Usuario">
                    <label for="nick">Nick:</label>
                    <input type="text" id="nick" name="nick" placeholder="Introduce el Nick de tu Usuario">
                    <label for="apellido1">Primer Apellido:</label>
                    <input type="text" id="apellido1" name="apellido1" placeholder="Primer Apellido">
                    <label for="apellido2">Segundo Apellido:</label>
                    <input type="text" id="apellido2" name="apellido2" placeholder="Segundo Apellido">
                    <label for="correo">Correo Electronico:</label>
                    <input type="email" id="correo" name="correo" placeholder="email@">
                    <label for="telefono">Telefono:</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Telefono">
                    <label for="ciudad">Ciudad:</label>
                    <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad">
                    <label for="contrasenia">Contraseña:</label>
                    <input type="password" id="contrasenia" name="contrasenia" placeholder="**********">
                    <label for="contrasenia2">Repetir Contraseña:</label>
                    <input type="password" id="contrasenia2" name="contrasenia2" placeholder="**********">
                    <input type="submit" value="Crear Cuenta">
                </form>

                <p>¿Ya tienes una cuenta?</p>
                <a href="login.php">Iniciar Sesión</a>
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