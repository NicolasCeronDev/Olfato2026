<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olfato Perfumeria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
    <link rel="stylesheet" href="../assets/css/contacto.css">
</head>
<body>
    <?php
    include '../includes/header.php';
    ?>
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
</html>