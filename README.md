DevSwap 🔄
Plataforma de intercambio de objetos tecnológicos entre desarrolladores
DevSwap es una aplicación web que permite a desarrolladores y entusiastas de la tecnología intercambiar dispositivos electrónicos, libros de programación, accesorios y otros objetos tecnológicos de forma segura y organizada.
📋 Descripción
DevSwap facilita el intercambio de objetos entre usuarios mediante un sistema de publicaciones y solicitudes. Los usuarios pueden publicar los objetos que desean intercambiar, especificar qué están buscando a cambio, y gestionar las solicitudes de intercambio de otros usuarios. La plataforma incluye un sistema de valoraciones para generar confianza entre los usuarios.
Características principales

Sistema de usuarios: Registro, inicio de sesión y gestión de perfiles
Publicación de objetos: Los usuarios pueden publicar objetos con descripción, fotos, categoría y estado
Búsqueda avanzada: Filtros por categoría, ubicación y estado del objeto
Gestión de intercambios: Sistema para enviar, recibir y gestionar solicitudes de intercambio
Sistema de valoraciones: Calificación de usuarios tras intercambios completados
Panel de administración: Gestión de usuarios, categorías y moderación de contenido
Categorías organizadas: Libros, portátiles, smartphones, tablets, accesorios y audio

🛠️ Tecnologías utilizadas

Frontend:

HTML5
CSS3 (archivo personalizado estilo/style.css)
JavaScript (integrado en PHP)


Backend:

PHP 7.4+ (con soporte para sesiones y MySQLi)


Base de datos:

MySQL 5.7+ / MariaDB 10.3+


Servidor web:

Apache 2.4+ con mod_rewrite habilitado
Nginx (alternativa)



📦 Requisitos del sistema

PHP >= 7.4
MySQL >= 5.7 o MariaDB >= 10.3
Apache >= 2.4 o Nginx >= 1.18
Extensiones PHP requeridas:

mysqli
session
fileinfo (para subida de imágenes)



🚀 Instalación
1. Clonar el repositorio
bashgit clone https://github.com/tu-usuario/devswap.git
cd devswap
2. Configurar la base de datos
Opción A: Usando MySQL desde línea de comandos
bashmysql -u root -p < DevSwap.sql
Opción B: Usando phpMyAdmin

Accede a phpMyAdmin
Crea una nueva base de datos llamada devswap
Importa el archivo DevSwap.sql

3. Configurar la conexión a la base de datos
Edita el archivo configuracion.php con tus credenciales:
php$servidor = "localhost";
$usuario = "tu_usuario";        // Cambia esto
$contrasenia = "tu_contraseña";  // Cambia esto
$nombre_Db = "devswap";

6. Acceder a la aplicación
Abre tu navegador y visita:
http://localhost/devswap

📁 Estructura del proyecto
devswap/
├── admin.php              # Panel de administración
├── buscar.php            # Búsqueda de objetos
├── configuracion.php     # Configuración de BD
├── index.php             # Página principal
├── intercambios.php      # Gestión de intercambios
├── login.php             # Inicio de sesión
├── publicar.php          # Publicar objetos
├── registro.php          # Registro de usuarios
├── usuario-normal.php    # Panel de usuario
├── DevSwap.sql           # Script de base de datos
├── estilo/
│   └── style.css         # Estilos de la aplicación
└── imagenes/
    ├── Fotos/            # Imágenes de productos
    └── Emoji/            # Iconos de categorías
