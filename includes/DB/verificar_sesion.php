<?php
// includes/DB/verificar_sesion.php - VERSIÓN ALTERNATIVA

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: ../pages/login.php');
    exit();
}
?>