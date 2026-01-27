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

    // Procesamos acciones POST
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Cancelar solicitud enviada
        if(isset($_POST['cancelar_enviada'])) {
            $id_p = mysqli_real_escape_string($conexion, $_POST['id_p']);
            $consulta_cancelar = "
                DELETE FROM SOLICITA 
                WHERE CORREO_U = '{$_SESSION['correo']}' AND ID_P = '$id_p'
            ";
            if(mysqli_query($conexion, $consulta_cancelar)) {
                $exito = 'Solicitud cancelada correctamente.';
            } else {
                $error = 'Error al cancelar la solicitud.';
            }
        }
        
        // Rechazar solicitud recibida
        if(isset($_POST['rechazar_recibida'])) {
            $correo_solicitante = mysqli_real_escape_string($conexion, $_POST['correo_solicitante']);
            $id_p = mysqli_real_escape_string($conexion, $_POST['id_p']);
            $consulta_rechazar = "
                UPDATE SOLICITA 
                SET ESTADO = 'RECHAZADO' 
                WHERE CORREO_U = '$correo_solicitante' AND ID_P = '$id_p'
            ";
            if(mysqli_query($conexion, $consulta_rechazar)) {
                $exito = 'Solicitud rechazada.';
            } else {
                $error = 'Error al rechazar la solicitud.';
            }
        }
        
        // Aceptar solicitud recibida
        if(isset($_POST['aceptar_recibida'])) {
            $correo_solicitante = mysqli_real_escape_string($conexion, $_POST['correo_solicitante']);
            $id_p = mysqli_real_escape_string($conexion, $_POST['id_p']);
            $consulta_aceptar = "
                UPDATE SOLICITA 
                SET ESTADO = 'ACEPTADO', FECHA = CURDATE()
                WHERE CORREO_U = '$correo_solicitante' AND ID_P = '$id_p'
            ";
            if(mysqli_query($conexion, $consulta_aceptar)) {
                $exito = 'Solicitud aceptada. ¡Intercambio confirmado!';
            } else {
                $error = 'Error al aceptar la solicitud.';
            }
        }
        
        // Calificar intercambio
        if(isset($_POST['calificar'])) {
            $id_p = mysqli_real_escape_string($conexion, $_POST['id_p']);
            $calificacion = mysqli_real_escape_string($conexion, $_POST['calificacion']);
            
            $consulta_calificar = "
                UPDATE SOLICITA 
                SET CALIFICACION = '$calificacion' 
                WHERE CORREO_U = '{$_SESSION['correo']}' AND ID_P = '$id_p'
            ";
            if(mysqli_query($conexion, $consulta_calificar)) {
                $exito = 'Calificación enviada correctamente.';
            } else {
                $error = 'Error al enviar la calificación.';
            }
        }
    }

    // Obtener solicitudes ENVIADAS por el usuario
    $solicitudes_enviadas = [];
    $consulta_enviadas = "
        SELECT S.ID_P, S.FECHA, S.ESTADO, P2.NOMBRE_P, U.NICK_U, P1.CORREO_U
        FROM SOLICITA S
        INNER JOIN PUBLICACION1 P1 ON S.ID_P = P1.ID_P
        INNER JOIN PUBLICACION2 P2 ON P1.ID_P = P2.ID_P
        INNER JOIN USUARIO U ON P1.CORREO_U = U.CORREO_U
        WHERE S.CORREO_U = '{$_SESSION['correo']}' AND S.ESTADO = 'PENDIENTE'
        ORDER BY S.FECHA DESC
        LIMIT 6
    ";
    $resultado_enviadas = mysqli_query($conexion, $consulta_enviadas);
    if($resultado_enviadas && mysqli_num_rows($resultado_enviadas) > 0) {
        while($fila = mysqli_fetch_assoc($resultado_enviadas)) {
            $solicitudes_enviadas[] = $fila;
        }
    }

    // Obtener solicitudes RECIBIDAS (solicitudes de otros usuarios sobre publicaciones del usuario actual)
    $solicitudes_recibidas = [];
    $consulta_recibidas = "
        SELECT S.CORREO_U, S.ID_P, S.FECHA, S.ESTADO, P2.NOMBRE_P, U.NICK_U
        FROM SOLICITA S
        INNER JOIN PUBLICACION1 P1 ON S.ID_P = P1.ID_P
        INNER JOIN PUBLICACION2 P2 ON P1.ID_P = P2.ID_P
        INNER JOIN USUARIO U ON S.CORREO_U = U.CORREO_U
        WHERE P1.CORREO_U = '{$_SESSION['correo']}' AND S.ESTADO = 'PENDIENTE'
        ORDER BY S.FECHA DESC
        LIMIT 6
    ";
    $resultado_recibidas = mysqli_query($conexion, $consulta_recibidas);
    if($resultado_recibidas && mysqli_num_rows($resultado_recibidas) > 0) {
        while($fila = mysqli_fetch_assoc($resultado_recibidas)) {
            $solicitudes_recibidas[] = $fila;
        }
    }

    // Obtener intercambios COMPLETADOS (aceptados)
    $intercambios_completados = [];
    $consulta_completados = "
        SELECT S.ID_P, S.FECHA, S.CALIFICACION, P2.NOMBRE_P, U.NICK_U, P1.CORREO_U
        FROM SOLICITA S
        INNER JOIN PUBLICACION1 P1 ON S.ID_P = P1.ID_P
        INNER JOIN PUBLICACION2 P2 ON P1.ID_P = P2.ID_P
        INNER JOIN USUARIO U ON P1.CORREO_U = U.CORREO_U
        WHERE S.CORREO_U = '{$_SESSION['correo']}' AND S.ESTADO = 'ACEPTADO'
        ORDER BY S.FECHA DESC
        LIMIT 6
    ";
    $resultado_completados = mysqli_query($conexion, $consulta_completados);
    if($resultado_completados && mysqli_num_rows($resultado_completados) > 0) {
        while($fila = mysqli_fetch_assoc($resultado_completados)) {
            $intercambios_completados[] = $fila;
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Usuario Normal (Intercambios) - DevSwap.</title>
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

            <h3>Gestiona tus intercambios</h3>
            
            <div class="impar">
                <h3>Solicitudes Enviadas (<?php echo count($solicitudes_enviadas); ?>)</h3>
                <?php if(count($solicitudes_enviadas) > 0): ?>
                    <?php foreach($solicitudes_enviadas as $solicitud): ?>
                        <div class="tarjeta">
                            <h3><?php echo htmlspecialchars($solicitud['NOMBRE_P']); ?></h3>
                            <p>Propietario: <?php echo htmlspecialchars($solicitud['NICK_U']); ?></p>
                            <p>Estado: <?php echo htmlspecialchars($solicitud['ESTADO']); ?></p>
                            <p><?php echo htmlspecialchars($solicitud['FECHA'] ?? 'Sin fecha'); ?></p>
                            <a href="#" onclick="cancelarEnviada('<?php echo htmlspecialchars($solicitud['ID_P']); ?>'); return false;">Cancelar</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No tienes solicitudes enviadas pendientes.</p>
                <?php endif; ?>
            </div>

            <div class="par">
                <h3>Solicitudes Recibidas (<?php echo count($solicitudes_recibidas); ?>)</h3>
                <?php if(count($solicitudes_recibidas) > 0): ?>
                    <?php foreach($solicitudes_recibidas as $solicitud): ?>
                        <div class="tarjeta">
                            <h3><?php echo htmlspecialchars($solicitud['NOMBRE_P']); ?></h3>
                            <p>Solicitante: <?php echo htmlspecialchars($solicitud['NICK_U']); ?></p>
                            <p>Estado: <?php echo htmlspecialchars($solicitud['ESTADO']); ?></p>
                            <p><?php echo htmlspecialchars($solicitud['FECHA'] ?? 'Sin fecha'); ?></p>
                            <a href="#" onclick="rechazarRecibida('<?php echo htmlspecialchars($solicitud['CORREO_U']); ?>', '<?php echo htmlspecialchars($solicitud['ID_P']); ?>'); return false;">Rechazar</a>
                            <a href="#" onclick="aceptarRecibida('<?php echo htmlspecialchars($solicitud['CORREO_U']); ?>', '<?php echo htmlspecialchars($solicitud['ID_P']); ?>'); return false;">Aceptar</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No tienes solicitudes recibidas pendientes.</p>
                <?php endif; ?>
            </div>

            <div class="impar">
                <h3>Intercambios Completados (<?php echo count($intercambios_completados); ?>)</h3>
                <?php if(count($intercambios_completados) > 0): ?>
                    <?php foreach($intercambios_completados as $intercambio): ?>
                        <div class="tarjeta">
                            <h3><?php echo htmlspecialchars($intercambio['NOMBRE_P']); ?></h3>
                            <p>Propietario: <?php echo htmlspecialchars($intercambio['NICK_U']); ?></p>
                            <p><?php echo htmlspecialchars($intercambio['FECHA']); ?></p>
                            <p class="Completado">Completado</p>
                            <?php if($intercambio['CALIFICACION']): ?>
                                <p>Calificación: <?php echo htmlspecialchars($intercambio['CALIFICACION']); ?> ⭐</p>
                            <?php else: ?>
                                <a href="#" onclick="mostrarCalificar('<?php echo htmlspecialchars($intercambio['ID_P']); ?>'); return false;">Calificar</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No tienes intercambios completados aún.</p>
                <?php endif; ?>
            </div>
        </main>

        <footer>
            <strong>DevSwap</strong><br>
            <hr>
            <strong>Plataforma de intercambio entre desarrolladores</strong><br>
            <hr>
            <strong>DevSwap - 2026</strong>
        </footer>

        <script>
            function cancelarEnviada(id_p) {
                if(confirm('¿Estás seguro de cancelar esta solicitud?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'intercambios.php';
                    
                    const inputAccion = document.createElement('input');
                    inputAccion.type = 'hidden';
                    inputAccion.name = 'cancelar_enviada';
                    inputAccion.value = '1';
                    
                    const inputIdP = document.createElement('input');
                    inputIdP.type = 'hidden';
                    inputIdP.name = 'id_p';
                    inputIdP.value = id_p;
                    
                    form.appendChild(inputAccion);
                    form.appendChild(inputIdP);
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function rechazarRecibida(correo_solicitante, id_p) {
                if(confirm('¿Estás seguro de rechazar esta solicitud?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'intercambios.php';
                    
                    const inputAccion = document.createElement('input');
                    inputAccion.type = 'hidden';
                    inputAccion.name = 'rechazar_recibida';
                    inputAccion.value = '1';
                    
                    const inputCorreo = document.createElement('input');
                    inputCorreo.type = 'hidden';
                    inputCorreo.name = 'correo_solicitante';
                    inputCorreo.value = correo_solicitante;
                    
                    const inputIdP = document.createElement('input');
                    inputIdP.type = 'hidden';
                    inputIdP.name = 'id_p';
                    inputIdP.value = id_p;
                    
                    form.appendChild(inputAccion);
                    form.appendChild(inputCorreo);
                    form.appendChild(inputIdP);
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function aceptarRecibida(correo_solicitante, id_p) {
                if(confirm('¿Deseas aceptar este intercambio?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'intercambios.php';
                    
                    const inputAccion = document.createElement('input');
                    inputAccion.type = 'hidden';
                    inputAccion.name = 'aceptar_recibida';
                    inputAccion.value = '1';
                    
                    const inputCorreo = document.createElement('input');
                    inputCorreo.type = 'hidden';
                    inputCorreo.name = 'correo_solicitante';
                    inputCorreo.value = correo_solicitante;
                    
                    const inputIdP = document.createElement('input');
                    inputIdP.type = 'hidden';
                    inputIdP.name = 'id_p';
                    inputIdP.value = id_p;
                    
                    form.appendChild(inputAccion);
                    form.appendChild(inputCorreo);
                    form.appendChild(inputIdP);
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function mostrarCalificar(id_p) {
                const calificacion = prompt('Califica este intercambio del 1 al 5:');
                if(calificacion !== null && calificacion >= 1 && calificacion <= 5) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'intercambios.php';
                    
                    const inputAccion = document.createElement('input');
                    inputAccion.type = 'hidden';
                    inputAccion.name = 'calificar';
                    inputAccion.value = '1';
                    
                    const inputIdP = document.createElement('input');
                    inputIdP.type = 'hidden';
                    inputIdP.name = 'id_p';
                    inputIdP.value = id_p;
                    
                    const inputCalificacion = document.createElement('input');
                    inputCalificacion.type = 'hidden';
                    inputCalificacion.name = 'calificacion';
                    inputCalificacion.value = calificacion;
                    
                    form.appendChild(inputAccion);
                    form.appendChild(inputIdP);
                    form.appendChild(inputCalificacion);
                    document.body.appendChild(form);
                    form.submit();
                } else if(calificacion !== null) {
                    alert('Por favor, introduce un número entre 1 y 5.');
                }
            }
        </script>
    </body>
</html>