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
$stmt_pedidos->bind_param("i", $usuario['id']); // Cambiado a id_usuario
$stmt_pedidos->execute();
$pedidos = $stmt_pedidos->get_result();

// Contar total de pedidos usando id_usuario
$sql_total_pedidos = "SELECT COUNT(*) as total FROM ordenes WHERE id_usuario = ?";
$stmt_total = $conexion->prepare($sql_total_pedidos);
$stmt_total->bind_param("i", $usuario['id']); // Cambiado a id_usuario
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
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .cuenta-sidebar {
            background: var(--color-fondo);
            padding: 30px;
            border-radius: 15px;
            height: fit-content;
            border: 1px solid var(--color-borde);
        }
        
        .cuenta-menu {
            list-style: none;
            margin-top: 25px;
        }
        
        .cuenta-menu li {
            margin-bottom: 8px;
        }
        
        .cuenta-menu a {
            color: var(--color-texto);
            text-decoration: none;
            padding: 12px 15px;
            display: block;
            border-radius: 8px;
            transition: var(--transicion);
            border: 1px solid transparent;
            font-weight: 500;
        }
        
        .cuenta-menu a:hover, .cuenta-menu a.activo {
            background: var(--color-dorado);
            color: var(--color-fondo);
            border-color: var(--color-dorado);
            transform: translateX(5px);
        }
        
        .cuenta-contenido {
            background: var(--color-fondo);
            padding: 35px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
        }
        
        .pedido-item {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 1px solid var(--color-borde);
            transition: var(--transicion);
        }
        
        .pedido-item:hover {
            background: rgba(255,255,255,0.08);
            transform: translateY(-2px);
        }
        
        .estado-badge {
            color: white;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .resumen-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .resumen-card {
            background: var(--color-gris-oscuro);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--color-borde);
            transition: var(--transicion);
        }
        
        .resumen-card:hover {
            transform: translateY(-3px);
        }
        
        .resumen-card.dorado {
            background: var(--color-dorado);
            color: var(--color-fondo);
        }
        
        .bienvenida-usuario {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .avatar-usuario {
            width: 60px;
            height: 60px;
            background: var(--color-dorado);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--color-fondo);
            font-weight: bold;
        }

        .info-pedido {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            font-size: 0.9rem;
            color: var(--color-gris-claro);
        }

        .productos-count {
            background: var(--color-dorado);
            color: var(--color-fondo);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
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
                        <h3 style="color: var(--color-dorado); margin: 0 0 5px 0;">¡Hola, <?php echo $usuario['nombre']; ?>!</h3>
                        <p style="color: var(--color-gris-claro); margin: 0; font-size: 0.9rem;">Bienvenido a tu cuenta</p>
                    </div>
                </div>
                
                <ul class="cuenta-menu">
                    <li><a href="mi-cuenta.php" class="activo">📊 Resumen</a></li>
                    <li><a href="mis-pedidos.php">📦 Mis Pedidos</a></li>
                    <li><a href="editar-perfil.php">👤 Mis Datos</a></li>
                    <li><a href="productos.php">🛍️ Seguir Comprando</a></li>
                    <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
                </ul>
            </div>
            
            <!-- Contenido Principal -->
            <div class="cuenta-contenido">
                <h2 style="color: var(--color-dorado); margin-bottom: 10px;">Resumen de tu Cuenta</h2>
                <p style="color: var(--color-gris-claro); margin-bottom: 30px;">
                    Aquí puedes ver tu actividad reciente y acceder a todas las opciones de tu cuenta.
                </p>
                
                <div class="resumen-cards">
                    <div class="resumen-card dorado">
                        <h3 style="margin: 0 0 10px 0;">Pedidos Realizados</h3>
                        <p style="font-size: 2.5rem; margin: 0; font-weight: bold;"><?php echo $total_pedidos; ?></p>
                    </div>
                    
                    <div class="resumen-card">
                        <h3 style="margin: 0 0 10px 0; color: var(--color-dorado);">Miembro desde</h3>
                        <p style="font-size: 1.3rem; margin: 0; color: var(--color-texto); font-weight: 600;">
                            <?php echo date('d/m/Y', strtotime($datos_usuario['fecha_registro'])); ?>
                        </p>
                    </div>
                    
                    <div class="resumen-card">
                        <h3 style="margin: 0 0 10px 0; color: var(--color-dorado);">Email</h3>
                        <p style="font-size: 1rem; margin: 0; color: var(--color-texto);">
                            <?php echo $usuario['email']; ?>
                        </p>
                    </div>
                </div>
                
                <h3 style="color: var(--color-dorado); margin: 40px 0 20px;">📦 Últimos Pedidos</h3>
                <?php if ($pedidos->num_rows > 0): ?>
                    <?php while($pedido = $pedidos->fetch_assoc()): ?>
                        <div class="pedido-item">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="color: var(--color-texto); font-size: 1.1rem;">
                                        Pedido #<?php echo $pedido['id_orden']; ?>
                                    </strong>
                                    <p style="color: var(--color-gris-claro); margin: 5px 0; font-size: 0.9rem;">
                                        📅 <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?>
                                    </p>
                                    <p style="color: var(--color-dorado); font-weight: bold; margin: 5px 0;">
                                        💰 Total: $<?php echo number_format($pedido['total_orden']); ?>
                                    </p>
                                </div>
                                <span class="estado-badge" style="background: <?php echo getColorEstado($pedido['estado_orden']); ?>">
                                    <?php echo ucfirst($pedido['estado_orden']); ?>
                                </span>
                            </div>
                            <!-- NUEVA INFORMACIÓN: Cantidad de productos e items -->
                            <div class="info-pedido">
                                <span>📦 <?php echo $pedido['total_productos']; ?> productos</span>
                                <span class="productos-count">🛒 <?php echo $pedido['total_items']; ?> items</span>
                                <span>💳 <?php echo ucfirst($pedido['metodo_pago']); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <div style="text-align: center; margin-top: 25px;">
                        <a href="mis-pedidos.php" class="boton boton-secundario">
                            <i class="fas fa-list mr-2"></i> Ver todos mis pedidos
                        </a>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 50px 20px; color: var(--color-gris-claro);">
                        <div style="font-size: 3rem; margin-bottom: 15px;">📦</div>
                        <p style="font-size: 1.2rem; margin-bottom: 15px;">Aún no has realizado pedidos</p>
                        <a href="productos.php" class="boton">
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