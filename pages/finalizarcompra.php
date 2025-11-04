<?php
include '../includes/DB/conexion_db.php';
include '../includes/DB/verificar_sesion.php';

// Obtener datos del usuario desde la sesión
$usuario = $_SESSION['usuario'] ?? null;

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que el usuario esté logueado
    if (!$usuario) {
        $_SESSION['error'] = "Debes iniciar sesión para realizar una compra";
        header('Location: login.php');
        exit;
    }

    // El carrito viene por POST desde JavaScript
    $carrito_json = $_POST['carrito'] ?? '[]';
    $carrito = json_decode($carrito_json, true);

    // Validar que el carrito no esté vacío
    if (empty($carrito)) {
        $_SESSION['error'] = "El carrito está vacío";
        header('Location: finalizarcompra.php');
        exit;
    }

    // Validar y sanitizar datos del formulario
    $nombre_cliente = trim($_POST['nombre']);
    $email_cliente = $usuario['email'];
    $telefono_cliente = trim($_POST['telefono']);
    $direccion_envio = trim($_POST['direccion']);
    $ciudad = trim($_POST['ciudad']);
    $barrio = trim($_POST['barrio']);
    $notas_cliente = trim($_POST['instrucciones'] ?? '');
    $metodo_pago = $_POST['metodo_pago'];

    // Calcular totales
    $subtotal = 0;
    $descuento_total = 0;

    foreach ($carrito as $item) {
        $precio_real = $item['precio'] * $item['cantidad'];
        $precio_descuento = $item['precio'] * (1 - ($item['descuento'] ?? 0) / 100) * $item['cantidad'];
        $subtotal += $precio_real;
        $descuento_total += ($precio_real - $precio_descuento);
    }

    $total_orden = $subtotal - $descuento_total;

    // Preparar detalles de pago según el método
    $detalles_pago = [];
    if ($metodo_pago === 'nequi') {
        $detalles_pago = [
            'numero_nequi' => $_POST['numero_nequi'] ?? '',
            'nombre_nequi' => $_POST['nombre_nequi'] ?? ''
        ];
    } elseif ($metodo_pago === 'daviplata') {
        $detalles_pago = [
            'numero_daviplata' => $_POST['numero_daviplata'] ?? '',
            'nombre_daviplata' => $_POST['nombre_daviplata'] ?? ''
        ];
    } elseif ($metodo_pago === 'contraentrega') {
        $detalles_pago = [
            'metodo' => 'Pago contra entrega'
        ];
    }

    try {
        // Iniciar transacción
        $conexion->begin_transaction();

        // Consulta con id_usuario
        $sql_orden = "INSERT INTO ordenes (
            estado_orden, total_orden, nombre_cliente, 
            email_cliente, telefono_cliente, direccion_envio, notas_cliente,
            ciudad, barrio, detalles_pago, metodo_pago, id_usuario
        ) VALUES ('Pendiente', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        

        $stmt_orden = $conexion->prepare($sql_orden);
        $detalles_pago_json = json_encode($detalles_pago);
        $id_usuario = $usuario['id'];

        $stmt_orden->bind_param(
            "dsssssssssi",
            $total_orden,           // d
            $nombre_cliente,        // s
            $email_cliente,         // s
            $telefono_cliente,      // s
            $direccion_envio,       // s
            $notas_cliente,         // s
            $ciudad,                // s
            $barrio,                // s
            $detalles_pago_json,    // s
            $metodo_pago,           // s
            $id_usuario             // i
        );

        if (!$stmt_orden->execute()) {
            throw new Exception("Error al insertar orden: " . $stmt_orden->error);
        }

        $id_orden = $conexion->insert_id;

        // Insertar detalles de la orden
        $sql_detalle = "INSERT INTO detalles_ordenes (
            id_orden, id_producto, cantidad, precio_unitario, 
            tamaño, Color, subtotal
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt_detalle = $conexion->prepare($sql_detalle);

        foreach ($carrito as $item) {
            $precio_unitario = $item['precio'] * (1 - ($item['descuento'] ?? 0) / 100);
            $subtotal_item = $precio_unitario * $item['cantidad'];

            $stmt_detalle->bind_param(
                "iiidssd",
                $id_orden,
                $item['id'],
                $item['cantidad'],
                $precio_unitario,
                $item['tamano'],
                $item['color'],
                $subtotal_item
            );

            if (!$stmt_detalle->execute()) {
                throw new Exception("Error al insertar detalle: " . $stmt_detalle->error);
            }

            // Actualizar stock
            $sql_update_stock = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
            $stmt_stock = $conexion->prepare($sql_update_stock);
            $stmt_stock->bind_param("ii", $item['cantidad'], $item['id']);

            if (!$stmt_stock->execute()) {
                throw new Exception("Error al actualizar stock: " . $stmt_stock->error);
            }
        }

        // Confirmar transacción
        $conexion->commit();

        // Redirigir a confirmación
        $_SESSION['orden_exitosa'] = $id_orden;
        header('Location: confirmacion_compra.php');
        exit;
    } catch (Exception $e) {
        $conexion->rollback();
        $_SESSION['error'] = "Error al procesar la orden: " . $e->getMessage();
        header('Location: finalizarcompra.php');
        exit;
    }
}

