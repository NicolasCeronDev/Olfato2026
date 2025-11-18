<?php
require_once '../admin_componentes.php';
require_once '../../../includes/config.php';

// Procesar cambios
if (isset($_POST['cambiar_rol'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $nuevo_rol = intval($_POST['es_administrador']);
    
    $sql = "UPDATE usuarios SET es_administrador = ? WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt->bind_param("ii", $nuevo_rol, $id_usuario) && $stmt->execute()) {
        $mensaje = "Rol de usuario actualizado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar el rol";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de usuarios
$sql = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
$usuarios = $conexion->query($sql);

// Iniciar la página
admin_head('Usuarios - Admin Olfato');
admin_container_start();
admin_sidebar('usuarios');
admin_content_start();

// Header de la página
admin_header('Gestión de Usuarios', 'Administra los usuarios de tu perfumería');

// Mostrar mensajes
if (isset($mensaje)) {
    admin_alert($mensaje, $tipo_mensaje);
}

// Estadísticas
$sql_estadisticas = "SELECT 
    COUNT(*) as total,
    SUM(es_administrador) as administradores
    FROM usuarios";
$estadisticas = $conexion->query($sql_estadisticas)->fetch_assoc();

echo '<div class="stats-grid">
    <div class="stat-card dorado">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-number">' . $estadisticas['total'] . '</div>
        <div class="stat-label">Total Usuarios</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-number">' . $estadisticas['administradores'] . '</div>
        <div class="stat-label">Administradores</div>
    </div>
</div>';

// Tabla de usuarios
echo '<div class="content-section">
    <h2 class="section-title">
        <i class="fas fa-list"></i> Lista de Usuarios
    </h2>';

if ($usuarios && $usuarios->num_rows > 0) {
    echo '<div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Fecha Registro</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
    
    while($usuario = $usuarios->fetch_assoc()) {
        $rol_badge = $usuario['es_administrador'] ? 'activo' : 'inactivo';
        $rol_texto = $usuario['es_administrador'] ? 'Administrador' : 'Cliente';
        
        echo '<tr>
            <td>' . $usuario['id_usuario'] . '</td>
            <td>' . htmlspecialchars($usuario['nombre_completo']) . '</td>
            <td>' . htmlspecialchars($usuario['email']) . '</td>
            <td>' . ($usuario['telefono'] ?: '-') . '</td>
            <td>' . date('d/m/Y', strtotime($usuario['fecha_registro'])) . '</td>
            <td>
                <form method="POST" style="display: flex; gap: 5px; align-items: center;">
                    <input type="hidden" name="id_usuario" value="' . $usuario['id_usuario'] . '">
                    <select name="es_administrador" class="form-control" style="padding: 5px 8px; font-size: 0.8rem; width: 140px;">
                        <option value="0" ' . (!$usuario['es_administrador'] ? 'selected' : '') . '>Cliente</option>
                        <option value="1" ' . ($usuario['es_administrador'] ? 'selected' : '') . '>Administrador</option>
                    </select>
                    <button type="submit" name="cambiar_rol" class="btn btn-primary" style="padding: 5px 8px;">
                        <i class="fas fa-save"></i>
                    </button>
                </form>
            </td>
            <td>
                <div style="display: flex; gap: 5px;">
                    <a href="../mi-cuenta.php" class="btn btn-secondary" title="Ver Perfil">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </td>
        </tr>';
    }
    
    echo '</tbody></table></div>';
} else {
    echo '<div class="no-data">
        <i class="fas fa-users"></i>
        <p>No hay usuarios registrados</p>
    </div>';
}
echo '</div>';

// Finalizar la página
admin_content_end();
admin_container_end();
admin_footer();
?>