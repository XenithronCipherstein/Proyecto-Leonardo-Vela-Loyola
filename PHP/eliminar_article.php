<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

// Obtener el ID del artículo a eliminar desde la solicitud POST
$id = $_POST['id'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Preparar la consulta SQL para eliminar el artículo
$sql = "DELETE FROM article WHERE ID_Article = $id";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Artículo eliminado con éxito.";
} else {
    echo "Error al eliminar el artículo: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
