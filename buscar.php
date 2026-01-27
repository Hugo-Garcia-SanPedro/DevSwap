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

    // Variables para filtros
    $filtro_objeto = '';
    $filtro_ubicacion = '';
    $filtro_categoria = '';
    $filtro_estado = '';

    // Obtenemos las categorias disponibles
    $categorias = [];
    $consulta_categorias = "SELECT NOMBRE_C FROM CATEGORIA ORDER BY NOMBRE_C";
    $resultado_categorias = mysqli_query($conexion, $consulta_categorias);
    if($resultado_categorias && mysqli_num_rows($resultado_categorias) > 0) {
        while($fila = mysqli_fetch_assoc($resultado_categorias)) {
            $categorias[] = $fila;
        }
    }

    // Procesamos filtros si se envió el formulario
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $filtro_objeto = mysqli_real_escape_string($conexion, $_POST['Objeto'] ?? '');
        $filtro_ubicacion = mysqli_real_escape_string($conexion, strtoupper($_POST['Ubicacion'] ?? ''));
        $filtro_categoria = mysqli_real_escape_string($conexion, strtoupper($_POST['Categoria'] ?? ''));
        $filtro_estado = mysqli_real_escape_string($conexion, strtoupper($_POST['Estado'] ?? ''));
    }

    // Construimos la consulta base
    $consulta = "
        SELECT P2.ID_P, P2.NOMBRE_P, P2.DESCRIPCION_P, P2.IMAGEN_P, P2.UBICACION, P2.ESTADO, P2.CAMBIO, P1.NOMBRE_C, U.NICK_U, P1.CORREO_U
        FROM PUBLICACION2 P2
        INNER JOIN PUBLICACION1 P1 ON P2.ID_P = P1.ID_P
        INNER JOIN USUARIO U ON P1.CORREO_U = U.CORREO_U
        WHERE P1.CORREO_U != '{$_SESSION['correo']}'
    ";

    // Añadimos filtros si existen
    if(!empty($filtro_objeto)) {
        $consulta .= " AND (P2.NOMBRE_P LIKE '%$filtro_objeto%' OR P2.DESCRIPCION_P LIKE '%$filtro_objeto%')";
    }
    if(!empty($filtro_ubicacion)) {
        $consulta .= " AND P2.UBICACION LIKE '%$filtro_ubicacion%'";
    }
    if(!empty($filtro_categoria) && $filtro_categoria != 'TODAS') {
        $consulta .= " AND P1.NOMBRE_C = '$filtro_categoria'";
    }
    if(!empty($filtro_estado) && $filtro_estado != 'TODOS') {
        $consulta .= " AND P2.ESTADO = '$filtro_estado'";
    }

    $consulta .= " ORDER BY RAND() LIMIT 12";

    // Ejecutamos la consulta
    $publicaciones = [];
    $resultado = mysqli_query($conexion, $consulta);
    if($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_assoc($resultado)) {
            $publicaciones[] = $fila;
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Usuario Normal (Buscar) - DevSwap.</title>
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
            <div class="impar">
                <h3>Busca los Objetos que quieras:</h3>
                <div class="registro">
                    
                    <form method="POST">
                        <!-- Que queremos buscar -->
                        <label for="Objeto">Buscar Objetos:</label>
                        <input type="text" id="Objeto" name="Objeto" placeholder="¿Que estas buscando?" 
                                value="<?php echo htmlspecialchars($filtro_objeto); ?>">
                        <!-- Filtrar por Ubicacion -->
                        <label for="Ubicacion">Ubicación:</label>
                        <input type="text" id="Ubicacion" name="Ubicacion" placeholder="Ubicacion"
                                value="<?php echo htmlspecialchars($filtro_ubicacion); ?>">
                        <!-- Filtrar por categoria -->
                        <label for="Categoria">Filtrar por Categoria:</label>
                        <select id="Categoria" name="Categoria">
                            <option value="TODAS" <?php echo ($filtro_categoria == 'TODAS' || empty($filtro_categoria)) ? 'selected' : ''; ?>>Todas</option>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['NOMBRE_C']); ?>"
                                    <?php echo ($filtro_categoria == $cat['NOMBRE_C']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['NOMBRE_C']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Filtrar por estado -->
                        <label for="Estado">Filtrar por Estado:</label>
                        <select id="Estado" name="Estado">
                            <option value="TODOS" <?php echo ($filtro_estado == 'TODOS' || empty($filtro_estado)) ? 'selected' : ''; ?>>Todos los Estados</option>
                            <option value="NUEVO" <?php echo ($filtro_estado == 'NUEVO') ? 'selected' : ''; ?>>Nuevo</option>
                            <option value="SEMINUEVO" <?php echo ($filtro_estado == 'SEMINUEVO') ? 'selected' : ''; ?>>Semi-Nuevo</option>
                            <option value="USADO" <?php echo ($filtro_estado == 'USADO') ? 'selected' : ''; ?>>Usado</option>
                            <option value="RESTAURADO" <?php echo ($filtro_estado == 'RESTAURADO') ? 'selected' : ''; ?>>Restaurado</option>
                        </select>
                        <input type="submit" value="Buscar Objetos">
                    </form>
                </div>
            </div>

            <div class="par">
                <h3>Objetos Disponibles (<?php echo count($publicaciones); ?> resultados)</h3>
                
                <?php if(count($publicaciones) > 0): ?>
                    <?php foreach($publicaciones as $pub): ?>
                        <div class="Oferta">
                            <?php 
                                $ruta_img = htmlspecialchars($pub['IMAGEN_P']);
                                // Verificar si la imagen existe
                                if(file_exists($ruta_img)) {
                                    echo '<img src="' . $ruta_img . '" alt="Imagen de ' . htmlspecialchars($pub['NOMBRE_P']) . '">';
                                } else {
                                    echo '<img src="imagenes/Fotos/default.jpg" alt="Imagen no disponible">';
                                }
                            ?>
                            <h4><?php echo htmlspecialchars($pub['NOMBRE_P']); ?></h4>
                            <p>Categoria: <?php echo htmlspecialchars($pub['NOMBRE_C']); ?>.</p>
                            <p>Ubicacion: <?php echo htmlspecialchars($pub['UBICACION']); ?>.</p>
                            <p>Estado: <?php echo htmlspecialchars($pub['ESTADO']); ?>.</p>
                            <p>Usuario: <?php echo htmlspecialchars($pub['NICK_U']); ?>.</p>
                            <p>Busca: <?php echo htmlspecialchars($pub['CAMBIO']); ?>.</p>
                            <a href="#" onclick="solicitarIntercambio('<?php echo htmlspecialchars($pub['ID_P']); ?>', '<?php echo htmlspecialchars($pub['NOMBRE_P']); ?>'); return false;">Solicitar Intercambio</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se encontraron publicaciones que coincidan con tu búsqueda.</p>
                <?php endif; ?>
            </div>
        </main>

        <footer>
            <strong>DevSwap</strong><br>
            <hr>
            <strong>Plataforma de intercambios entre desarrolladores</strong><br>
            <hr>
            <strong>DevSwap - 2026</strong>
        </footer>

        <script>
            function solicitarIntercambio(id_publicacion, nombre_articulo) {
                if(confirm('¿Deseas solicitar el intercambio de "' + nombre_articulo + '"?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'solicitar-intercambio.php';
                    
                    const inputIdP = document.createElement('input');
                    inputIdP.type = 'hidden';
                    inputIdP.name = 'id_publicacion';
                    inputIdP.value = id_publicacion;
                    
                    form.appendChild(inputIdP);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    </body>
</html>