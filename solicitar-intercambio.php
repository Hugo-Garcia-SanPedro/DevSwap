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

    // Procesamos la solicitud de intercambio
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_publicacion'])) {
        $id_publicacion = mysqli_real_escape_string($conexion, $_POST['id_publicacion']);
        $correo_usuario = $_SESSION['correo'];
        
        // Verificamos que la publicacion no sea del mismo usuario
        $consulta_verificar = "
            SELECT CORREO_U 
            FROM PUBLICACION1 
            WHERE ID_P = '$id_publicacion'
        ";
        $resultado_verificar = mysqli_query($conexion, $consulta_verificar);
        
        if($resultado_verificar && mysqli_num_rows($resultado_verificar) > 0) {
            $fila = mysqli_fetch_assoc($resultado_verificar);
            
            if($fila['CORREO_U'] == $correo_usuario) {
                // No puede solicitar intercambio de su propia publicacion
                header("Location: buscar.php?error=No puedes solicitar tu propia publicación");
                exit();
            }
            
            // Verificamos que no exista ya una solicitud
            $consulta_existe = "
                SELECT * 
                FROM SOLICITA 
                WHERE CORREO_U = '$correo_usuario' AND ID_P = '$id_publicacion'
            ";
            $resultado_existe = mysqli_query($conexion, $consulta_existe);
            
            if($resultado_existe && mysqli_num_rows($resultado_existe) > 0) {
                header("Location: buscar.php?error=Ya has solicitado este intercambio");
                exit();
            }
            
            // Insertamos la solicitud
            $consulta_insertar = "
                INSERT INTO SOLICITA(CORREO_U, ID_P, FECHA, ESTADO)
                VALUES ('$correo_usuario', '$id_publicacion', CURDATE(), 'PENDIENTE')
            ";
            
            if(mysqli_query($conexion, $consulta_insertar)) {
                header("Location: intercambios.php?exito=Solicitud enviada correctamente");
                exit();
            } else {
                header("Location: buscar.php?error=Error al enviar la solicitud");
                exit();
            }
        } else {
            header("Location: buscar.php?error=Publicación no encontrada");
            exit();
        }
    } else {
        header("Location: buscar.php");
        exit();
    }
?>