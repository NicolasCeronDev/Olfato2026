<?php
include '../includes/DB/conexion_db.php';
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olfato Perfumeria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
    <link rel="stylesheet" href="../assets/css/productos.css">
    <link rel="stylesheet" href="../assets/css/Componentes/productos_componente.css">


</head>

<body>
    <?php
    include '../includes/header.php';
    ?>
    <!-- Header de la página -->
    <header class="pagina-header">
        <div class="contenedor">
            <h1 class="pagina-titulo">Nuestra Colección</h1>
            <p class="pagina-subtitulo">Descubre las fragancias exclusivas que capturan la esencia de la elegancia y sofisticación</p>
        </div>
    </header>

    <!-- Secciones visuales de categorías -->
    <div class="contenedor">
        <div class="secciones-categorias">
            <!-- Sección Hombre -->

            <a href="parahombre.php" target="_blank" class="categoria-enlace">
                <div class="categoria-seccion" data-categoria="hombre">
                    <img src="../assets/Contenido/Perfumes/CategoriasFondos/ParaEllos.jpg"
                        alt="Perfumes Hombre" class="categoria-imagen">
                    <div class="categoria-overlay">
                        <div class="categoria-contenido">
                            <h2 class="categoria-titulo">PARA HOMBRE</h2>
                            <p class="categoria-subtitulo">Amaderados • Cítricos • Orientales</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Sección Mujer -->
            <a href="paramujer.php" target="_blank" class="categoria-enlace">
                <div class="categoria-seccion" data-categoria="mujer">
                    <img src="../assets/Contenido/Perfumes/CategoriasFondos/ParaEllas.jpg"
                        alt="Perfumes Mujer" class="categoria-imagen">
                    <div class="categoria-overlay">
                        <div class="categoria-contenido">
                            <h2 class="categoria-titulo">PARA MUJER</h2>
                            <p class="categoria-subtitulo">Florales • Frutales • Gourmand • Orientales</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Sección Árabes -->
            <a href="arabes.php" target="_blank" class="categoria-enlace">
                <div class="categoria-seccion" data-categoria="arabes">
                    <img src="../assets/Contenido/Perfumes/CategoriasFondos/Arabes.jpg"
                        alt="Perfumes Árabes" class="categoria-imagen">
                    <div class="categoria-overlay">
                        <div class="categoria-contenido">
                            <h2 class="categoria-titulo">PERFUMES ÁRABES</h2>
                            <p class="categoria-subtitulo">Esencias intensas y duraderas</p>
                        </div>
                    </div>
                </div>

                <!-- Sección Unisex -->
                <a href="unisex.php" target="_blank" class="categoria-enlace">
                    <div class="categoria-seccion" data-categoria="unisex">
                        <img src="../assets/Contenido/Perfumes/CategoriasFondos/Unisex-.jpg"
                            alt="Fragancias Unisex" class="categoria-imagen">
                        <div class="categoria-overlay">
                            <div class="categoria-contenido">
                                <h2 class="categoria-titulo">UNISEX</h2>
                                <p class="categoria-subtitulo">Fragancias para todos</p>
                            </div>
                        </div>
                    </div>
        </div>
    </div>

    <!-- Sección: OFERTAS ESPECIALES -->
    <section class="seccion productos-ofertas" style="background-color: var(--color-gris-oscuro);">
        <div class="contenedor">
            <div class="titulo-seccion">
                <h2><i class="fas fa-tag" style="color: var(--color-ofertas);"></i> OFERTAS ESPECIALES</h2>
                <div class="separador"></div>
                <p>Aprovecha nuestros descuentos exclusivos. Calidad premium a precios irresistibles.</p>
            </div>

            <div id="contenedor-ofertas">
                <?php
                include '../includes/DB/productos_componente.php';
                // Mostramos solo productos con ofertas
                mostrarProductos('todos', 6, 'ofertas', true);
                ?>
            </div>
        </div>
    </section>

    <!-- Sección: NUEVAS FRAGANCIAS -->
    <section class="seccion productos-nuevos" style="background-color: var(--color-fondo);">
        <div class="contenedor">
            <div class="titulo-seccion">
                <h2><i class="fas fa-star" style="color: var(--color-dorado);"></i> NUEVOS LANZAMIENTOS</h2>
                <div class="separador"></div>
                <p>Descubre nuestras últimas incorporaciones. Fragancias frescas y innovadoras que marcan tendencia.</p>
            </div>

            <div id="contenedor-nuevos">
                <?php
                // Mostramos los productos más recientes
                mostrarProductos('todos', 6, 'nuevos', false);
                ?>
            </div>
        </div>
    </section>

    <!-- Sección: LOS MÁS VENDIDOS -->
    <section class="seccion productos-destacados" style="background-color: var(--color-gris-oscuro);">
        <div class="contenedor">
            <div class="titulo-seccion">
                <h2><i class="fas fa-crown" style="color: var(--color-dorado);"></i> LOS MÁS VENDIDOS</h2>
                <div class="separador"></div>
                <p>Las fragancias que han conquistado a nuestros clientes. Clásicos que nunca pasan de moda.</p>
            </div>

            <div id="contenedor-mas-vendidos">
                <?php
                // Por ahora mostramos los más antiguos como "más vendidos"
                mostrarProductos('todos', 6, 'vendidos', false);
                ?>
            </div>
        </div>
    </section>

    <?php
    include '../includes/footer.php';
    ?>
</body>

<script src="../assets/js/productos.js"></script>

</html>