<?php
include '../../../includes/config.php';
include '../../../includes/DB/verificar_sesion.php';

// Verificar que sea administrador
if (!$_SESSION['usuario']['es_admin']) {
    header('Location: ../../login.php');
    exit;
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_oferta = trim($_POST['nombre_oferta']);
    $valor_descuento = floatval($_POST['valor_descuento']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $activa = isset($_POST['activa']) ? 1 : 0;
    
    if (!empty($nombre_oferta) && $valor_descuento > 0) {
        $sql = "INSERT INTO ofertas (nombre_oferta, valor_descuento, fecha_inicio, fecha_fin, activa) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sdssi", $nombre_oferta, $valor_descuento, $fecha_inicio, $fecha_fin, $activa);
        
        if ($stmt->execute()) {
            $mensaje = "Oferta creada exitosamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al crear la oferta: " . $conexion->error;
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = "Por favor completa todos los campos requeridos";
        $tipo_mensaje = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Oferta - Admin Olfato</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../../../assets/css/General.css">
    <style>
        .admin-container {
            padding: 100px 0 50px;
            background: var(--color-gris-oscuro);
            min-height: 100vh;
        }
        
        .admin-header {
            background: var(--color-fondo);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 1px solid var(--color-borde);
        }
        
        .admin-form {
            background: var(--color-fondo);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .grupo-formulario {
            margin-bottom: 25px;
        }
        
        .grupo-formulario label {
            display: block;
            color: var(--color-texto);
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .grupo-formulario input,
        .grupo-formulario select {
            width: 100%;
            padding: 12px 15px;
            background: var(--color-gris-oscuro);
            border: 1px solid var(--color-borde);
            border-radius: 8px;
            color: var(--color-texto);
            font-family: inherit;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input {
            width: auto;
        }
    </style>
</head>
<body>
    <?php include '../../../includes/header.php'; ?>
    
    <section class="admin-container">
        <div class="contenedor">
            <div class="admin-header">
                <h1 class="titulo-principal">Agregar Nueva Oferta</h1>
                <p class="subtitulo">Crea promociones especiales para tus productos</p>
            </div>
            
            <?php if (!empty($mensaje)): ?>
                <div class="mensaje <?php echo $tipo_mensaje; ?>" style="margin-bottom: 30px;">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <div class="admin-form">
                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="grupo-formulario">
                            <label for="nombre_oferta">Nombre de la Oferta *</label>
                            <input type="text" id="nombre_oferta" name="nombre_oferta" required>
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="valor_descuento">Valor de Descuento (%) *</label>
                            <input type="number" id="valor_descuento" name="valor_descuento" min="1" max="100" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="grupo-formulario">
                            <label for="fecha_inicio">Fecha de Inicio *</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="fecha_fin">Fecha de Fin *</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                    
                    <div class="grupo-formulario">
                        <div class="checkbox-group">
                            <input type="checkbox" id="activa" name="activa" value="1" checked>
                            <label for="activa">Oferta activa</label>
                        </div>
                    </div>
                    
                    <div class="botones-accion" style="display: flex; gap: 15px; margin-top: 30px;">
                        <button type="submit" class="boton">
                            <i class="fas fa-save mr-2"></i> Crear Oferta
                        </button>
                        <a href="index.php" class="boton boton-secundario">
                            <i class="fas fa-arrow-left mr-2"></i> Volver a Ofertas
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <?php include '../../../includes/footer.php'; ?>
</body>
</html>