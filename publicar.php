<?php
    // Comenzamos la sesion
    session_start();

    // Fichero para la configuracion de la base de datos
    require_once 'configuracion.php';

    // Verificamos que el usuario se haya logueado
    if(!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'USUARIO') {
        header("Location: login.php");
        exit();
    }

    $error = '';
    $exito = '';

    // Obtenemos las categorias disponibles
    $categorias = [];
    $consulta_categorias = "SELECT NOMBRE_C FROM CATEGORIA ORDER BY NOMBRE_C";
    $resultado_categorias = mysqli_query($conexion, $consulta_categorias);
    if($resultado_categorias && mysqli_num_rows($resultado_categorias) > 0) {
        while($fila = mysqli_fetch_assoc($resultado_categorias)) {
            $categorias[] = $fila;
        }
    }

    // Comprobamos que se haya enviado un POST para crear publicacion
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre_p = mysqli_real_escape_string($conexion, $_POST['Nombre'] ?? '');
        $descripcion_p = mysqli_real_escape_string($conexion, $_POST['Descripcion'] ?? '');
        $ubicacion = mysqli_real_escape_string($conexion, strtoupper($_POST['Ubicacion'] ?? ''));
        $categoria = mysqli_real_escape_string($conexion, strtoupper($_POST['Categoria'] ?? ''));
        $estado = mysqli_real_escape_string($conexion, strtoupper($_POST['Estado'] ?? ''));
        $cambio = mysqli_real_escape_string($conexion, strtoupper($_POST['Busqueda'] ?? ''));
        $correo_u = $_SESSION['correo'];

        // Comprobamos que todos los campos esten completos
        if(empty($nombre_p) || empty($descripcion_p) || empty($ubicacion) || empty($categoria) || empty($estado) || empty($cambio)) {
            $error = 'Error: Debes completar todos los campos.';

        } else {
            // Generamos un ID unico para la publicacion
            $id_p = 'ID' . str_pad(rand(1, 9999), 3, '0', STR_PAD_LEFT);
            
            // Verificamos que el ID no exista
            $consulta_verificar = "SELECT ID_P FROM PUBLICACION1 WHERE ID_P = '$id_p'";
            $resultado_verificar = mysqli_query($conexion, $consulta_verificar);
            
            // Si existe, generamos otro
            while(mysqli_num_rows($resultado_verificar) > 0) {
                $id_p = 'ID' . str_pad(rand(1, 9999), 3, '0', STR_PAD_LEFT);
                $resultado_verificar = mysqli_query($conexion, $consulta_verificar);
            }

            // Procesamos la imagen
            $ruta_imagen = '';
            
            // Verificamos si se subió una imagen
            if(isset($_FILES['fichero']) && $_FILES['fichero']['error'] == 0) {
                $archivo = $_FILES['fichero'];
                $nombre_archivo = $archivo['name'];
                $tmp_archivo = $archivo['tmp_name'];
                $tamaño_archivo = $archivo['size'];
                
                // Extensiones permitidas
                $extensiones_permitidas = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
                
                // Validar extension
                if(!in_array($extension, $extensiones_permitidas)) {
                    $error = 'Error: Solo se permiten imágenes (jpg, jpeg, png, gif, webp).';
                }
                // Validar tamaño (máximo 5MB)
                elseif($tamaño_archivo > 5000000) {
                    $error = 'Error: La imagen es demasiado grande. Máximo 5MB.';
                }
                else {
                    // Crear directorio si no existe
                    $directorio_destino = 'imagenes/Fotos/';
                    if(!file_exists($directorio_destino)) {
                        mkdir($directorio_destino, 0777, true);
                    }
                    
                    // Generar nombre único
                    $nombre_unico = 'pub_' . $id_p . '_' . time() . '.' . $extension;
                    $ruta_imagen = $directorio_destino . $nombre_unico;
                    
                    // Mover archivo al destino
                    if(!move_uploaded_file($tmp_archivo, $ruta_imagen)) {
                        $error = 'Error al subir la imagen.';
                    }
                }
            } else {
                // Si no se sube imagen, usar una por defecto
                $ruta_imagen = 'imagenes/Fotos/default.jpg';
            }
            
            // Si no hay error, insertamos en la base de datos
            if(empty($error)) {
                // Primero insertamos en PUBLICACION1
                $consulta_pub1 = "
                    INSERT INTO PUBLICACION1(ID_P, CORREO_U, NOMBRE_C)
                    VALUES ('$id_p', '$correo_u', '$categoria')
                ";
                
                $resultado_pub1 = mysqli_query($conexion, $consulta_pub1);
                
                if($resultado_pub1) {
                    // Luego insertamos en PUBLICACION2
                    $consulta_pub2 = "
                        INSERT INTO PUBLICACION2(ID_P, CAMBIO, ESTADO, UBICACION, NOMBRE_P, DESCRIPCION_P, IMAGEN_P)
                        VALUES ('$id_p', '$cambio', '$estado', '$ubicacion', '$nombre_p', '$descripcion_p', '$ruta_imagen')
                    ";
                    
                    $resultado_pub2 = mysqli_query($conexion, $consulta_pub2);
                    
                    if($resultado_pub2) {
                        $exito = 'Publicación creada satisfactoriamente.';
                    } else {
                        $error = 'Error al crear la publicación: ' . mysqli_error($conexion);
                        // Eliminamos la entrada de PUBLICACION1 si falla PUBLICACION2
                        mysqli_query($conexion, "DELETE FROM PUBLICACION1 WHERE ID_P = '$id_p'");
                        // Eliminamos la imagen si se subió
                        if(file_exists($ruta_imagen) && $ruta_imagen != 'imagenes/Fotos/default.jpg') {
                            unlink($ruta_imagen);
                        }
                    }
                } else {
                    $error = 'Error al crear la publicación: ' . mysqli_error($conexion);
                    // Eliminamos la imagen si se subió
                    if(file_exists($ruta_imagen) && $ruta_imagen != 'imagenes/Fotos/default.jpg') {
                        unlink($ruta_imagen);
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Usuario Normal (Publicar) - DevSwap.</title>
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
                <a href="usuario-normal.php">Mi Perfil</a>
                <a href="intercambios.php">Mis Intercambios</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </header>
        
        <main>
            <!-- Mostrar mensajes de exito o error -->
            <?php if(!empty($exito)): ?>
                <div style="background: #4CAF50; color: white; padding: 15px; margin: 20px; border-radius: 5px;">
                    <h3><?php echo htmlspecialchars($exito); ?></h3>
                </div>
            <?php endif; ?>

            <?php if(!empty($error)): ?>
                <div style="background: #f44336; color: white; padding: 15px; margin: 20px; border-radius: 5px;">
                    <h3><?php echo htmlspecialchars($error); ?></h3>
                </div>
            <?php endif; ?>

            <div class="registro">
                <h3>Publicar Objeto</h3>
                <form method="POST" enctype="multipart/form-data">
                    <!-- Campo para poner el nombre del objeto -->
                    <label for="Nombre">Nombre del Articulo:</label>
                    <input type="text" id="Nombre" name="Nombre" placeholder="Nombre" required>
                    <!-- Campo para poner la descripcion del objeto -->
                    <label for="Descripcion">Descripcion del Artículo:</label>
                    <input type="text" id="Descripcion" name="Descripcion" placeholder="Descripción" required>
                    <!-- Campo para poner la ubicacion -->
                    <label for="Ubicacion">Ubicación:</label>
                    <input type="text" id="Ubicacion" name="Ubicacion" placeholder="Ubicación" required>
                    <!-- Campo para definir la categoria del objeto -->
                    <label for="Categoria">Categoria del Objeto:</label>
                    <select id="Categoria" name="Categoria" required>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['NOMBRE_C']); ?>">
                                <?php echo htmlspecialchars($cat['NOMBRE_C']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- Campo para definir el estado del objeto -->
                    <label for="Estado">Estado del Objeto:</label>
                    <select id="Estado" name="Estado" required>
                        <option value="NUEVO">Nuevo</option>
                        <option value="USADO">Usado</option>
                        <option value="RESTAURADO">Restaurado</option>
                        <option value="SEMINUEVO">Seminuevo</option>
                    </select>
                    <!-- Campo para describir que buscamos -->
                    <label for="Busqueda">Articulo(s) que buscas:</label>
                    <input type="text" id="Busqueda" name="Busqueda" placeholder="Ej: iPad, teclado..." required>
                    <!-- Campo para subir una imagen -->
                    <label for="fichero" class="boton">Elegir fichero:</label>
                    <input type="file" id="fichero" name="fichero" accept="image/*">
                    <input type="submit" value="Publicar Articulo">
                </form>
                <a href="intercambios.php">Volver a Mis Publicaciones</a>
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