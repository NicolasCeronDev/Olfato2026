<?php
include '../includes/DB/conexion_db.php';
include '../includes/DB/verificar_sesion.php';

// Verificar si hay una orden exitosa
if (!isset($_SESSION['orden_exitosa'])) {
    header('Location: productos.php');
    exit;
}

$id_orden = $_SESSION['orden_exitosa'];

// Obtener detalles de la orden
$sql_orden = "SELECT * FROM ordenes WHERE id_orden = ?";
$stmt_orden = $conexion->prepare($sql_orden);
$stmt_orden->bind_param("i", $id_orden);
$stmt_orden->execute();
$orden = $stmt_orden->get_result()->fetch_assoc();

// Obtener detalles de los productos de la orden
$sql_detalles = "SELECT do.*, p.nombre, p.imagen_url 
                 FROM detalles_ordenes do 
                 JOIN productos p ON do.id_producto = p.id_producto 
                 WHERE do.id_orden = ?";
$stmt_detalles = $conexion->prepare($sql_detalles);
$stmt_detalles->bind_param("i", $id_orden);
$stmt_detalles->execute();
$detalles = $stmt_detalles->get_result();

// Limpiar la sesión después de mostrar la confirmación
unset($_SESSION['orden_exitosa']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra - Olfato Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
    <style>
        .confirmacion-compra {
            padding: 120px 0 60px;
            background: var(--color-gris-oscuro);
            min-height: 100vh;
        }
        
        .contenedor-confirmacion {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .tarjeta-confirmacion {
            background: var(--color-fondo);
            padding: 50px;
            border-radius: 20px;
            border: 1px solid var(--color-borde);
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .icono-exito {
            font-size: 4rem;
            color: #27ae60;
            margin-bottom: 20px;
        }
        
        .titulo-confirmacion {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--color-dorado);
            margin-bottom: 15px;
        }
        
        .subtitulo-confirmacion {
            font-size: 1.2rem;
            color: var(--color-texto);
            margin-bottom: 30px;
            opacity: 0.8;
        }
        
        .numero-orden {
            background: var(--color-dorado);
            color: var(--color-fondo);
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 1.3rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 30px;
        }
        
        .info-orden {
            background: var(--color-gris-oscuro);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .info-linea {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--color-borde);
        }
        
        .info-linea:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .productos-lista {
            margin: 30px 0;
        }
        
        .producto-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: var(--color-gris-oscuro);
            border-radius: 10px;
            margin-bottom: 10px;
        }
        
        .producto-imagen {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .producto-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .producto-info {
            flex: 1;
            text-align: left;
        }
        
        .producto-nombre {
            font-weight: 600;
            color: var(--color-texto);
            margin-bottom: 5px;
        }
        
        .producto-detalles {
            color: var(--color-gris-claro);
            font-size: 0.9rem;
        }
        
        .botones-accion {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .confirmacion-compra {
                padding: 100px 0 40px;
            }
            
            .tarjeta-confirmacion {
                padding: 30px 20px;
            }
            
            .titulo-confirmacion {
                font-size: 2rem;
            }
            
            .botones-accion {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="confirmacion-compra">
        <div class="contenedor-confirmacion">
            <div class="tarjeta-confirmacion">
                <div class="icono-exito">
                    <i class="fas fa-check-circle"></i>
                </div>
                
                <h1 class="titulo-confirmacion">¡Compra Exitosa!</h1>
                <p class="subtitulo-confirmacion">Tu pedido ha sido procesado correctamente</p>
                
                <div class="numero-orden">
                    Orden #<?php echo $id_orden; ?>
                </div>
                
                <div class="info-orden">
                    <h3 style="color: var(--color-dorado); margin-bottom: 20px; text-align: center;">Resumen de tu orden</h3>
                    
                    <div class="info-linea">
                        <span><strong>Cliente:</strong></span>
                        <span><?php echo htmlspecialchars($orden['nombre_cliente']); ?></span>
                    </div>
                    
                    <div class="info-linea">
                        <span><strong>Email:</strong></span>
                        <span><?php echo htmlspecialchars($orden['email_cliente']); ?></span>
                    </div>
                    
                    <div class="info-linea">
                        <span><strong>Teléfono:</strong></span>
                        <span><?php echo htmlspecialchars($orden['telefono_cliente']); ?></span>
                    </div>
                    
                    <div class="info-linea">
                        <span><strong>Dirección:</strong></span>
                        <span><?php echo htmlspecialchars($orden['direccion_envio']); ?></span>
                    </div>
                    
                    <div class="info-linea">
                        <span><strong>Ciudad:</strong></span>
                        <span><?php echo htmlspecialchars($orden['ciudad']); ?> - <?php echo htmlspecialchars($orden['barrio']); ?></span>
                    </div>
                    
                    <div class="info-linea">
                        <span><strong>Método de pago:</strong></span>
                        <span><?php echo ucfirst($orden['metodo_pago']); ?></span>
                    </div>
                    
                    <div class="info-linea" style="border-top: 2px solid var(--color-dorado); padding-top: 15px; margin-top: 15px;">
                        <span><strong>Total:</strong></span>
                        <span style="color: var(--color-dorado); font-weight: 700; font-size: 1.2rem;">
                            $<?php echo number_format($orden['total_orden']); ?>
                        </span>
                    </div>
                </div>
                
                <?php if ($detalles->num_rows > 0): ?>
                <div class="productos-lista">
                    <h3 style="color: var(--color-dorado); margin-bottom: 20px;">Productos en tu orden</h3>
                    
                    <?php while($producto = $detalles->fetch_assoc()): ?>
                    <div class="producto-item">
                        <div class="producto-imagen">
                            <img src="../assets/<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>">
                        </div>
                        <div class="producto-info">
                            <div class="producto-nombre"><?php echo $producto['nombre']; ?></div>
                            <div class="producto-detalles">
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
                <?php endif; ?>
                
                <div class="mensaje-entrega" style="background: rgba(212, 175, 55, 0.1); padding: 20px; border-radius: 10px; margin: 25px 0;">
                    <h4 style="color: var(--color-dorado); margin-bottom: 10px;">
                        <i class="fas fa-shipping-fast mr-2"></i>Información de entrega
                    </h4>
                    <p style="color: var(--color-texto); margin: 0;">
                        Tu pedido será procesado y enviado en un plazo de 24-48 horas. 
                        Te contactaremos al número proporcionado para coordinar la entrega.
                    </p>
                </div>
                
                <div class="botones-accion">
                    <a href="productos.php" class="boton boton-secundario">
                        <i class="fas fa-shopping-bag mr-2"></i> Seguir Comprando
                    </a>
                    <a href="mi-cuenta.php" class="boton">
                        <i class="fas fa-user mr-2"></i> Ver Mis Pedidos
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>