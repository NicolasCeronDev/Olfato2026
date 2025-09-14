// Variables globales del carrito
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

// Elementos del DOM del carrito
const botonCarrito = document.getElementById('boton-carrito');
const carritoElement = document.getElementById('carrito');
const overlayCarrito = document.getElementById('overlay-carrito');
const cerrarCarrito = document.getElementById('cerrar-carrito');
const contenidoCarrito = document.getElementById('contenido-carrito');
const subtotalCarrito = document.getElementById('subtotal-carrito');
const descuentoCarrito = document.getElementById('descuento-carrito');
const totalCarrito = document.getElementById('total-carrito');
const botonPago = document.getElementById('boton-pago');

// Inicialización del carrito
document.addEventListener('DOMContentLoaded', function () {
    actualizarContadorCarrito();
    renderizarCarrito();

    // Event listeners del carrito
    botonCarrito.addEventListener('click', abrirCarrito);
    overlayCarrito.addEventListener('click', cerrarCarritoFunc);
    cerrarCarrito.addEventListener('click', cerrarCarritoFunc);
    botonPago.addEventListener('click', finalizarCompra);
});

// Funciones del carrito
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

// Funciones globales para los botones onclick
window.cambiarCantidadCarrito = cambiarCantidadCarrito;
window.eliminarItemCarrito = eliminarItemCarrito;