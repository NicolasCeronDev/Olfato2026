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


        <!-- Sección Emprende -->
        <section id="emprende" class="seccion emprende">
            <div class="contenedor">
                <div class="titulo-seccion">
                    <h2>EMPRENDE CON NOSOTROS</h2>
                    <div class="separador"></div>
                    <p>Únete a nuestra red de distribuidores y comienza tu propio negocio con Olfato Perfumería.</p>
                </div>

                <div class="emprende-contenido">
                    <div class="emprende-info">
                        <h3 class="emprende-titulo">Oportunidad de Negocio</h3>
                        <p>Ofrecemos un modelo de negocio probado con márgenes atractivos, capacitación completa y apoyo
                            continuo para que puedas emprender con éxito en el mundo de la perfumería.</p>

                        <ul class="emprende-lista">
                            <li class="emprende-item">
                                <i class="fas fa-check emprende-icono"></i>
                                <span>Márgenes de ganancia de hasta el 50%</span>
                            </li>
                            <li class="emprende-item">
                                <i class="fas fa-check emprende-icono"></i>
                                <span>Productos de alta calidad y exclusivos</span>
                            </li>
                            <li class="emprende-item">
                                <i class="fas fa-check emprende-icono"></i>
                                <span>Capacitación en ventas y marketing</span>
                            </li>
                            <li class="emprende-item">
                                <i class="fas fa-check emprende-icono"></i>
                                <span>Soporte continuo y material promocional</span>
                            </li>
                        </ul>

                        <a href="#contacto" class="boton">QUIERO MÁS INFORMACIÓN</a>
                    </div>

                    <div class="emprende-imagen-contenedor">
                        <img src="/assets/Contenido/Local/NuestroLocal.png"
                            alt="Emprende con Olfato" class="emprende-imagen">
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección Contacto -->
        <section id="contacto" class="seccion contacto">
            <div class="contenedor">
                <div class="titulo-seccion">
                    <h2>CONTÁCTANOS</h2>
                    <div class="separador"></div>
                    <p>Estamos aquí para responder tus preguntas y ayudarte a encontrar la fragancia perfecta.</p>
                </div>

                <div class="contacto-contenido">
                    <div>
                        <form id="formulario-contacto" class="formulario">
                            <div class="formulario-grupo doble">
                                <div>
                                    <label for="nombre" class="formulario-etiqueta">Nombre</label>
                                    <input type="text" id="nombre" name="nombre" class="formulario-control" required>
                                </div>
                                <div>
                                    <label for="email" class="formulario-etiqueta">Email</label>
                                    <input type="email" id="email" name="email" class="formulario-control" required>
                                </div>
                            </div>

                            <div class="formulario-grupo">
                                <label for="asunto" class="formulario-etiqueta">Asunto</label>
                                <input type="text" id="asunto" name="asunto" class="formulario-control" required>
                            </div>

                            <div class="formulario-grupo">
                                <label for="mensaje" class="formulario-etiqueta">Mensaje</label>
                                <textarea id="mensaje" name="mensaje" class="formulario-control" required></textarea>
                            </div>

                            <button type="submit" class="boton ancho-completo">ENVIAR MENSAJE</button>
                        </form>

                        <div class="contacto-info">
                            <div class="contacto-item">
                                <div class="contacto-icono">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h4>Ubicación</h4>
                                    <p>Cl. 66 #1b-67, Neiva</p>
                                </div>
                            </div>

                            <div class="contacto-item">
                                <div class="contacto-icono">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <h4>Teléfono</h4>
                                    <p>+57 (316) 4238 5351</p>
                                </div>
                            </div>

                            <div class="contacto-item">
                                <div class="contacto-icono">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h4>Email</h4>
                                    <p>Olfato@PerfumeriaColombia.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mapa">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3984.480257372606!2d-75.294128!3d2.964158!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3b75a9d99e0c2b%3A0x7c0674a5466b3c08!2sCl.%2066%20%231b-67%2C%20Neiva%2C%20Huila!5e0!3m2!1ses!2sco!4v1758499546789!5m2!1ses!2sco" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
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
<script src="/assets/js/presentaciones.js"></script>

</html>