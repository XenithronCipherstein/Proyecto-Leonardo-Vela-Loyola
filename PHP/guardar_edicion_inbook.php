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

// Obtener los datos enviados por AJAX
$idInBook = $_POST['idInBook'];
$idLibro = $_POST['idLibro'];
$title = $_POST['title'];
$chapter = $_POST['chapter'];
$pages = $_POST['pages'];
$type = $_POST['type'];
$note = $_POST['note'];

// Consulta SQL para actualizar el registro en la tabla inbook
$sql = "UPDATE inbook SET
        ID_book = $idLibro,
        title = '$title',
        chapter = '$chapter',
        pages = '$pages',
        type = '$type',
        note = '$note'
        WHERE ID_Inbook = $idInBook";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Edición exitosa";
} else {
    echo "Error al editar el Inbook: " . $conn->error;
}

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
