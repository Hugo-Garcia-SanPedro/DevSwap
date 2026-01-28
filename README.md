# DevSwap 💻🔄

**DevSwap** es una plataforma web desarrollada en PHP diseñada para que la comunidad de desarrolladores pueda intercambiar objetos, hardware o periféricos de forma sencilla. El proyecto fomenta la economía circular permitiendo que los usuarios den una segunda vida a su equipamiento tecnológico.

---

## 🚀 Características Principales

### Para Usuarios
* **Panel de Control Personalizado**: Vista general de la actividad del usuario.
* **Gestión de Intercambios**: Seguimiento de solicitudes aceptadas y pendientes en tiempo real.
* **Buscador de Ofertas**: Sistema para localizar objetos disponibles subidos por otros desarrolladores.

### Para Administradores
* **Gestión de Comunidad**: Capacidad para crear y eliminar usuarios del sistema.
* **Control de Categorías**: Creación de nuevas categorías de intercambio con soporte para subida de imágenes y descripciones personalizadas.
* **Moderación**: Herramientas para mantener la integridad de la plataforma.

---

## 🛠️ Stack Tecnológico

* **Lenguaje**: PHP 8.x
* **Base de Datos**: MySQL / MariaDB
* **Frontend**: HTML5, CSS3 y JavaScript nativo
* **Servidor**: Compatible con entornos XAMPP / Apache

---

## 📋 Requisitos e Instalación en XAMPP

Para poner en marcha el proyecto en tu máquina local, sigue estos pasos:

### 1. Preparar el Entorno
1. Instala [XAMPP](https://www.apachefriends.org/).
2. Clona este repositorio dentro de la carpeta `htdocs` de tu instalación de XAMPP (generalmente en `C:\xampp\htdocs`).

### 2. Configurar la Base de Datos SQL
El sistema requiere una base de datos relacional con la siguiente estructura lógica:



1. Accede a **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Crea una base de datos llamada `devswap`.
3. Importa el archivo `.sql`, del repositorio. 

### 3. Conexión
Edita el archivo `configuracion.php` para que coincida con tus credenciales locales:
```php
$conexion = mysqli_connect("localhost", "tu_usuario", "tu_password", "devswap");
