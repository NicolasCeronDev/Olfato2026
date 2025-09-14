// Variables globales del carrito
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

// Elementos del DOM del carrito Y menú móvil
const botonMenuMovil = document.getElementById('boton-menu-movil');
const menuPrincipal = document.getElementById('menu-principal');
const overlayMenu = document.getElementById('overlay-menu');
const botonCarrito = document.getElementById('boton-carrito');
const carritoElement = document.getElementById('carrito');
const overlayCarrito = document.getElementById('overlay-carrito');
const cerrarCarrito = document.getElementById('cerrar-carrito');
const contenidoCarrito = document.getElementById('contenido-carrito');
const subtotalCarrito = document.getElementById('subtotal-carrito');
const descuentoCarrito = document.getElementById('descuento-carrito');
const totalCarrito = document.getElementById('total-carrito');
const botonPago = document.getElementById('boton-pago');

// Inicialización del carrito Y menú móvil
document.addEventListener('DOMContentLoaded', function () {
    actualizarContadorCarrito();
    renderizarCarrito();

    // Event listeners del carrito Y menú móvil
    botonMenuMovil.addEventListener('click', toggleMenuMovil);
    overlayMenu.addEventListener('click', cerrarMenuMovil);
    botonCarrito.addEventListener('click', abrirCarrito);
    overlayCarrito.addEventListener('click', cerrarCarritoFunc);
    cerrarCarrito.addEventListener('click', cerrarCarritoFunc);
    botonPago.addEventListener('click', finalizarCompra);
    
    // Cerrar menú al hacer clic en un enlace (solo móvil)
    const enlacesMenu = document.querySelectorAll('.menu-enlace');
    enlacesMenu.forEach(enlace => {
        enlace.addEventListener('click', cerrarMenuMovil);
    });
});

// ===== FUNCIONES DEL MENÚ MÓVIL =====
function toggleMenuMovil() {
    menuPrincipal.classList.toggle('activo');
    overlayMenu.classList.toggle('activo');
    botonMenuMovil.classList.toggle('activo');
    
    if (menuPrincipal.classList.contains('activo')) {
        document.body.style.overflow = 'hidden';
        botonMenuMovil.innerHTML = '<i class="fas fa-times"></i>';
        botonMenuMovil.setAttribute('aria-label', 'Cerrar menú');
    } else {
        document.body.style.overflow = 'auto';
        botonMenuMovil.innerHTML = '<i class="fas fa-bars"></i>';
        botonMenuMovil.setAttribute('aria-label', 'Abrir menú');
    }
}

function cerrarMenuMovil() {
    menuPrincipal.classList.remove('activo');
    overlayMenu.classList.remove('activo');
    botonMenuMovil.classList.remove('activo');
    document.body.style.overflow = 'auto';
    botonMenuMovil.innerHTML = '<i class="fas fa-bars"></i>';
    botonMenuMovil.setAttribute('aria-label', 'Abrir menú');
}

// ===== FUNCIONES DEL CARRITO =====
function abrirCarrito() {
    carritoElement.classList.add('activo');
    overlayCarrito.classList.add('activo');
    document.body.style.overflow = 'hidden';
}

function cerrarCarritoFunc() {
    carritoElement.classList.remove('activo');
    overlayCarrito.classList.remove('activo');
    document.body.style.overflow = 'auto';
}

function actualizarContadorCarrito() {
    const contador = document.getElementById('contador-carrito');
    const totalItems = carrito.reduce((total, item) => total + item.cantidad, 0);
    contador.textContent = totalItems;
}

function renderizarCarrito() {
    if (carrito.length === 0) {
        contenidoCarrito.innerHTML = `
            <div class="carrito-vacio">
                <i class="fas fa-shopping-bag carrito-vacio-icono"></i>
                <p>Tu carrito está vacío</p>
            </div>
        `;
        botonPago.disabled = true;
        botonPago.classList.add('deshabilitado');
    } else {
        contenidoCarrito.innerHTML = '';
        let subtotal = 0;
        let descuentoTotal = 0;

        carrito.forEach((item, index) => {
            const precioReal = item.precio * item.cantidad;
            const precioDescuento = item.precio * (1 - item.descuento / 100) * item.cantidad;
            const descuentoItem = precioReal - precioDescuento;

            subtotal += precioReal;
            descuentoTotal += descuentoItem;

            const elementoItem = document.createElement('div');
            elementoItem.classList.add('carrito-item');
            elementoItem.innerHTML = `
                <div class="carrito-item-imagen">
                    <img src="${item.imagen}" alt="${item.nombre}">
                </div>
                <div class="carrito-item-info">
                    <h4 class="carrito-item-titulo">${item.nombre}</h4>
                    <p class="carrito-item-detalles">${item.tamano} • ${item.color}</p>
                    <div class="carrito-item-cantidad">
                        <button class="cantidad-boton" onclick="cambiarCantidadCarrito(${index}, -1)">-</button>
                        <span class="cantidad-valor">${item.cantidad}</span>
                        <button class="cantidad-boton" onclick="cambiarCantidadCarrito(${index}, 1)">+</button>
                    </div>
                </div>
                <div class="carrito-item-precio">
                    <div class="carrito-item-precio-actual">$${precioDescuento.toFixed(2)}</div>
                    ${item.descuento > 0 ? `<div class="carrito-item-precio-anterior">$${precioReal.toFixed(2)}</div>` : ''}
                </div>
                <button class="carrito-item-eliminar" onclick="eliminarItemCarrito(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            contenidoCarrito.appendChild(elementoItem);
        });

        subtotalCarrito.textContent = `$${subtotal.toFixed(2)}`;
        descuentoCarrito.textContent = `-$${descuentoTotal.toFixed(2)}`;
        totalCarrito.textContent = `$${(subtotal - descuentoTotal).toFixed(2)}`;

        botonPago.disabled = false;
        botonPago.classList.remove('deshabilitado');
    }
}

function cambiarCantidadCarrito(index, cambio) {
    if (carrito[index].cantidad + cambio > 0) {
        carrito[index].cantidad += cambio;
        guardarCarrito();
        actualizarContadorCarrito();
        renderizarCarrito();
    }
}

function eliminarItemCarrito(index) {
    carrito.splice(index, 1);
    guardarCarrito();
    actualizarContadorCarrito();
    renderizarCarrito();
}

function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

function finalizarCompra() {
    if (carrito.length > 0) {
        alert('Redirigiendo al proceso de pago...');
        // Aquí iría la lógica para redirigir a la página de pago
    }
}

// Cerrar menú móvil al redimensionar la ventana si se hace más grande
window.addEventListener('resize', function() {
    if (window.innerWidth > 768 && menuPrincipal.classList.contains('activo')) {
        cerrarMenuMovil();
    }
});

// Exponer funciones globales para los botones onclick
window.cambiarCantidadCarrito = cambiarCantidadCarrito;
window.eliminarItemCarrito = eliminarItemCarrito;