<?php
// Verificar que sea administrador
session_start();
require_once '../../includes/config.php';

if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['es_admin']) {
    header('Location: ../login.php');
    exit();
}

// Obtener estadísticas para el dashboard
$stats = [];

// Total de productos
$sql_productos = "SELECT COUNT(*) as total FROM productos";
$result = $conexion->query($sql_productos);
$stats['total_productos'] = $result->fetch_assoc()['total'];

// Total de pedidos
$sql_pedidos = "SELECT COUNT(*) as total FROM ordenes";
$result = $conexion->query($sql_pedidos);
$stats['total_pedidos'] = $result->fetch_assoc()['total'];

// Total de usuarios
$sql_usuarios = "SELECT COUNT(*) as total FROM usuarios";
$result = $conexion->query($sql_usuarios);
$stats['total_usuarios'] = $result->fetch_assoc()['total'];

// Ventas totales
$sql_ventas = "SELECT SUM(total_orden) as total FROM ordenes WHERE estado_orden = 'entregado'";
$result = $conexion->query($sql_ventas);
$stats['ventas_totales'] = $result->fetch_assoc()['total'] ?? 0;

// Pedidos recientes
$sql_pedidos_recientes = "SELECT o.*, u.nombre_completo 
                         FROM ordenes o 
                         JOIN usuarios u ON o.id_usuario = u.id_usuario 
                         ORDER BY o.fecha_orden DESC 
                         LIMIT 5";
$pedidos_recientes = $conexion->query($sql_pedidos_recientes);

