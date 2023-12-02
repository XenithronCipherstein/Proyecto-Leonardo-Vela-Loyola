<?php
// Archivo eliminar_libro.php

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

// Prepara la consulta SQL para eliminar el libro
$sql = "DELETE FROM book WHERE ID_Book = $idLibro";

// Ejecuta la consulta
if ($conn->query($sql) === TRUE) {
    // Si la eliminación fue exitosa, devuelve un mensaje de éxito
    echo json_encode(array('success' => 'Libro eliminado con éxito'));
} else {
    // Si hubo un error en la eliminación, devuelve un mensaje de error
    echo json_encode(array('error' => 'Error al eliminar el libro: ' . $conn->error));
}

// Cierra la conexión a la base de datos
$conn->close();
?>
