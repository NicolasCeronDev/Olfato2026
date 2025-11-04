<?php
session_start();
include '../includes/DB/conexion_db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['usuario']);
    $password = trim($_POST['clave']);

    if (!empty($email) && !empty($password)) {
        // Buscar usuario en la base de datos
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // Verificar contraseña
            if ($password === $usuario['password']) {
                // Iniciar sesión
                $_SESSION['usuario'] = [
                    'id' => $usuario['id_usuario'],
                    'email' => $usuario['email'],
                    'nombre' => $usuario['nombre_completo'],
                    'es_admin' => $usuario['es_administrador']
                ];

                // Redirigir según el tipo de usuario
                if ($usuario['es_administrador']) {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: mi-cuenta.php');
                }
                exit();
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado";
        }
    } else {
        $error = "Por favor completa todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Olfato Perfumería</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <style>
        /* Estilos para el botón cerrar - CORREGIDOS */
        .login-box {
            position: relative;
            padding-top: 50px;
            /* Espacio para la X */
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: transparent;
            color: #C9A227;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            z-index: 10;
        }

        .close-btn:hover {
            background: #C9A227;
            color: #000;
            transform: scale(1.1);
        }

        .login-header {
            position: relative;
            margin-bottom: 10px;
        }

        .login-box h1 {
            margin-top: 0;
            padding-top: 0;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <a href="#" class="close-btn" onclick="goBack()" aria-label="Cerrar login y volver a la página anterior">
            x
        </a>
        <!-- Botón de cerrar -->
        <div class="login-header">
            <img src="../assets/Contenido/Local/LogoSinfondo.png" width="150px" alt="Olfato Perfumería">

        </div>

        <?php if (!empty($error)): ?>
            <div class="mensaje-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <input type="text" id="usuario" name="usuario" required placeholder=" " value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">
                <label for="usuario">Usuario o Email</label>
            </div>
            <div class="input-group">
                <input type="password" id="clave" name="clave" required placeholder=" ">
                <label for="clave">Contraseña</label>
            </div>
            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>
        <div class="extra-links">
            <a href="#">¿Olvidaste tu contraseña?</a> |
            <a href="registro.php">Crear cuenta</a>
        </div>
    </div>
      <script>
        function goBack() {
            // Intentar usar el historial del navegador primero
            if (document.referrer && !document.referrer.includes('login.php')) {
                window.location.href = document.referrer;
            } else {
                // Si no hay referencia válida, ir al index
                window.location.href = '../pages/index.php';
            }
        }
    </script>
</body>

</html>