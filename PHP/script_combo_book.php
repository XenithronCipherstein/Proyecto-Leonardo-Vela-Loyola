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

// Consulta para obtener todos los libros
$sqlBooks = "SELECT ID_Book, title FROM book";
$resultBooks = $conn->query($sqlBooks);

// Construir las opciones del combo
$options = "";
if ($resultBooks->num_rows > 0) {
    while ($rowBook = $resultBooks->fetch_assoc()) {
        // Concatenar las opciones al string $options
        $options .= "<option value='" . $rowBook["ID_Book"] . "'>" . $rowBook["title"] . "</option>";
    }
}

// Devolver las opciones como respuesta al cliente (puede ser JSON dependiendo de tu necesidad)
echo $options;

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
