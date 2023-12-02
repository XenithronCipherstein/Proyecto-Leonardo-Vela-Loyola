<?php
// Verificar si se recibe el ID de la persona y los nuevos datos
if (isset($_POST["id"]) && isset($_POST["sumame"]) && isset($_POST["givenNames"])) {
    $id = $_POST["id"];
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

    // Preparar la consulta SQL para actualizar los detalles de la persona por su ID
    $sql = "UPDATE person SET sumame = '$sumame', given_names = '$givenNames' WHERE ID_Person = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Detalles de persona actualizados exitosamente.";
    } else {
        echo "Error al actualizar detalles de persona: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si no se reciben todos los datos necesarios, mostrar un mensaje de error
    echo "Error: Datos incompletos.";
}
?>
