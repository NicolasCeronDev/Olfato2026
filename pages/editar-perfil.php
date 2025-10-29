<?php
// Verificar que el usuario esté logueado
include '../includes/DB/verificar_sesion.php';
include '../includes/DB/conexion_db.php';

$usuario = $_SESSION['usuario'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Olfato Perfumería</title>
    <link rel="stylesheet" href="../assets/css/General.css">
    <style> 
        .mi-cuenta {
            padding: 100px 0 50px;
            background: var(--color-gris-oscuro);
            min-height: 100vh;
        }
        
        .cuenta-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .cuenta-sidebar {
            background: var(--color-fondo);
            padding: 30px;
            border-radius: 15px;
            height: fit-content;
            border: 1px solid var(--color-borde);
        }
        
        .cuenta-menu {
            list-style: none;
            margin-top: 25px;
        }
        
        .cuenta-menu li {
            margin-bottom: 8px;
        }
        
        .cuenta-menu a {
            color: var(--color-texto);
            text-decoration: none;
            padding: 12px 15px;
            display: block;
            border-radius: 8px;
            transition: var(--transicion);
            border: 1px solid transparent;
            font-weight: 500;
        }
        
        .cuenta-menu a:hover, .cuenta-menu a.activo {
            background: var(--color-dorado);
            color: var(--color-fondo);
            border-color: var(--color-dorado);
            transform: translateX(5px);
        }
        
        .cuenta-contenido {
            background: var(--color-fondo);
            padding: 35px;
            border-radius: 15px;
            border: 1px solid var(--color-borde);
        }
        
        .pedido-item {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 1px solid var(--color-borde);
            transition: var(--transicion);
        }
        
        .pedido-item:hover {
            background: rgba(255,255,255,0.08);
            transform: translateY(-2px);
        }
        
        .estado-badge {
            color: white;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .resumen-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .resumen-card {
            background: var(--color-gris-oscuro);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--color-borde);
            transition: var(--transicion);
        }
        
        .resumen-card:hover {
            transform: translateY(-3px);
        }
        
        .resumen-card.dorado {
            background: var(--color-dorado);
            color: var(--color-fondo);
        }
        
        .bienvenida-usuario {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .avatar-usuario {
            width: 60px;
            height: 60px;
            background: var(--color-dorado);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--color-fondo);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="mi-cuenta">
        <div class="cuenta-grid">
            <!-- Sidebar -->
            <div class="cuenta-sidebar">
                <div class="bienvenida-usuario">
                    <div class="avatar-usuario">
                        <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
                    </div>
                    <div>
                        <h3 style="color: var(--color-dorado); margin: 0 0 5px 0;">¡Hola, <?php echo $usuario['nombre']; ?>!</h3>
                        <p style="color: var(--color-gris-claro); margin: 0; font-size: 0.9rem;">Bienvenido a tu cuenta</p>
                    </div>
                </div>
                
                <ul class="cuenta-menu">
                    <li><a href="mi-cuenta.php" class="activo">📊 Resumen</a></li>
                    <li><a href="mis-pedidos.php">📦 Mis Pedidos</a></li>
                    <li><a href="editar-perfil.php">👤 Mis Datos</a></li>
                    <li><a href="productos.php">🛍️ Seguir Comprando</a></li>
                    <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
                </ul>
            </div>
            
           
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>