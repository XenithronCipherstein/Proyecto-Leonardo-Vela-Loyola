<?php
// Archivo obtener_libro.php

// Conexión a la base de datos (ajusta los valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Obtén el ID del libro desde la solicitud POST
$idLibro = $_POST['idLibro'];

// Prepara la consulta SQL para obtener los detalles del libro
$sql = "SELECT * FROM book WHERE ID_Book = $idLibro";

// Ejecuta la consulta
$result = $conn->query($sql);

// Verifica si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Obtiene los detalles del libro como un array asociativo
    $detallesLibro = $result->fetch_assoc();

    // Devuelve los detalles del libro como JSON
    echo json_encode($detallesLibro);
} else {
    // Si no se encuentra el libro, devuelve un mensaje de error
    echo json_encode(array('error' => 'Libro no encontrado'));
}

// Cierra la conexión a la base de datos
$conn->close();
?>
