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

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$calle = $_POST['calle'];
$ciudad = $_POST['ciudad'];
$provincia = $_POST['provincia'];
$codigoPostal = $_POST['codigoPostal'];
$pais = $_POST['pais'];

// Preparar la consulta SQL para insertar la institución
$sql = "INSERT INTO institution (name, street, city, province, postal_code, country) VALUES ('$nombre', '$calle', '$ciudad', '$provincia', '$codigoPostal', '$pais')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Institución agregada correctamente";
} else {
    echo "Error al agregar la institución: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
