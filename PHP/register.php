<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación del lado del servidor
    $required_fields = ["nombres", "apellidos", "correo", "fecha_nacimiento", "telefono", "username", "contrasena"];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("Error: El campo $field es obligatorio.");
        }
    }
    // Conectar a la base de datos
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "trabajo_final_editorial";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    // Obtener datos del formulario
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $correo = $_POST["correo"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $telefono = $_POST["telefono"];
    $username = $_POST["username"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT); // Hashear la contraseña



    // Consulta preparada para prevenir la inyección de SQL
    $insert_query = $conn->prepare("INSERT INTO usuarios (nombres, apellidos, correo, fecha_nacimiento, telefono, username, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $insert_query->bind_param("sssssss", $nombres, $apellidos, $correo, $fecha_nacimiento, $telefono, $username, $contrasena);

    if ($insert_query->execute()) {
        echo "Usuario registrado correctamente. <a href='index.php'>Inicia sesión</a>.";
    } else {
        echo "Error al registrar el usuario: " . $insert_query->error;
        // Puedes agregar más detalles del error, como $conn->error
    }

    $insert_query->close();
    $conn->close();
}
?>

