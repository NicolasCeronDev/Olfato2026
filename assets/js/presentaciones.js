// Mapeo de colores a imágenes (ejemplo con imágenes de Unsplash)
        const imagenesPorColor = {
            'dorado': {
                '30ml': '/assets/Contenido/Presentaciones/30ml/Dorado.png',
                '60ml': '/assets/Contenido/Presentaciones/60ml/Dorado.png',
                '100ml': '/'
            },
            'negro': {
                '30ml': '/assets/Contenido/Presentaciones/30ml/Negro.png',
                '60ml': '/assets/Contenido/Presentaciones/60ml/Negro.png',
                '100ml': '//'
            },
            'rojo': {
                '30ml': '/assets/Contenido/Presentaciones/30ml/Rojo.png',
                '60ml': '/assets/Contenido/Presentaciones/60ml/Rojo.png',
                '100ml': ''
            },
            'azul': {
                '30ml': '/assets/Contenido/Presentaciones/30ml/Azul.png',
                '60ml': '/assets/Contenido/Presentaciones/60ml/Azul.png',
                '100ml': '//'
            },
            'fucsia': {
                '30ml': '/assets/Contenido/Presentaciones/30ml/Fucsia.png',
                '60ml': '/assets/Contenido/Presentaciones/60ml/Fucsia.png',
                '100ml': '/'
            },
            'morado': {
                '30ml': '/assets/Contenido/Presentaciones/30ml/Morado.png',
                '60ml': '/assets/Contenido/Presentaciones/60ml/Morado.png',
                '100ml': '/'
            },
            'verde': {
                '30ml': '/assets/Contenido/Presentaciones/30ml/Verde.png',
                '60ml': '/assets/Contenido/Presentaciones/60ml/Verde.png',
                '100ml': '/'
            },
            'default': {
                '30ml': '/assets/Contenido/Presentaciones/30ml/Default.png',
                '60ml': '/assets/Contenido/Presentaciones/60ml/Default.png',
                '100ml': '/'
            }
        };

        // Función para cambiar la imagen según el color seleccionado
        function cambiarImagen(tamaño, color) {
            const imagen = document.getElementById(`imagen-${tamaño}`);
            
            if (color && imagenesPorColor[color] && imagenesPorColor[color][tamaño]) {
                imagen.src = imagenesPorColor[color][tamaño];
            } else {
                imagen.src = imagenesPorColor['default'][tamaño];
            }
            
            // Añadir efecto de transición suave
            imagen.style.opacity = '0';
            setTimeout(() => {
                imagen.style.opacity = '1';
            }, 300);
        }

        // Inicializar opacidad para transición
        document.querySelectorAll('.presentacion-imagen').forEach(img => {
            img.style.transition = 'opacity 0.3s ease';
        });