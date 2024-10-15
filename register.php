<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secure_login";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Error: Token CSRF inválido.";
    } else {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error al registrar el usuario.";
        }
        $stmt->close();
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

        <p>¿Ya tienes una cuenta? <a href="index.php">Iniciar sesión aquí</a></p>
    </div>
</body>
</html>
