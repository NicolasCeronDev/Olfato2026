<?php
// Verificar que el usuario esté logueado
include '../includes/DB/verificar_sesion.php';
include '../includes/DB/conexion_db.php';

$usuario = $_SESSION['usuario'];

// Obtener información del usuario para el sidebar
$sql_usuario = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = $conexion->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuario['id']);
$stmt_usuario->execute();
$datos_usuario = $stmt_usuario->get_result()->fetch_assoc();

// Obtener todos los pedidos del usuario (usando id_usuario)
$sql_pedidos = "SELECT 
                    o.*,
                    COUNT(do.id_detalle_orden) as total_productos,
                    SUM(do.cantidad) as total_items
                FROM ordenes o
                LEFT JOIN detalles_ordenes do ON o.id_orden = do.id_orden
                WHERE o.id_usuario = ?
                GROUP BY o.id_orden
                ORDER BY o.fecha_orden DESC";
$stmt_pedidos = $conexion->prepare($sql_pedidos);
$stmt_pedidos->bind_param("i", $usuario['id']);
$stmt_pedidos->execute();
$pedidos = $stmt_pedidos->get_result();

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
    <title>Mis Pedidos - Olfato Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
    <style>
        .mis-pedidos {
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
        
        .pedido-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--color-borde);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            transition: var(--transicion);
        }
        
        .pedido-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--sombra-caja);
            background: rgba(255,255,255,0.05);
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
        
        .detalles-pedido {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--color-borde);
        }
        
        .producto-mini {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: var(--color-gris-oscuro);
            border-radius: 10px;
            margin-bottom: 12px;
            transition: var(--transicion);
            border: 1px solid var(--color-borde);
        }
        
        .producto-mini:hover {
            background: rgba(255,255,255,0.08);
            transform: translateX(5px);
        }
        
        .producto-mini-imagen {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .producto-mini-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .info-resumen-pedido {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 20px 0;
            padding: 20px;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            border: 1px solid var(--color-borde);
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
            .mis-pedidos {
                padding: 90px 0 30px;
            }
            
            .cuenta-contenido {
                padding: 30px 25px;
            }
            
            .info-resumen-pedido {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .titulo-principal {
                font-size: 2rem;
            }
            
            .cuenta-grid {
                padding: 0 20px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="mis-pedidos">
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
                    <li><a href="mis-pedidos.php" class="activo"><i class="fas fa-box"></i> Mis Pedidos</a></li>
                    <li><a href="editar-perfil.php"><i class="fas fa-user"></i> Mis Datos</a></li>
                    <li><a href="productos.php"><i class="fas fa-shopping-bag"></i> Seguir Comprando</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
            
            <!-- Contenido Principal -->
            <div class="cuenta-contenido">
                <h1 class="titulo-principal">Mis Pedidos</h1>
                <p class="subtitulo">
                    Gestiona y revisa el estado de todos tus pedidos
                </p>
                
                <?php if ($pedidos->num_rows > 0): ?>
                    <?php while($pedido = $pedidos->fetch_assoc()): ?>
                        <?php
                        // Obtener productos de este pedido
                        $sql_productos = "SELECT do.*, p.nombre, p.imagen_url 
                                         FROM detalles_ordenes do 
                                         JOIN productos p ON do.id_producto = p.id_producto 
                                         WHERE do.id_orden = ?";
                        $stmt_productos = $conexion->prepare($sql_productos);
                        $stmt_productos->bind_param("i", $pedido['id_orden']);
                        $stmt_productos->execute();
                        $productos = $stmt_productos->get_result();
                        ?>
                        
                        <div class="pedido-card">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                                <div style="flex: 1;">
                                    <h3 style="color: var(--color-texto); margin: 0 0 10px 0; font-size: 1.4rem; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-receipt icono-dorado"></i> Pedido #<?php echo $pedido['id_orden']; ?>
                                    </h3>
                                    <p style="color: var(--color-gris-claro); margin: 8px 0; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?>
                                    </p>
                                    <p style="color: var(--color-dorado); font-weight: bold; margin: 10px 0; font-size: 1.3rem; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-dollar-sign"></i> Total: $<?php echo number_format($pedido['total_orden']); ?>
                                    </p>
                                </div>
                                <span class="estado-badge" style="background: <?php echo getColorEstado($pedido['estado_orden']); ?>">
                                    <?php echo ucfirst($pedido['estado_orden']); ?>
                                </span>
                            </div>
                            
                            <!-- Resumen del pedido -->
                            <div class="info-resumen-pedido">
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-cube"></i> Productos diferentes</span>
                                    <span class="info-value"><?php echo $pedido['total_productos']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-layer-group"></i> Unidades totales</span>
                                    <span class="info-value dorado"><?php echo $pedido['total_items']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-credit-card"></i> Método de pago</span>
                                    <span class="info-value"><?php echo ucfirst($pedido['metodo_pago']); ?></span>
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 25px;">
                                <div>
                                    <h4 style="color: var(--color-dorado); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-truck"></i> Información de Envío
                                    </h4>
                                    <p style="color: var(--color-texto); margin: 10px 0; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-phone"></i> <strong>Teléfono:</strong> <?php echo $pedido['telefono_cliente']; ?>
                                    </p>
                                    <p style="color: var(--color-gris-claro); margin: 10px 0; font-size: 1rem; display: flex; align-items: flex-start; gap: 8px;">
                                        <i class="fas fa-map-marker-alt" style="margin-top: 2px;"></i> <?php echo $pedido['direccion_envio']; ?>
                                    </p>
                                    <p style="color: var(--color-gris-claro); margin: 10px 0; font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-map-pin"></i> <?php echo $pedido['ciudad']; ?> - <?php echo $pedido['barrio']; ?>
                                    </p>
                                </div>
                                
                                <div>
                                    <h4 style="color: var(--color-dorado); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-file-invoice-dollar"></i> Detalles del Pago
                                    </h4>
                                    <p style="color: var(--color-texto); margin: 10px 0; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-wallet"></i> <strong>Método:</strong> <?php echo ucfirst($pedido['metodo_pago']); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="detalles-pedido">
                                <h4 style="color: var(--color-dorado); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-shopping-cart"></i> Productos (<?php echo $productos->num_rows; ?>)
                                </h4>
                                <?php while($producto = $productos->fetch_assoc()): ?>
                                    <div class="producto-mini">
                                        <div class="producto-mini-imagen">
                                            <img src="../assets/<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>">
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: var(--color-texto); font-size: 1rem;">
                                                <?php echo $producto['nombre']; ?>
                                            </div>
                                            <div style="color: var(--color-gris-claro); font-size: 0.9rem; margin-top: 5px;">
                                                <?php echo $producto['tamaño']; ?> • <?php echo $producto['Color']; ?> • 
                                                Cantidad: <?php echo $producto['cantidad']; ?>
                                            </div>
                                        </div>
                                        <div style="color: var(--color-dorado); font-weight: 600; font-size: 1.1rem;">
                                            $<?php echo number_format($producto['subtotal']); ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            
                            <?php if (!empty($pedido['notas_cliente'])): ?>
                                <div style="margin-top: 25px; padding: 20px; background: rgba(212, 175, 55, 0.1); border-radius: 10px;">
                                    <strong style="color: var(--color-dorado); display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                        <i class="fas fa-sticky-note"></i> Notas:
                                    </strong>
                                    <p style="color: var(--color-texto); margin: 0; line-height: 1.5;"><?php echo $pedido['notas_cliente']; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 70px 20px; color: var(--color-gris-claro);">
                        <div style="font-size: 5rem; margin-bottom: 25px; opacity: 0.7; color: var(--color-dorado);">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <p style="font-size: 1.5rem; margin-bottom: 20px; color: var(--color-texto);">Aún no has realizado pedidos</p>
                        <p style="margin-bottom: 35px; opacity: 0.8; max-width: 400px; margin-left: auto; margin-right: auto; font-size: 1.1rem;">
                            Cuando realices tu primera compra, aparecerá aquí
                        </p>
                        <a href="productos.php" class="boton" style="padding: 16px 40px; font-size: 1.1rem;">
                            <i class="fas fa-shopping-bag mr-2"></i> Descubrir Productos
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>