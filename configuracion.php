<?php
    // Configuración de la base de datos
    $servidor = "localhost";
    $usuario = "root"; 
    $contrasenia = "";        
    $nombre_Db = "devswap"; 

    // Crear conexión
    $conexion = mysqli_connect($servidor, $usuario, $contrasenia, $nombre_Db);

    // Verificar conexión
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>