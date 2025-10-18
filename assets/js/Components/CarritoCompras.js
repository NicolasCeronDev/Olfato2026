// ===== SISTEMA UNIFICADO DE CARRITO =====
//Contiene la logica tanto para el label del producto para elegir opciones y cantidad.
//Envia el producto al carrito al presionar añadir al carrito 

// Variables globales
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

// Elementos del DOM
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

// ===== INICIALIZACIÓN =====
document.addEventListener('DOMContentLoaded', function () {
    inicializarCarrito();
    inicializarMenuMovil();
    inicializarModalProducto();
});

// ===== FUNCIONES DEL CARRITO =====
function inicializarCarrito() {
    actualizarContadorCarrito();
    renderizarCarrito();

    // Event listeners del carrito
    if (botonCarrito) {
        botonCarrito.addEventListener('click', abrirCarrito);
    }
    if (overlayCarrito) {
        overlayCarrito.addEventListener('click', cerrarCarritoFunc);
    }
    if (cerrarCarrito) {
        cerrarCarrito.addEventListener('click', cerrarCarritoFunc);
    }
    if (botonPago) {
        botonPago.addEventListener('click', finalizarCompra);
    }
}

function inicializarMenuMovil() {
    if (botonMenuMovil) {
        botonMenuMovil.addEventListener('click', toggleMenuMovil);
    }
    if (overlayMenu) {
        overlayMenu.addEventListener('click', cerrarMenuMovil);
    }
    
    // Cerrar menú al hacer clic en un enlace (solo móvil)
    const enlacesMenu = document.querySelectorAll('.menu-enlace');
    enlacesMenu.forEach(enlace => {
        enlace.addEventListener('click', cerrarMenuMovil);
    });
}

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
    renderizarCarrito();
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
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const totalItems = carrito.reduce((total, item) => total + item.cantidad, 0);
    
    const contador = document.getElementById('contador-carrito');
    if (contador) {
        contador.textContent = totalItems;
        contador.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

function renderizarCarrito() {
    carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    
    if (carrito.length === 0) {
        contenidoCarrito.innerHTML = `
            <div class="carrito-vacio">
                <i class="fas fa-shopping-bag carrito-vacio-icono"></i>
                <p>Tu carrito está vacío</p>
            </div>
        `;
        if (botonPago) {
            botonPago.disabled = true;
            botonPago.classList.add('deshabilitado');
        }
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
                    <img src="../assets/${item.imagen}" alt="${item.nombre}">
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

        if (subtotalCarrito) subtotalCarrito.textContent = `$${subtotal.toFixed(2)}`;
        if (descuentoCarrito) descuentoCarrito.textContent = `-$${descuentoTotal.toFixed(2)}`;
        if (totalCarrito) totalCarrito.textContent = `$${(subtotal - descuentoTotal).toFixed(2)}`;

        if (botonPago) {
            botonPago.disabled = false;
            botonPago.classList.remove('deshabilitado');
        }
    }
    
    actualizarContadorCarrito();
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

// ===== FUNCIONES DEL MODAL DE PRODUCTO =====
function inicializarModalProducto() {
    const modalProducto = document.getElementById('modal-producto');
    const modalCerrar = document.getElementById('modal-cerrar');
    const botonesAbrirModal = document.querySelectorAll('.abrir-modal');
    const botonConfirmarCarrito = document.getElementById('confirmar-carrito');
    
    if (!modalProducto || !botonConfirmarCarrito) return;

    let productoActual = {};
    let tamañoSeleccionado = '100ml';
    let colorSeleccionado = 'dorado';
    let cantidad = 1;

    // Abrir modal al hacer clic en cualquier parte de la tarjeta del producto
    document.querySelectorAll('.producto-tarjeta').forEach(tarjeta => {
        tarjeta.addEventListener('click', function(e) {
            if (!e.target.classList.contains('abrir-modal') && 
                !e.target.closest('.abrir-modal')) {
                const boton = this.querySelector('.abrir-modal');
                if (boton) {
                    abrirModalConProducto(boton);
                }
            }
        });
    });

    // Abrir modal al hacer clic en el botón "AÑADIR AL CARRITO"
    botonesAbrirModal.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.stopPropagation();
            abrirModalConProducto(this);
        });
    });

    function abrirModalConProducto(boton) {
        productoActual = {
            id: boton.getAttribute('data-producto-id'),
            nombre: boton.getAttribute('data-producto-nombre'),
            descripcion: boton.getAttribute('data-producto-descripcion'),
            imagen: boton.getAttribute('data-producto-imagen'),
            precios: {
                '30ml': parseFloat(boton.getAttribute('data-precio-30ml')),
                '60ml': parseFloat(boton.getAttribute('data-precio-60ml')),
                '100ml': parseFloat(boton.getAttribute('data-precio-100ml'))
            },
            descuento: parseFloat(boton.getAttribute('data-descuento'))
        };

        document.getElementById('modal-titulo').textContent = productoActual.nombre;
        document.getElementById('modal-descripcion').textContent = productoActual.descripcion;
        document.getElementById('modal-imagen').src = '../assets/' + productoActual.imagen;
        document.getElementById('modal-imagen').alt = productoActual.nombre;

        // Configurar opciones de tamaño
        const tamañosOpciones = document.getElementById('tamaños-opciones');
        tamañosOpciones.innerHTML = '';

        Object.keys(productoActual.precios).forEach(tamaño => {
            if (productoActual.precios[tamaño] > 0) {
                const opcion = document.createElement('div');
                opcion.className = `tamaño-opcion ${tamaño === '100ml' ? 'seleccionado' : ''}`;
                opcion.textContent = tamaño;
                opcion.setAttribute('data-tamaño', tamaño);
                
                opcion.addEventListener('click', function() {
                    document.querySelectorAll('.tamaño-opcion').forEach(opt => {
                        opt.classList.remove('seleccionado');
                    });
                    this.classList.add('seleccionado');
                    tamañoSeleccionado = this.getAttribute('data-tamaño');
                    actualizarPrecioTotal();
                });
                
                tamañosOpciones.appendChild(opcion);
            }
        });

        // Configurar opciones de color
        document.querySelectorAll('.color-opcion').forEach(opcion => {
            opcion.classList.remove('seleccionado');
            if (opcion.getAttribute('data-color') === 'dorado') {
                opcion.classList.add('seleccionado');
            }
            
            opcion.addEventListener('click', function() {
                document.querySelectorAll('.color-opcion').forEach(opt => {
                    opt.classList.remove('seleccionado');
                });
                this.classList.add('seleccionado');
                colorSeleccionado = this.getAttribute('data-color');
            });
        });

        // Reiniciar cantidad
        cantidad = 1;
        document.getElementById('cantidad-valor').textContent = cantidad;
        
        // Configurar controles de cantidad
        document.getElementById('restar-cantidad').addEventListener('click', function() {
            if (cantidad > 1) {
                cantidad--;
                document.getElementById('cantidad-valor').textContent = cantidad;
                actualizarPrecioTotal();
            }
        });

        document.getElementById('sumar-cantidad').addEventListener('click', function() {
            cantidad++;
            document.getElementById('cantidad-valor').textContent = cantidad;
            actualizarPrecioTotal();
        });

        tamañoSeleccionado = '100ml';
        actualizarPrecioTotal();
        
        modalProducto.classList.add('activo');
        document.body.style.overflow = 'hidden';
    }

    function actualizarPrecioTotal() {
        const precioBase = productoActual.precios[tamañoSeleccionado] || 0;
        const precioConDescuento = productoActual.descuento > 0 ? 
            precioBase * (1 - productoActual.descuento/100) : precioBase;
        const total = precioConDescuento * cantidad;
        
        document.getElementById('precio-total').textContent = total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Cerrar modal
    modalCerrar.addEventListener('click', function() {
        modalProducto.classList.remove('activo');
        document.body.style.overflow = 'auto';
    });

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target === modalProducto) {
            modalProducto.classList.remove('activo');
            document.body.style.overflow = 'auto';
        }
    });

    // ===== CORRECCIÓN PRINCIPAL: Reemplazar alert por función de carrito =====
    botonConfirmarCarrito.addEventListener('click', function() {
        const productoCarrito = {
            id: productoActual.id,
            nombre: productoActual.nombre,
            imagen: productoActual.imagen,
            tamano: tamañoSeleccionado,
            color: colorSeleccionado,
            cantidad: cantidad,
            precio: productoActual.precios[tamañoSeleccionado] || 0,
            descuento: productoActual.descuento || 0
        };
        
        agregarAlCarrito(productoCarrito);
        
        modalProducto.classList.remove('activo');
        document.body.style.overflow = 'auto';
    });
}

