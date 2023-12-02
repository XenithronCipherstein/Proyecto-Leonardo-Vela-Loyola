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

// Obtener los datos del formulario de edición
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$calle = $_POST['calle'];
$ciudad = $_POST['ciudad'];
$provincia = $_POST['provincia'];
$codigoPostal = $_POST['codigoPostal'];
$pais = $_POST['pais'];

// Actualizar los datos en la base de datos
$sql = "UPDATE publisher SET
        name = '$nombre',
        street = '$calle',
        city = '$ciudad',
        province = '$provincia',
        postal_code = '$codigoPostal',
        country = '$pais'
        WHERE ID_Publisher = $id";

if ($conn->query($sql) === TRUE) {
    echo "Edición guardada correctamente";
} else {
    echo "Error al guardar la edición: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
