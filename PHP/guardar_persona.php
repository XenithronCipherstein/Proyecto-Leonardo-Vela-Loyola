<?php
// Verificar si se reciben los datos necesarios
if (isset($_POST["sumame"]) && isset($_POST["givenNames"])) {
    $sumame = $_POST["sumame"];
    $givenNames = $_POST["givenNames"];

    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trabajo_final_editorial";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para insertar una nueva persona
    $sql = "INSERT INTO person (sumame, given_names) VALUES ('$sumame', '$givenNames')";

    if ($conn->query($sql) === TRUE) {
        echo "Persona agregada exitosamente.";
    } else {
        echo "Error al agregar persona: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si no se reciben los datos necesarios, mostrar un mensaje de error
    echo "Error: Datos incompletos.";
}
?>
    