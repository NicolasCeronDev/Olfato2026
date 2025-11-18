<?php
require_once 'admin_componentes.php';
require_once '../../includes/config.php';

// Obtener estadísticas para el dashboard
$stats = [];

// Total de productos
$sql_productos = "SELECT COUNT(*) as total FROM productos";
$result = $conexion->query($sql_productos);
$stats['total_productos'] = $result ? $result->fetch_assoc()['total'] : 0;

// Total de pedidos
$sql_pedidos = "SELECT COUNT(*) as total FROM ordenes";
$result = $conexion->query($sql_pedidos);
$stats['total_pedidos'] = $result ? $result->fetch_assoc()['total'] : 0;

// Total de usuarios
$sql_usuarios = "SELECT COUNT(*) as total FROM usuarios";
$result = $conexion->query($sql_usuarios);
$stats['total_usuarios'] = $result ? $result->fetch_assoc()['total'] : 0;

// Ventas totales
$sql_ventas = "SELECT SUM(total_orden) as total FROM ordenes WHERE estado_orden = 'entregado'";
$result = $conexion->query($sql_ventas);
$stats['ventas_totales'] = $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;

// Pedidos pendientes
$sql_pendientes = "SELECT COUNT(*) as total FROM ordenes WHERE estado_orden = 'Pendiente'";
$result = $conexion->query($sql_pendientes);
$stats['pedidos_pendientes'] = $result ? $result->fetch_assoc()['total'] : 0;

// Ofertas activas
$sql_ofertas = "SELECT COUNT(*) as total FROM ofertas WHERE activa = 1 AND fecha_fin >= CURDATE()";
$result = $conexion->query($sql_ofertas);
$stats['ofertas_activas'] = $result ? $result->fetch_assoc()['total'] : 0;

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

// Iniciar la página
admin_head('Dashboard - Admin Olfato');
admin_container_start();
admin_sidebar('dashboard');
admin_content_start();

// Header de la página
admin_header('Dashboard', 'Resumen general de tu perfumería');

// Estadísticas
echo '<div class="stats-grid">
    <div class="stat-card dorado">
        <div class="stat-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-number">' . $stats['total_pedidos'] . '</div>
        <div class="stat-label">Total Pedidos</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-cube"></i>
        </div>
        <div class="stat-number">' . $stats['total_productos'] . '</div>
        <div class="stat-label">Productos</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-number">' . $stats['total_usuarios'] . '</div>
        <div class="stat-label">Usuarios</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-number">' . $stats['pedidos_pendientes'] . '</div>
        <div class="stat-label">Pedidos Pendientes</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-number">$' . number_format($stats['ventas_totales'], 0, ',', '.') . '</div>
        <div class="stat-label">Ventas Totales</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-percentage"></i>
        </div>
        <div class="stat-number">' . $stats['ofertas_activas'] . '</div>
        <div class="stat-label">Ofertas Activas</div>
    </div>
</div>';

// Pedidos Recientes
echo '<div class="content-section">
    <h2 class="section-title">
        <i class="fas fa-clock"></i> Pedidos Recientes
    </h2>';
    
if ($pedidos_recientes && $pedidos_recientes->num_rows > 0) {
    echo '<div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
    
    while($pedido = $pedidos_recientes->fetch_assoc()) {
        echo '<tr>
            <td>#' . $pedido['id_orden'] . '</td>
            <td>' . htmlspecialchars($pedido['nombre_cliente']) . '</td>
            <td>' . date('d/m/Y H:i', strtotime($pedido['fecha_orden'])) . '</td>
            <td style="color: var(--color-dorado); font-weight: 600;">
                $' . number_format($pedido['total_orden'], 0, ',', '.') . '
            </td>
            <td>
                <span class="badge ' . strtolower($pedido['estado_orden']) . '">
                    ' . ucfirst($pedido['estado_orden']) . '
                </span>
            </td>
            <td>
                <a href="pedidos/ver.php?id=' . $pedido['id_orden'] . '" class="btn btn-secondary">
                    <i class="fas fa-eye"></i> Ver
                </a>
            </td>
        </tr>';
    }
    
    echo '</tbody></table></div>';
} else {
    echo '<div class="no-data">
        <i class="fas fa-shopping-cart"></i>
        <p>No hay pedidos recientes</p>
    </div>';
}
echo '</div>';

// Productos Bajos en Stock
echo '<div class="content-section">
    <h2 class="section-title">
        <i class="fas fa-exclamation-triangle"></i> Productos con Stock Bajo
    </h2>';
    
if ($stock_bajo && $stock_bajo->num_rows > 0) {
    echo '<div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
    
    while($producto = $stock_bajo->fetch_assoc()) {
        echo '<tr>
            <td>' . htmlspecialchars($producto['nombre']) . '</td>
            <td class="alert-stock">' . $producto['stock'] . ' unidades</td>
            <td>
                <a href="productos/editar.php?id=' . $producto['id_producto'] . '" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Actualizar
                </a>
            </td>
        </tr>';
    }
    
    echo '</tbody></table></div>';
} else {
    echo '<div class="no-data">
        <i class="fas fa-check-circle"></i>
        <p>Todo el stock está en niveles normales</p>
    </div>';
}
echo '</div>';

// Acciones Rápidas
echo '<div class="content-section">
    <h2 class="section-title">
        <i class="fas fa-bolt"></i> Acciones Rápidas
    </h2>
    
    <div class="quick-actions">
        <a href="productos/agregar.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Producto
        </a>
        <a href="pedidos/index.php" class="btn btn-secondary">
            <i class="fas fa-list"></i> Ver Todos los Pedidos
        </a>
        <a href="usuarios/index.php" class="btn btn-secondary">
            <i class="fas fa-user-plus"></i> Gestionar Usuarios
        </a>
        <a href="ofertas/agregar.php" class="btn btn-secondary">
            <i class="fas fa-percentage"></i> Crear Oferta
        </a>
    </div>
</div>';

// Finalizar la página
admin_content_end();
admin_container_end();
admin_footer();
?>