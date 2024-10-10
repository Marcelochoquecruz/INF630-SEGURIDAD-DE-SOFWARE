<?php
session_start();

// Aquí podrías incluir la lógica para manejar la sesión y cerrar sesión si es necesario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salida Exitosa</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlaza tu archivo CSS -->
</head>
<body>
    <div class="container">
        <h2>¡Has iniciado sesión exitosamente!</h2>
        <p>Bienvenido a tu panel.</p>
        <a href="login.php" class="button">Regresar al Login</a>
    </div>
</body>
</html>