// Si no es POST, mostrar el formulario normal
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Olfato Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
    <style>
        .finalizar-compra {
            padding: 120px 0 60px;
            background: var(--color-gris-oscuro);
            min-height: 100vh;
        }

        .compra-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .formulario-seccion {
            background: var(--color-fondo);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
            margin-bottom: 30px;
        }

        .resumen-pedido {
            background: var(--color-fondo);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .seccion-titulo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--color-dorado);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--color-dorado);
        }

        .grupo-formulario {
            margin-bottom: 20px;
        }

        .grupo-formulario label {
            display: block;
            color: var(--color-texto);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .grupo-formulario input,
        .grupo-formulario select,
        .grupo-formulario textarea {
            width: 100%;
            padding: 12px 15px;
            background: var(--color-gris-oscuro);
            border: 1px solid var(--color-borde);
            border-radius: 8px;
            color: var(--color-texto);
            font-family: inherit;
            transition: var(--transicion);
        }

        .grupo-formulario input:focus,
        .grupo-formulario select:focus,
        .grupo-formulario textarea:focus {
            outline: none;
            border-color: var(--color-dorado);
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
        }

        .grupo-formulario input[readonly] {
            background: var(--color-gris-medio);
            color: var(--color-gris-claro);
            cursor: not-allowed;
        }

        .metodo-pago-opciones {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .metodo-pago {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transicion);
            background: var(--color-gris-oscuro);
        }

        .metodo-pago:hover {
            border-color: var(--color-dorado);
            transform: translateY(-2px);
        }

        .metodo-pago.seleccionado {
            border-color: var(--color-dorado);
            background: rgba(212, 175, 55, 0.1);
        }

        .metodo-pago i {
            font-size: 2rem;
            color: var(--color-dorado);
            margin-bottom: 10px;
        }

        .item-pedido {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid var(--color-borde);
        }

        .item-imagen {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            background: var(--color-gris-medio);
        }

        .item-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-info {
            flex: 1;
        }

        .item-nombre {
            font-weight: 600;
            color: var(--color-texto);
            margin-bottom: 5px;
        }

        .item-detalles {
            color: var(--color-gris-claro);
            font-size: 0.9rem;
        }

        .item-precio {
            color: var(--color-dorado);
            font-weight: 600;
        }

        .resumen-linea {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: var(--color-texto);
        }

        .resumen-total {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid var(--color-dorado);
            padding-top: 15px;
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--color-dorado);
        }

        .carrito-vacio {
            text-align: center;
            padding: 40px 20px;
            color: var(--color-gris-claro);
        }

        .carrito-vacio i {
            font-size: 3rem;
            color: var(--color-dorado);
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .mensaje-error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c66;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .cargando {
            text-align: center;
            padding: 20px;
            color: var(--color-gris-claro);
        }

        @media (max-width: 968px) {
            .compra-grid {
                grid-template-columns: 1fr;
            }

            .resumen-pedido {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .finalizar-compra {
                padding: 100px 0 40px;
            }

            .formulario-seccion,
            .resumen-pedido {
                padding: 25px;
            }

            .metodo-pago-opciones {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <section class="finalizar-compra">
        <div class="compra-grid">
            <!-- Formulario de compra -->
            <div class="formulario-compra">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mensaje-error">
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" id="form-compra">
                    <!-- Campo oculto para el carrito -->
                    <input type="hidden" name="carrito" id="carrito-input">

                    <div class="formulario-seccion">
                        <h2 class="seccion-titulo">Información de Envío</h2>

                        <div class="grupo-formulario">
                            <label for="nombre">Nombre completo *</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="grupo-formulario">
                                <label for="email">Correo electrónico *</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" readonly required>
                            </div>

                            <div class="grupo-formulario">
                                <label for="telefono">Teléfono *</label>
                                <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="grupo-formulario">
                            <label for="direccion">Dirección *</label>
                            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?>" required>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="grupo-formulario">
                                <label for="ciudad">Ciudad *</label>
                                <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($usuario['ciudad'] ?? ''); ?>" required>
                            </div>

                            <div class="grupo-formulario">
                                <label for="barrio">Barrio *</label>
                                <input type="text" id="barrio" name="barrio" value="<?php echo htmlspecialchars($usuario['barrio'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="grupo-formulario">
                            <label for="instrucciones">Instrucciones de entrega (opcional)</label>
                            <textarea id="instrucciones" name="instrucciones" rows="3" placeholder="Ej: Llamar antes de llegar, dejar con el portero, etc."></textarea>
                        </div>
                    </div>

                    <div class="formulario-seccion">
                        <h2 class="seccion-titulo">Método de Pago</h2>

                        <div class="metodo-pago-opciones">
                            <div class="metodo-pago seleccionado" data-metodo="nequi">
                                <i class="fas fa-mobile-alt"></i>
                                <div>Nequi</div>
                            </div>

                            <div class="metodo-pago" data-metodo="daviplata">
                                <i class="fas fa-wallet"></i>
                                <div>DaviPlata</div>
                            </div>

                            <div class="metodo-pago" data-metodo="contraentrega">
                                <i class="fas fa-money-bill-wave"></i>
                                <div>Contra Entrega</div>
                            </div>
                        </div>

                        <!-- Campos para Nequi -->
                        <div id="campos-nequi" style="margin-top: 20px;">
                            <div class="grupo-formulario">
                                <label for="numero_nequi">Número de Nequi *</label>
                                <input type="text" id="numero_nequi" name="numero_nequi" placeholder="312 123 4567">
                            </div>
                            <div class="grupo-formulario">
                                <label for="nombre_nequi">Nombre en Nequi *</label>
                                <input type="text" id="nombre_nequi" name="nombre_nequi" placeholder="Como aparece en tu Nequi">
                            </div>
                        </div>

                        <!-- Campos para DaviPlata -->
                        <div id="campos-daviplata" style="display: none; margin-top: 20px;">
                            <div class="grupo-formulario">
                                <label for="numero_daviplata">Número de DaviPlata *</label>
                                <input type="text" id="numero_daviplata" name="numero_daviplata" placeholder="312 123 4567">
                            </div>
                            <div class="grupo-formulario">
                                <label for="nombre_daviplata">Nombre en DaviPlata *</label>
                                <input type="text" id="nombre_daviplata" name="nombre_daviplata" placeholder="Como aparece en tu DaviPlata">
                            </div>
                        </div>

                        <!-- Mensaje para contra entrega -->
                        <div id="mensaje-contraentrega" style="display: none; margin-top: 20px; padding: 15px; background: var(--color-gris-oscuro); border-radius: 8px;">
                            <p style="color: var(--color-dorado); margin: 0;">Pagarás cuando recibas tu pedido en la puerta de tu casa.</p>
                        </div>

                        <input type="hidden" name="metodo_pago" id="metodo_pago_input" value="nequi">
                    </div>

                    <button type="submit" class="boton ancho-completo" style="padding: 15px; font-size: 1.1rem;">
                        <i class="fas fa-lock mr-2"></i> CONFIRMAR COMPRA
                    </button>
                </form>
            </div>

            <!-- Resumen del pedido -->
            <div class="resumen-pedido">
                <h2 class="seccion-titulo">Resumen del Pedido</h2>
                <div id="resumen-contenido">
                    <div class="cargando">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Cargando carrito...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Cargar carrito desde localStorage
        document.addEventListener('DOMContentLoaded', function() {
            cargarCarrito();
        });

        function cargarCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const resumenContenido = document.getElementById('resumen-contenido');
            const carritoInput = document.getElementById('carrito-input');

            // Guardar carrito en el campo oculto para enviar por POST
            carritoInput.value = JSON.stringify(carrito);

            if (carrito.length === 0) {
                resumenContenido.innerHTML = `
                    <div class="carrito-vacio">
                        <i class="fas fa-shopping-bag"></i>
                        <p>Tu carrito está vacío</p>
                        <a href="productos.php" class="boton boton-secundario mt-3">Continuar comprando</a>
                    </div>
                `;
            } else {
                let subtotal = 0;
                let descuentoTotal = 0;
                let html = '<div class="lista-items">';

                carrito.forEach((item, index) => {
                    const precioReal = item.precio * item.cantidad;
                    const precioDescuento = item.precio * (1 - (item.descuento || 0) / 100) * item.cantidad;
                    const descuentoItem = precioReal - precioDescuento;

                    subtotal += precioReal;
                    descuentoTotal += descuentoItem;

                    html += `
                        <div class="item-pedido">
                            <div class="item-imagen">
                                <img src="../assets/${item.imagen}" alt="${item.nombre}">
                            </div>
                            <div class="item-info">
                                <div class="item-nombre">${item.nombre}</div>
                                <div class="item-detalles">${item.tamano} • ${item.color}</div>
                                <div class="item-detalles">Cantidad: ${item.cantidad}</div>
                            </div>
                            <div class="item-precio">
                                $${precioDescuento.toLocaleString()}
                            </div>
                        </div>
                    `;
                });

                html += '</div>';
                html += `
                    <div class="resumen-totales" style="margin-top: 20px;">
                        <div class="resumen-linea">
                            <span>Subtotal:</span>
                            <span>$${subtotal.toLocaleString()}</span>
                        </div>
                `;

                if (descuentoTotal > 0) {
                    html += `
                        <div class="resumen-linea">
                            <span>Descuento:</span>
                            <span style="color: #27ae60;">-$${descuentoTotal.toLocaleString()}</span>
                        </div>
                    `;
                }

                html += `
                        <div class="resumen-linea">
                            <span>Envío:</span>
                            <span>Gratis</span>
                        </div>
                        
                        <div class="resumen-total">
                            <span>Total:</span>
                            <span>$${(subtotal - descuentoTotal).toLocaleString()}</span>
                        </div>
                    </div>
                `;

                resumenContenido.innerHTML = html;
            }
        }

        // Selección de método de pago
        document.querySelectorAll('.metodo-pago').forEach(metodo => {
            metodo.addEventListener('click', function() {
                const metodoSeleccionado = this.getAttribute('data-metodo');
                document.getElementById('metodo_pago_input').value = metodoSeleccionado;

                document.querySelectorAll('.metodo-pago').forEach(m => m.classList.remove('seleccionado'));
                this.classList.add('seleccionado');

                // Mostrar campos específicos
                document.getElementById('campos-nequi').style.display = metodoSeleccionado === 'nequi' ? 'block' : 'none';
                document.getElementById('campos-daviplata').style.display = metodoSeleccionado === 'daviplata' ? 'block' : 'none';
                document.getElementById('mensaje-contraentrega').style.display = metodoSeleccionado === 'contraentrega' ? 'block' : 'none';
            });
        });

        // Validación del formulario
        document.getElementById('form-compra').addEventListener('submit', function(e) {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            if (carrito.length === 0) {
                e.preventDefault();
                alert('Tu carrito está vacío');
                return;
            }

            const metodoPago = document.getElementById('metodo_pago_input').value;
            let valido = true;

            // Validaciones básicas
            const camposRequeridos = ['nombre', 'email', 'telefono', 'direccion', 'ciudad', 'barrio'];
            camposRequeridos.forEach(campo => {
                const input = document.getElementById(campo);
                if (!input.value.trim()) {
                    valido = false;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '';
                }
            });

            // Validaciones específicas por método de pago
            if (metodoPago === 'nequi') {
                const camposNequi = ['numero_nequi', 'nombre_nequi'];
                camposNequi.forEach(campo => {
                    const input = document.getElementById(campo);
                    if (!input.value.trim()) {
                        valido = false;
                        input.style.borderColor = 'red';
                    } else {
                        input.style.borderColor = '';
                    }
                });
            } else if (metodoPago === 'daviplata') {
                const camposDaviplata = ['numero_daviplata', 'nombre_daviplata'];
                camposDaviplata.forEach(campo => {
                    const input = document.getElementById(campo);
                    if (!input.value.trim()) {
                        valido = false;
                        input.style.borderColor = 'red';
                    } else {
                        input.style.borderColor = '';
                    }
                });
            }

            if (!valido) {
                e.preventDefault();
                alert('Por favor completa todos los campos requeridos');
                return;
            }

            // Limpiar carrito después de enviar el formulario
            localStorage.removeItem('carrito');
        });
    </script>
</body>

</html>