// ===== FUNCIÓN PARA AGREGAR AL CARRITO =====
function agregarAlCarrito(producto) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    
    const productoExistente = carrito.find(item => 
        item.id === producto.id && 
        item.tamano === producto.tamano && 
        item.color === producto.color
    );
    
    if (productoExistente) {
        productoExistente.cantidad += producto.cantidad;
    } else {
        carrito.push(producto);
    }
    
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContadorCarrito();
    mostrarNotificacion('Producto agregado al carrito');
}

// ===== FUNCIÓN DE NOTIFICACIÓN =====
function mostrarNotificacion(mensaje) {
    // Eliminar notificación existente si hay una
    const notificacionExistente = document.querySelector('.notificacion-carrito');
    if (notificacionExistente) {
        notificacionExistente.remove();
    }

    const notificacion = document.createElement('div');
    notificacion.className = 'notificacion-carrito';
    notificacion.textContent = mensaje;
    notificacion.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: var(--color-dorado);
        color: var(--color-fondo);
        padding: 12px 20px;
        border-radius: 5px;
        z-index: 10000;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        animation: slideInNotification 0.3s ease;
    `;
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.style.animation = 'slideOutNotification 0.3s ease';
        setTimeout(() => {
            if (notificacion.parentNode) {
                notificacion.parentNode.removeChild(notificacion);
            }
        }, 300);
    }, 3000);
}

// ===== AGREGAR ESTILOS PARA ANIMACIONES =====
const estilo = document.createElement('style');
estilo.textContent = `
    @keyframes slideInNotification {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutNotification {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(estilo);

// ===== FUNCIONES GLOBALES =====
window.cambiarCantidadCarrito = cambiarCantidadCarrito;
window.eliminarItemCarrito = eliminarItemCarrito;

// Cerrar menú móvil al redimensionar
window.addEventListener('resize', function() {
    if (window.innerWidth > 768 && menuPrincipal.classList.contains('activo')) {
        cerrarMenuMovil();
    }
});