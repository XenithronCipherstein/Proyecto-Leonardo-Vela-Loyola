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

// Consulta para obtener todas las instituciones
$sqlInstitutions = "SELECT ID_Institution, name FROM institution";
$resultInstitutions = $conn->query($sqlInstitutions);

// Construir las opciones del combo
$options = "";
if ($resultInstitutions->num_rows > 0) {
    while ($rowInstitution = $resultInstitutions->fetch_assoc()) {
        // Concatenar las opciones al string $options
        $options .= "<option value='" . $rowInstitution["ID_Institution"] . "'>" . $rowInstitution["name"] . "</option>";
    }
}

// Devolver las opciones como respuesta al cliente (puede ser JSON dependiendo de tu necesidad)
echo $options;

// Cerrar la conexión a la base de datos aquí
?>
