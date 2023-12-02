<?php
// Conectar a la base de datos (reemplaza los valores con los de tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los valores del formulario
$idPublication = $_POST['idPublication'];
$idPerson = $_POST['idPerson'];

// Consulta para insertar el nuevo autor en la tabla 'author'
$sql = "INSERT INTO editor (ID_Publication, ID_Person) VALUES ('$idPublication', '$idPerson')";

if ($conn->query($sql) === TRUE) {
    echo "Autor agregado correctamente.";
} else {
    echo "Error al agregar autor: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
