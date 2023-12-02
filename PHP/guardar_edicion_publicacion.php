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

// Obtener datos del formulario de edición
$id = $_POST['id'];
$tipo = $_POST['tipo'];

// Actualizar los detalles de la publicación en la base de datos
$sql = "UPDATE publication SET type = '$tipo' WHERE ID_Publication = $id";

if ($conn->query($sql) === TRUE) {
    echo "Publicación actualizada con éxito";
} else {
    echo "Error al actualizar la publicación: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
