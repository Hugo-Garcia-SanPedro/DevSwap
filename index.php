<?php
    // Iniciamos la sesion
    session_start();

    // Introducimos la configuracion de la base de datos
    require_once 'configuracion.php';

    $error = '';
    $oferta_encontrada = false;

    // Si la conexion es correcta se hace la consulta a la base de datos
    if($conexion) {
        $consulta = "
            SELECT P2.NOMBRE_P AS NOMBRE_P, P1.NOMBRE_C AS NOMBRE_C, P2.UBICACION AS UBICACION, U.NICK_U AS NICK, P2.CAMBIO AS CAMBIO, P2.IMAGEN_P AS IMAGEN
            FROM PUBLICACION2 P2
            INNER JOIN PUBLICACION1 P1 ON P1.ID_P = P2.ID_P
            INNER JOIN USUARIO U ON P1.CORREO_U = U.CORREO_U
        ";

        $resultado = mysqli_query($conexion, $consulta);
        if($resultado && mysqli_num_rows($resultado) > 0) {
            $datos_usuario = mysqli_fetch_assoc($resultado);
            $oferta_encontrada = true;
        }

        if($oferta_encontrada) {
            $_SESSION['nombre_p'] = $datos_usuario['NOMBRE_P'];
            $_SESSION['nombre_c'] = $datos_usuario['NOMBRE_C'];
            $_SESSION['ubicacion'] = $datos_usuario['UBICACION'];
            $_SESSION['nick'] = $datos_usuario['NICK'];
            $_SESSION['cambio'] = $datos_usuario['CAMBIO'];
            $_SESSION['imagen'] = $datos_usuario['IMAGEN'];
        }

    } else {
        $error = 'Conexion fallida con la base de datos.';
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>DevSwap - Plataforma de intercambio entre desarrolladores.</title>
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
                <a href="#top">Inicio</a>
                <a href="#Como-Funciona">Cómo Funciona</a>
                <a href="#Ofertas">Ofertas</a>
                <a href="login.php">Iniciar Sesión</a>
            </div>

            <div class="banner-principal">
                <h2>Intercambia lo que no utilices.</h2>
                <p>Unete a la comunidad de intercambios entre desarrolladores, mas grande</p>
                <a href="registro.php">Registrate Gratis</a>
                <a href="#Ofertas">Ver Ofertas</a>
            </div>
        </header>

        <main>
            <div>
                <h3>¿Porque elegir DevSwap?</h3>
                <div class="tarjeta">
                    <h3>Busqueda Fácil</h3>
                    <p>Encuentra lo que buscas con filtros simples</p>
                </div>

                <div class="tarjeta">
                    <h3>Seguro</h3>
                    <p>Sistema de valoración de clientes</p>
                </div>

                <div class="tarjeta">
                    <h3>Gratis</h3>
                    <p>Página que busca fomentar el intercambio de objetos</p>
                </div>
            </div>

            <div id="Funcionamiento">
                <h3 id="Como-Funciona">Como Funciona</h3>
                <div class="tarjeta">
                    <h3>1. Registrate</h3>
                    <p>Crea tu cuenta gratuita</p>
                </div>

                <div class="tarjeta">
                    <h3>2. Publica</h3>
                    <p>Añade los objetos que quieras intercambiar</p>
                </div>

                <div class="tarjeta">
                    <h3>3. Negocia E Intercambia</h3>
                    <p>Contacta con otros usuarios y realiza el intercambio</p>
                </div>
            </div>

            <div>
                <h3 id="Ofertas">Ofertas Destacadas</h3>
                <div class="oferta">
                    <img src="<?php echo $_SESSION['imagen'] ?>">
                    <h4><?php echo $_SESSION['nombre_p'] ?></h4>
                    <p>Categoria: <?php echo $_SESSION['nombre_c'] ?></p>
                    <p>Ubicación: <?php echo $_SESSION['ubicacion'] ?></p>
                    <p>Usuario: <?php echo $_SESSION['nick'] ?></p>
                    <p>Busca: <?php echo $_SESSION['cambio'] ?></p>
                </div>

                <div class="oferta">
                    <img src="<?php echo $_SESSION['imagen'] ?>">
                    <h4><?php echo $_SESSION['nombre_p'] ?></h4>
                    <p>Categoria: <?php echo $_SESSION['nombre_c'] ?></p>
                    <p>Ubicación: <?php echo $_SESSION['ubicacion'] ?></p>
                    <p>Usuario: <?php echo $_SESSION['nick'] ?></p>
                    <p>Busca: <?php echo $_SESSION['cambio'] ?></p>
                </div>

                <div class="oferta">
                    <img src="<?php echo $_SESSION['imagen'] ?>">
                    <h4><?php echo $_SESSION['nombre_p'] ?></h4>
                    <p>Categoria: <?php echo $_SESSION['nombre_c'] ?></p>
                    <p>Ubicación: <?php echo $_SESSION['ubicacion'] ?></p>
                    <p>Usuario: <?php echo $_SESSION['nick'] ?></p>
                    <p>Busca: <?php echo $_SESSION['cambio'] ?></p>
                </div>

                <!-- <div class="Oferta">
                    <img src="imagenes/Fotos/libro.jpg" alt="Libro de Programación">
                    <h4>El Programador Pragmatico.</h4>
                    <p>Categoria: Libros.</p>
                    <p>Ubicación: Salamanca.</p>
                    <p>Usuario: Laura Perez.</p>
                    <p>Busca: Ratón inalambrico.</p>
                </div>

                <div class="Oferta">
                    <img src="imagenes/Fotos/tecladoRGB.jpg" alt="Teclado con Luces RGB">
                    <h4>Teclado con Luces RGB.</h4>
                    <p>Categorias: Accesorios.</p>
                    <p>Ubicación: Barcelona.</p>
                    <p>Usuario: Mario Luque.</p>
                    <p>Busca: Apple Pencil.</p>
                </div> -->
            </div>

            <div>
                <h2>¿Listo para empezar?</h2>
                <p>Unete a miles de desarrolladores.</p>
                <a href="registro.php">Registrate Gratis</a>
            </div>
        </main>

        <footer>
            <div class="footer-pagina">
                <strong>DevSwap</strong><br>
                <hr>
                <strong>Plataforma de intercambios entre desarrolladores.</strong><br>
                <hr>
                <strong>2026 - DevSwap</strong>
            </div>
        </footer>
    </body>
</html>