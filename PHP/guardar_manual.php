<?php
session_start();

// Verificar si el usuario está autenticado (ajustar según tus necesidades)
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "No estás autenticado. Acceso denegado.";
    exit;
}

// Obtener los datos del formulario
$idManual = $_POST["idManual"];
$title = $_POST["title"];
$idOrganization = $_POST["idOrganization"];
$edition = $_POST["edition"];
$pub_month = $_POST["pub_month"];
$pub_year = $_POST["pub_year"];
$note = $_POST["note"];

// Realizar las operaciones de inserción o actualización en la base de datos
// (Aquí deberías agregar tu lógica para interactuar con la base de datos)

// Ejemplo: Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Ejemplo de consulta de inserción
$sql = "INSERT INTO manual (ID_Manual, title, organization, edition, pub_month, pub_year, note) VALUES ('$idManual', '$title', '$idOrganization', '$edition', '$pub_month', '$pub_year', '$note')";

if ($conn->query($sql) === TRUE) {
    echo "Manual guardado exitosamente.";
} else {
    echo "Error al guardar el manual: " . $conn->error;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
