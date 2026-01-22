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
        if(isset($_POST['gestion-usuarios'])) {
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
    }

    // Comprobamos que se haya usado el metodo POST, en el segundo formulario
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['gestion-categorias'])) {
            $nombre_c = $_POST['nombre_c'] ?? '';
            $emoji = $_POST['emoji'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';

            // Comprobamos que los campos esten completos
            if(empty($nombre_c) || empty($emoji) || empty($descripcion)) {
                $error = 'Error al completar los campos.';

            } else {
                // Creamos la consulta SQL, para insertar los valores
                $consulta_crear_categoria = "
                    INSERT INTO CATEGORIA(NOMBRE_C, DESCRIPCION_C, IMAGEN_C)
                    VALUES
                    ('$nombre_c', '$descripcion', '$emoji')
                ";

                // Obtenemos el resultado de la consulta
                $resultado_crear_categoria = mysqli_query($conexion, $consulta_crear_categoria);
                if($resultado_crear_categoria) {
                    $exito = 'La categoria se creo satisfactoriamente.';

                } else {
                    $error = 'Error al crear la categoria. ' . mysqli_error($conexion);
                }
            }
        }
    }

    // Presentar usuarios
    $usuarios = [];
    $obtener_usuarios = "
        SELECT U.CORREO_U, U.NICK_U, U.NOMBRE_U
        FROM USUARIO U
        ORDER BY RAND()
        LIMIT 3
    ";

    $resultado_obtener_usuarios = mysqli_query($conexion, $obtener_usuarios);
    if($resultado_obtener_usuarios && mysqli_num_rows($resultado_obtener_usuarios) > 0) {
        while($fila = mysqli_fetch_assoc($resultado_obtener_usuarios)) {
            $usuarios[] = $fila;
        }
    }

    // Presentar categorias
    $categorias = [];
    $obtener_categorias = "
        SELECT C.NOMBRE_C, C.DESCRIPCION_C, C.IMAGEN_C
        FROM CATEGORIA C
        ORDER BY RAND()
        LIMIT 3
    ";

    $resultado_obtener_categorias = mysqli_query($conexion, $obtener_categorias);
    if($resultado_obtener_categorias && mysqli_num_rows($resultado_obtener_categorias) > 0) {
        while($fila_c = mysqli_fetch_assoc($resultado_obtener_categorias)) {
            $categorias[] = $fila_c;
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
                        <input type="hidden" name="gestion-usuarios">
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

                <!-- Bucle para mostrar los usuarios -->
                <?php foreach($usuarios as $usuario): ?>
                    <div class="tarjeta">
                        <h3><?php echo htmlspecialchars($usuario['CORREO_U']); ?></h3>
                        <h3>Nombre: <?php echo htmlspecialchars($usuario['NOMBRE_U']); ?></h3>
                        <p>Nick: <?php echo htmlspecialchars($usuario['NICK_U']); ?></p>
                        <a href="#Top">Eliminar</a>
                    </div>  
                <?php endforeach; ?>
                <a href="#Top">Ver Mas Usuarios</a>
            </div>

            <!-- Zona para crear categorias -->
            <div class="impar">
                <div class="registro">
                    <h3 id="Categorias">Gestión de las Categorias</h3>
                    <form method="POST" action="admin.php">
                        <input type="hidden" name="gestion-categorias">
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

                <!-- Bucle para crear las categorias -->
                <?php foreach($categorias as $categoria): ?>
                    <div class="Oferta">
                        <img src="<?php echo htmlspecialchars($categoria['IMAGEN_C']); ?>" alt="Emoji categorias.">
                        <h4>Categoria: <?php echo htmlspecialchars($categoria['NOMBRE_C']); ?></h4>
                        <p>Descripción: <?php echo htmlspecialchars($categoria['DESCRIPCION_C']); ?></p>
                        <a>Eliminar Categoria</a>
                    </div>
                <?php endforeach; ?>
                
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