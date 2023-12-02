<?php
// Conectar a la base de datos (asegúrate de proporcionar tus propias credenciales)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos del formulario
$idInProceeding = $_POST['idInProceeding'];
$idProceeding = $_POST['idProceeding'];
$titleInProceeding = $_POST['titleInProceeding'];
$pagesInProceeding = $_POST['pagesInProceeding'];
$typeInProceeding = $_POST['typeInProceeding'];
$noteInProceeding = $_POST['noteInProceeding'];

// Insertar los datos en la tabla inproceedings
$sql = "INSERT INTO inproceedings (ID_InProceedings, Proceedings_ID, title, pages, type, note)
        VALUES ('$idInProceeding', '$idProceeding', '$titleInProceeding', '$pagesInProceeding', '$typeInProceeding', '$noteInProceeding')";

if ($conn->query($sql) === TRUE) {
    // Éxito en la inserción
    $response = array("status" => "success", "message" => "InProceeding agregado con éxito");
    echo json_encode($response);
} else {
    // Error en la inserción
    $response = array("status" => "error", "message" => "Error al agregar InProceeding: " . $conn->error);
    echo json_encode($response);
}

// Cerrar la conexión
$conn->close();
?>
