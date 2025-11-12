<?php
// pages/admin/pedidos/ver.php
require_once '../../../includes/config.php';

if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['es_admin']) {
    header('Location: ' . PAGES_PATH . 'login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id_orden = intval($_GET['id']);

// Obtener información del pedido
$sql_pedido = "SELECT o.*, u.nombre_completo, u.email, u.telefono 
               FROM ordenes o 
               LEFT JOIN usuarios u ON o.id_usuario = u.id_usuario 
               WHERE o.id_orden = ?";
$stmt_pedido = $conexion->prepare($sql_pedido);
$stmt_pedido->bind_param("i", $id_orden);
$stmt_pedido->execute();
$pedido = $stmt_pedido->get_result()->fetch_assoc();

if (!$pedido) {
    header('Location: index.php');
    exit();
}

// Obtener detalles del pedido
$sql_detalles = "SELECT do.*, p.nombre, p.imagen_url 
                 FROM detalles_ordenes do 
                 JOIN productos p ON do.id_producto = p.id_producto 
                 WHERE do.id_orden = ?";
$stmt_detalles = $conexion->prepare($sql_detalles);
$stmt_detalles->bind_param("i", $id_orden);
$stmt_detalles->execute();
$detalles = $stmt_detalles->get_result();

// Procesar detalles de pago
$detalles_pago = json_decode($pedido['detalles_pago'] ?? '{}', true);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido #<?php echo $id_orden; ?> - Admin Olfato Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <style>
        :root {
            --color-fondo: #000000;
            --color-texto: #FFFFFF;
            --color-dorado: #D4AF37;
            --color-dorado-oscuro: #B8860B;
            --color-gris-oscuro: #171c2b;
            --color-gris-medio: #2a3049;
            --color-gris-claro: #9da3c6;
            --color-borde: rgba(212, 175, 55, 0.2);
            --sombra-caja: 0 10px 30px rgba(0, 0, 0, 0.5);
            --transicion: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--color-fondo);
            color: var(--color-texto);
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
        }

        .admin-container {
            min-height: 100vh;
            background: var(--color-gris-oscuro);
        }

        .admin-header {
            background: var(--color-fondo);
            border-bottom: 1px solid var(--color-borde);
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .admin-nav {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-logo img {
            height: 40px;
        }

        .admin-logo h1 {
            font-family: 'Playfair Display', serif;
            color: var(--color-dorado);
            font-size: 1.5rem;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-main {
            padding: 100px 20px 50px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--color-dorado);
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--color-dorado);
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transicion);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            font-family: inherit;
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

        .content-card {
            background: var(--color-fondo);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
            margin-bottom: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-section {
            background: var(--color-gris-oscuro);
            padding: 25px;
            border-radius: 10px;
            border: 1px solid var(--color-borde);
        }

        .info-section h3 {
            color: var(--color-dorado);
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
            border-bottom: 1px solid var(--color-dorado);
            padding-bottom: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--color-borde);
        }

        .info-label {
            color: var(--color-gris-claro);
            font-weight: 500;
        }

        .info-value {
            color: var(--color-texto);
            font-weight: 600;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pendiente {
            background: #f39c12;
            color: white;
        }

        .badge-enviado {
            background: #3498db;
            color: white;
        }

        .badge-entregado {
            background: #27ae60;
            color: white;
        }

        .badge-cancelado {
            background: #e74c3c;
            color: white;
        }

        .product-list {
            margin-top: 20px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: var(--color-gris-oscuro);
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid var(--color-borde);
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .product-details {
            color: var(--color-gris-claro);
            font-size: 0.9rem;
        }

        .product-price {
            color: var(--color-dorado);
            font-weight: 600;
            text-align: right;
        }

        .order-total {
            background: var(--color-dorado);
            color: var(--color-fondo);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .nav-links a {
            color: var(--color-texto);
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            transition: var(--transicion);
        }

        .nav-links a:hover {
            background: var(--color-dorado);
            color: var(--color-fondo);
        }

        .nav-links a.active {
            background: var(--color-dorado);
            color: var(--color-fondo);
        }

        @media (max-width: 768px) {
            .admin-main {
                padding: 120px 15px 30px;
            }

            .page-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                flex-wrap: wrap;
            }

            .product-item {
                flex-direction: column;
                text-align: center;
            }

            .product-price {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- Header del Admin -->
        <header class="admin-header">
            <nav class="admin-nav">
                <div class="admin-logo">
                    <img src="/olfato2026/assets/Contenido/Local/LogoSinfondo.png" alt="Olfato Perfumería">
                    <h1>Panel Admin</h1>
                </div>
                <div class="admin-user">
                    <span>Hola, <?php echo $_SESSION['usuario']['nombre']; ?></span>
                    <a href="<?php echo PAGES_PATH; ?>logout.php" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            </nav>
        </header>

        <!-- Contenido Principal -->
        <main class="admin-main">
            <div class="nav-links">
                <a href="../dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="../productos/"><i class="fas fa-cube"></i> Productos</a>
                <a href="index.php" class="active"><i class="fas fa-shopping-cart"></i> Pedidos</a>
                <a href="../usuarios/"><i class="fas fa-users"></i> Usuarios</a>
                <a href="../ofertas/"><i class="fas fa-percentage"></i> Ofertas</a>
                <a href="<?php echo PAGES_PATH; ?>mi-cuenta.php"><i class="fas fa-user"></i> Mi Cuenta</a>
            </div>

            <div class="content-card">
                <div class="page-header">
                    <h1 class="page-title">Pedido #<?php echo $pedido['id_orden']; ?></h1>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Pedidos
                    </a>
                </div>

                <!-- Información del Pedido -->
                <div class="info-grid">
                    <!-- Información del Cliente -->
                    <div class="info-section">
                        <h3><i class="fas fa-user"></i> Información del Cliente</h3>
                        <div class="info-item">
                            <span class="info-label">Nombre:</span>
                            <span class="info-value"><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($pedido['email_cliente']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Teléfono:</span>
                            <span class="info-value"><?php echo htmlspecialchars($pedido['telefono_cliente']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fecha del Pedido:</span>
                            <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Estado:</span>
                            <span class="badge badge-<?php echo strtolower($pedido['estado_orden']); ?>">
                                <?php echo ucfirst($pedido['estado_orden']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Información de Envío -->
                    <div class="info-section">
                        <h3><i class="fas fa-truck"></i> Información de Envío</h3>
                        <div class="info-item">
                            <span class="info-label">Dirección:</span>
                            <span class="info-value"><?php echo htmlspecialchars($pedido['direccion_envio']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ciudad:</span>
                            <span class="info-value"><?php echo htmlspecialchars($pedido['ciudad']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Barrio:</span>
                            <span class="info-value"><?php echo htmlspecialchars($pedido['barrio']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Método de Pago:</span>
                            <span class="info-value"><?php echo ucfirst($pedido['metodo_pago']); ?></span>
                        </div>
                        <?php if (!empty($pedido['notas_cliente'])): ?>
                            <div class="info-item">
                                <span class="info-label">Notas:</span>
                                <span class="info-value"><?php echo htmlspecialchars($pedido['notas_cliente']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Productos del Pedido -->
                <div class="info-section">
                    <h3><i class="fas fa-box"></i> Productos del Pedido</h3>
                    <div class="product-list">
                        <?php if ($detalles->num_rows > 0): ?>
                            <?php while ($producto = $detalles->fetch_assoc()): ?>
                                <div class="product-item">
                                    <img src="<?php echo ASSETS_PATH . $producto['imagen_url']; ?>"
                                        alt="<?php echo $producto['nombre']; ?>"
                                        class="product-image"
                                        onerror="this.src='<?php echo ASSETS_PATH; ?>Contenido/Local/LogoSinfondo.png'">
                                    <div class="product-info">
                                        <div class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></div>
                                        <div class="product-details">
                                            Tamaño: <?php echo $producto['tamaño']; ?> •
                                            Color: <?php echo $producto['Color']; ?> •
                                            Cantidad: <?php echo $producto['cantidad']; ?>
                                        </div>
                                    </div>
                                    <div class="product-price">
                                        $<?php echo number_format($producto['subtotal']); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p style="text-align: center; color: var(--color-gris-claro); padding: 20px;">
                                No hay productos en este pedido.
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Total del Pedido -->
                    <div class="order-total">
                        <div class="total-amount">
                            Total: $<?php echo number_format($pedido['total_orden']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>