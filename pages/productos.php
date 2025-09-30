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

            <!-- Sección Mujer -->
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

            <!-- Sección Árabes -->
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

    <!-- Layout principal -->
    <main class="contenedor">
        <div class="productos-layout">
                <!-- Mensaje de productos (los productos vendrán del backend) -->
                <div class="mensaje-productos">
                    <i class="fas fa-cube"></i>
                    <h3>Productos Cargados desde el Backend</h3>
                    <p>Aquí se mostrarán todos los productos filtrados según tu selección.<br>
                    La información se cargará dinámicamente desde la base de datos.</p>
                </div>
            </section>
        </div>
    </main>
    <?php
    include '../includes/footer.php';
    ?>
</body>

<script src="../assets/js/productos.js"></script>

</html>