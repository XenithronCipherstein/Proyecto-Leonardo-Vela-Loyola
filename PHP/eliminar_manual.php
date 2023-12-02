<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del manual a eliminar
$idManual = $_POST["idManual"];

// Consulta SQL para eliminar el manual
$sql = "DELETE FROM manual WHERE ID_Manual = $idManual";

if ($conn->query($sql) === TRUE) {
    echo "Eliminación exitosa.";
} else {
    echo "Error al eliminar el manual: " . $conn->error;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
