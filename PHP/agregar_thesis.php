<?php
// Conectar a la base de datos (ajusta las credenciales según tu configuración)
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
$ID_Thesis = $_POST['ID_Thesis'];
$title = $_POST['title'];
$school = $_POST['school'];
$type = $_POST['type'];
$pubMonth = $_POST['pubMonth'];
$pubYear = $_POST['pubYear'];
$note = $_POST['note'];

// Preparar la consulta SQL para insertar los datos en la tabla 'thesis'
$sql = "INSERT INTO thesis (ID_Thesis, title, school, type, pub_month, pub_year, note)
        VALUES ('$ID_Thesis', '$title', '$school', '$type', '$pubMonth', '$pubYear', '$note')";

if ($conn->query($sql) === TRUE) {
    // Éxito al agregar la tesis
    $response = array("status" => "success", "message" => "Tesis agregada correctamente");
} else {
    // Error al agregar la tesis
    $response = array("status" => "error", "message" => "Error al agregar la tesis: " . $conn->error);
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
