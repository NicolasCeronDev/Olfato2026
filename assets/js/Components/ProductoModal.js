// JavaScript para el modal de producto
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const modalProducto = document.getElementById('modal-producto');
    const modalCerrar = document.getElementById('modal-cerrar');
    const botonesAbrirModal = document.querySelectorAll('.abrir-modal');
    const botonConfirmarCarrito = document.getElementById('confirmar-carrito');
    const cantidadValor = document.getElementById('cantidad-valor');
    const restarCantidad = document.getElementById('restar-cantidad');
    const sumarCantidad = document.getElementById('sumar-cantidad');
    const precioTotal = document.getElementById('precio-total');
    
    // Variables para almacenar datos del producto
    let productoActual = {};
    let tamañoSeleccionado = '100ml';
    let colorSeleccionado = 'dorado';
    let cantidad = 1;
    
    // Abrir modal al hacer clic en cualquier parte de la tarjeta del producto
    document.querySelectorAll('.producto-tarjeta').forEach(tarjeta => {
        tarjeta.addEventListener('click', function(e) {
            // Evitar que se abra cuando se hace clic en el botón "AÑADIR AL CARRITO"
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
            e.stopPropagation(); // Evitar que el evento se propague a la tarjeta
            abrirModalConProducto(this);
        });
    });
    
    // Función para abrir el modal con los datos del producto
    function abrirModalConProducto(boton) {
        // Obtener datos del producto desde los atributos data
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
        
        // Actualizar el contenido del modal
        document.getElementById('modal-titulo').textContent = productoActual.nombre;
        document.getElementById('modal-descripcion').textContent = productoActual.descripcion;
        document.getElementById('modal-imagen').src = '../assets/' + productoActual.imagen;
        document.getElementById('modal-imagen').alt = productoActual.nombre;
        
        // Generar opciones de tamaño
        const tamañosOpciones = document.getElementById('tamaños-opciones');
        tamañosOpciones.innerHTML = '';
        
        Object.keys(productoActual.precios).forEach(tamaño => {
            if (productoActual.precios[tamaño] > 0) {
                const opcion = document.createElement('div');
                opcion.className = `tamaño-opcion ${tamaño === '100ml' ? 'seleccionado' : ''}`;
                opcion.textContent = tamaño;
                opcion.setAttribute('data-tamaño', tamaño);
                opcion.setAttribute('data-precio', productoActual.precios[tamaño]);
                
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
        
        // Generar opciones de color
        const coloresOpciones = document.getElementById('colores-opciones');
        if (!coloresOpciones) {
            // Si no existe el contenedor de colores, lo creamos
            const opcionGrupoColores = document.createElement('div');
            opcionGrupoColores.className = 'opcion-grupo';
            opcionGrupoColores.innerHTML = `
                <label>Color</label>
                <div class="colores-opciones" id="colores-opciones">
                    <div class="color-opcion color-dorado seleccionado" data-color="dorado"></div>
                    <div class="color-opcion color-negro" data-color="negro"></div>
                    <div class="color-opcion color-rojo" data-color="rojo"></div>
                    <div class="color-opcion color-azul" data-color="azul"></div>
                    <div class="color-opcion color-fucsia" data-color="fucsia"></div>
                    <div class="color-opcion color-morado" data-color="morado"></div>
                    <div class="color-opcion color-verde" data-color="verde"></div>
                </div>
            `;
            document.querySelector('.modal-opciones').appendChild(opcionGrupoColores);
            
            // Agregar eventos a las opciones de color
            document.querySelectorAll('.color-opcion').forEach(opcion => {
                opcion.addEventListener('click', function() {
                    document.querySelectorAll('.color-opcion').forEach(opt => {
                        opt.classList.remove('seleccionado');
                    });
                    this.classList.add('seleccionado');
                    colorSeleccionado = this.getAttribute('data-color');
                });
            });
        } else {
            // Si ya existe, solo actualizamos la selección
            document.querySelectorAll('.color-opcion').forEach(opcion => {
                opcion.classList.remove('seleccionado');
                if (opcion.getAttribute('data-color') === 'dorado') {
                    opcion.classList.add('seleccionado');
                }
            });
            colorSeleccionado = 'dorado';
        }
        
        // Reiniciar cantidad
        cantidad = 1;
        cantidadValor.textContent = cantidad;
        
        // Actualizar precio total
        tamañoSeleccionado = '100ml';
        actualizarPrecioTotal();
        
        // Mostrar modal
        modalProducto.classList.add('activo');
        document.body.style.overflow = 'hidden';
    }
    
    // Cerrar modal
    modalCerrar.addEventListener('click', function() {
        modalProducto.classList.remove('activo');
        document.body.style.overflow = 'auto';
    });
    
    // Cerrar modal al hacer clic fuera del contenido
    window.addEventListener('click', function(e) {
        if (e.target === modalProducto) {
            modalProducto.classList.remove('activo');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Control de cantidad
    restarCantidad.addEventListener('click', function() {
        if (cantidad > 1) {
            cantidad--;
            cantidadValor.textContent = cantidad;
            actualizarPrecioTotal();
        }
    });
    
    sumarCantidad.addEventListener('click', function() {
        cantidad++;
        cantidadValor.textContent = cantidad;
        actualizarPrecioTotal();
    });
    
    // Actualizar precio total
    function actualizarPrecioTotal() {
        const precioBase = productoActual.precios[tamañoSeleccionado] || 0;
        const precioConDescuento = productoActual.descuento > 0 ? 
            precioBase * (1 - productoActual.descuento/100) : precioBase;
        const total = precioConDescuento * cantidad;
        
        precioTotal.textContent = total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Confirmar añadir al carrito
    botonConfirmarCarrito.addEventListener('click', function() {
        // Aquí iría la lógica para agregar al carrito
        const precioBase = productoActual.precios[tamañoSeleccionado] || 0;
        const precioConDescuento = productoActual.descuento > 0 ? 
            precioBase * (1 - productoActual.descuento/100) : precioBase;
        const total = precioConDescuento * cantidad;
        
        alert(`Producto agregado al carrito:\n\n${productoActual.nombre}\nTamaño: ${tamañoSeleccionado}\nColor: ${colorSeleccionado}\nCantidad: ${cantidad}\nTotal: $${total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`);
        
        // Cerrar el modal después de agregar al carrito
        modalProducto.classList.remove('activo');
        document.body.style.overflow = 'auto';
    });
});