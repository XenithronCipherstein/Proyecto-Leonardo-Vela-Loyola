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
$ID_Misc = $_POST['ID_Misc'];
$title = $_POST['title'];
$address = $_POST['address'];
$howpublished = $_POST['howpublished'];
$pub_month = $_POST['pub_month'];
$pub_year = $_POST['pub_year'];
$note = $_POST['note'];

// Preparar la consulta SQL para insertar el misceláneo
$sql = "INSERT INTO misc (ID_Misc, title, address, howpublished, pub_month, pub_year, note) 
        VALUES ('$ID_Misc', '$title', '$address', '$howpublished', '$pub_month', '$pub_year', '$note')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    // Éxito en la inserción
    $response = ["success" => true, "message" => "Misceláneo agregado correctamente."];
} else {
    // Error en la inserción
    $response = ["success" => false, "message" => "Error al agregar misceláneo: " . $conn->error];
}

// Devolver la respuesta como JSON
echo json_encode($response);

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
