<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfumería</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>

    <div class="login-box">
        <h1>Bienvenido</h1>
        <form action="#" method="post">
            <div class="input-group">
                <input type="text" id="usuario" name="usuario" required placeholder=" ">
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
            <a href="#">Crear cuenta</a>
        </div>
    </div>

</body>

</html>