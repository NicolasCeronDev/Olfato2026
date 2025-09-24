document.addEventListener('DOMContentLoaded', function() {
            const sliderContainer = document.querySelector('.slider-contenedor');
            const slides = document.querySelectorAll('.slide');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const dots = document.querySelectorAll('.dot');
            const progressBar = document.getElementById('progressBar');
            
            let currentSlide = 0;
            const totalSlides = slides.length;
            let autoSlideInterval;
            
            // Función para actualizar el slider
            function updateSlider() {
                sliderContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
                
                // Actualizar dots
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
                
                // Actualizar barra de progreso
                progressBar.style.transform = `translateX(${currentSlide * 100}%)`;
                
                // Reiniciar el intervalo de auto-slide
                resetAutoSlide();
            }
            
            // Navegación siguiente
            nextBtn.addEventListener('click', function() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
            });
            
            // Navegación anterior
            prevBtn.addEventListener('click', function() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateSlider();
            });
            
            // Navegación por dots
            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    currentSlide = parseInt(this.getAttribute('data-slide'));
                    updateSlider();
                });
            });
            
            // Auto-slide cada 5 segundos
            function startAutoSlide() {
                autoSlideInterval = setInterval(function() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    updateSlider();
                }, 5000);
            }
            
            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                startAutoSlide();
            }
            
            // Iniciar auto-slide
            startAutoSlide();
            
            // Pausar auto-slide al hacer hover
            const slider = document.querySelector('.slider-notas');
            slider.addEventListener('mouseenter', function() {
                clearInterval(autoSlideInterval);
            });
            
            slider.addEventListener('mouseleave', function() {
                startAutoSlide();
            });
        });