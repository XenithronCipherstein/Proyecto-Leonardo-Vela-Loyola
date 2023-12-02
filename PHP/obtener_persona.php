<?php
// Verificar si se recibe el ID de la persona
if (isset($_GET["id"])) {
    $id = $_GET["id"];

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

    // Preparar la consulta SQL para obtener los detalles de la persona por su ID
    $sql = "SELECT * FROM person WHERE ID_Person = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Convertir el resultado a formato JSON y enviarlo como respuesta
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        // Si no se encuentra la persona, devolver un mensaje de error
        echo "Error: Persona no encontrada.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si no se recibe el ID de la persona, mostrar un mensaje de error
    echo "Error: ID de persona no proporcionado.";
}
?>
