document.addEventListener('DOMContentLoaded', function() {
    // Navegación por categorías visuales
    const seccionesCategorias = document.querySelectorAll('.categoria-seccion');
    
    seccionesCategorias.forEach(seccion => {
        seccion.addEventListener('click', function() {
            const categoria = this.getAttribute('data-categoria');
            
            // Hacer scroll suave a la sección correspondiente
            const seccionProductos = document.getElementById('contenedor-' + categoria);
            if (seccionProductos) {
                seccionProductos.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Efecto hover mejorado para categorías
    seccionesCategorias.forEach(seccion => {
        seccion.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        seccion.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});