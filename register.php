<?php
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secure_login";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Error: Token CSRF inválido.";
    } else {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $pass = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hashear contraseña

            // Insertar nuevo usuario con sentencias preparadas
            $sql = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $sql->bind_param("ss", $email, $pass);

            if ($sql->execute()) {
                header("Location: login.php"); // Redirigir al login después del registro exitoso
                exit();
            } else {
                $error = "Error al registrar usuario.";
            }

            $sql->close();
        } else {
            $error = "Por favor, rellena ambos campos.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlaza tu archivo CSS -->
</head>
<body>
    <div class="container">
        <h2>Registrarse</h2>

        <form method="post" action="register.php">
            <input type="email" name="email" placeholder="E-Mail" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit">Registrarse</button>
        </form>

        <?php
        // Mostrar errores si los hay
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>

        <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión aquí</a></p>
    </div>
</body>
</html>
