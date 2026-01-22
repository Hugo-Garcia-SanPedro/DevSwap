<?php
    // Comenzamos la sesion
    session_start();

    // Incluimos el fichero de la configuracion de la base de datos
    require_once 'configuracion.php';

    // Vemos que el administrador se haya logueado
    if(!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'ADMINISTRADOR') {
        header('Location: login.php');
    }

    $error = '';
    $exito = '';

    // Comprobamos que se haya enviado un POST, para crear usuario
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $correo_u = $_POST['Correo'] ?? '';
        $nick_u = $_POST['Nick'] ?? '';
        $apellido1 = $_POST['Apellido1'] ?? '';
        $apellido2 = $_POST['Apellido2'] ?? '';
        $nombre_u = $_POST['Nombre'] ?? '';
        $telefono = $_POST['Telefono'] ?? '';
        $ciudad = $_POST['Ciudad'] ?? '';
        $contrasenia_u = $_POST['Contrasenia'] ?? '';

        // Comprobamos que todos los campos se hayan completado
        if(empty($correo_u) || empty($nick_u) || empty($apellido1) || empty($apellido2) || empty($nombre_u) || empty($telefono) || empty($ciudad) ||empty($contrasenia_u)) {
            $error = 'Se ha dejado algun campo sin completar';

        } else {
            // Creamos la consulta con los campos del post
            $consulta_crear_usuario = "
                INSERT INTO USUARIO(CORREO_U, NICK_U, APELLIDO1, APELLIDO2, NOMBRE_U, TELEFONO, CIUDAD, CONTRASEÑA_U)
                VALUES
                ('$correo_u', '$nick_u', '$apellido1', '$apellido2', '$nombre_u', '$telefono', '$ciudad', '$contrasenia_u')
            ";

            $resultado_crear_usuario = mysqli_query($conexion, $consulta_crear_usuario);

            if($resultado_crear_usuario) {
                $exito = 'El usuario se creo satisfactoriamente.';

            } else {
                $error = 'Error al crear el usuario: ' . mysqli_error($conexion);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Administrador - DevSwap.</title>
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
                <a href="#Usuarios">Gestión de Usuarios</a>
                <a href="#Moderacion">Moderación de Ofertas</a>
                <a href="#Categorias">Gestión de Categorias</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>

            <div class="banner-principal">
                <h2>Panel de Administración</h2>
                <h2>👋Hola, <?php echo htmlspecialchars($_SESSION['nick']); ?></h2>
                <p>Gestiona todos los aspectos de la plataforma, DevSwap</p>
            </div>
        </header>
    
        <main>
            <?php if($exito != ''): ?>
                <h1> <?php echo htmlspecialchars($exito); ?></h1>
            <?php endif; ?>
            <?php if($error != ''): ?>
                <h1> <?php echo htmlspecialchars($error); ?></h1>
            <?php endif; ?>

            <!-- Creacion de usuarios -->
            <div class="impar">
                <div class="registro">
                    <h3 id="Usuarios">Gestión de Usuarios</h3>
                    <form method="POST" action="admin.php">
                        <label for="Nombre">Nombre:</label>
                        <input type="text" id="Nombre" name="Nombre" placeholder="Nombre de Usuario">
                        <label for="Nick">Nick:</label>
                        <input type="text" id="Nick" name="Nick" placeholder="Nombre de Usuario">
                        <label for="Apellido1">Primer Apellido:</label>
                        <input type="text" id="Apellido1" name="Apellido1" placeholder="Primer Apellido">
                        <label for="Apellido2">Segundo Apellido:</label>
                        <input type="text" id="Apellido2" name="Apellido2" placeholder="Segundo Apellido">
                        <label for="Correo">Correo Electronico:</label>
                        <input type="email" id="Correo" name="Correo" placeholder="email@">
                        <label for="Telefono">Telefono:</label>
                        <input type="text" id="Telefono" name="Telefono" placeholder="Telefono">
                        <label for="Ciudad">Ciudad:</label>
                        <input type="text" id="Ciudad" name="Ciudad" placeholder="Ciudad">
                        <label for="Contrasenia">Contraseña:</label>
                        <input type="password" id="Contrasenia" name="Contrasenia" placeholder="**********">
                        <input type="submit" value="Crear Cuenta">
                    </form>
                </div>
            </div>

            <!-- Usuarios que hay -->
            <div class="par">
                <h3>Gestión de Usuarios:</h3>
                <div class="tarjeta">
                    <h3>Laura@gmail.com - Laura Gonzalez</h3>
                    <p>Numero de Publicaciones: 5</p>
                    <p>⭐Valoración: 4.5</p>
                    <a href="#Top">Eliminar</a>
                </div>        
                <div class="tarjeta">
                    <h3>Pedro@hotmail.com - Pedro Perez</h3>
                    <p>Numero de Publicaciones: 4</p>
                    <p>⭐Valoración: 2.4</p>
                    <a href="#Top">Eliminar</a>
                </div>
                <div class="tarjeta">
                    <h3>Sara Garcia - Saragar@gmail.com</h3>
                    <p>Numero de Publicaciones: 12</p>
                    <p>⭐Valoración: 5</p>
                    <a href="#Top">Eliminar</a>
                </div>
                <a href="#Top">Ver Mas Usuarios</a>
            </div>

            <!-- Zona para crear categorias -->
            <div class="impar">
                <div class="registro">
                    <h3 id="Categorias">Gestión de las Categorias</h3>
                    <form method="POST" action="admin.php">
                        <label for="nombre_c">Nombre de la Categoría:</label>
                        <input type="text" id="nombre_c" name="nombre_c" placeholder="Nombre">
                        <label for="emoji">Emoji de la Categoría:</label>
                        <input type="text" id="emoji" name="emoji" maxlength="2" placeholder="💻">
                        <label for="descripcion">Descripción Breve:</label>
                        <input type="text" id="descripcion" name="descripcion" placeholder="Descripción">
                        <input type="submit" value="Crear Categoría">
                    </form>
                </div>
            </div>

            <!-- Categorias creadas -->
            <div class="par">
                <h3>Gestión de Categorias</h3>
                <div class="Oferta">
                    <img src="imagenes/Emoji/emoji_videojuego.png" alt="Emoji de un mando de videojuegos.">
                    <h4>Categoria de Videojuegos.</h4>
                    <p>Descripción: Categoria relacionada con los videojuegos.</p>
                    <a>Eliminar Categoria</a>
                </div>
                <div class="Oferta">
                    <img src="imagenes/Emoji/emoji_telefono.png" alt="Emoji de un telefono.">
                    <h4>Categorias de Smartphones.</h4>
                    <p>Descripción: Categoria para subir ofertas de smartphones.</p>
                    <a>Eliminar Categoria</a>
                </div>
                <div class="Oferta">
                    <img src="imagenes/Emoji/emoji_libros.png" alt="Emoji de un libro.">
                    <h4>Categorias de Libros.</h4>
                    <p>Descripción: Categoria para subir libros relacionados con la tecnología.</p>
                    <a>Eliminar Categoria</a>
                </div>
                <a href="#Top">Ver Mas Categorias</a>
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