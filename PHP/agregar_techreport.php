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
$ID_Techreport = $_POST['ID_Techreport'];
$title = $_POST['title'];
$ID_Institution = $_POST['ID_Institution'];
$type = $_POST['type'];
$number = $_POST['number'];
$pub_month = $_POST['pub_month'];
$pub_year = $_POST['pub_year'];
$note = $_POST['note'];

// Preparar la consulta SQL para insertar el informe técnico
$sql = "INSERT INTO techreport (ID_Techreport, title, ID_Institution, type, number, pub_month, pub_year, note) 
        VALUES ('$ID_Techreport', '$title', '$ID_Institution', '$type', '$number', '$pub_month', '$pub_year', '$note')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    // Éxito en la inserción
    $response = ["success" => true, "message" => "Informe técnico agregado correctamente."];
} else {
    // Error en la inserción
    $response = ["success" => false, "message" => "Error al agregar informe técnico: " . $conn->error];
}

// Devolver la respuesta como JSON
echo json_encode($response);

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
