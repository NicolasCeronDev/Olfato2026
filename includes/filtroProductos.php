<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olfato Perfumeria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="../assets/css/General.css">
    <link rel="stylesheet" href="../assets/css/FiltroProductos.css">
</head>
<body>
    <!-- Panel de filtros -->
<aside class="filtros-panel">
    <h2 class="filtros-titulo">Filtrar Productos</h2>

    <!-- Filtro por Categoría -->
    <div class="filtro-grupo">
        <h3><i class="fas fa-tags"></i> Categoría</h3>
        <div class="filtro-opciones">
            <div class="filtro-opcion activo" data-categoria="todos">
                <div class="filtro-checkbox"></div>
                <span>Todos los productos</span>
                <span class="filtro-contador">48</span>
            </div>
            <div class="filtro-opcion" data-categoria="hombre">
                <div class="filtro-checkbox"></div>
                <span>Perfumes Hombre</span>
                <span class="filtro-contador">12</span>
            </div>
            <div class="filtro-opcion" data-categoria="mujer">
                <div class="filtro-checkbox"></div>
                <span>Perfumes Mujer</span>
                <span class="filtro-contador">18</span>
            </div>
            <div class="filtro-opcion" data-categoria="arabes">
                <div class="filtro-checkbox"></div>
                <span>Perfumes Árabes</span>
                <span class="filtro-contador">8</span>
            </div>
            <div class="filtro-opcion" data-categoria="unisex">
                <div class="filtro-checkbox"></div>
                <span>Fragancias Unisex</span>
                <span class="filtro-contador">10</span>
            </div>
        </div>
    </div>

    <!-- Filtro por Notas Olfativas -->
    <div class="filtro-grupo">
        <h3><i class="fas fa-wind"></i> Notas Olfativas</h3>
        <div class="filtro-opciones">
            <div class="filtro-opcion" data-nota="amaderado">
                <div class="filtro-checkbox"></div>
                <span>Amaderado</span>
                <span class="filtro-contador">6</span>
            </div>
            <div class="filtro-opcion" data-nota="citrico">
                <div class="filtro-checkbox"></div>
                <span>Cítrico</span>
                <span class="filtro-contador">5</span>
            </div>
            <div class="filtro-opcion" data-nota="oriental">
                <div class="filtro-checkbox"></div>
                <span>Oriental</span>
                <span class="filtro-contador">7</span>
            </div>
            <div class="filtro-opcion" data-nota="floral">
                <div class="filtro-checkbox"></div>
                <span>Floral</span>
                <span class="filtro-contador">8</span>
            </div>
            <div class="filtro-opcion" data-nota="frutal">
                <div class="filtro-checkbox"></div>
                <span>Frutal</span>
                <span class="filtro-contador">6</span>
            </div>
            <div class="filtro-opcion" data-nota="gourmand">
                <div class="filtro-checkbox"></div>
                <span>Gourmand</span>
                <span class="filtro-contador">4</span>
            </div>
        </div>
    </div>

    <!-- Filtro por Ofertas -->
    <div class="filtro-grupo">
        <h3><i class="fas fa-percentage"></i> Ofertas</h3>
        <div class="filtro-opciones">
            <div class="filtro-opcion" data-oferta="true">
                <div class="filtro-checkbox"></div>
                <span>En Oferta</span>
                <span class="filtro-contador">5</span>
            </div>
        </div>
    </div>

    <button class="boton ancho-completo" style="margin-top: 20px;" id="limpiar-filtros">
        <i class="fas fa-times"></i> Limpiar Filtros
    </button>
</aside>

<!-- Área de productos -->
<section class="productos-area">
    <div class="productos-header">
        <div class="productos-contador">
            Mostrando <span id="contador-productos">0</span> de 48 productos
        </div>
        <div class="productos-ordenamiento">
            <label for="ordenar">Ordenar por:</label>
            <select id="ordenar" class="ordenamiento-select">
                <option value="popularidad">Popularidad</option>
                <option value="nombre">Nombre A-Z</option>
                <option value="precio-asc">Precio: Menor a Mayor</option>
                <option value="precio-desc">Precio: Mayor a Menor</option>
                <option value="nuevos">Más Nuevos</option>
            </select>
        </div>
    </div>

    <!-- Mensaje de productos (los productos vendrán del backend) -->
    <div class="mensaje-productos">
        <i class="fas fa-cube"></i>
        <h3>Productos Cargados desde el Backend</h3>
        <p>Aquí se mostrarán todos los productos filtrados según tu selección.<br>
            La información se cargará dinámicamente desde la base de datos.</p>
    </div>
</section>
</body>
</html>