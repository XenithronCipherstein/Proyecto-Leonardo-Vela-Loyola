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

// Obtener datos del formulario de edición
$idLibro = $_POST['idLibro'];
$titulo = $_POST['titulo'];
$idEditorial = $_POST['idEditorial'];
$volumen = $_POST['volumen'];
$serie = $_POST['serie'];
$edicion = $_POST['edicion'];
$mesPublicacion = $_POST['mesPublicacion'];
$anioPublicacion = $_POST['anioPublicacion'];
$nota = $_POST['nota'];

// Actualizar los datos en la base de datos
$sql = "UPDATE book SET 
        title = '$titulo',
        ID_Publisher = $idEditorial,
        volume = '$volumen',
        series = '$serie',
        edition = '$edicion',
        pub_month = '$mesPublicacion',
        pub_year = $anioPublicacion,
        note = '$nota'
        WHERE ID_Book = $idLibro";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Edición del libro guardada con éxito"]);
} else {
    echo json_encode(["error" => "Error al guardar la edición del libro: " . $conn->error]);
}

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
