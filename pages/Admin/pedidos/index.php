<?php
require_once '../admin_componentes.php';
require_once '../../../includes/config.php';

// Procesar cambio de estado
if (isset($_POST['cambiar_estado'])) {
    $id_orden = intval($_POST['id_orden']);
    $nuevo_estado = $_POST['estado'];
    
    $sql = "UPDATE ordenes SET estado_orden = ? WHERE id_orden = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt->bind_param("si", $nuevo_estado, $id_orden) && $stmt->execute()) {
        $mensaje = "Estado del pedido actualizado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar el estado";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de pedidos
$sql = "SELECT o.*, u.nombre_completo, u.email 
        FROM ordenes o 
        LEFT JOIN usuarios u ON o.id_usuario = u.id_usuario 
        ORDER BY o.fecha_orden DESC";
$pedidos = $conexion->query($sql);

// Iniciar la página
admin_head('Pedidos - Admin Olfato');
admin_container_start();
admin_sidebar('pedidos');
admin_content_start();

// Header de la página
admin_header('Gestión de Pedidos', 'Administra todos los pedidos de tu perfumería');

// Mostrar mensajes
if (isset($mensaje)) {
    admin_alert($mensaje, $tipo_mensaje);
}

// Estadísticas rápidas
$sql_estadisticas = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estado_orden = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estado_orden = 'Enviado' THEN 1 ELSE 0 END) as enviados,
    SUM(CASE WHEN estado_orden = 'Entregado' THEN 1 ELSE 0 END) as entregados
    FROM ordenes";
$estadisticas = $conexion->query($sql_estadisticas)->fetch_assoc();

echo '<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-number">' . $estadisticas['total'] . '</div>
        <div class="stat-label">Total Pedidos</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-number">' . $estadisticas['pendientes'] . '</div>
        <div class="stat-label">Pendientes</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-shipping-fast"></i>
        </div>
        <div class="stat-number">' . $estadisticas['enviados'] . '</div>
        <div class="stat-label">Enviados</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number">' . $estadisticas['entregados'] . '</div>
        <div class="stat-label">Entregados</div>
    </div>
</div>';

// Tabla de pedidos
echo '<div class="content-section">
    <h2 class="section-title">
        <i class="fas fa-list"></i> Todos los Pedidos
    </h2>';

if ($pedidos && $pedidos->num_rows > 0) {
    echo '<div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Método Pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
    
    while($pedido = $pedidos->fetch_assoc()) {
        echo '<tr>
            <td>#' . $pedido['id_orden'] . '</td>
            <td>
                <div>
                    <strong>' . htmlspecialchars($pedido['nombre_cliente']) . '</strong><br>
                    <small style="color: var(--color-gris-claro);">' . $pedido['email'] . '</small>
                </div>
            </td>
            <td>' . date('d/m/Y H:i', strtotime($pedido['fecha_orden'])) . '</td>
            <td style="color: var(--color-dorado); font-weight: 600;">
                $' . number_format($pedido['total_orden'], 0, ',', '.') . '
            </td>
            <td>' . ucfirst($pedido['metodo_pago']) . '</td>
            <td>
                <form method="POST" style="display: flex; gap: 5px; align-items: center;">
                    <input type="hidden" name="id_orden" value="' . $pedido['id_orden'] . '">
                    <select name="estado" class="form-control" style="padding: 5px 8px; font-size: 0.8rem; width: 120px;">
                        <option value="Pendiente" ' . ($pedido['estado_orden'] == 'Pendiente' ? 'selected' : '') . '>Pendiente</option>
                        <option value="Enviado" ' . ($pedido['estado_orden'] == 'Enviado' ? 'selected' : '') . '>Enviado</option>
                        <option value="Entregado" ' . ($pedido['estado_orden'] == 'Entregado' ? 'selected' : '') . '>Entregado</option>
                        <option value="Cancelado" ' . ($pedido['estado_orden'] == 'Cancelado' ? 'selected' : '') . '>Cancelado</option>
                    </select>
                    <button type="submit" name="cambiar_estado" class="btn btn-primary" style="padding: 5px 8px;">
                        <i class="fas fa-save"></i>
                    </button>
                </form>
            </td>
            <td>
                <div style="display: flex; gap: 5px;">
                    <a href="ver.php?id=' . $pedido['id_orden'] . '" class="btn btn-secondary" title="Ver Detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </td>
        </tr>';
    }
    
    echo '</tbody></table></div>';
} else {
    echo '<div class="no-data">
        <i class="fas fa-shopping-cart"></i>
        <p>No hay pedidos registrados</p>
    </div>';
}
echo '</div>';

// Finalizar la página
admin_content_end();
admin_container_end();
admin_footer();
?>