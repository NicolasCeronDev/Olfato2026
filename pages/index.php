<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olfato Perfumeria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
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

        <!-- Sección Productos (Estructura lista para datos futuros) -->
        <section id="productos" class="seccion productos">
            <div class="contenedor">
                <div class="titulo-seccion">
                    <h2>NUESTRA COLECCIÓN</h2>
                    <div class="separador"></div>
                    <p>Descubre nuestras fragancias exclusivas, cuidadosamente seleccionadas para ofrecerte una experiencia olfativa única.</p>
                </div>

                <!-- Filtros de categorías -->
                <div class="categorias">
                    <button class="categoria-boton activo" data-categoria="todos">Todos</button>
                    <button class="categoria-boton" data-categoria="mujer">Mujer</button>
                    <button class="categoria-boton" data-categoria="hombre">Hombre</button>
                    <button class="categoria-boton" data-categoria="unisex">Unisex</button>
                    <button class="categoria-boton" data-categoria="unisex">Arabes</button>
                    <button class="categoria-boton" data-categoria="ofertas">Ofertas</button>
                </div>

                <!-- Contenedor donde se cargarán los productos dinámicamente -->
                <div class="productos-grid" id="contenedor-productos">
                    <!-- Los productos se cargarán aquí dinámicamente con JavaScript -->
                    <div class="producto-placeholder">
                        <div class="placeholder-imagen"></div>
                        <div class="placeholder-info">
                            <div class="placeholder-linea largo"></div>
                            <div class="placeholder-linea medio"></div>
                            <div class="placeholder-precio">
                                <div class="placeholder-linea corto"></div>
                                <div class="placeholder-boton"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Repetir placeholders para simular la grid -->
                    <div class="producto-placeholder">
                        <div class="placeholder-imagen"></div>
                        <div class="placeholder-info">
                            <div class="placeholder-linea largo"></div>
                            <div class="placeholder-linea medio"></div>
                            <div class="placeholder-precio">
                                <div class="placeholder-linea corto"></div>
                                <div class="placeholder-boton"></div>
                            </div>
                        </div>
                    </div>

                    <div class="producto-placeholder">
                        <div class="placeholder-imagen"></div>
                        <div class="placeholder-info">
                            <div class="placeholder-linea largo"></div>
                            <div class="placeholder-linea medio"></div>
                            <div class="placeholder-precio">
                                <div class="placeholder-linea corto"></div>
                                <div class="placeholder-boton"></div>
                            </div>
                        </div>
                    </div>

                    <div class="producto-placeholder">
                        <div class="placeholder-imagen"></div>
                        <div class="placeholder-info">
                            <div class="placeholder-linea largo"></div>
                            <div class="placeholder-linea medio"></div>
                            <div class="placeholder-precio">
                                <div class="placeholder-linea corto"></div>
                                <div class="placeholder-boton"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón para ver más productos -->
                <div class="texto-centrado">
                    <a href="productos.php" class="boton boton-secundario">VER TODOS LOS PRODUCTOS</a>
                </div>
            </div>
        </section>

        <!-- Sección Presentaciones -->
        <section id="presentaciones" class="seccion presentaciones">
            <div class="contenedor">
                <div class="titulo-seccion">
                    <h2>PRESENTACIONES EXCLUSIVAS</h2>
                    <div class="separador"></div>
                    <p>En Olfato Perfumería, no solo nos preocupamos por la calidad de nuestras fragancias,
                        sino también por la presentación en la que llegan a tus manos. Descubre nuestras
                        opciones de envases, cuidadosamente seleccionadas para preservar la esencia de tu perfume.</p>
                </div>

                <div class="presentaciones-grid">
                    <!-- Presentación 30ml -->
                    <div class="presentacion-tarjeta">
                        <div class="presentacion-imagen-contenedor">
                            <img src="/assets/Contenido/Presentaciones/30ml/Default.png"
                                alt="Botella 30ml" class="presentacion-imagen" id="imagen-30ml">
                        </div>
                        <div class="presentacion-info">
                            <h3 class="presentacion-titulo">Botella 30ml</h3>
                            <p class="presentacion-descripcion">Presentación ideal para probar nuevas fragancias o llevar contigo tu aroma favorito.</p>
                            <div class="producto-precio">
                                <span class="precio-actual">$20.000</span>
                                <div class="colores-disponibles">
                                    <select class="selector-colores" onchange="cambiarImagen('30ml', this.value)">
                                        <option value="">Colores disponibles</option>
                                        <option value="dorado">Dorado</option>
                                        <option value="negro">Negro</option>
                                        <option value="rojo">Rojo</option>
                                        <option value="azul">Azul</option>
                                        <option value="fucsia">Fucsia</option>
                                        <option value="morado">Morado</option>
                                        <option value="verde">Verde</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Presentación 60ml -->
                    <div class="presentacion-tarjeta">
                        <div class="presentacion-imagen-contenedor">
                            <img src="/assets/Contenido/Presentaciones/60ml/Default.png"
                                alt="Botella 60ml" class="presentacion-imagen" id="imagen-60ml">
                        </div>
                        <div class="presentacion-info">
                            <h3 class="presentacion-titulo">Botella 60ml</h3>
                            <p class="presentacion-descripcion">Tamaño perfecto para uso regular, con diseño elegante y práctico.</p>
                            <div class="producto-precio">
                                <span class="precio-actual">$35.000</span>
                                <div class="colores-disponibles">
                                    <select class="selector-colores" onchange="cambiarImagen('60ml', this.value)">
                                        <option value="">Colores disponibles</option>
                                        <option value="dorado">Dorado</option>
                                        <option value="negro">Negro</option>
                                        <option value="rojo">Rojo</option>
                                        <option value="azul">Azul</option>
                                        <option value="fucsia">Fucsia</option>
                                        <option value="morado">Morado</option>
                                        <option value="verde">Verde</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Presentación 100ml -->
                    <div class="presentacion-tarjeta">
                        <div class="presentacion-imagen-contenedor">
                            <img src="/assets/Contenido/Presentaciones/100ml/Default.png"
                                alt="Botella 100ml" class="presentacion-imagen" id="imagen-100ml">
                        </div>
                        <div class="presentacion-info">
                            <h3 class="presentacion-titulo">Botella 100ml</h3>
                            <p class="presentacion-descripcion">Edición premium para quienes desean disfrutar de su fragancia favorita por más tiempo.</p>
                            <div class="producto-precio">
                                <span class="precio-actual">$55.000</span>
                                <div class="colores-disponibles">
                                    <select class="selector-colores" onchange="cambiarImagen('100ml', this.value)">
                                        <option value="">Colores disponibles</option>
                                        <option value="dorado">Dorado</option>
                                        <option value="negro">Negro</option>
                                        <option value="rojo">Rojo</option>
                                        <option value="azul">Azul</option>
                                        <option value="fucsia">Fucsia</option>
                                        <option value="morado">Morado</option>
                                        <option value="verde">Verde</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección TikTok -->
        <section class="seccion tiktok">
            <div class="contenedor">
                <div class="titulo-seccion">
                    <h2>OLFATO PERFUMERIA</h2>
                    <div class="separador"></div>
                    <p>Síguenos en TikTok y descubre testimonios, nuevos lanzamientos y promociones exclusivas.</p>
                </div>


                <div class="tiktok-grid">
                    <!-- TikTok 1 -->
                    <div class="tiktok-tarjeta">
                        <a href="https://www.tiktok.com/@olfato.perfumeria/video/7475009051051527429" target="_blank" class="tiktok-link">
                            <div class="tiktok-video-container">
                                <img src="/assets/Contenido/TikToks/TikTok1.png" alt="TikTok Olfato Perfumería" class="tiktok-video-thumbnail">
                                <div class="tiktok-play-button">
                                    <i class="fab fa-tiktok"></i>
                                </div>
                            </div>
                            <div class="tiktok-info">
                                <p>¡De la fae un perfum! #tendencia #olfatoperfumeria #nosefia</p>
                            </div>
                        </a>
                    </div>

                    <!-- TikTok 2 -->
                    <div class="tiktok-tarjeta">
                        <a href="https://www.tiktok.com/@olfato.perfumeria/video/7529588807864274181" target="_blank" class="tiktok-link">
                            <div class="tiktok-video-container">
                                <img src="/assets/Contenido/TikToks/TikTok2.png" alt="TikTok Olfato Perfumería" class="tiktok-video-thumbnail">
                                <div class="tiktok-play-button">
                                    <i class="fab fa-tiktok"></i>
                                </div>
                            </div>
                            <div class="tiktok-info">
                                <p>🔥Nuestros más vendidos 🔥 Pregunta por tu fragancia favorita</p>
                            </div>
                        </a>
                    </div>

                    <!-- TikTok 3 -->
                    <div class="tiktok-tarjeta">
                        <a href="https://www.tiktok.com/@olfato.perfumeria/video/7496549061089135927" target="_blank" class="tiktok-link">
                            <div class="tiktok-video-container">
                                <img src="/assets/Contenido/TikToks/TikTok3.png" alt="TikTok Olfato Perfumería" class="tiktok-video-thumbnail">
                                <div class="tiktok-play-button">
                                    <i class="fab fa-tiktok"></i>
                                </div>
                            </div>
                            <div class="tiktok-info">
                                <p>Los más pedidos por nuestras clientas! Fragancias exquisitas</p>
                            </div>
                        </a>
                    </div>

                    <!-- TikTok 4 -->
                    <div class="tiktok-tarjeta">
                        <a href="https://www.tiktok.com/@olfato.perfumeria/video/7485848426782018871" target="_blank" class="tiktok-link">
                            <div class="tiktok-video-container">
                                <img src="/assets/Contenido/TikToks/TikTok4.png" alt="TikTok Olfato Perfumería" class="tiktok-video-thumbnail">
                                <div class="tiktok-play-button">
                                    <i class="fab fa-tiktok"></i>
                                </div>
                            </div>
                            <div class="tiktok-info">
                                <p>Descubre nuestras fragancias #seguidores #tendencia #perfumes</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="https://www.tiktok.com/@olfato.perfumeria?is_from_webapp=1&sender_device=pc" target="_blank" class="boton boton-secundario">
                        <i class="fab fa-tiktok mr-2"></i> SEGUIR EN TIKTOK
                    </a>
                </div>
            </div>
        </section>
        <script async src="https://www.tiktok.com/embed.js"></script>

        <!-- Sección Notas que definen una esencia -->
        <section id="notas" class="seccion notas">
            <div class="contenedor">
                <div class="titulo-seccion">
                    <h2>NOTAS QUE DEFINEN UNA ESENCIA</h2>
                    <div class="separador"></div>
                    <p>Descubre las tres capas aromáticas que componen cada una de nuestras fragancias exclusivas</p>
                </div>

                <div class="slider-notas">
                    <div class="slider-contenedor">
                        <!-- Slide 1 - Notas de salida -->
                        <div class="slide">
                            <div class="slide-imagen">
                                <img src="/assets/Contenido/Notas/NotasSalida.png" alt="Notas de salida">
                            </div>
                            <div class="slide-contenido">
                                <h3 class="slide-titulo">Notas de Salida</h3>
                                <p class="slide-descripcion">Son las primeras impresiones de una fragancia, las notas que percibimos inmediatamente después de la aplicación. Suelen ser ligeras y volátiles, creando el impacto inicial.</p>
                                <ul class="slide-caracteristicas">
                                    <li><i class="fas fa-check"></i> Primera impresión aromática</li>
                                    <li><i class="fas fa-check"></i> Aromas ligeros y frescos</li>
                                    <li><i class="fas fa-check"></i> Ejemplos: cítricos, hierbas frescas</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Slide 2 - Notas de corazón -->
                        <div class="slide">
                            <div class="slide-imagen">
                                <img src="/assets/Contenido/Notas/NotasCorazon.png" alt="Notas de corazón">
                            </div>
                            <div class="slide-contenido">
                                <h3 class="slide-titulo">Notas de Corazón</h3>
                                <p class="slide-descripcion">Constituyen el cuerpo principal de la fragancia, emergiendo una vez que las notas de salida se disipan. Definen el carácter y la personalidad del perfume.</p>
                                <ul class="slide-caracteristicas">
                                    <li><i class="fas fa-check"></i> Alma de la fragancia</li>
                                    <li><i class="fas fa-check"></i> Aromas florales y especiados</li>
                                    <li><i class="fas fa-check"></i> Ejemplos: rosas, jazmín, especias</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Slide 3 - Notas de fondo -->
                        <div class="slide">
                            <div class="slide-imagen">
                                <img src="/assets/Contenido/Notas/NotasFondo.png" alt="Notas de fondo">
                            </div>
                            <div class="slide-contenido">
                                <h3 class="slide-titulo">Notas de Fondo</h3>
                                <p class="slide-descripcion">Son las que fijan la esencia con aromas intensos y persistentes. Proporcionan profundidad y durabilidad a la fragancia, dejando un rastro memorable.</p>
                                <ul class="slide-caracteristicas">
                                    <li><i class="fas fa-check"></i> Base de la fragancia</li>
                                    <li><i class="fas fa-check"></i> Aromas intensos y persistentes</li>
                                    <li><i class="fas fa-check"></i> Ejemplos: maderas, ámbares, musk</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="slider-progress">
                        <div class="progress-bar" id="progressBar"></div>
                    </div>

                    <div class="slider-controls">
                        <button class="slider-btn" id="prevBtn">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="slider-btn" id="nextBtn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="slider-dots">
                        <span class="dot active" data-slide="0"></span>
                        <span class="dot" data-slide="1"></span>
                        <span class="dot" data-slide="2"></span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php
    include '../includes/footer.php';
    ?>
</body>
<script>
    // Validación básica del formulario
    document.getElementById('formulario-contacto').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('¡Gracias por tu mensaje! Te contactaremos pronto.');
        this.reset();
    });
</script>
<script src="../assets/js/presentaciones.js"></script>
<script src="../assets/js/productos.js"></script>
<script src="../assets/js/sliderNotas.js"></script>

</html>