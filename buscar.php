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
                <a href="intercambios.phpl">Mis Intercambios</a>
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
                        <input type="text" id="Objeto" placeholder="¿Que estas buscando?">
                        <!-- Filtrar por Ubicacion -->
                        <label for="Ubicacion">Ubicación:</label>
                        <input type="text" id="Ubicacion" placeholder="Ubicacion">
                        <!-- Filtrar por categoria -->
                        <label for="Categoria">Filtrar por Categoria:</label>
                        <select id="Categoria">
                            <option value="Todas">Todas</option>
                            <option value="Libro">Libro</option>
                            <option value="Portatil">Portatil</option>
                            <option value="Accesorios">Accesorios</option>
                        </select>
                        <!-- Filtrar por estado -->
                        <label for="Estado">Filtrar por Estado:</label>
                        <select id="Estado">
                            <option value="Todos">Todos los Estados</option>
                            <option value="Nuevo">Nuevo</option>
                            <option value="Seminuevo">Semi-Nuevo</option>
                            <option value="Restaurado">Restaurado</option>
                        </select>
                        <input type="submit" value="Buscar Objetos">
                    </form>
                </div>
            </div>

            <div class="par">
                <h3>Objetos Disponibles</h3>
                <div class="Oferta">
                    <img src="imagenes/Fotos/MacBook.jpg" alt="Foto de un macBook">
                    <h4>Portail MacBook Pro M4</h4>
                    <p>Categoria: Portatiles.</p>
                    <p>Ubicacion: Salamanca.</p>
                    <p>Usuario: Jorge Saenz.</p>
                    <p>Busca: iPhone 16 Pro.</p>
                    <a>Solicitar Intercambio</a>
                </div>
                <div class="Oferta">
                    <img src="imagenes/Fotos/cascos.jpg" alt="Foto de unos cascos">
                    <h4>Cascos Sony</h4>
                    <p>Categoria: Audio.</p>
                    <p>Ubicacion: Madrid.</p>
                    <p>Usuario: Pedro Garcia.</p>
                    <p>Busca: Libros de Programación.</p>
                    <a>Solicitar Intercambio</a>
                </div>
                <div class="Oferta">
                    <img src="imagenes/Fotos/iPhone.jpg" alt="Foto de un iPhone">
                    <h4>Apple iPhone 17 PRO</h4>
                    <p>Categoria: Smartphone.</p>
                    <p>Ubicacion: Zamora.</p>
                    <p>Usuario: Sara Lopez.</p>
                    <p>Busca: MacBook Air.</p>
                    <a>Solicitar Intercambio</a>
                </div>
                <div class="Oferta">
                    <img src="imagenes/Fotos/libro.jpg" alt="Foto de un libro">
                    <h4>El Programador Pragmatico</h4>
                    <p>Categoria: Libro.</p>
                    <p>Ubicacion: Badajoz.</p>
                    <p>Usuario: Mario Gonzalez.</p>
                    <p>Busca: Funda para smartphones.</p>
                    <a>Solicitar Intercambio</a>
                </div>
                <div class="Oferta">
                    <img src="imagenes/Fotos/tablet.jpg" alt="Foto de una tablet">
                    <h4>Table Xiaomi Redmi Pad 7</h4>
                    <p>Categoria: Tablets.</p>
                    <p>Ubicacion: Barcelona.</p>
                    <p>Usuario: Laura Lopez.</p>
                    <p>Busca: iPhone 16 Pro.</p>
                    <a>Solicitar Intercambio</a>
                </div>
                <div class="Oferta">
                    <img src="imagenes/Fotos/tecladoRGB.jpg" alt="Foto de un teclado">
                    <h4>Teclado con luces RGB</h4>
                    <p>Categoria: Accesorios.</p>
                    <p>Ubicacion: Valencia.</p>
                    <p>Usuario: Pedro Hernando.</p>
                    <p>Busca: Libros de programación.</p>
                    <a>Solicitar Intercambio</a>
                </div>
                <a>Ver Mas Ofertas</a>
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