<?php
// Tu código de conexión a la base de datos aquí
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
$idMisc = $_POST['idMisc'];
$title = $_POST['title'];
$address = $_POST['address'];
$howpublished = $_POST['howpublished'];
$pub_month = $_POST['pub_month'];
$pub_year = $_POST['pub_year'];
$note = $_POST['note'];

// Preparar la consulta SQL para actualizar el misceláneo
$sql = "UPDATE misc SET title='$title', address='$address', howpublished='$howpublished', pub_month='$pub_month', pub_year='$pub_year', note='$note' WHERE ID_Misc='$idMisc'";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    // Éxito en la actualización
    $response = ["success" => true, "message" => "Misceláneo actualizado correctamente."];
} else {
    // Error en la actualización
    $response = ["success" => false, "message" => "Error al actualizar misceláneo: " . $conn->error];
}

// Devolver la respuesta como JSON
echo json_encode($response);

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
