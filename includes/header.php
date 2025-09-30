<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olfato Perfumería</title>

    <!-- Fuentes de Google -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Hojas de estilo -->
    <link rel="stylesheet" href="../assets/css/general.css"> <!-- Estilos globales -->
    <link rel="stylesheet" href="../assets/css/componentes/header.css"><!-- Estilos del header -->
</head>

<body>
    <!-- Cabecera principal -->
    <header class="cabecera">
        <div class="contenedor cabecera-contenido">
            <!-- Logo con imagen -->
            <div class="logo">
                <img src="../assets/Contenido/Local/LogoSinfondo.png" alt="Olfato Perfumería" class="logo-imagen">
            </div>

            <!-- Navegación principal -->
            <nav class="navegacion">
                <ul class="menu" id="menu-principal">
                    <li class="menu-item"><a href="../pages/index.php#inicio" class="menu-enlace">Inicio</a></li>
                    <li class="menu-item"><a href="../pages/index.php#presentaciones" class="menu-enlace">Presentaciones</a></li>
                    <li class="menu-item"><a href="../pages/index.php#notas" class="menu-enlace">Notas</a></li>
                    <li class="menu-item"><a href="../pages/productos.php" class="menu-enlace">Productos</a></li>
                    <li class="menu-item"><a href="../pages/emprende.php" class="menu-enlace">Emprende</a></li>
                    <li class="menu-item"><a href="../pages/contacto.php" class="menu-enlace">Contacto</a></li>

                    <!-- Botones de usuario y carrito - ESTILOS UNIFICADOS -->
                    <li class="menu-item menu-item-botones">
                        <!-- Botón de Login -->
                        <button id="boton-login" class="boton-icono header-boton" aria-label="Iniciar sesión">
                            <i class="fas fa-user"></i>
                        </button>

                        <!-- Botón de Carrito -->
                        <button id="boton-carrito" class="boton-icono header-boton carrito-boton" aria-label="Carrito de compras">
                            <i class="fas fa-shopping-bag"></i>
                            <span id="contador-carrito" class="contador-carrito">0</span>
                        </button>
                    </li>
                </ul>
            </nav>

            <!-- Botón móvil para menú -->
            <button class="menu-movil-boton" id="boton-menu-movil" aria-label="Abrir menú">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <div class="overlay-menu" id="overlay-menu"></div> <!-- Overlay para el menú móvil -->

    <!-- Overlay para el carrito -->
    <div class="carrito-overlay" id="overlay-carrito"></div>

    <!-- Carrito lateral deslizable -->
    <div class="carrito-lateral" id="carrito">
        <div class="carrito-cabecera">
            <h3 class="carrito-titulo">TU CARRITO</h3>
            <button class="carrito-cerrar" id="cerrar-carrito" aria-label="Cerrar carrito">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="carrito-contenido" id="contenido-carrito">
            <div class="carrito-vacio">
                <i class="fas fa-shopping-bag carrito-vacio-icono"></i>
                <p>Tu carrito está vacío</p>
            </div>
        </div>

        <div class="carrito-resumen">
            <div class="carrito-resumen-linea">
                <span>Subtotal</span>
                <span id="subtotal-carrito">$0.00</span>
            </div>
            <div class="carrito-resumen-linea">
                <span>Descuento</span>
                <span id="descuento-carrito">-$0.00</span>
            </div>
            <div class="carrito-resumen-total">
                <span>Total</span>
                <span class="valor" id="total-carrito">$0.00</span>
            </div>

            <button class="boton ancho-completo mt-3" id="boton-pago">FINALIZAR COMPRA</button>

            <a href="#" class="boton boton-secundario ancho-completo mt-2">
                <i class="fas fa-arrow-left mr-2"></i> Continuar comprando
            </a>
        </div>
    </div>
</body>
<!-- Script para funcionalidad del carrito -->
<script src="../assets/js/Components/carrito.js"></script>