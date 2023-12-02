<?php
// Tu código de conexión a la base de datos aquí
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del Inbook desde la solicitud POST
$idInbook = $_POST["idInbook"];

// Consulta para obtener los detalles del Inbook
$sqlInbook = "SELECT * FROM inbook WHERE ID_Inbook = $idInbook";
$resultInbook = $conn->query($sqlInbook);

// Verificar si se obtuvieron resultados
if ($resultInbook->num_rows > 0) {
    // Obtener los detalles del Inbook
    $detallesInbook = $resultInbook->fetch_assoc();

    // Devolver los detalles como respuesta al cliente (puede ser JSON dependiendo de tu necesidad)
    echo json_encode($detallesInbook);
} else {
    // Si no se encontraron resultados, devolver un error o un array vacío
    echo json_encode(["error" => "No se encontraron detalles para el Inbook con ID $idInbook"]);
}

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
