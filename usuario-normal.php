<?php
    // Comenzamos la sesion
    session_start();

    // Fichero para la configuracion de la base de datos
    require_once 'configuracion.php';

    // Verificamos que el usuario se hay logueado
    if(!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'USUARIO') {
        header("Location: login.php");
    }

    $consulta = "
        SELECT COUNT(*) as TOTAL_ACEPTADOS
        FROM SOLICITA S
        WHERE S.CORREO_U = '{$_SESSION['correo']}' AND S.ESTADO = 'ACEPTADO'
    ";

    $resultado = mysqli_query($conexion, $consulta);
    if($resultado && mysqli_num_rows($resultado) > 0) {
        $resultado = mysqli_fetch_assoc($resultado);
        $total_aceptados = $resultado['TOTAL_ACEPTADOS'];
    }

    $consulta_pendientes = "
        SELECT COUNT(*) as TOTAL_PENDIENTES
        FROM SOLICITA S
        WHERE S.CORREO_U = '{$_SESSION['correo']}' AND S.ESTADO = 'PENDIENTE'
    ";

    $resultado_pendientes = mysqli_query($conexion, $consulta_pendientes);
    if($resultado_pendientes && mysqli_num_rows($resultado_pendientes) > 0) {
        $resultado_pendientes = mysqli_fetch_assoc($resultado_pendientes);
        $total_pendientes = $resultado_pendientes['TOTAL_PENDIENTES'];
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Usuario Normal - DevSwap.</title>
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
                <a href="publicar.php">Publicar</a>
                <a href="buscar.php">Buscar</a>
                <a href="intercambios.php">Mis Intercambios</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>

            <div class="banner-principal">
                <h2>Panel de Usuario</h2>
                <!-- Mostramos el nick del usuario que se ha logueado en la sesion -->
                <h2>👋Hola, <?php echo htmlspecialchars($_SESSION['nick']); ?></h2>
                <p>Busca y crea publicaciones!!!!!!</p>
            </div>
        </header>

        <main>
            <div>
                <div class="tarjeta">
                    <h3>Buscar Objetos</h3>
                    <p>Encuentra los objetos disponibles para intercambiar.</p>
                    <a href="buscar.php">Buscar</a>
                </div>
                <div class="tarjeta">
                    <h3>Crear Publicaciones</h3>
                    <p>Crea tus publicaciones y empieza a intercambiar objetos.</p>
                    <a href="publicar.php">Publicar</a>
                </div>
                <div class="tarjeta">
                    <h3>Mis Intercambios</h3>
                    <p>Visualiza tus intercambios que estan en activo.</p>
                    <a href="intercambios.php">Mis Intercambios</a>
                </div>
            </div>

            <div>
                <h3>Mi Resumen de Actividad</h3>
                <div class="tarjeta">
                    <h3>Intercambios Realizados:</h3>
                    <h4><?php echo htmlspecialchars($total_aceptados); ?></h4>
                </div>
                <div class="tarjeta">
                    <h3>Solicitudes Pendientes:</h3>
                    <h4><?php echo htmlspecialchars($total_pendientes); ?></h4>
                </div>
            </div>
        </main>

        <footer>
            <strong>DevSwap</strong><br>
            <hr>
            <strong>Plataforma de intercambios entre desarrolladores</strong><br>
            <hr>
            <strong>DevSwap - 2026</strong>
        </footer>
    </body>
</html>