<?php
// includes/Admin/admin_componentes.php

function admin_verificar_sesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['es_admin']) {
        header('Location: /olfato2026/pages/login.php');
        exit();
    }
}

function admin_head($titulo = 'Admin Olfato') {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($titulo) . '</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap">
        <link rel="stylesheet" href="/olfato2026/assets/css/General.css">
        <style>
            ' . admin_estilos() . '
        </style>
    </head>
    <body>';
}

function admin_estilos() {
    return '
    body {
        background: var(--color-gris-oscuro);
        margin: 0;
        padding: 0;
        font-family: "Montserrat", sans-serif;
        color: var(--color-texto);
    }

    .admin-container {
        display: flex;
        min-height: 100vh;
    }

    .admin-sidebar {
        width: 280px;
        background: var(--color-fondo);
        border-right: 1px solid var(--color-borde);
        padding: 30px 0;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar-header {
        padding: 0 30px 30px;
        border-bottom: 1px solid var(--color-borde);
        margin-bottom: 20px;
    }

    .logo-admin {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo-admin img {
        max-width: 150px;
        height: auto;
    }

    .admin-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .admin-menu li {
        margin: 0;
    }

    .admin-menu a {
        display: flex;
        align-items: center;
        padding: 15px 30px;
        color: var(--color-texto);
        text-decoration: none;
        transition: var(--transicion);
        border-left: 3px solid transparent;
    }

    .admin-menu a:hover {
        background: rgba(212, 175, 55, 0.1);
        color: var(--color-dorado);
    }

    .admin-menu a.activo {
        background: var(--color-dorado);
        color: var(--color-fondo);
        border-left-color: var(--color-dorado-oscuro);
    }

    .admin-menu i {
        width: 20px;
        margin-right: 12px;
        text-align: center;
    }

    .admin-main {
        flex: 1;
        margin-left: 280px;
        padding: 30px;
    }

    .page-header {
        background: var(--color-fondo);
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        border: 1px solid var(--color-borde);
    }

    .page-title {
        font-family: "Playfair Display", serif;
        color: var(--color-dorado);
        margin: 0 0 10px 0;
        font-size: 2.2rem;
    }

    .page-subtitle {
        color: var(--color-gris-claro);
        margin: 0;
        font-size: 1.1rem;
    }

    .content-section {
        background: var(--color-fondo);
        padding: 25px;
        border-radius: 10px;
        border: 1px solid var(--color-borde);
        margin-bottom: 25px;
    }

    .section-title {
        font-family: "Playfair Display", serif;
        color: var(--color-dorado);
        margin: 0 0 20px 0;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--color-gris-oscuro);
        border-radius: 8px;
        overflow: hidden;
    }

    .data-table th,
    .data-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid var(--color-borde);
    }

    .data-table th {
        background: rgba(0,0,0,0.3);
        color: var(--color-dorado);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .data-table tr:hover {
        background: rgba(255,255,255,0.03);
    }

    .btn {
        padding: 8px 16px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: var(--transicion);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--color-dorado);
        color: var(--color-fondo);
    }

    .btn-primary:hover {
        background: var(--color-dorado-oscuro);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: transparent;
        color: var(--color-dorado);
        border: 1px solid var(--color-dorado);
    }

    .btn-secondary:hover {
        background: var(--color-dorado);
        color: var(--color-fondo);
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    .btn-success {
        background: #27ae60;
        color: white;
    }

    .btn-success:hover {
        background: #229954;
        transform: translateY(-2px);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge.pendiente { background: #f39c12; color: white; }
    .badge.enviado { background: #3498db; color: white; }
    .badge.entregado { background: #27ae60; color: white; }
    .badge.cancelado { background: #e74c3c; color: white; }
    .badge.activo { background: #27ae60; color: white; }
    .badge.inactivo { background: #95a5a6; color: white; }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: var(--color-texto);
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        background: var(--color-gris-oscuro);
        border: 1px solid var(--color-borde);
        border-radius: 5px;
        color: var(--color-texto);
        font-family: inherit;
        font-size: 1rem;
        transition: var(--transicion);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-dorado);
        box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-group input {
        width: auto;
    }

    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: var(--color-gris-claro);
    }

    .no-data i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
        color: var(--color-dorado);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background: rgba(39, 174, 96, 0.1);
        border: 1px solid #27ae60;
        color: #27ae60;
    }

    .alert-error {
        background: rgba(231, 76, 60, 0.1);
        border: 1px solid #e74c3c;
        color: #e74c3c;
    }

    .alert-warning {
        background: rgba(243, 156, 18, 0.1);
        border: 1px solid #f39c12;
        color: #f39c12;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--color-fondo);
        padding: 25px;
        border-radius: 10px;
        border: 1px solid var(--color-borde);
        text-align: center;
        transition: var(--transicion);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--sombra-caja);
    }

    .stat-card.dorado {
        background: var(--color-dorado);
        color: var(--color-fondo);
    }

    .stat-icon {
        font-size: 2.2rem;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin: 10px 0;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .alert-stock {
        color: #e74c3c;
        font-weight: 600;
    }

    @media (max-width: 1024px) {
        .admin-sidebar {
            width: 250px;
        }
        
        .admin-main {
            margin-left: 250px;
        }
    }

    @media (max-width: 768px) {
        .admin-container {
            flex-direction: column;
        }
        
        .admin-sidebar {
            position: static;
            width: 100%;
            height: auto;
        }
        
        .admin-main {
            margin-left: 0;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
    ';
}

function admin_sidebar($pagina_activa = 'dashboard') {
    // Definir rutas absolutas base
    $base_url = '/olfato2026/pages/Admin';
    
    $menu_items = [
        'dashboard' => [
            'icon' => 'chart-bar', 
            'text' => 'Dashboard', 
            'url' => $base_url . '/dashboard.php'
        ],
        'productos' => [
            'icon' => 'cube', 
            'text' => 'Productos', 
            'url' => $base_url . '/productos/index.php'
        ],
        'pedidos' => [
            'icon' => 'shopping-cart', 
            'text' => 'Pedidos', 
            'url' => $base_url . '/pedidos/index.php'
        ],
        'usuarios' => [
            'icon' => 'users', 
            'text' => 'Usuarios', 
            'url' => $base_url . '/usuarios/index.php'
        ],
        'ofertas' => [
            'icon' => 'percentage', 
            'text' => 'Ofertas', 
            'url' => $base_url . '/ofertas/index.php'
        ]
    ];
    
    echo '<div class="admin-sidebar">
        <div class="sidebar-header">
            <div class="logo-admin">
                <img src="/olfato2026/assets/Contenido/Local/LogoSinfondo.png" alt="Olfato Perfumería">
            </div>
            <div style="text-align: center;">
                <h3 style="color: var(--color-dorado); margin: 0 0 5px 0; font-size: 1.1rem;">Panel de Administración</h3>
                <p style="color: var(--color-gris-claro); margin: 0; font-size: 0.9rem;">' . $_SESSION['usuario']['nombre'] . '</p>
            </div>
        </div>
        
        <ul class="admin-menu">';
        
    foreach ($menu_items as $key => $item) {
        $activo = ($pagina_activa == $key) ? 'activo' : '';
        echo '<li><a href="' . $item['url'] . '" class="' . $activo . '"><i class="fas fa-' . $item['icon'] . '"></i> ' . $item['text'] . '</a></li>';
    }
    
    echo '<li><a href="/olfato2026/pages/mi-cuenta.php"><i class="fas fa-user"></i> Mi Cuenta</a></li>
            <li><a href="/olfato2026/pages/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </div>';
}

function admin_header($titulo, $subtitulo = '') {
    echo '<div class="page-header">
        <h1 class="page-title">' . htmlspecialchars($titulo) . '</h1>';
    if ($subtitulo) {
        echo '<p class="page-subtitle">' . htmlspecialchars($subtitulo) . '</p>';
    }
    echo '</div>';
}

function admin_footer() {
    echo '</body></html>';
}

function admin_content_start() {
    echo '<div class="admin-main">';
}

function admin_content_end() {
    echo '</div>';
}

function admin_container_start() {
    echo '<div class="admin-container">';
}

function admin_container_end() {
    echo '</div>';
}

// Función para mostrar mensajes de alerta
function admin_alert($mensaje, $tipo = 'success') {
    echo '<div class="alert alert-' . $tipo . '">
        <i class="fas fa-' . ($tipo == 'success' ? 'check-circle' : ($tipo == 'error' ? 'exclamation-circle' : 'exclamation-triangle')) . '"></i>
        ' . htmlspecialchars($mensaje) . '
    </div>';
}
?>