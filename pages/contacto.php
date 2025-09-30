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
<?php
include '../includes/header.php';
?>

<!-- Formulario -->
<section class="contact-form-section">
    <form class="contact-form">
        <h2 class="form-title">Déjanos tus datos y te contáctaremos</h2>
        <p class="form-subtitle">Nuestro equipo estará encantado de atenderte lo antes posible</p>
        <div class="input-group"><i class="fa-solid fa-user"></i><input type="text" placeholder="Nombre completo"></div>
        <div class="input-group"><i class="fa-solid fa-envelope"></i><input type="email" placeholder="Correo electrónico"></div>
        <div class="input-group"><i class="fa-solid fa-phone"></i><input type="tel" placeholder="Número telefónico"></div>
        <div class="input-group"><i class="fa-solid fa-tag"></i><input type="text" placeholder="Asunto"></div>
        <div class="input-group"><i class="fa-solid fa-building"></i><input type="text" placeholder="Compañía / Organización"></div>
        <div class="input-group textarea"><i class="fa-solid fa-comment"></i><textarea placeholder="Escribe tu mensaje..."></textarea></div>
        <button type="submit" class="btn-submit">Enviar mensaje</button>
    </form>
</section>

<!-- Info rápida -->
<section class="quick-info">
    <div class="info-card">
        <i class="fa-solid fa-clock"></i>
        <h3>Horarios</h3>
        <p>Lunes a Sábado<br>9:00 am - 7:00 pm</p>
    </div>
    <div class="info-card">
        <i class="fa-solid fa-location-dot"></i>
        <h3>Ubicación</h3>
        <p>Cl. 66 #1b-67, Neiva, Huila</p>
    </div>
    <div class="info-card">
        <i class="fa-solid fa-phone-volume"></i>
        <h3>Teléfono</h3>
        <p>+57 316 423 85351</p>
    </div>
</section>

<!-- Mapa -->
<section class="map-section">
    <h2>Nuestra ubicación</h2>
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3984.480257372606!2d-75.294128!3d2.964158!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3b75a9d99e0c2b%3A0x7c0674a5466b3c08!2sCl.%2066%20%231b-67%2C%20Neiva%2C%20Huila!5e0!3m2!1ses!2sco!4v1758499546789!5m2!1ses!2sco" width="80%" height="50%" style="border:10%;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>

</section>

<!-- FAQ -->
<section class="faq">
    <h2>Preguntas Frecuentes</h2>

    <details class="faq-item">
        <summary>¿Hacen envíos a todo el país?</summary>
        <p>Sí, realizamos envíos a todo el territorio colombiano a través de transportadoras seguras y confiables.</p>
    </details>

    <details class="faq-item">
        <summary>¿Cuánto tarda el envío?</summary>
        <p>El tiempo de entrega varía según la ciudad. Generalmente entre <strong>2 a 5 días hábiles</strong> después de confirmar tu pedido.</p>
    </details>

    <details class="faq-item">
        <summary>¿Qué métodos de pago aceptan?</summary>
        <p>Aceptamos pagos con <strong>tarjeta de crédito, débito, transferencias, PSE y pagos contra entrega</strong> en algunas ciudades.</p>
    </details>
</section>


<!-- Botón WhatsApp -->
<a href="https://wa.me/5731642385351" class="whatsapp-btn" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<script>
    // FAQ toggle
    document.querySelectorAll(".faq-question").forEach(button => {
        button.addEventListener("click", () => {
            const answer = button.nextElementSibling;
            answer.classList.toggle("active");
        });
    });
</script>



<?php
include '../includes/footer.php';
?>

</body>

</html>