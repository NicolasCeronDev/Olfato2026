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

// Obtener pedidos del usuario usando id_usuario (CONSULTA MEJORADA)
$sql_pedidos = "SELECT 
                    o.id_orden,
                    o.fecha_orden,
                    o.estado_orden,
                    o.total_orden,
                    o.metodo_pago,
                    o.ciudad,
                    o.barrio,
                    COUNT(do.id_detalle_orden) as total_productos,
                    SUM(do.cantidad) as total_items
                FROM ordenes o
                LEFT JOIN detalles_ordenes do ON o.id_orden = do.id_orden
                WHERE o.id_usuario = ?
                GROUP BY o.id_orden
                ORDER BY o.fecha_orden DESC 
                LIMIT 5";
$stmt_pedidos = $conexion->prepare($sql_pedidos);
$stmt_pedidos->bind_param("i", $usuario['id']);
$stmt_pedidos->execute();
$pedidos = $stmt_pedidos->get_result();

// Contar total de pedidos usando id_usuario
$sql_total_pedidos = "SELECT COUNT(*) as total FROM ordenes WHERE id_usuario = ?";
$stmt_total = $conexion->prepare($sql_total_pedidos);
$stmt_total->bind_param("i", $usuario['id']);
$stmt_total->execute();
$total_pedidos = $stmt_total->get_result()->fetch_assoc()['total'];

