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
            
            // Verificar contraseña (simple para proyecto universitario)
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
</head>
<body>
    <div class="login-box">
        <img src="../assets/Contenido/Local/LogoSinfondo.png" width="150px" alt="Olfato Perfumería">
        
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
</body>
</html>