// Productos bajos en stock
$sql_stock_bajo = "SELECT * FROM productos WHERE stock < 10 ORDER BY stock ASC LIMIT 5";
$stock_bajo = $conexion->query($sql_stock_bajo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Olfato Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../../assets/css/General.css">
    <style>
        .admin-dashboard {
            padding: 100px 0 50px;
            background: var(--color-gris-oscuro);
            min-height: 100vh;
        }
        
        .admin-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .admin-sidebar {
            background: var(--color-fondo);
            padding: 30px 25px;
            border-radius: 15px;
            height: fit-content;
            border: 1px solid var(--color-borde);
            position: sticky;
            top: 100px;
        }
        
        .admin-menu {
            list-style: none;
            margin-top: 30px;
        }
        
        .admin-menu li {
            margin-bottom: 10px;
        }
        
        .admin-menu a {
            color: var(--color-texto);
            text-decoration: none;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 10px;
            transition: var(--transicion);
            border: 1px solid transparent;
            font-weight: 500;
        }
        
        .admin-menu a:hover, .admin-menu a.activo {
            background: var(--color-dorado);
            color: var(--color-fondo);
            border-color: var(--color-dorado);
            transform: translateX(5px);
        }
        
        .admin-contenido {
            background: var(--color-fondo);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: var(--color-gris-oscuro);
            padding: 30px 25px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--color-borde);
            transition: var(--transicion);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--sombra-caja);
        }
        
        .stat-card.dorado {
            background: var(--color-dorado);
            color: var(--color-fondo);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 2.2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .seccion-admin {
            margin-bottom: 40px;
        }
        
        .titulo-seccion-admin {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--color-dorado);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--color-dorado);
        }
        
        .tabla-admin {
            width: 100%;
            border-collapse: collapse;
            background: var(--color-gris-oscuro);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .tabla-admin th,
        .tabla-admin td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid var(--color-borde);
        }
        
        .tabla-admin th {
            background: var(--color-dorado);
            color: var(--color-fondo);
            font-weight: 600;
        }
        
        .tabla-admin tr:hover {
            background: rgba(255,255,255,0.05);
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge.pendiente { background: #f39c12; color: white; }
        .badge.enviado { background: #3498db; color: white; }
        .badge.entregado { background: #27ae60; color: white; }
        .badge.cancelado { background: #e74c3c; color: white; }
        
        .btn-admin {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transicion);
            display: inline-block;
        }
        
        .btn-primary {
            background: var(--color-dorado);
            color: var(--color-fondo);
        }
        
        .btn-primary:hover {
            background: var(--color-dorado-oscuro);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--color-dorado);
            border: 1px solid var(--color-dorado);
        }
        
        .btn-secondary:hover {
            background: var(--color-dorado);
            color: var(--color-fondo);
        }
        
        .alerta-stock {
            color: #e74c3c;
            font-weight: 600;
        }
        
        @media (max-width: 1024px) {
            .admin-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-sidebar {
                position: static;
            }
        }
        
        @media (max-width: 768px) {
            .admin-dashboard {
                padding: 90px 0 30px;
            }
            
            .admin-contenido {
                padding: 25px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .tabla-admin {
                font-size: 0.9rem;
            }
            
            .tabla-admin th,
            .tabla-admin td {
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    
    <section class="admin-dashboard">
        <div class="admin-grid">
            <!-- Sidebar del Admin -->
            <div class="admin-sidebar">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h2 style="color: var(--color-dorado); margin: 0 0 10px 0;">Panel Admin</h2>
                    <p style="color: var(--color-gris-claro); margin: 0; font-size: 0.9rem;">
                        <?php echo $_SESSION['usuario']['nombre']; ?>
                    </p>
                </div>
                
                <ul class="admin-menu">
                    <li><a href="dashboard.php" class="activo"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="productos/"><i class="fas fa-cube"></i> Productos</a></li>
                    <li><a href="pedidos/"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                    <li><a href="usuarios/"><i class="fas fa-users"></i> Usuarios</a></li>
                    <li><a href="ofertas/"><i class="fas fa-percentage"></i> Ofertas</a></li>
                    <li><a href="../mi-cuenta.php"><i class="fas fa-user"></i> Mi Cuenta</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
            
            <!-- Contenido Principal -->
            <div class="admin-contenido">
                <h1 style="font-family: 'Playfair Display', serif; color: var(--color-dorado); margin-bottom: 10px;">
                    Dashboard
                </h1>
                <p style="color: var(--color-gris-claro); margin-bottom: 30px;">
                    Resumen general de tu perfumería
                </p>
                
                <!-- Estadísticas -->
                <div class="stats-grid">
                    <div class="stat-card dorado">
                        <div class="stat-icon">
                            <i class="fas fa-cube"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['total_productos']; ?></div>
                        <div class="stat-label">Productos Totales</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--color-dorado);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['total_pedidos']; ?></div>
                        <div class="stat-label">Pedidos Totales</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--color-dorado);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['total_usuarios']; ?></div>
                        <div class="stat-label">Usuarios Registrados</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--color-dorado);">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-number">$<?php echo number_format($stats['ventas_totales']); ?></div>
                        <div class="stat-label">Ventas Totales</div>
                    </div>
                </div>
                
                <!-- Pedidos Recientes -->
                <div class="seccion-admin">
                    <h2 class="titulo-seccion-admin">
                        <i class="fas fa-clock mr-2"></i> Pedidos Recientes
                    </h2>
                    
                    <?php if ($pedidos_recientes->num_rows > 0): ?>
                        <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($pedido = $pedidos_recientes->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $pedido['id_orden']; ?></td>
                                    <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?></td>
                                    <td>$<?php echo number_format($pedido['total_orden']); ?></td>
                                    <td>
                                        <span class="badge <?php echo strtolower($pedido['estado_orden']); ?>">
                                            <?php echo ucfirst($pedido['estado_orden']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="pedidos/ver.php?id=<?php echo $pedido['id_orden']; ?>" class="btn-admin btn-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="color: var(--color-gris-claro); text-align: center; padding: 40px;">
                            No hay pedidos recientes.
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Productos Bajos en Stock -->
                <div class="seccion-admin">
                    <h2 class="titulo-seccion-admin">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Stock Bajo
                    </h2>
                    
                    <?php if ($stock_bajo->num_rows > 0): ?>
                        <table class="tabla-admin">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Categoría</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($producto = $stock_bajo->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td class="alerta-stock"><?php echo $producto['stock']; ?> unidades</td>
                                    <td><?php echo $producto['id_categoria']; ?></td>
                                    <td>
                                        <a href="productos/editar.php?id=<?php echo $producto['id_producto']; ?>" class="btn-admin btn-primary">
                                            <i class="fas fa-edit"></i> Actualizar
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="color: var(--color-gris-claro); text-align: center; padding: 40px;">
                            Todo el stock está en niveles normales.
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Acciones Rápidas -->
                <div class="seccion-admin">
                    <h2 class="titulo-seccion-admin">
                        <i class="fas fa-bolt mr-2"></i> Acciones Rápidas
                    </h2>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <a href="productos/agregar.php" class="btn-admin btn-primary" style="text-align: center;">
                            <i class="fas fa-plus mr-2"></i> Agregar Producto
                        </a>
                        <a href="pedidos/" class="btn-admin btn-secondary" style="text-align: center;">
                            <i class="fas fa-list mr-2"></i> Ver Todos los Pedidos
                        </a>
                        <a href="usuarios/" class="btn-admin btn-secondary" style="text-align: center;">
                            <i class="fas fa-user-plus mr-2"></i> Gestionar Usuarios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include '../../includes/footer.php'; ?>
</body>
</html>