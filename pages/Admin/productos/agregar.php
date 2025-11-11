<?php
// pages/admin/productos/agregar.php
require_once '../../../includes/config.php';

if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['es_admin']) {
    header('Location: ' . PAGES_PATH . 'login.php');
    exit();
}

// Obtener categorías y géneros para los select
$sql_generos = "SELECT * FROM generos_perfumes";
$generos = $conexion->query($sql_generos);

$sql_categorias = "SELECT * FROM categorias";
$categorias = $conexion->query($sql_categorias);

$sql_ofertas = "SELECT * FROM ofertas WHERE fecha_fin >= CURDATE()";
$ofertas = $conexion->query($sql_ofertas);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $id_genero_perfume = intval($_POST['id_genero_perfume']);
    $id_categoria = intval($_POST['id_categoria']);
    $stock = intval($_POST['stock']);
    $id_oferta = !empty($_POST['id_oferta']) ? intval($_POST['id_oferta']) : null;
    
    // Precios
    $precio_30ml = floatval($_POST['precio_30ml']);
    $precio_60ml = floatval($_POST['precio_60ml']);
    $precio_100ml = floatval($_POST['precio_100ml']);
    
    // Manejo de imagen
    $imagen_url = 'Contenido/Perfumes/default.png';
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = uniqid() . '.' . $extension;
        $ruta_destino = ROOT_PATH . '/assets/Contenido/Perfumes/' . $nombre_archivo;
        
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $imagen_url = 'Contenido/Perfumes/' . $nombre_archivo;
        }
    }
    
    try {
        $conexion->begin_transaction();
        
        // Insertar producto
        $sql_producto = "INSERT INTO productos (nombre, descripcion, imagen_url, id_genero_perfume, id_categoria, stock, id_oferta) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_producto = $conexion->prepare($sql_producto);
        $stmt_producto->bind_param("sssiiii", $nombre, $descripcion, $imagen_url, $id_genero_perfume, $id_categoria, $stock, $id_oferta);
        $stmt_producto->execute();
        
        $id_producto = $conexion->insert_id;
        
        // Insertar precios
        $sql_precios = "INSERT INTO precios_tamaños (id_producto, tamaño, precio) VALUES (?, ?, ?)";
        $stmt_precios = $conexion->prepare($sql_precios);
        
        if ($precio_30ml > 0) {
            $stmt_precios->bind_param("isd", $id_producto, '30ml', $precio_30ml);
            $stmt_precios->execute();
        }
        
        if ($precio_60ml > 0) {
            $stmt_precios->bind_param("isd", $id_producto, '60ml', $precio_60ml);
            $stmt_precios->execute();
        }
        
        if ($precio_100ml > 0) {
            $stmt_precios->bind_param("isd", $id_producto, '100ml', $precio_100ml);
            $stmt_precios->execute();
        }
        
        $conexion->commit();
        
        $_SESSION['mensaje'] = "Producto agregado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
        header('Location: index.php');
        exit();
        
    } catch (Exception $e) {
        $conexion->rollback();
        $error = "Error al agregar el producto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto - Admin Olfato Perfumería</title>
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
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: var(--color-texto);
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 1rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            background: var(--color-gris-oscuro);
            border: 1px solid var(--color-borde);
            border-radius: 8px;
            color: var(--color-texto);
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transicion);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--color-dorado);
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .prices-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .price-item {
            background: var(--color-gris-oscuro);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--color-borde);
        }

        .price-item label {
            color: var(--color-dorado);
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        .btn-submit {
            background: var(--color-dorado);
            color: var(--color-fondo);
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transicion);
            width: 100%;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: var(--color-dorado-oscuro);
            transform: translateY(-2px);
        }

        .error-message {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid #e74c3c;
            color: #e74c3c;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
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

            .form-grid,
            .prices-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                flex-wrap: wrap;
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
                <a href="index.php"><i class="fas fa-cube"></i> Productos</a>
                <a href="../pedidos/"><i class="fas fa-shopping-cart"></i> Pedidos</a>
                <a href="../usuarios/"><i class="fas fa-users"></i> Usuarios</a>
                <a href="../ofertas/"><i class="fas fa-percentage"></i> Ofertas</a>
                <a href="<?php echo PAGES_PATH; ?>mi-cuenta.php"><i class="fas fa-user"></i> Mi Cuenta</a>
            </div>

            <div class="content-card">
                <div class="page-header">
                    <h1 class="page-title">Agregar Producto</h1>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Productos
                    </a>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nombre">Nombre del Producto *</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion">Descripción *</label>
                        <textarea id="descripcion" name="descripcion" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="imagen">Imagen del Producto</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*">
                        <small style="color: var(--color-gris-claro); margin-top: 5px; display: block;">
                            Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB
                        </small>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_genero_perfume">Género *</label>
                            <select id="id_genero_perfume" name="id_genero_perfume" required>
                                <option value="">Seleccionar género</option>
                                <?php while($genero = $generos->fetch_assoc()): ?>
                                    <option value="<?php echo $genero['id_genero_perfume']; ?>">
                                        <?php echo htmlspecialchars($genero['nombre_genero_perfume']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_categoria">Categoría *</label>
                            <select id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <?php while($categoria = $categorias->fetch_assoc()): ?>
                                    <option value="<?php echo $categoria['id_categoria']; ?>">
                                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="stock">Stock *</label>
                            <input type="number" id="stock" name="stock" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_oferta">Oferta (Opcional)</label>
                            <select id="id_oferta" name="id_oferta">
                                <option value="">Sin oferta</option>
                                <?php while($oferta = $ofertas->fetch_assoc()): ?>
                                    <option value="<?php echo $oferta['id_oferta']; ?>">
                                        <?php echo htmlspecialchars($oferta['nombre_oferta']); ?> (<?php echo $oferta['valor_descuento']; ?>%)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Precios por Tamaño *</label>
                        <div class="prices-grid">
                            <div class="price-item">
                                <label for="precio_30ml">30ml</label>
                                <input type="number" id="precio_30ml" name="precio_30ml" min="0" step="0.01" required>
                            </div>
                            <div class="price-item">
                                <label for="precio_60ml">60ml</label>
                                <input type="number" id="precio_60ml" name="precio_60ml" min="0" step="0.01" required>
                            </div>
                            <div class="price-item">
                                <label for="precio_100ml">100ml</label>
                                <input type="number" id="precio_100ml" name="precio_100ml" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Guardar Producto
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>