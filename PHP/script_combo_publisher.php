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

// Consulta para obtener todas las opciones de la tabla 'publisher'
$sqlPublishers = "SELECT ID_Publisher, name FROM publisher";
$resultPublishers = $conn->query($sqlPublishers);

// Construir las opciones del segundo combo
$optionsPublishers = array();
if ($resultPublishers->num_rows > 0) {
    while ($rowPublisher = $resultPublishers->fetch_assoc()) {
        // Agregar las opciones al array $optionsPublishers
        $optionsPublishers[] = array(
            'ID_Publisher' => $rowPublisher["ID_Publisher"],
            'name' => $rowPublisher["name"]
        );
    }
}

// Devolver las opciones como respuesta al cliente en formato JSON
echo json_encode($optionsPublishers);

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
