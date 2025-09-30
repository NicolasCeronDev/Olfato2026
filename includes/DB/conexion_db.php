<?php
// Configuración de la base de datos
$servidor = "127.0.0.1";
$usuario = "root"; // Cambia por tu usuario de MySQL
$password = ""; // Cambia por tu contraseña de MySQL
$basedatos = "olfatoperfumeria";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $password, $basedatos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el charset
$conexion->set_charset("utf8mb4");
?>