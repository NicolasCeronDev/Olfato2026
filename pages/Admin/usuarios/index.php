<?php
// pages/admin/usuarios/index.php
require_once '../../../includes/config.php';

if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['es_admin']) {
    header('Location: ' . PAGES_PATH . 'login.php');
    exit();
}

// Procesar cambio de rol de usuario
if (isset($_POST['cambiar_rol'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $nuevo_rol = intval($_POST['nuevo_rol']);

    $sql = "UPDATE usuarios SET es_administrador = ? WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $nuevo_rol, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Rol de usuario actualizado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el rol";
        $_SESSION['tipo_mensaje'] = "error";
    }

    header('Location: index.php');
    exit();
}

// Procesar eliminación de usuario
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);

    // No permitir eliminar el propio usuario
    if ($id_eliminar != $_SESSION['usuario']['id']) {
        $sql_eliminar = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql_eliminar);
        $stmt->bind_param("i", $id_eliminar);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario eliminado correctamente";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el usuario";
            $_SESSION['tipo_mensaje'] = "error";
        }
    } else {
        $_SESSION['mensaje'] = "No puedes eliminar tu propio usuario";
        $_SESSION['tipo_mensaje'] = "error";
    }

    header('Location: index.php');
    exit();
}

// Obtener todos los usuarios
$sql = "SELECT u.*, 
               (SELECT COUNT(*) FROM ordenes WHERE id_usuario = u.id_usuario) as total_pedidos,
               (SELECT SUM(total_orden) FROM ordenes WHERE id_usuario = u.id_usuario AND estado_orden = 'entregado') as total_gastado
        FROM usuarios u 
        ORDER BY u.fecha_registro DESC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Admin Olfato Perfumería</title>
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

        .badge-admin {
            background: #9b59b6;
            color: white;
        }

        .badge-user {
            background: #3498db;
            color: white;
        }

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

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--color-dorado);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
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
                <a href="index.php" class="active"><i class="fas fa-users"></i> Usuarios</a>
                <a href="../ofertas/"><i class="fas fa-percentage"></i> Ofertas</a>
                <a href="<?php echo PAGES_PATH; ?>mi-cuenta.php"><i class="fas fa-user"></i> Mi Cuenta</a>
            </div>

            <!-- Estadísticas -->
            <?php
            // Obtener estadísticas de usuarios
            $sql_stats = "SELECT 
                COUNT(*) as total_usuarios,
                SUM(es_administrador) as total_admins,
                COUNT(*) - SUM(es_administrador) as total_clientes
                FROM usuarios";
            $stats = $conexion->query($sql_stats)->fetch_assoc();
            ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_usuarios']; ?></div>
                    <div class="stat-label">Total Usuarios</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_admins']; ?></div>
                    <div class="stat-label">Administradores</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_clientes']; ?></div>
                    <div class="stat-label">Clientes</div>
                </div>
            </div>

            <div class="content-card">
                <div class="page-header">
                    <h1 class="page-title">Gestión de Usuarios</h1>
                    <div style="color: var(--color-gris-claro);">
                        Total: <strong><?php echo $resultado->num_rows; ?></strong> usuarios
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
                                <th>Usuario</th>
                                <th>Información</th>
                                <th>Pedidos</th>
                                <th>Total Gastado</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado->num_rows > 0): ?>
                                <?php while ($usuario = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div class="user-avatar">
                                                    <?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($usuario['nombre_completo']); ?></strong>
                                                    <br>
                                                    <small style="color: var(--color-gris-claro);">
                                                        <?php echo htmlspecialchars($usuario['email']); ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small style="color: var(--color-gris-claro);">
                                                Tel: <?php echo $usuario['telefono'] ? htmlspecialchars($usuario['telefono']) : 'No registrado'; ?>
                                                <br>
                                                Reg: <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <strong><?php echo $usuario['total_pedidos']; ?></strong> pedidos
                                        </td>
                                        <td>
                                            <strong>$<?php echo number_format($usuario['total_gastado'] ?? 0); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $usuario['es_administrador'] ? 'admin' : 'user'; ?>">
                                                <?php echo $usuario['es_administrador'] ? 'Administrador' : 'Cliente'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <?php if ($usuario['id_usuario'] != $_SESSION['usuario']['id']): ?>
                                                    <form method="POST" action="" class="form-inline">
                                                        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                                        <select name="nuevo_rol" class="form-select" required>
                                                            <option value="0" <?php echo !$usuario['es_administrador'] ? 'selected' : ''; ?>>Cliente</option>
                                                            <option value="1" <?php echo $usuario['es_administrador'] ? 'selected' : ''; ?>>Administrador</option>
                                                        </select>
                                                        <button type="submit" name="cambiar_rol" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-sync"></i>
                                                        </button>
                                                    </form>
                                                    <a href="index.php?eliminar=<?php echo $usuario['id_usuario']; ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span style="color: var(--color-gris-claro); font-size: 0.8rem;">
                                                        Tu usuario
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--color-gris-claro);">
                                        <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                        <br>
                                        No hay usuarios registrados
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