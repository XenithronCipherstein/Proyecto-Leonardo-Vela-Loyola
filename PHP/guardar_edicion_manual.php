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

// Obtener datos del formulario de edición
$idManual = $_POST["idManual"];
$title = $_POST["title"];
$idOrganization = $_POST["idOrganization"];
$edition = $_POST["edition"];
$pub_month = $_POST["pub_month"];
$pub_year = $_POST["pub_year"];
$note = $_POST["note"];

// Actualizar el manual en la base de datos
$sql = "UPDATE manual SET 
        title = '$title', 
        organization = $idOrganization, 
        edition = '$edition', 
        pub_month = '$pub_month', 
        pub_year = $pub_year, 
        note = '$note'
        WHERE ID_Manual = $idManual";

if ($conn->query($sql) === TRUE) {
    echo "Edición del manual exitosa.";
} else {
    echo "Error al editar el manual: " . $conn->error;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
