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

// Obtener los datos del formulario
$idInBook = $_POST['idInBook'];
$idBook = $_POST['idBook'];
$title = $_POST['title'];
$chapter = $_POST['chapter'];
$pages = $_POST['pages'];
$type = $_POST['type'];
$note = $_POST['note'];

// Consulta para insertar datos en la tabla inbook
$sql = "INSERT INTO inbook (ID_Inbook, ID_book, title, chapter, pages, type, note) 
        VALUES ('$idInBook', '$idBook', '$title', '$chapter', '$pages', '$type', '$note')";

if ($conn->query($sql) === TRUE) {
    echo "Inbook guardado exitosamente";
} else {
    echo "Error al guardar el Inbook: " . $conn->error;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
