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

// Obtener datos del formulario
$tipo = $_POST['tipo'];

// Insertar nueva publicación en la base de datos
$sql = "INSERT INTO publication (type) VALUES ('$tipo')";

if ($conn->query($sql) === TRUE) {
    echo "Publicación agregada con éxito";
} else {
    echo "Error al agregar la publicación: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
