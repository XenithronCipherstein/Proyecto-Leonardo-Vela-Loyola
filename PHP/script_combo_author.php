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

// Construir las opciones del combo para publicaciones
$optionsPublications = "";
if ($resultPublications->num_rows > 0) {
    while ($rowPublication = $resultPublications->fetch_assoc()) {
        // Concatenar las opciones al string $optionsPublications
        $optionsPublications .= "<option value='" . $rowPublication["ID_Publication"] . "'>" . $rowPublication["type"] . "</option>";
    }
}

// Consulta para obtener todas las personas
$sqlPersons = "SELECT ID_Person, sumame, given_names FROM person";
$resultPersons = $conn->query($sqlPersons);

// Construir las opciones del combo para personas
$optionsPersons = "";
if ($resultPersons->num_rows > 0) {
    while ($rowPerson = $resultPersons->fetch_assoc()) {
        // Concatenar las opciones al string $optionsPersons
        $optionsPersons .= "<option value='" . $rowPerson["ID_Person"] . "'>" . $rowPerson["sumame"] . ' ' . $rowPerson["given_names"] . "</option>";
    }
}

// Devolver las opciones como respuesta al cliente en formato JSON
$response = array(
    'publications' => $optionsPublications,
    'persons' => $optionsPersons
);

echo json_encode($response);

// Cerrar la conexión a la base de datos aquí
?>
