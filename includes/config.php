<?php
// includes/config.php - Configuración centralizada

// Configuración de rutas base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_path = dirname($_SERVER['SCRIPT_NAME']);

// Definir BASE_URL dinámicamente
define('BASE_URL', $protocol . '://' . $host . $script_path);
define('ROOT_PATH', dirname(__DIR__));

// Detectar si estamos en el admin
$current_script = $_SERVER['SCRIPT_NAME'];
define('IS_ADMIN', strpos($current_script, '/admin/') !== false);

// Calcular la ruta base para assets
if (IS_ADMIN) {
    define('ASSETS_PATH', '../../assets/');
    define('PAGES_PATH', '../../pages/');
} else {
    define('ASSETS_PATH', '../assets/');
    define('PAGES_PATH', './');
}

// Incluir conexión a la base de datos
require_once ROOT_PATH . '/includes/DB/conexion_db.php';

// Manejo de sesiones
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>