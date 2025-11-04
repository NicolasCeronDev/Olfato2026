<?php
// Verificar que el usuario esté logueado
include '../includes/DB/verificar_sesion.php';
include '../includes/DB/conexion_db.php';

$usuario = $_SESSION['usuario'];

// Obtener información del usuario
$sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario['id']);
$stmt->execute();
$datos_usuario = $stmt->get_result()->fetch_assoc();

// Procesar actualización de datos
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $ciudad = trim($_POST['ciudad']);
    $barrio = trim($_POST['barrio']);
    
    if (!empty($nombre)) {
        $sql_update = "UPDATE usuarios SET 
                      nombre_completo = ?, 
                      telefono = ?, 
                      direccion = ?, 
                      ciudad = ?, 
                      barrio = ? 
                      WHERE id_usuario = ?";
        
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("sssssi", $nombre, $telefono, $direccion, $ciudad, $barrio, $usuario['id']);
        
        if ($stmt_update->execute()) {
            $mensaje = "Tus datos se han actualizado correctamente";
            $tipo_mensaje = "success";
            
            // Actualizar datos en sesión
            $_SESSION['usuario']['nombre'] = $nombre;
            
            // Recargar datos del usuario
            $datos_usuario = $conexion->query("SELECT * FROM usuarios WHERE id_usuario = " . $usuario['id'])->fetch_assoc();
        } else {
            $mensaje = "Error al actualizar los datos: " . $conexion->error;
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = "El nombre completo es obligatorio";
        $tipo_mensaje = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Datos - Olfato Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
    <style>
        .mis-datos {
            padding: 100px 0 50px;
            background: var(--color-gris-oscuro);
            min-height: 100vh;
        }
        
        .cuenta-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }
        
        .cuenta-sidebar {
            background: var(--color-fondo);
            padding: 35px 30px;
            border-radius: 15px;
            height: fit-content;
            border: 1px solid var(--color-borde);
            position: sticky;
            top: 120px;
        }
        
        .cuenta-menu {
            list-style: none;
            margin-top: 30px;
        }
        
        .cuenta-menu li {
            margin-bottom: 12px;
        }
        
        .cuenta-menu a {
            color: var(--color-texto);
            text-decoration: none;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 12px;
            transition: var(--transicion);
            border: 1px solid transparent;
            font-weight: 500;
            font-size: 1rem;
        }
        
        .cuenta-menu a:hover, .cuenta-menu a.activo {
            background: var(--color-dorado);
            color: var(--color-fondo);
            border-color: var(--color-dorado);
            transform: translateX(8px);
            box-shadow: var(--sombra-caja);
        }
        
        .cuenta-menu i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .cuenta-contenido {
            background: var(--color-fondo);
            padding: 45px 40px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
            min-height: 600px;
        }
        
        .bienvenida-usuario {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--color-borde);
        }
        
        .avatar-usuario {
            width: 80px;
            height: 80px;
            background: var(--color-dorado);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--color-fondo);
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .titulo-principal {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--color-dorado);
            margin-bottom: 15px;
        }
        
        .subtitulo {
            font-size: 1.2rem;
            color: var(--color-gris-claro);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .formulario-datos {
            max-width: 800px;
        }

        .grupo-formulario {
            margin-bottom: 25px;
        }

        .grupo-formulario label {
            display: block;
            color: var(--color-texto);
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .grupo-formulario input {
            width: 100%;
            padding: 14px 16px;
            background: var(--color-gris-oscuro);
            border: 1px solid var(--color-borde);
            border-radius: 10px;
            color: var(--color-texto);
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transicion);
        }

        .grupo-formulario input:focus {
            outline: none;
            border-color: var(--color-dorado);
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
        }

        .grupo-formulario input[readonly] {
            background: var(--color-gris-medio);
            color: var(--color-gris-claro);
            cursor: not-allowed;
        }

        .formulario-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .mensaje {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mensaje.success {
            background: rgba(46, 204, 113, 0.1);
            border: 1px solid #27ae60;
            color: #27ae60;
        }

        .mensaje.error {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }

        .info-adicional {
            background: rgba(255,255,255,0.03);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--color-borde);
            margin-top: 40px;
        }

        .info-adicional h3 {
            color: var(--color-dorado);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
        }

        .info-linea {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--color-borde);
        }

        .info-linea:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--color-gris-claro);
            font-weight: 500;
        }

        .info-valor {
            color: var(--color-texto);
            font-weight: 600;
        }

        .botones-accion {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        @media (max-width: 1200px) {
            .cuenta-grid {
                max-width: 1200px;
                padding: 0 25px;
            }
        }

        @media (max-width: 1024px) {
            .cuenta-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .cuenta-sidebar {
                position: static;
            }
            
            .formulario-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .mis-datos {
                padding: 90px 0 30px;
            }
            
            .cuenta-contenido {
                padding: 30px 25px;
            }
            
            .titulo-principal {
                font-size: 2rem;
            }
            
            .botones-accion {
                flex-direction: column;
            }
            
            .cuenta-grid {
                padding: 0 20px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="mis-datos">
        <div class="cuenta-grid">
            <!-- Sidebar -->
            <div class="cuenta-sidebar">
                <div class="bienvenida-usuario">
                    <div class="avatar-usuario">
                        <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
                    </div>
                    <div>
                        <h3 style="color: var(--color-dorado); margin: 0 0 5px 0; font-size: 1.3rem;">¡Hola, <?php echo $usuario['nombre']; ?>!</h3>
                        <p style="color: var(--color-gris-claro); margin: 0; font-size: 0.95rem;">Bienvenido a tu cuenta</p>
                    </div>
                </div>
                
                <ul class="cuenta-menu">
                    <li><a href="mi-cuenta.php"><i class="fas fa-chart-bar"></i> Resumen</a></li>
                    <li><a href="mis-pedidos.php"><i class="fas fa-box"></i> Mis Pedidos</a></li>
                    <li><a href="editar-perfil.php" class="activo"><i class="fas fa-user"></i> Mis Datos</a></li>
                    <li><a href="productos.php"><i class="fas fa-shopping-bag"></i> Seguir Comprando</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
            
            <!-- Contenido Principal -->
            <div class="cuenta-contenido">
                <h1 class="titulo-principal">Mis Datos Personales</h1>
                <p class="subtitulo">
                    Actualiza tu información personal y de contacto para una mejor experiencia de compra.
                </p>
                
                <?php if (!empty($mensaje)): ?>
                    <div class="mensaje <?php echo $tipo_mensaje; ?>">
                        <i class="fas <?php echo $tipo_mensaje === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="formulario-datos">
                    <div class="grupo-formulario">
                        <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($datos_usuario['email']); ?>" readonly>
                        <small style="color: var(--color-gris-claro); font-size: 0.9rem; margin-top: 5px; display: block;">
                            El correo electrónico no se puede modificar
                        </small>
                    </div>
                    
                    <div class="grupo-formulario">
                        <label for="nombre"><i class="fas fa-user"></i> Nombre Completo *</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($datos_usuario['nombre_completo']); ?>" required>
                    </div>
                    
                    <div class="formulario-grid">
                        <div class="grupo-formulario">
                            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($datos_usuario['telefono'] ?? ''); ?>">
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="ciudad"><i class="fas fa-city"></i> Ciudad</label>
                            <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($datos_usuario['ciudad'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="grupo-formulario">
                        <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección</label>
                        <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($datos_usuario['direccion'] ?? ''); ?>">
                    </div>
                    
                    <div class="grupo-formulario">
                        <label for="barrio"><i class="fas fa-map-pin"></i> Barrio</label>
                        <input type="text" id="barrio" name="barrio" value="<?php echo htmlspecialchars($datos_usuario['barrio'] ?? ''); ?>">
                    </div>
                    
                    <div class="botones-accion">
                        <button type="submit" class="boton" style="padding: 16px 35px; font-size: 1.1rem;">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                        <a href="mi-cuenta.php" class="boton boton-secundario" style="padding: 16px 35px; font-size: 1.1rem;">
                            <i class="fas fa-arrow-left mr-2"></i> Volver al Resumen
                        </a>
                    </div>
                </form>
                
                <div class="info-adicional">
                    <h3><i class="fas fa-info-circle"></i> Información Adicional</h3>
                    
                    <div class="info-linea">
                        <span class="info-label">Miembro desde</span>
                        <span class="info-valor"><?php echo date('d/m/Y', strtotime($datos_usuario['fecha_registro'])); ?></span>
                    </div>
                    
                    <div class="info-linea">
                        <span class="info-label">Total de pedidos</span>
                        <span class="info-valor">
                            <?php 
                            $sql_total = "SELECT COUNT(*) as total FROM ordenes WHERE id_usuario = ?";
                            $stmt_total = $conexion->prepare($sql_total);
                            $stmt_total->bind_param("i", $usuario['id']);
                            $stmt_total->execute();
                            $total_pedidos = $stmt_total->get_result()->fetch_assoc()['total'];
                            echo $total_pedidos;
                            ?>
                        </span>
                    </div>
                    
                    <div class="info-linea">
                        <span class="info-label">Última actualización</span>
                        <span class="info-valor"><?php echo date('d/m/Y H:i'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>