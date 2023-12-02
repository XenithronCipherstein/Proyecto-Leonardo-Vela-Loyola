<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Conectar a la base de datos
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "trabajo_final_editorial";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta preparada para prevenir la inyección de SQL
    $stmt = $conn->prepare("SELECT id, username, contrasena FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($password, $hashed_password)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $id;
            header("Location: listar_publication.php");
            exit;
        } else {
            echo "Credenciales incorrectas";
        }
    } else {
        echo "Credenciales incorrectas";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link rel="stylesheet" href="styles_login.css">
</head>
<body>
    <div class="container">
        <div class="form-container" id="loginForm">
            <h2>Login</h2>
            <form action="" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button class="login-button">Iniciar sesión</button>
            </form>
            <button class="toggle-button" onclick="toggleForms()">Registrarse</button>
        </div>

        <div class="form-container" id="registerForm" style="display: none;">
            <h2>Registro</h2>
            <form action="register.php" method="post">
                <input type="text" name="nombres" placeholder="Nombres" required>
                <br>
                <input type="text" name="apellidos" placeholder="Apellidos" required>
                <br>
                <input type="email" name="correo" placeholder="Correo Electrónico" required>
                <br>
                <input type="date" name="fecha_nacimiento" required>
                <br>
                <input type="text" name="telefono" placeholder="Teléfono" required>
                <br>
                <input type="text" name="username" placeholder="Nombre de Usuario" required>
                <br>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <br>
                <button class="register-button">Registrarse</button>
            </form>
            <button class="toggle-button" onclick="toggleForms()">Iniciar sesión</button>
        </div>
    </div>

<!-- Modifica la función toggleForms para ocultar/mostrar formularios -->
<script>
    function toggleForms() {
        var loginForm = document.getElementById('loginForm');
        var registerForm = document.getElementById('registerForm');

        loginForm.style.display = loginForm.style.display === 'none' ? 'flex' : 'none';
        registerForm.style.display = registerForm.style.display === 'none' ? 'flex' : 'none';
    }
</script>
</body>
</html>
