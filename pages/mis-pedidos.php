<?php
// Verificar que el usuario esté logueado
include '../includes/DB/verificar_sesion.php';
include '../includes/DB/conexion_db.php';

$usuario = $_SESSION['usuario'];

// Obtener todos los pedidos del usuario
$sql_pedidos = "SELECT * FROM ordenes WHERE email_cliente = ? ORDER BY fecha_orden DESC";
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
    <title>Mis Pedidos - Olfato Perfumería</title>
    <link rel="stylesheet" href="../assets/css/General.css">
    <style>
        .mis-pedidos {
            padding: 100px 0 50px;
            background: var(--color-gris-oscuro);
            min-height: 100vh;
        }
        
        .pedidos-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .pedido-card {
            background: var(--color-fondo);
            border: 1px solid var(--color-borde);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .estado-pendiente { background: #f39c12; }
        .estado-enviado { background: #3498db; }
        .estado-entregado { background: #27ae60; }
        
        .estado-badge {
            color: white;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="mis-pedidos">
        <div class="pedidos-grid">
            <h1 style="color: var(--color-dorado); margin-bottom: 30px; text-align: center;">Mis Pedidos</h1>
            
            <?php if ($pedidos->num_rows > 0): ?>
                <?php while($pedido = $pedidos->fetch_assoc()): ?>
                    <div class="pedido-card">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                <h3 style="color: var(--color-texto); margin: 0;">Pedido #<?php echo $pedido['id_orden']; ?></h3>
                                <p style="color: var(--color-gris-claro); margin: 5px 0;">
                                    Fecha: <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?>
                                </p>
                            </div>
                            <span class="estado-badge estado-pendiente">
                                <?php echo $pedido['estado_orden']; ?>
                            </span>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <h4 style="color: var(--color-dorado); margin-bottom: 10px;">Información de Envío</h4>
                                <p style="color: var(--color-texto); margin: 5px 0;"><?php echo $pedido['nombre_cliente']; ?></p>
                                <p style="color: var(--color-gris-claro); margin: 5px 0;"><?php echo $pedido['direccion_envio']; ?></p>
                                <p style="color: var(--color-gris-claro); margin: 5px 0;"><?php echo $pedido['ciudad']; ?> - <?php echo $pedido['barrio']; ?></p>
                            </div>
                            
                            <div>
                                <h4 style="color: var(--color-dorado); margin-bottom: 10px;">Detalles del Pedido</h4>
                                <p style="color: var(--color-texto); margin: 5px 0;">
                                    <strong>Total:</strong> $<?php echo number_format($pedido['total_orden']); ?>
                                </p>
                                <p style="color: var(--color-gris-claro); margin: 5px 0;">
                                    <strong>Método de pago:</strong> <?php echo $pedido['metodo_pago']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px; color: var(--color-gris-claro);">
                    <p style="font-size: 1.3rem; margin-bottom: 20px;">Aún no has realizado pedidos.</p>
                    <a href="productos.php" class="boton">Descubrir Productos</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>