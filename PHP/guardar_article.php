<?php
// Conexión a la base de datos (reemplaza estos valores con los tuyos)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir los datos del formulario
$idPublication = $_POST['idPublication'];
$titulo = $_POST['titulo'];
$revista = $_POST['revista'];
$volumen = $_POST['volumen'];
$numero = $_POST['numero'];
$paginas = $_POST['paginas'];
$mesPublicacion = $_POST['mesPublicacion'];
$anioPublicacion = $_POST['anioPublicacion'];
$nota = $_POST['nota'];

// Insertar el artículo en la base de datos
$sql = "INSERT INTO article (ID_Article, title, journal, volume, number, pages, pub_month, pub_year, note) 
        VALUES ('$idPublication', '$titulo', '$revista', '$volumen', '$numero', '$paginas', '$mesPublicacion', '$anioPublicacion', '$nota')";

if ($conn->query($sql) === TRUE) {
    echo "Artículo agregado exitosamente.";
} else {
    echo "Error al agregar el artículo: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
