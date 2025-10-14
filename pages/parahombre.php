<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olfato Perfumeria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/paramujer.css">
    <link rel="stylesheet" href="../assets/css/Componentes/productos_componente.css">
</head>

<body>
    <?php
    include '../includes/header.php';
    ?>

    <section class="categoria">
        <section class="categoria">
            <section class="seccion-volver">
                <a href="productos.php" class="boton-volver">
                    ⬅ Volver atrás
                </a>
            </section>
            <h2 class="titulo-categoria"> Cítricas</h2>
            <div class="grid-productos">
                <?php
                include '../includes/DB/productos_componente.php';
                mostrarProductos('Masculino', 0, 'normal', 'Citricas'); ?>
            </div>
        </section>

        <!-- 🍊 Sección Amaderadas -->
        <section class="categoria">
            <h2 class="titulo-categoria">Amaderadas</h2>
            <div class="grid-productos">
                <?php
                mostrarProductos('Masculino', 0, 'normal', 'Amaderadas'); ?>
            </div>
        </section>

        <section class="categoria">
            <h2 class="titulo-categoria">Orientales</h2>
            <div class="grid-productos">
                <?php
                mostrarProductos('Masculino', 0, 'normal', 'Orientales'); ?>
            </div>
        </section>