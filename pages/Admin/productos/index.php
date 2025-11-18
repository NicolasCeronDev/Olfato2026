<?php
require_once '../admin_componentes.php';
require_once '../../../includes/config.php';

// Procesar eliminación de producto
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);

    // Primero eliminar los precios asociados
    $sql_eliminar_precios = "DELETE FROM precios_tamaños WHERE id_producto = ?";
    $stmt_precios = $conexion->prepare($sql_eliminar_precios);
    $stmt_precios->bind_param("i", $id_eliminar);
    $stmt_precios->execute();

    // Luego eliminar el producto
    $sql_eliminar = "DELETE FROM productos WHERE id_producto = ?";
    $stmt = $conexion->prepare($sql_eliminar);
    $stmt->bind_param("i", $id_eliminar);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Producto eliminado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el producto";
        $_SESSION['tipo_mensaje'] = "error";
    }

    header('Location: /olfato2026/pages/Admin/productos/index.php');
    exit();
}

// Obtener todos los productos con información de categorías y géneros
$sql = "SELECT p.*, g.nombre_genero_perfume, c.nombre_categoria, o.nombre_oferta 
        FROM productos p 
        LEFT JOIN generos_perfumes g ON p.id_genero_perfume = g.id_genero_perfume 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
        LEFT JOIN ofertas o ON p.id_oferta = o.id_oferta
        ORDER BY p.id_producto DESC";
$resultado = $conexion->query($sql);

// Iniciar la página
admin_head('Gestión de Productos - Admin Olfato');
admin_container_start();
admin_sidebar('productos');
admin_content_start();

// Header de la página
admin_header('Gestión de Productos', 'Administra todos los productos de tu perfumería');

// Mostrar mensajes
if (isset($_SESSION['mensaje'])) {
    admin_alert($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<style>
/* Estilos específicos para la tabla de productos (manteniendo los originales) */
.content-card {
    background: var(--color-fondo);
    padding: 30px;
    border-radius: 15px;
    border: 1px solid var(--color-borde);
    margin-bottom: 30px;
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

.product-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
}

.stock-low {
    color: #e74c3c;
    font-weight: 600;
}

.stock-ok {
    color: #27ae60;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn-sm {
    padding: 8px 12px;
    font-size: 0.8rem;
}

.no-products {
    text-align: center;
    padding: 40px;
    color: var(--color-gris-claro);
}

.no-products i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
    color: var(--color-dorado);
}

@media (max-width: 768px) {
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
}
</style>

<div class="content-card">
    <div class="page-header">
        <h1 class="page-title">Gestión de Productos</h1>
        <a href="/olfato2026/pages/Admin/productos/agregar.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Producto
        </a>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Género</th>
                    <th>Stock</th>
                    <th>Oferta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while ($producto = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="/olfato2026/assets/<?php echo $producto['imagen_url']; ?>"
                                    alt="<?php echo $producto['nombre']; ?>"
                                    class="product-image"
                                    onerror="this.src='/olfato2026/assets/Contenido/Local/LogoSinfondo.png'">
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                <br>
                                <small style="color: var(--color-gris-claro);">
                                    ID: <?php echo $producto['id_producto']; ?>
                                </small>
                            </td>
                            <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre_genero_perfume']); ?></td>
                            <td>
                                <span class="<?php echo $producto['stock'] < 10 ? 'stock-low' : 'stock-ok'; ?>">
                                    <?php echo $producto['stock']; ?> unidades
                                </span>
                            </td>
                            <td>
                                <?php echo $producto['nombre_oferta'] ? htmlspecialchars($producto['nombre_oferta']) : 'Sin oferta'; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="/olfato2026/pages/Admin/productos/editar.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="/olfato2026/pages/Admin/productos/index.php?eliminar=<?php echo $producto['id_producto']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-products">
                            <i class="fas fa-box-open"></i>
                            <br>
                            No hay productos registrados
                            <br>
                            <a href="/olfato2026/pages/Admin/productos/agregar.php" class="btn btn-primary" style="margin-top: 15px;">
                                <i class="fas fa-plus"></i> Agregar Primer Producto
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; color: var(--color-gris-claro);">
        <p>Total de productos: <strong><?php echo $resultado->num_rows; ?></strong></p>
    </div>
</div>

<?php
// Finalizar la página
admin_content_end();
admin_container_end();
admin_footer();
?>