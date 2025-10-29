<?php
// Verificar que el usuario esté logueado
include '../includes/DB/verificar_sesion.php';
include '../includes/DB/conexion_db.php';

$usuario = $_SESSION['usuario'];

// Obtener todos los pedidos del usuario (usando el email de la sesión)
$sql_pedidos = "SELECT * FROM ordenes WHERE email_cliente = ? ORDER BY fecha_orden DESC";
$stmt_pedidos = $conexion->prepare($sql_pedidos);
$stmt_pedidos->bind_param("s", $usuario['email']);
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
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: var(--transicion);
        }
        
        .pedido-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .estado-badge {
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .detalles-pedido {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--color-borde);
        }
        
        .producto-mini {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: var(--color-gris-oscuro);
            border-radius: 8px;
            margin-bottom: 8px;
        }
        
        .producto-mini-imagen {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .producto-mini-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .ver-detalles {
            background: var(--color-dorado);
            color: var(--color-fondo);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transicion);
        }
        
        .ver-detalles:hover {
            background: var(--color-dorado-oscuro);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="mis-pedidos">
        <div class="pedidos-grid">
            <h1 style="color: var(--color-dorado); margin-bottom: 10px; text-align: center;">Mis Pedidos</h1>
            <p style="color: var(--color-gris-claro); text-align: center; margin-bottom: 40px;">
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
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                <h3 style="color: var(--color-texto); margin: 0;">Pedido #<?php echo $pedido['id_orden']; ?></h3>
                                <p style="color: var(--color-gris-claro); margin: 5px 0;">
                                    📅 <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?>
                                </p>
                            </div>
                            <span class="estado-badge" style="background: <?php echo getColorEstado($pedido['estado_orden']); ?>">
                                <?php echo ucfirst($pedido['estado_orden']); ?>
                            </span>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div>
                                <h4 style="color: var(--color-dorado); margin-bottom: 12px;">🚚 Información de Envío</h4>
                                <p style="color: var(--color-texto); margin: 6px 0;">
                                    <strong>📞 Teléfono:</strong> <?php echo $pedido['telefono_cliente']; ?>
                                </p>
                                <p style="color: var(--color-gris-claro); margin: 6px 0; font-size: 0.95rem;">
                                    <?php echo $pedido['direccion_envio']; ?>
                                </p>
                                <p style="color: var(--color-gris-claro); margin: 6px 0; font-size: 0.95rem;">
                                    📍 <?php echo $pedido['ciudad']; ?> - <?php echo $pedido['barrio']; ?>
                                </p>
                            </div>
                            
                            <div>
                                <h4 style="color: var(--color-dorado); margin-bottom: 12px;">💰 Detalles del Pago</h4>
                                <p style="color: var(--color-texto); margin: 6px 0;">
                                    <strong>💳 Método:</strong> <?php echo ucfirst($pedido['metodo_pago']); ?>
                                </p>
                                <p style="color: var(--color-dorado); font-size: 1.3rem; font-weight: bold; margin: 10px 0;">
                                    Total: $<?php echo number_format($pedido['total_orden']); ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="detalles-pedido">
                            <h4 style="color: var(--color-dorado); margin-bottom: 15px;">🛍️ Productos (<?php echo $productos->num_rows; ?>)</h4>
                            <?php while($producto = $productos->fetch_assoc()): ?>
                                <div class="producto-mini">
                                    <div class="producto-mini-imagen">
                                        <img src="../assets/<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>">
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: var(--color-texto);">
                                            <?php echo $producto['nombre']; ?>
                                        </div>
                                        <div style="color: var(--color-gris-claro); font-size: 0.85rem;">
                                            <?php echo $producto['tamaño']; ?> • <?php echo $producto['Color']; ?> • 
                                            Cantidad: <?php echo $producto['cantidad']; ?>
                                        </div>
                                    </div>
                                    <div style="color: var(--color-dorado); font-weight: 600;">
                                        $<?php echo number_format($producto['subtotal']); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        
                        <?php if (!empty($pedido['notas_cliente'])): ?>
                            <div style="margin-top: 15px; padding: 15px; background: rgba(212, 175, 55, 0.1); border-radius: 8px;">
                                <strong style="color: var(--color-dorado);">📝 Notas:</strong>
                                <p style="color: var(--color-texto); margin: 5px 0 0 0;"><?php echo $pedido['notas_cliente']; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 80px 20px; color: var(--color-gris-claro);">
                    <div style="font-size: 4rem; margin-bottom: 20px;">📦</div>
                    <p style="font-size: 1.4rem; margin-bottom: 15px;">Aún no has realizado pedidos</p>
                    <p style="margin-bottom: 30px; opacity: 0.8;">Cuando realices tu primera compra, aparecerá aquí</p>
                    <a href="productos.php" class="boton" style="padding: 12px 30px;">
                        <i class="fas fa-shopping-bag mr-2"></i> Descubrir Productos
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>