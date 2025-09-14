<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olfato Perfumeria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="/assets/css/General.css">
</head>

<body>
    <?php
    include '../includes/header.php';
    ?>

    <!-- Contenido principal de la página -->
    <main>
        <!-- Sección Hero con imagen de fondo -->
        <section id="inicio" class="hero">
            <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1480&q=80"
                alt="Perfumes de lujo" class="hero-imagen">
            <div class="hero-overlay"></div>

            <div class="hero-contenido">
                <h1 class="hero-titulo">OLFATO PERFUMERÍA</h1>
                <p class="hero-subtitulo">Descubre la esencia de la elegancia en cada fragancia</p>
                <a href="#productos" class="boton">EXPLORAR COLECCIÓN</a>
            </div>

            <a href="#productos" class="hero-flecha">
                <i class="fas fa-chevron-down"></i>
            </a>
        </section>

        <!-- Aquí irán las demás secciones de la página -->
    </main>

    <?php
    include '../includes/footer.php';
    ?>
</body>

</html>