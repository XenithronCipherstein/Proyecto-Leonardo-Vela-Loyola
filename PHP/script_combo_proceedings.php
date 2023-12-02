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

// Consulta para obtener todas las proceedings
$sqlProceedings = "SELECT ID_Proceedings, title FROM proceedings";
$resultProceedings = $conn->query($sqlProceedings);

// Construir las opciones del combo
$options = "";
if ($resultProceedings->num_rows > 0) {
    while ($rowProceeding = $resultProceedings->fetch_assoc()) {
        // Concatenar las opciones al string $options
        $options .= "<option value='" . $rowProceeding["ID_Proceedings"] . "'>" . $rowProceeding["title"] . "</option>";
    }
}

// Devolver las opciones como respuesta al cliente (puede ser JSON dependiendo de tu necesidad)
echo $options;

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
