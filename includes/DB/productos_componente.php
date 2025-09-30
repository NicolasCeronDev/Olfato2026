<?php
// includes/DB/productos_componente.php - VERSIÓN MEJORADA
include '../includes/DB/conexion_db.php';

function mostrarProductos($categoria = 'todos', $limite = 6, $tipo_seccion = 'normal')
{
    global $conexion;

    // Construir la consulta según el tipo de sección
    $sql = "SELECT p.*, g.nombre_genero_perfume, c.nombre_categoria, o.valor_descuento 
            FROM productos p 
            LEFT JOIN generos_perfumes g ON p.id_genero_perfume = g.id_genero_perfume 
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            LEFT JOIN ofertas o ON p.id_oferta = o.id_oferta";

    $where_conditions = [];
    $params = [];
    $types = "";

    // Filtrar por categoría si no es 'todos'
    if ($categoria !== 'todos') {
        $where_conditions[] = "g.nombre_genero_perfume = ?";
        $params[] = $categoria;
        $types .= "s";
    }

    // MODIFICACIÓN: Filtrar según el tipo de sección
    switch ($tipo_seccion) {
        case 'ofertas':
            $where_conditions[] = "o.valor_descuento > 0";
            break;
        case 'nuevos':
            // Ordenar por ID descendente (los más recientes primero)
            $sql .= " ORDER BY p.id_producto DESC";
            break;
        case 'vendidos':
            // Por ahora ordenar por ID ascendente (los más antiguos)
            $sql .= " ORDER BY p.id_producto ASC";
            break;
        default:
            $sql .= " ORDER BY p.nombre";
    }

    // Aplicar condiciones WHERE si existen
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    // MODIFICACIÓN: Si no se definió orden en el switch, usar orden por defecto
    if ($tipo_seccion === 'ofertas' || $tipo_seccion === 'normal') {
        $sql .= " ORDER BY p.nombre";
    }

    $sql .= " LIMIT ?";
    $params[] = $limite;
    $types .= "i";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo '<div class="productos-grid">';

        while ($producto = $resultado->fetch_assoc()) {
            // Obtener precios del producto
            $precios_sql = "SELECT tamaño, precio FROM precios_tamaños WHERE id_producto = ?";
            $precios_stmt = $conexion->prepare($precios_sql);
            $precios_stmt->bind_param("i", $producto['id_producto']);
            $precios_stmt->execute();
            $precios_resultado = $precios_stmt->get_result();

            $precios = [];
            while ($precio = $precios_resultado->fetch_assoc()) {
                $precios[$precio['tamaño']] = $precio['precio'];
            }

            // Calcular precios con descuento si existe
            $precio_30ml = $precios['30ml'] ?? 0;
            $precio_60ml = $precios['60ml'] ?? 0;
            $precio_100ml = $precios['100ml'] ?? 0;

            $descuento = $producto['valor_descuento'] ?? 0;
            $tiene_descuento = $descuento > 0;

            $precio_100ml_descuento = $tiene_descuento ? $precio_100ml * (1 - $descuento / 100) : $precio_100ml;

            // Mostrar el producto
            echo '
            <div class="producto-tarjeta" data-categoria="' . strtolower($producto['nombre_genero_perfume']) . '">
                <div class="producto-imagen-contenedor">
                    ' . ($tiene_descuento ? '<div class="etiqueta-oferta">-' . $descuento . '%</div>' : '') . '
                    <img src="../assets/' . $producto['imagen_url'] . '" alt="' . $producto['nombre'] . '" class="producto-imagen">
                </div>
                <div class="producto-info">
                    <h3 class="producto-titulo">' . $producto['nombre'] . '</h3>
                    
                    <button class="boton-producto abrir-modal" 
                            data-producto-id="' . $producto['id_producto'] . '"
                            data-producto-nombre="' . $producto['nombre'] . '"
                            data-producto-descripcion="' . htmlspecialchars($producto['descripcion']) . '"
                            data-producto-imagen="' . $producto['imagen_url'] . '"
                            data-precio-30ml="' . $precio_30ml . '"
                            data-precio-60ml="' . $precio_60ml . '"
                            data-precio-100ml="' . $precio_100ml . '"
                            data-precio-100ml-descuento="' . $precio_100ml_descuento . '"
                            data-descuento="' . $descuento . '">
                        AÑADIR AL CARRITO
                    </button>
                </div>
            </div>';

            $precios_stmt->close();
        }

        echo '</div>';
    } else {
        echo '<div class="mensaje-vacio">
                <i class="fas fa-box-open"></i>
                <h3>No hay productos disponibles</h3>
                <p>No encontramos productos en esta categoría.</p>
              </div>';
    }

    $stmt->close();
    
    // MODIFICACIÓN: El modal se incluye aquí para que esté disponible en todas las páginas
    echo '
    <!-- Modal para productos (se carga automáticamente con el componente) -->
    <div class="modal-producto" id="modal-producto">
        <div class="modal-contenido">
            <button class="modal-cerrar" id="modal-cerrar">&times;</button>
            <div class="modal-imagen">
                <img id="modal-imagen" src="" alt="">
            </div>
            <div class="modal-info">
                <h2 id="modal-titulo"></h2>
                <p id="modal-descripcion"></p>
                
                <div class="modal-opciones">
                    <div class="opcion-grupo">
                        <label>Tamaño</label>
                        <div class="tamaños-opciones" id="tamaños-opciones">
                            <!-- Las opciones de tamaño se llenan con JavaScript -->
                        </div>
                    </div>
                    
                    <div class="opcion-grupo">
                        <label>Color</label>
                        <div class="colores-opciones" id="colores-opciones">
                            <div class="color-opcion color-dorado seleccionado" data-color="dorado"></div>
                            <div class="color-opcion color-negro" data-color="negro"></div>
                            <div class="color-opcion color-rojo" data-color="rojo"></div>
                            <div class="color-opcion color-azul" data-color="azul"></div>
                            <div class="color-opcion color-fucsia" data-color="fucsia"></div>
                            <div class="color-opcion color-morado" data-color="morado"></div>
                            <div class="color-opcion color-verde" data-color="verde"></div>
                        </div>
                    </div>
                    
                    <div class="opcion-grupo">
                        <label>Cantidad</label>
                        <div class="cantidad-selector">
                            <button class="cantidad-btn" id="restar-cantidad">-</button>
                            <span class="cantidad-valor" id="cantidad-valor">1</span>
                            <button class="cantidad-btn" id="sumar-cantidad">+</button>
                        </div>
                    </div>
                </div>
                
                <div class="modal-precio-final">
                    <span class="precio-total">Total: $<span id="precio-total">0</span></span>
                </div>
                
                <button class="boton-modal" id="confirmar-carrito">
                    AÑADIR AL CARRITO
                </button>
            </div>
        </div>
    </div>';
}
?>
<script src="../assets/js/Components/ProductoModal.js"></script>