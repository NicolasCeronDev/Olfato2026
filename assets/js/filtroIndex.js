// Filtrado básico de productos - VERSIÓN SIMPLE
document.addEventListener('DOMContentLoaded', function() {
    const botonesCategoria = document.querySelectorAll('.categoria-boton');
    
    botonesCategoria.forEach(boton => {
        boton.addEventListener('click', function() {
            const categoria = this.getAttribute('data-categoria');
            
            // Recargar la página con parámetro de categoría
            window.location.href = window.location.pathname + '?categoria=' + categoria;
        });
    });
    
    // Hacer scroll suave a la sección de productos después de recargar
    if (window.location.search.includes('categoria=')) {
        setTimeout(() => {
            const seccionProductos = document.getElementById('productos');
            if (seccionProductos) {
                seccionProductos.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 100);
    }
});

function filtrarProductos(categoria) {
    const productos = document.querySelectorAll('.producto-tarjeta');
    let productosVisibles = 0;
    const limite = 6;
    
    productos.forEach(producto => {
        const productoCategoria = producto.getAttribute('data-categoria');
        
        if (categoria === 'todos' || productoCategoria.toLowerCase().includes(categoria.toLowerCase())) {
            if (productosVisibles < limite) {
                producto.style.display = 'block';
                productosVisibles++;
            } else {
                producto.style.display = 'none';
            }
        } else {
            producto.style.display = 'none';
        }
    });
}