<?php
    // Comenzamos la sesion
    session_start();

    // Incluimos el fichero de la configuracion de la base de datos
    require_once 'configuracion.php';

    // Vemos que el administrador se haya logueado
    if(!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'ADMINISTRADOR') {
        header('Location: login.php');
        exit();
    }

    $error = '';
    $exito = '';

    // Comprobamos que se haya enviado un POST, para crear usuario
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['gestion-usuarios'])) {
            $correo_u = mysqli_real_escape_string($conexion, $_POST['Correo'] ?? '');
            $nick_u = mysqli_real_escape_string($conexion, $_POST['Nick'] ?? '');
            $apellido1 = mysqli_real_escape_string($conexion, $_POST['Apellido1'] ?? '');
            $apellido2 = mysqli_real_escape_string($conexion, $_POST['Apellido2'] ?? '');
            $nombre_u = mysqli_real_escape_string($conexion, $_POST['Nombre'] ?? '');
            $telefono = mysqli_real_escape_string($conexion, $_POST['Telefono'] ?? '');
            $ciudad = mysqli_real_escape_string($conexion, $_POST['Ciudad'] ?? '');
            $contrasenia_u = mysqli_real_escape_string($conexion, $_POST['Contrasenia'] ?? '');

            // Comprobamos que todos los campos se hayan completado
            if(empty($correo_u) || empty($nick_u) || empty($apellido1) || empty($apellido2) || empty($nombre_u) || empty($telefono) || empty($ciudad) ||empty($contrasenia_u)) {
                $error = 'Se ha dejado algun campo sin completar';

            } else {
                // Encriptar contraseña
                $contrasenia_hash = password_hash($contrasenia_u, PASSWORD_DEFAULT);
                
                // Creamos la consulta con los campos del post
                $consulta_crear_usuario = "
                    INSERT INTO USUARIO(CORREO_U, NICK_U, APELLIDO1, APELLIDO2, NOMBRE_U, TELEFONO, CIUDAD, CONTRASEÑA_U)
                    VALUES
                    ('$correo_u', '$nick_u', '$apellido1', '$apellido2', '$nombre_u', '$telefono', '$ciudad', '$contrasenia_hash')
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

    // Comprobamos que se haya usado el metodo POST, en el segundo formulario para crear categorías
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['gestion-categorias'])) {
            $nombre_c = mysqli_real_escape_string($conexion, strtoupper($_POST['nombre_c'] ?? ''));
            $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion'] ?? '');

            // Comprobamos que los campos esten completos
            if(empty($nombre_c) || empty($descripcion)) {
                $error = 'Error: Debes completar todos los campos.';

            } else {
                // Procesamos la imagen
                $ruta_imagen = '';
                
                // Verificamos si se subió una imagen
                if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                    $archivo = $_FILES['imagen'];
                    $nombre_archivo = $archivo['name'];
                    $tipo_archivo = $archivo['type'];
                    $tamaño_archivo = $archivo['size'];
                    $tmp_archivo = $archivo['tmp_name'];
                    
                    // Extensiones permitidas
                    $extensiones_permitidas = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'avif');
                    $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
                    
                    // Validar extensión
                    if(!in_array($extension, $extensiones_permitidas)) {
                        $error = 'Error: Solo se permiten imágenes (jpg, jpeg, png, gif, webp, avif).';
                    }
                    // Validar tamaño (máximo 5MB)
                    elseif($tamaño_archivo > 5000000) {
                        $error = 'Error: La imagen es demasiado grande. Máximo 5MB.';
                    }
                    else {
                        // Crear directorio si no existe
                        $directorio_destino = 'imagenes/Emoji/';
                        if(!file_exists($directorio_destino)) {
                            mkdir($directorio_destino, 0777, true);
                        }
                        
                        // Generar nombre único para evitar sobrescrituras
                        $nombre_unico = 'emoji_' . strtolower($nombre_c) . '_' . time() . '.' . $extension;
                        $ruta_imagen = $directorio_destino . $nombre_unico;
                        
                        // Mover archivo al destino
                        if(move_uploaded_file($tmp_archivo, $ruta_imagen)) {
                            // Imagen subida correctamente
                        } else {
                            $error = 'Error al subir la imagen.';
                        }
                    }
                } else {
                    // Si no se sube imagen, usar una por defecto o dejar vacío
                    $ruta_imagen = 'imagenes/Emoji/default.png';
                }
                
                // Si no hay error, insertamos en la base de datos
                if(empty($error)) {
                    // Creamos la consulta SQL, para insertar los valores
                    $consulta_crear_categoria = "
                        INSERT INTO CATEGORIA(NOMBRE_C, DESCRIPCION_C, IMAGEN_C)
                        VALUES
                        ('$nombre_c', '$descripcion', '$ruta_imagen')
                    ";

                    // Obtenemos el resultado de la consulta
                    $resultado_crear_categoria = mysqli_query($conexion, $consulta_crear_categoria);
                    if($resultado_crear_categoria) {
                        $exito = 'La categoria se creo satisfactoriamente.';

                    } else {
                        $error = 'Error al crear la categoria. ' . mysqli_error($conexion);
                        // Si falla la inserción, eliminamos la imagen subida
                        if(file_exists($ruta_imagen) && $ruta_imagen != 'imagenes/Emoji/default.png') {
                            unlink($ruta_imagen);
                        }
                    }
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
        LIMIT 6
    ";

    $resultado_obtener_usuarios = mysqli_query($conexion, $obtener_usuarios);
    if($resultado_obtener_usuarios && mysqli_num_rows($resultado_obtener_usuarios) > 0) {
        while($fila = mysqli_fetch_assoc($resultado_obtener_usuarios)) {
            $usuarios[] = $fila;
        }
    }

    // Presentar categorias
    $limite_categorias = 6; // Por defecto muestra 6
    if(isset($_GET['ver_categorias']) && $_GET['ver_categorias'] == 'todos') {
        $limite_categorias = 1000; // Muestra todas (número grande)
    }

    // Presentar categorias
    $categorias = [];
    $obtener_categorias = "
        SELECT C.NOMBRE_C, C.DESCRIPCION_C, C.IMAGEN_C
        FROM CATEGORIA C
        ORDER BY C.NOMBRE_C
        LIMIT $limite_categorias
    ";

    $resultado_obtener_categorias = mysqli_query($conexion, $obtener_categorias);
    if($resultado_obtener_categorias && mysqli_num_rows($resultado_obtener_categorias) > 0) {
        while($fila_c = mysqli_fetch_assoc($resultado_obtener_categorias)) {
            $categorias[] = $fila_c;
        }
    }

    // Contar total de categorías
    $count_categorias = mysqli_query($conexion, "SELECT COUNT(*) as total FROM CATEGORIA");
    $total_categorias = mysqli_fetch_assoc($count_categorias)['total'];
    
    // Eliminar un usuario
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['eliminar_usuario'])) {
            $correo_eliminar = mysqli_real_escape_string($conexion, $_POST['correo_eliminar'] ?? '');
            
            if(!empty($correo_eliminar)) {
                $consulta_eliminar = "
                    DELETE FROM USUARIO 
                    WHERE CORREO_U = '$correo_eliminar'
                ";

                $resultado_eliminar = mysqli_query($conexion, $consulta_eliminar);
                if($resultado_eliminar) {
                    $exito = 'Usuario eliminado correctamente.';
                } else {
                    $error = 'Error al eliminar el usuario: ' . mysqli_error($conexion);
                }
            }
        }
    }

    // Eliminar una categoria
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['eliminar_categoria'])) {
            $nombre_eliminar = mysqli_real_escape_string($conexion, $_POST['nombre_eliminar'] ?? '');
            
            if(!empty($nombre_eliminar)) {
                $consulta_eliminar = "
                    DELETE FROM CATEGORIA 
                    WHERE NOMBRE_C = '$nombre_eliminar'
                ";

                $resultado_eliminar = mysqli_query($conexion, $consulta_eliminar);
                if($resultado_eliminar) {
                    $exito = 'Categoria eliminada correctamente.';
                } else {
                    $error = 'Error al eliminar la categoria: ' . mysqli_error($conexion);
                }
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
            <!-- Mensajes de exito o error -->
            <?php if($exito != ''): ?>
                <div>
                    <h3><?php echo htmlspecialchars($exito); ?></h3>
                </div>
            <?php endif; ?>
            <?php if($error != ''): ?>
                <div>
                    <h3><?php echo htmlspecialchars($error); ?></h3>
                </div>
            <?php endif; ?>

            <!-- Creacion de usuarios -->
            <div class="impar">
                <div class="registro">
                    <h3 id="Usuarios">Gestión de Usuarios</h3>
                    <form method="POST" action="admin.php">
                        <input type="hidden" name="gestion-usuarios">
                        <label for="Nombre">Nombre:</label>
                        <input type="text" id="Nombre" name="Nombre" placeholder="Nombre de Usuario" required>
                        <label for="Nick">Nick:</label>
                        <input type="text" id="Nick" name="Nick" placeholder="Nombre de Usuario" required>
                        <label for="Apellido1">Primer Apellido:</label>
                        <input type="text" id="Apellido1" name="Apellido1" placeholder="Primer Apellido" required>
                        <label for="Apellido2">Segundo Apellido:</label>
                        <input type="text" id="Apellido2" name="Apellido2" placeholder="Segundo Apellido" required>
                        <label for="Correo">Correo Electronico:</label>
                        <input type="email" id="Correo" name="Correo" placeholder="email@" required>
                        <label for="Telefono">Telefono:</label>
                        <input type="text" id="Telefono" name="Telefono" placeholder="Telefono" required>
                        <label for="Ciudad">Ciudad:</label>
                        <input type="text" id="Ciudad" name="Ciudad" placeholder="Ciudad" required>
                        <label for="Contrasenia">Contraseña:</label>
                        <input type="password" id="Contrasenia" name="Contrasenia" placeholder="**********" required>
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
                        <a href="#" onclick="eliminarUsuario('<?php echo htmlspecialchars($usuario['CORREO_U']); ?>'); return false;">Eliminar Usuario</a>
                    </div>  
                <?php endforeach; ?>
            </div>

            <!-- Zona para crear categorias -->
            <div class="impar">
                <div class="registro">
                    <h3 id="Categorias">Gestión de las Categorias</h3>
                    <form method="POST" action="admin.php" enctype="multipart/form-data">
                        <input type="hidden" name="gestion-categorias">
                        <label for="nombre_c">Nombre de la Categoría:</label>
                        <input type="text" id="nombre_c" name="nombre_c" placeholder="Nombre" required>
                        
                        <label for="imagen">Imagen de la Categoría:</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*">
                        
                        <label for="descripcion">Descripción Breve:</label>
                        <input type="text" id="descripcion" name="descripcion" placeholder="Descripción" required>
                        <input type="submit" value="Crear Categoría">
                    </form>
                </div>
            </div>

            <!-- Categorias creadas -->
            <div class="par">
                <h3>Gestión de Categorias (<?php echo count($categorias); ?> de <?php echo $total_categorias; ?>)</h3>

                <!-- Bucle para crear las categorias desde la base de datos -->
                <?php if(count($categorias) > 0): ?>
                    <?php foreach($categorias as $categoria): ?>
                        <div class="Oferta">
                            <?php 
                                $ruta_img = htmlspecialchars($categoria['IMAGEN_C']);
                                // Verificar si la imagen existe
                                if(file_exists($ruta_img)) {
                                    echo '<img src="' . $ruta_img . '" alt="Imagen de ' . htmlspecialchars($categoria['NOMBRE_C']) . '">';
                                } else {
                                    echo '<img src="imagenes/Emoji/default.png" alt="Imagen no disponible">';
                                }
                            ?>
                            <h4>Categoria: <?php echo htmlspecialchars($categoria['NOMBRE_C']); ?></h4>
                            <p>Descripción: <?php echo htmlspecialchars($categoria['DESCRIPCION_C']); ?></p>
                            <a href="#" onclick="eliminarCategoria('<?php echo htmlspecialchars($categoria['NOMBRE_C']); ?>'); return false;">Eliminar Categoria</a>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Botón dinámico para ver más o menos -->
                    <?php if($total_categorias > 6 && !isset($_GET['ver_categorias'])): ?>
                        <a href="admin.php?ver_categorias=todos#Categorias">Ver Todas las Categorias (<?php echo $total_categorias; ?>)</a>
                    <?php elseif(isset($_GET['ver_categorias'])): ?>
                        <a href="admin.php#Categorias">Ver Menos Categorias</a>
                    <?php endif; ?>
                <?php else: ?>
                    <p>No hay categorías disponibles.</p>
                <?php endif; ?>
            </div>
        </main>

        <footer>
            <strong>DevSwap</strong><br>
            <hr>
            <strong>Plataforma de intercambios entre desarrolladores</strong><br>
            <hr>
            <strong>2026 - DevSwap</strong>
        </footer>

        <script>
            function eliminarUsuario(correo) {
                if(confirm('¿Estás seguro de eliminar este usuario?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'admin.php';
                    
                    const inputAccion = document.createElement('input');
                    inputAccion.type = 'hidden';
                    inputAccion.name = 'eliminar_usuario';
                    inputAccion.value = '1';
                    
                    const inputCorreo = document.createElement('input');
                    inputCorreo.type = 'hidden';
                    inputCorreo.name = 'correo_eliminar';
                    inputCorreo.value = correo;
                    
                    form.appendChild(inputAccion);
                    form.appendChild(inputCorreo);
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function eliminarCategoria(nombre) {
                if(confirm('¿Estás seguro que quieres eliminar esta categoria?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'admin.php';

                    const inputAccion = document.createElement('input'); 
                    inputAccion.type = 'hidden';
                    inputAccion.name = 'eliminar_categoria';
                    inputAccion.value = '1';

                    const inputNombre = document.createElement('input');
                    inputNombre.type = 'hidden';
                    inputNombre.name = 'nombre_eliminar'; 
                    inputNombre.value = nombre;

                    form.appendChild(inputAccion);
                    form.appendChild(inputNombre);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    </body>
</html>