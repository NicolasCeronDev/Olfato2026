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

// Obtener pedidos del usuario
$sql_pedidos = "SELECT * FROM ordenes WHERE email_cliente = ? ORDER BY fecha_orden DESC LIMIT 5";
$stmt_pedidos = $conexion->prepare($sql_pedidos);
$stmt_pedidos->bind_param("s", $usuario['email']);
$stmt_pedidos->execute();
$pedidos = $stmt_pedidos->get_result();
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
            border-radius: 10px;
            height: fit-content;
            border: 1px solid var(--color-borde);
        }
        
        .cuenta-menu {
            list-style: none;
            margin-top: 20px;
        }
        
        .cuenta-menu li {
            margin-bottom: 10px;
        }
        
        .cuenta-menu a {
            color: var(--color-texto);
            text-decoration: none;
            padding: 12px 15px;
            display: block;
            border-radius: 5px;
            transition: var(--transicion);
            border: 1px solid transparent;
        }
        
        .cuenta-menu a:hover, .cuenta-menu a.activo {
            background: var(--color-dorado);
            color: var(--color-fondo);
            border-color: var(--color-dorado);
        }
        
        .cuenta-contenido {
            background: var(--color-fondo);
            padding: 30px;
            border-radius: 10px;
            border: 1px solid var(--color-borde);
        }
        
        .pedido-item {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid var(--color-borde);
        }
        
        .estado-pendiente {
            background: #f39c12;
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
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
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--color-borde);
        }
        
        .resumen-card.dorado {
            background: var(--color-dorado);
            color: var(--color-fondo);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="mi-cuenta">
        <div class="cuenta-grid">
            <!-- Sidebar -->
            <div class="cuenta-sidebar">
                <h3 style="color: var(--color-dorado); margin-bottom: 10px;">¡Hola, <?php echo $usuario['nombre']; ?>!</h3>
                <p style="color: var(--color-gris-claro);">Bienvenido a tu cuenta</p>
                
                <ul class="cuenta-menu">
                    <li><a href="mi-cuenta.php" class="activo">📊 Resumen</a></li>
                    <li><a href="mis-pedidos.php">📦 Mis Pedidos</a></li>
                    <li><a href="#">👤 Mis Datos</a></li>
                    <li><a href="productos.php">🛍️ Seguir Comprando</a></li>
                    <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
                </ul>
            </div>
            
            <!-- Contenido Principal -->
            <div class="cuenta-contenido">
                <h2 style="color: var(--color-dorado); margin-bottom: 10px;">Resumen de tu Cuenta</h2>
                <p style="color: var(--color-gris-claro); margin-bottom: 30px;">Aquí puedes ver tu actividad reciente y acceder a todas las opciones de tu cuenta.</p>
                
                <div class="resumen-cards">
                    <div class="resumen-card dorado">
                        <h3>Pedidos Realizados</h3>
                        <p style="font-size: 2.5rem; margin: 10px 0; font-weight: bold;"><?php echo $pedidos->num_rows; ?></p>
                    </div>
                    
                    <div class="resumen-card">
                        <h3>Miembro desde</h3>
                        <p style="font-size: 1.2rem; margin: 10px 0; color: var(--color-dorado);">
                            <?php echo date('d/m/Y', strtotime($datos_usuario['fecha_registro'])); ?>
                        </p>
                    </div>
                </div>
                
                <h3 style="color: var(--color-dorado); margin: 40px 0 20px;">Últimos Pedidos</h3>
                <?php if ($pedidos->num_rows > 0): ?>
                    <?php while($pedido = $pedidos->fetch_assoc()): ?>
                        <div class="pedido-item">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="color: var(--color-texto);">Pedido #<?php echo $pedido['id_orden']; ?></strong>
                                    <p style="color: var(--color-gris-claro); margin: 5px 0;">Fecha: <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?></p>
                                    <p style="color: var(--color-dorado); font-weight: bold; margin: 5px 0;">
                                        Total: $<?php echo number_format($pedido['total_orden']); ?>
                                    </p>
                                </div>
                                <span class="estado-pendiente">
                                    <?php echo $pedido['estado_orden']; ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="mis-pedidos.php" class="boton boton-secundario">Ver todos mis pedidos</a>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: var(--color-gris-claro);">
                        <p style="font-size: 1.2rem; margin-bottom: 15px;">Aún no has realizado pedidos.</p>
                        <a href="../productos.php" class="boton">¡Descubre nuestros productos!</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>