// Función para obtener el color del estado
function getColorEstado($estado) {
    switch(strtolower($estado)) {
        case 'pendiente': return '#f39c12';
        case 'enviado': return '#3498db';
        case 'entregado': return '#27ae60';
        case 'cancelado': return '#e74c3c';
        default: return '#95a5a6';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Olfato Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
    <style> 
        .mi-cuenta {
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
        
        .pedido-item {
            background: rgba(255,255,255,0.03);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 1px solid var(--color-borde);
            transition: var(--transicion);
        }
        
        .pedido-item:hover {
            background: rgba(255,255,255,0.05);
            transform: translateY(-5px);
            box-shadow: var(--sombra-caja);
        }
        
        .estado-badge {
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .resumen-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        .resumen-card {
            background: var(--color-gris-oscuro);
            padding: 35px 30px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid var(--color-borde);
            transition: var(--transicion);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .resumen-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--sombra-caja);
        }
        
        .resumen-card.dorado {
            background: var(--color-dorado);
            color: var(--color-fondo);
            border-color: var(--color-dorado);
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

        .info-pedido {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--color-borde);
        }

        .info-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 15px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            transition: var(--transicion);
        }

        .info-item:hover {
            background: rgba(255,255,255,0.08);
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--color-gris-claro);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--color-texto);
        }

        .info-value.dorado {
            color: var(--color-dorado);
        }

        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .pedido-info {
            flex: 1;
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

        .seccion-titulo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--color-dorado);
            margin: 50px 0 30px 0;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--color-dorado);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-titulo {
            font-size: 1.2rem;
            margin: 0 0 20px 0;
            font-weight: 600;
        }

        .card-valor {
            font-size: 2.5rem;
            margin: 0;
            font-weight: bold;
            line-height: 1.2;
        }

        .card-email {
            font-size: 1rem;
            margin: 0;
            word-break: break-word;
        }

        .icono-dorado {
            color: var(--color-dorado);
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
        }

        @media (max-width: 768px) {
            .mi-cuenta {
                padding: 90px 0 30px;
            }
            
            .cuenta-contenido {
                padding: 30px 25px;
            }
            
            .resumen-cards {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .info-pedido {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .pedido-header {
                flex-direction: column;
                gap: 20px;
            }
            
            .titulo-principal {
                font-size: 2rem;
            }
            
            .seccion-titulo {
                font-size: 1.5rem;
            }
            
            .cuenta-grid {
                padding: 0 20px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="mi-cuenta">
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
                    <li><a href="mi-cuenta.php" class="activo"><i class="fas fa-chart-bar"></i> Resumen</a></li>
                    <li><a href="mis-pedidos.php"><i class="fas fa-box"></i> Mis Pedidos</a></li>
                    <li><a href="editar-perfil.php"><i class="fas fa-user"></i> Mis Datos</a></li>
                    <li><a href="productos.php"><i class="fas fa-shopping-bag"></i> Seguir Comprando</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
            
            <!-- Contenido Principal -->
            <div class="cuenta-contenido">
                <h1 class="titulo-principal">Resumen de tu Cuenta</h1>
                <p class="subtitulo">
                    Aquí puedes ver tu actividad reciente y acceder a todas las opciones de tu cuenta.
                </p>
                
                <div class="resumen-cards">
                    <div class="resumen-card dorado">
                        <h3 class="card-titulo"><i class="fas fa-shopping-cart"></i> Pedidos Realizados</h3>
                        <p class="card-valor"><?php echo $total_pedidos; ?></p>
                    </div>
                    
                    <div class="resumen-card">
                        <h3 class="card-titulo" style="color: var(--color-dorado);"><i class="fas fa-calendar-alt"></i> Miembro desde</h3>
                        <p class="card-valor" style="color: var(--color-texto); font-size: 1.5rem;">
                            <?php echo date('d/m/Y', strtotime($datos_usuario['fecha_registro'])); ?>
                        </p>
                    </div>
                    
                    <div class="resumen-card">
                        <h3 class="card-titulo" style="color: var(--color-dorado);"><i class="fas fa-envelope"></i> Email</h3>
                        <p class="card-email" style="color: var(--color-texto);">
                            <?php echo $usuario['email']; ?>
                        </p>
                    </div>
                </div>
                
                <h2 class="seccion-titulo"><i class="fas fa-box"></i> Últimos Pedidos</h2>
                <?php if ($pedidos->num_rows > 0): ?>
                    <?php while($pedido = $pedidos->fetch_assoc()): ?>
                        <div class="pedido-item">
                            <div class="pedido-header">
                                <div class="pedido-info">
                                    <strong style="color: var(--color-texto); font-size: 1.3rem; display: block; margin-bottom: 10px;">
                                        <i class="fas fa-receipt icono-dorado"></i> Pedido #<?php echo $pedido['id_orden']; ?>
                                    </strong>
                                    <p style="color: var(--color-gris-claro); margin: 8px 0; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?>
                                    </p>
                                    <p style="color: var(--color-dorado); font-weight: bold; margin: 10px 0; font-size: 1.2rem; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-dollar-sign"></i> Total: $<?php echo number_format($pedido['total_orden']); ?>
                                    </p>
                                </div>
                                <span class="estado-badge" style="background: <?php echo getColorEstado($pedido['estado_orden']); ?>">
                                    <?php echo ucfirst($pedido['estado_orden']); ?>
                                </span>
                            </div>
                            
                            <div class="info-pedido">
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-cube"></i> Productos</span>
                                    <span class="info-value"><?php echo $pedido['total_productos']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-shopping-cart"></i> Items</span>
                                    <span class="info-value dorado"><?php echo $pedido['total_items']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-credit-card"></i> Pago</span>
                                    <span class="info-value"><?php echo ucfirst($pedido['metodo_pago']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    
                    <div style="text-align: center; margin-top: 40px;">
                        <a href="mis-pedidos.php" class="boton boton-secundario" style="padding: 16px 35px; font-size: 1.1rem;">
                            <i class="fas fa-list mr-2"></i> Ver todos mis pedidos
                        </a>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 70px 20px; color: var(--color-gris-claro);">
                        <div style="font-size: 5rem; margin-bottom: 25px; opacity: 0.7; color: var(--color-dorado);">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <p style="font-size: 1.5rem; margin-bottom: 20px; color: var(--color-texto);">Aún no has realizado pedidos</p>
                        <p style="margin-bottom: 35px; opacity: 0.8; max-width: 400px; margin-left: auto; margin-right: auto; font-size: 1.1rem;">
                            Descubre nuestra exclusiva colección de fragancias y realiza tu primer pedido.
                        </p>
                        <a href="productos.php" class="boton" style="padding: 16px 40px; font-size: 1.1rem;">
                            <i class="fas fa-shopping-bag mr-2"></i> ¡Descubre nuestros productos!
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>