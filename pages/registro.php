<?php
include '../includes/DB/conexion_db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $ciudad = trim($_POST['ciudad']);
    $barrio = trim($_POST['barrio']);
    
    if (!empty($nombre) && !empty($email) && !empty($password)) {
        // Verificar si el email ya existe
        $sql = "SELECT id_usuario FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Este email ya está registrado";
        } else {
            // Insertar nuevo usuario
            $sql = "INSERT INTO usuarios (email, password, nombre_completo, telefono, direccion, ciudad, barrio) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssssss", $email, $password, $nombre, $telefono, $direccion, $ciudad, $barrio);
            
            if ($stmt->execute()) {
                $success = "¡Registro exitoso! Ahora puedes iniciar sesión";
                // Limpiar el formulario
                $_POST = array();
            } else {
                $error = "Error en el registro: " . $conexion->error;
            }
        }
    } else {
        $error = "Por favor completa todos los campos obligatorios";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Olfato Perfumería</title>
    <link rel="stylesheet" href="../assets/css/registro.css">
</head>
<body>
    <div class="registro-box">
        <img src="../assets/Contenido/Local/LogoSinfondo.png" width="150px" alt="Olfato Perfumería">
        <h1>Crear Cuenta</h1>
        
        <?php if (!empty($error)): ?>
            <div class="mensaje-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="mensaje-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" id="nombre" name="nombre" required placeholder=" " value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                <label for="nombre" class="campo-obligatorio">Nombre Completo</label>
            </div>
            
            <div class="input-group">
                <input type="email" id="email" name="email" required placeholder=" " value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <label for="email" class="campo-obligatorio">Correo Electrónico</label>
            </div>
            
            <div class="input-group">
                <input type="password" id="password" name="password" required placeholder=" ">
                <label for="password" class="campo-obligatorio">Contraseña</label>
            </div>
            
            <div class="input-group">
                <input type="tel" id="telefono" name="telefono" placeholder=" " value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
                <label for="telefono">Teléfono</label>
            </div>
            
            <div class="input-group">
                <input type="text" id="direccion" name="direccion" placeholder=" " value="<?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?>">
                <label for="direccion">Dirección</label>
            </div>
            
            <div class="input-group">
                <input type="text" id="ciudad" name="ciudad" placeholder=" " value="<?php echo isset($_POST['ciudad']) ? htmlspecialchars($_POST['ciudad']) : ''; ?>">
                <label for="ciudad">Ciudad</label>
            </div>
            
            <div class="input-group">
                <input type="text" id="barrio" name="barrio" placeholder=" " value="<?php echo isset($_POST['barrio']) ? htmlspecialchars($_POST['barrio']) : ''; ?>">
                <label for="barrio">Barrio</label>
            </div>
            
            <button type="submit" class="btn-registro">Registrarse</button>
        </form>
        
        <div class="extra-links">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html> 