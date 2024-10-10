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
            $pass = $_POST['password'];

            // Consultar el usuario con sentencias preparadas
            $sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $sql->bind_param("s", $email);
            $sql->execute();
            $result = $sql->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Verificar la contraseña
                if (password_verify($pass, $row['password'])) {
                    // Sesión segura
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerar token CSRF
                    
                    // Redirigir a 'login_exit.php'
                    header("Location: login_exit.php"); // Cambia aquí a register.php
                    exit();
                } else {
                    $error = "Contraseña incorrecta";
                }
            } else {
                $error = "Usuario no encontrado";
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
    <title>chelisimo27@gmail.com</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="script.js"></script>
</head>
<body>
    
    <div class="header-content" id="inicio">
        <div>
            <img src="images/escudo1.png" alt="Escudo del Colegio Alternativo" class="logo">
        </div>
        <div class="header-text">
            <h2> UNIVERSIDAD AUTONOMA TOMAS FRIAS </h2>
            <h2>Ingenieria Informatica</h2>
        </div>
    </div>
    <header>
        <nav>
            <ul>
                <li><a href="#inicio"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="#institucional"><i class="fas fa-lock"></i> Seguridad</a></li>
                <li><a href="#fotos"><i class="fas fa-eye"></i> Análisis</a></li>
                <li><a href="#inscripciones"><i class="fas fa-certificate"></i> Certificaciones en Kali Linux</a></li>
               
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <img src="images/fondo3.jpg" alt="Análisis de Seguridad Cibernética">
            <div class="overlay" style="background-color: rgba(0, 0, 0, 0.5);">
                <h1>Practica 1</h1>
                <h5>Docente: M.Sc. Huascar Fedor Gonzales Guzman</h5>
                <h5>Auxiliar: Univ.</h5>
                <h5>Estudiante Univ. Marcelo Choque Cruz</h5>
            </div>
        </section>

        <div class="form-container">
                <h2>Iniciar Sesión</h2>
                <form method="post" action="login.php" class="login-form">
                    <input type="email" name="email" placeholder="E-Mail" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit">Iniciar sesión</button>
                </form>
        
                <?php
                // Mostrar errores si los hay
                if (isset($error)) {
                    echo "<p class='error'>$error</p>";
                }
                ?>
        
                <p>No tienes una cuenta? <a href="register.php">Registrarte aquí</a></p>
                <p><a href="login_exit.php" class="back-button">Regresar al login</a></p>
            </div>
        
                <?php
                // Mostrar errores si los hay
                if (isset($error)) {
                    echo "<p class='error'>$error</p>";
                }
                ?>
        
                
            </div>
            <div class="container3">
                <div class="mission">
                    <h1>"Comprometidos con la seguridad digital y la protección de la información en todos los niveles del sistema."</h1>
                </div>
                <div class="info">
                    <div class="objectives">
                        <img src="images/icono-objetivos-home.png" alt="Objetivos">
                        <h3>OBJETIVOS</h3>
                        <p>Fortalecer la infraestructura de seguridad a través de pruebas de penetración, auditorías regulares y la implementación de mejores prácticas de criptografía para mitigar amenazas.</p>
                    </div>
                    <div class="levels">
                        <img src="images/icono-niveles-home.png" alt="Niveles">
                        <h3>NIVELES</h3>
                        <p>Desde la seguridad básica en redes hasta técnicas avanzadas de ciberdefensa, cubrimos todas las áreas de protección informática. Formación en seguridad digital en todos los niveles.</p>
                    </div>
                </div>
            </div>
        <section class="menu2">
            <ul>
                <li><a href="#">
                    <h1>Auditoría de Sistemas</h1>
                    <h2>Pruebas de Intrusión en Aplicaciones</h2>
                </a></li>
                <li><a href="#">
                    <h1>Criptografía</h1>
                    <h2>Protección de Datos en Tránsito</h2>
                </a></li>
                <li><a href="#">
                    <h1>Firewall Avanzado</h1>
                    <h2>Defensa contra Ataques Externos</h2>
                </a></li>
                <li><a href="#">
                    <h1>Análisis Forense</h1>
                    <h2>Investigación de Cibercrimen</h2>
                </a></li>
            </ul>
        </section>
    </main>

   <section id="principal" class="seccion-principal">
    <h1>Bienvenidos a la Seguridad Digital</h1>
    <p>Explora las mejores herramientas y técnicas para proteger sistemas y redes de ataques cibernéticos.</p>

    <div class="contenedor-recursos">
        <div class="recurso">
            <h2><i class="fas fa-network-wired"></i> Seguridad en Redes</h2>
                  <img src="images/cripto.jpg" alt="Seguridad en Redes">
        </div>

        <div class="recurso">
            <h2><i class="fas fa-key"></i> Criptografía</h2>
            <p>Aprende a cifrar datos</p>
            <img src="images/cripto.jpg" alt="Criptografía">
        </div>

        <div class="recurso">
            <h2><i class="fas fa-user-shield"></i> Penetration Testing</h2>
            <p>Herramientas y técnicas</p>
            <img src="images/cripto.jpg" alt="Penetration Testing">
        </div>
    </div>
</section>


    <section id="institucional" class="seccion-institucional">
    <h1>Nuestra Filosofía de Seguridad</h1>
    <p>En nuestra organización, estamos comprometidos con la formación en ciberseguridad desde los niveles básicos hasta los más avanzados, promoviendo la defensa integral de los sistemas informáticos. Nuestro enfoque va más allá del software, integrando buenas prácticas de seguridad física, lógica y de datos.</p>
    <p>Desde el aprendizaje inicial sobre redes seguras hasta el análisis profundo de vulnerabilidades, nuestros programas están diseñados para preparar a los profesionales del futuro en un mundo cada vez más digital y vulnerable a ciberataques.</p>
    <p>Nuestro equipo docente está formado por expertos en ciberseguridad y profesionales certificados en Kali Linux, brindando una formación de calidad que permite a nuestros estudiantes enfrentar con éxito los desafíos de seguridad del mañana.</p>
</section>
<section id="fotos" class="seccion-fotos">
    <h1>Nuestra Galería de Ciberseguridad</h1>
    <p>Descubre algunos de los proyectos más emocionantes realizados en nuestros laboratorios de ciberseguridad, donde la teoría se convierte en práctica real.</p>

    <div class="grid-fotos">
        <div class="card">
            <img src="images/edu.png" alt="Ciberseguridad Básica">
            <h2>Introducción a Kali Linux</h2>
            <p>Primeros pasos en la plataforma líder de pruebas de seguridad.</p>
        </div>
        <div class="card">
            <img src="images/edu.png" alt="Proyectos Avanzados">
            <h2>Proyectos de PenTesting</h2>
            <p>Estudiantes poniendo a prueba sus conocimientos en entornos simulados.</p>
        </div>
        <div class="card">
            <img src="images/edu.png" alt="Clase de Hacking Ético">
            <h2>Análisis de Vulnerabilidades</h2>
            <p>Usando herramientas avanzadas para detectar debilidades en sistemas.</p>
        </div>
        <div class="card">
            <img src="images/edu.png" alt="Actividades de Red">
            <h2>Seguridad en Redes</h2>
            <p>Implementando firewalls y redes seguras en tiempo real.</p>
        </div>
        <div class="card">
            <img src="images/edu.png" alt="Excursiones de Seguridad">
            <h2>Simulacros de Ataques</h2>
            <p>Estudiantes practicando técnicas de respuesta a incidentes en escenarios simulados.</p>
        </div>
        <div class="card">
            <img src="images/edu.png" alt="Eventos de Ciberseguridad">
            <h2>Eventos de Hacking</h2>
            <p>Competiciones y desafíos para poner a prueba las habilidades de seguridad.</p>
        </div>
        
    </div>
</section>


 <section id="inscripciones" class="seccion-inscripciones">
    <h1>Certificaciones en Kali Linux</h1>
    <p>Accede a nuestros programas especializados en Kali Linux, donde aprenderás a realizar pruebas de penetración, análisis de vulnerabilidades y más. ¡Inscríbete hoy y prepárate para proteger el mundo digital!</p>

</section>

    <footer>
        <p>&copy; 2024 Universidad Autónoma Tomás Frías - Facultad de Ingeniería Informática. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
