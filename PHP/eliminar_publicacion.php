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

// Obtener el ID de la publicación desde la solicitud POST
$id = $_POST['id'];

// Eliminar la publicación de la base de datos
$sql = "DELETE FROM publication WHERE ID_Publication = $id";

if ($conn->query($sql) === TRUE) {
    echo "Publicación eliminada con éxito";
} else {
    echo "Error al eliminar la publicación: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
