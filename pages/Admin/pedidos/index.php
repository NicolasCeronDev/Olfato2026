<?php
// pages/admin/pedidos/index.php
require_once '../../../includes/config.php';

if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['es_admin']) {
    header('Location: ' . PAGES_PATH . 'login.php');
    exit();
}

// Procesar cambio de estado del pedido
if (isset($_POST['cambiar_estado'])) {
    $id_orden = intval($_POST['id_orden']);
    $nuevo_estado = $_POST['nuevo_estado'];
    
    $sql = "UPDATE ordenes SET estado_orden = ? WHERE id_orden = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $id_orden);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Estado del pedido actualizado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el estado";
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    header('Location: index.php');
    exit();
}

// Obtener todos los pedidos con información de usuarios
$sql = "SELECT o.*, u.nombre_completo, u.email,
               (SELECT COUNT(*) FROM detalles_ordenes WHERE id_orden = o.id_orden) as total_productos,
               (SELECT SUM(cantidad) FROM detalles_ordenes WHERE id_orden = o.id_orden) as total_items
        FROM ordenes o 
        LEFT JOIN usuarios u ON o.id_usuario = u.id_usuario 
        ORDER BY o.fecha_orden DESC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - Admin Olfato Perfumería</title>
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

        .btn-sm {
            padding: 8px 12px;
            font-size: 0.8rem;
        }

        .content-card {
            background: var(--color-fondo);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
            margin-bottom: 30px;
        }

        .table-container {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--color-gris-oscuro);
            border-radius: 10px;
            overflow: hidden;
        }

        .admin-table th,
        .admin-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid var(--color-borde);
        }

        .admin-table th {
            background: var(--color-dorado);
            color: var(--color-fondo);
            font-weight: 600;
        }

        .admin-table tr:hover {
            background: rgba(255,255,255,0.05);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pendiente { background: #f39c12; color: white; }
        .badge-enviado { background: #3498db; color: white; }
        .badge-entregado { background: #27ae60; color: white; }
        .badge-cancelado { background: #e74c3c; color: white; }

        .actions {
            display: flex;
            gap: 8px;
        }

        .form-inline {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .form-select {
            background: var(--color-gris-oscuro);
            border: 1px solid var(--color-borde);
            color: var(--color-texto);
            padding: 6px 10px;
            border-radius: 4px;
            font-family: inherit;
        }

        .mensaje {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--color-fondo);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid var(--color-borde);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--color-dorado);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--color-gris-claro);
            font-size: 0.9rem;
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

            .admin-table {
                font-size: 0.9rem;
            }

            .actions {
                flex-direction: column;
            }

            .form-inline {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                flex-wrap: wrap;
            }

            .stats-grid {
                grid-template-columns: 1fr;
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
                    <img src="<?php echo ASSETS_PATH; ?>Contenido/Local/LogoSinfondo.png" alt="Olfato Perfumería">
                    <h1>Panel Admin</h1>
                </div>
                <div class="admin-user">
                    <span>Hola, <?php echo $_SESSION['usuario']['nombre']; ?></span>
                    <a href="<?php echo PAGES_PATH; ?>logout.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            </nav>
        </header>

        <!-- Navegación -->
        <main class="admin-main">
            <div class="nav-links">
                <a href="../dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="../productos/"><i class="fas fa-cube"></i> Productos</a>
                <a href="index.php" class="active"><i class="fas fa-shopping-cart"></i> Pedidos</a>
                <a href="../usuarios/"><i class="fas fa-users"></i> Usuarios</a>
                <a href="../ofertas/"><i class="fas fa-percentage"></i> Ofertas</a>
                <a href="<?php echo PAGES_PATH; ?>mi-cuenta.php"><i class="fas fa-user"></i> Mi Cuenta</a>
            </div>

            <!-- Estadísticas -->
            <?php
            // Obtener estadísticas de pedidos
            $sql_stats = "SELECT 
                COUNT(*) as total_pedidos,
                SUM(CASE WHEN estado_orden = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado_orden = 'enviado' THEN 1 ELSE 0 END) as enviados,
                SUM(CASE WHEN estado_orden = 'entregado' THEN 1 ELSE 0 END) as entregados,
                SUM(total_orden) as ventas_totales
                FROM ordenes";
            $stats = $conexion->query($sql_stats)->fetch_assoc();
            ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_pedidos']; ?></div>
                    <div class="stat-label">Total Pedidos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['pendientes']; ?></div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['enviados']; ?></div>
                    <div class="stat-label">Enviados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">$<?php echo number_format($stats['ventas_totales'] ?? 0); ?></div>
                    <div class="stat-label">Ventas Totales</div>
                </div>
            </div>

            <div class="content-card">
                <div class="page-header">
                    <h1 class="page-title">Gestión de Pedidos</h1>
                    <div style="color: var(--color-gris-claro);">
                        Total: <strong><?php echo $resultado->num_rows; ?></strong> pedidos
                    </div>
                </div>
                
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="mensaje <?php echo $_SESSION['tipo_mensaje']; ?>">
                        <i class="fas fa-<?php echo $_SESSION['tipo_mensaje'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                        <?php echo $_SESSION['mensaje']; ?>
                    </div>
                    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
                <?php endif; ?>
                
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Productos</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado->num_rows > 0): ?>
                                <?php while($pedido = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <strong>#<?php echo $pedido['id_orden']; ?></strong>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></strong>
                                        <br>
                                        <small style="color: var(--color-gris-claro);">
                                            <?php echo htmlspecialchars($pedido['email']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_orden'])); ?>
                                    </td>
                                    <td>
                                        <strong>$<?php echo number_format($pedido['total_orden']); ?></strong>
                                        <br>
                                        <small style="color: var(--color-gris-claro);">
                                            <?php echo ucfirst($pedido['metodo_pago']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php echo $pedido['total_productos']; ?> productos
                                        <br>
                                        <small style="color: var(--color-gris-claro);">
                                            <?php echo $pedido['total_items']; ?> items
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($pedido['estado_orden']); ?>">
                                            <?php echo ucfirst($pedido['estado_orden']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="ver.php?id=<?php echo $pedido['id_orden']; ?>" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <form method="POST" action="" class="form-inline">
                                                <input type="hidden" name="id_orden" value="<?php echo $pedido['id_orden']; ?>">
                                                <select name="nuevo_estado" class="form-select" required>
                                                    <option value="pendiente" <?php echo $pedido['estado_orden'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                                    <option value="enviado" <?php echo $pedido['estado_orden'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                                    <option value="entregado" <?php echo $pedido['estado_orden'] == 'entregado' ? 'selected' : ''; ?>>Entregado</option>
                                                    <option value="cancelado" <?php echo $pedido['estado_orden'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                                </select>
                                                <button type="submit" name="cambiar_estado" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--color-gris-claro);">
                                        <i class="fas fa-shopping-cart" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                        <br>
                                        No hay pedidos registrados
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>