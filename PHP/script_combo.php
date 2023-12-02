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
// Consulta para obtener todas las publicaciones
$sqlPublications = "SELECT ID_Publication, type FROM publication";
$resultPublications = $conn->query($sqlPublications);

// Construir las opciones del combo
$options = "";
if ($resultPublications->num_rows > 0) {
    while ($rowPublication = $resultPublications->fetch_assoc()) {
        // Concatenar las opciones al string $options
        $options .= "<option value='" . $rowPublication["ID_Publication"] . "'>" . $rowPublication["type"] . "</option>";
    }
}

// Devolver las opciones como respuesta al cliente (puede ser JSON dependiendo de tu necesidad)
echo $options;

// Cerrar la conexión a la base de datos aquí
?>
