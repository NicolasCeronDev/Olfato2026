<?php
// pages/admin/ofertas/index.php
require_once '../../../includes/config.php';

if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['es_admin']) {
    header('Location: ' . PAGES_PATH . 'login.php');
    exit();
}

// Procesar eliminación de oferta
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);

    // Primero quitar la oferta de los productos
    $sql_quitar_oferta = "UPDATE productos SET id_oferta = NULL WHERE id_oferta = ?";
    $stmt_quitar = $conexion->prepare($sql_quitar_oferta);
    $stmt_quitar->bind_param("i", $id_eliminar);
    $stmt_quitar->execute();

    // Luego eliminar la oferta
    $sql_eliminar = "DELETE FROM ofertas WHERE id_oferta = ?";
    $stmt = $conexion->prepare($sql_eliminar);
    $stmt->bind_param("i", $id_eliminar);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Oferta eliminada correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la oferta";
        $_SESSION['tipo_mensaje'] = "error";
    }

    header('Location: index.php');
    exit();
}

// Obtener todas las ofertas
$sql = "SELECT o.*, 
               (SELECT COUNT(*) FROM productos WHERE id_oferta = o.id_oferta) as total_productos
        FROM ofertas o 
        ORDER BY o.fecha_inicio DESC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ofertas - Admin Olfato Perfumería</title>
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

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
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
            background: rgba(255, 255, 255, 0.05);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-activa {
            background: #27ae60;
            color: white;
        }

        .badge-expirada {
            background: #e74c3c;
            color: white;
        }

        .badge-futura {
            background: #3498db;
            color: white;
        }

        .actions {
            display: flex;
            gap: 8px;
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

        .oferta-descuento {
            background: var(--color-dorado);
            color: var(--color-fondo);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1rem;
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
                    <img src="/olfato2026/assets/Contenido/Local/LogoSinfondo.png" alt="Olfato Perfumería">
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
                <a href="../pedidos/"><i class="fas fa-shopping-cart"></i> Pedidos</a>
                <a href="../usuarios/"><i class="fas fa-users"></i> Usuarios</a>
                <a href="index.php" class="active"><i class="fas fa-percentage"></i> Ofertas</a>
                <a href="<?php echo PAGES_PATH; ?>mi-cuenta.php"><i class="fas fa-user"></i> Mi Cuenta</a>
            </div>

            <!-- Estadísticas -->
            <?php
            // Obtener estadísticas de ofertas
            $sql_stats = "SELECT 
                COUNT(*) as total_ofertas,
                SUM(CASE WHEN fecha_fin >= CURDATE() AND fecha_inicio <= CURDATE() THEN 1 ELSE 0 END) as ofertas_activas,
                SUM(CASE WHEN fecha_fin < CURDATE() THEN 1 ELSE 0 END) as ofertas_expiradas
                FROM ofertas";
            $stats = $conexion->query($sql_stats)->fetch_assoc();
            ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_ofertas']; ?></div>
                    <div class="stat-label">Total Ofertas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['ofertas_activas']; ?></div>
                    <div class="stat-label">Ofertas Activas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['ofertas_expiradas']; ?></div>
                    <div class="stat-label">Ofertas Expiradas</div>
                </div>
            </div>

            <div class="content-card">
                <div class="page-header">
                    <h1 class="page-title">Gestión de Ofertas</h1>
                    <a href="agregar.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Oferta
                    </a>
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
                                <th>Nombre</th>
                                <th>Descuento</th>
                                <th>Fechas</th>
                                <th>Productos</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado->num_rows > 0): ?>
                                <?php while ($oferta = $resultado->fetch_assoc()):
                                    $fecha_actual = date('Y-m-d');
                                    $fecha_inicio = $oferta['fecha_inicio'];
                                    $fecha_fin = $oferta['fecha_fin'];

                                    if ($fecha_actual < $fecha_inicio) {
                                        $estado = 'futura';
                                        $texto_estado = 'Próxima';
                                    } elseif ($fecha_actual > $fecha_fin) {
                                        $estado = 'expirada';
                                        $texto_estado = 'Expirada';
                                    } else {
                                        $estado = 'activa';
                                        $texto_estado = 'Activa';
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($oferta['nombre_oferta']); ?></strong>
                                            <br>
                                            <small style="color: var(--color-gris-claro);">
                                                <?php echo htmlspecialchars($oferta['descripcion_oferta']); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="oferta-descuento">
                                                <?php echo $oferta['valor_descuento']; ?>% OFF
                                            </div>
                                        </td>
                                        <td>
                                            <small style="color: var(--color-gris-claro);">
                                                Inicio: <?php echo date('d/m/Y', strtotime($oferta['fecha_inicio'])); ?>
                                                <br>
                                                Fin: <?php echo date('d/m/Y', strtotime($oferta['fecha_fin'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <strong><?php echo $oferta['total_productos']; ?></strong> productos
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $estado; ?>">
                                                <?php echo $texto_estado; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <a href="editar.php?id=<?php echo $oferta['id_oferta']; ?>" class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <a href="index.php?eliminar=<?php echo $oferta['id_oferta']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('¿Estás seguro de que quieres eliminar esta oferta?')">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--color-gris-claro);">
                                        <i class="fas fa-percentage" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                        <br>
                                        No hay ofertas registradas
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 30px; color: var(--color-gris-claro);">
                    <p>Total de ofertas: <strong><?php echo $resultado->num_rows; ?></strong></p>
                </div>
            </div>
        </main>
    </div>
</body>

</html>