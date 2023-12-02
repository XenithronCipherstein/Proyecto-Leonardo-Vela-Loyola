<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$calle = $_POST['calle'];
$ciudad = $_POST['ciudad'];
$provincia = $_POST['provincia'];
$codigoPostal = $_POST['codigoPostal'];
$pais = $_POST['pais'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Preparar la consulta SQL
$sql = "INSERT INTO publisher (name, street, city, province, postal_code, country)
        VALUES ('$nombre', '$calle', '$ciudad', '$provincia', '$codigoPostal', '$pais')";

// Ejecutar la consulta y verificar si fue exitosa
if ($conn->query($sql) === TRUE) {
    echo "Editorial agregada con éxito";
} else {
    echo "Error al agregar la editorial: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
