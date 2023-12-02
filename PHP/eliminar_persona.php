<?php
// Verificar si se recibe el ID de la persona
if (isset($_POST["id"])) {
    $id = $_POST["id"];

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

    // Preparar la consulta SQL para eliminar la persona por su ID
    $sql = "DELETE FROM person WHERE ID_Person = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Persona eliminada exitosamente.";
    } else {
        echo "Error al eliminar persona: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si no se recibe el ID de la persona, mostrar un mensaje de error
    echo "Error: ID de persona no proporcionado.";
}
?>
