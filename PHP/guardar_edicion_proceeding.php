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

// Verificar si se enviaron datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idProceedings = $_POST['idProceedings'];
    $title = $_POST['title'];
    $publisher = $_POST['publisher'];
    $volume = $_POST['volume'];
    $series = $_POST['series'];
    $organization = $_POST['organization'];
    $pub_month = $_POST['pub_month'];  // Fíjate en el cambio aquí
    $pub_year = $_POST['pub_year'];    // Fíjate en el cambio aquí
    $note = $_POST['note'];

    // Preparar la consulta SQL para actualizar el procedimiento
    $sql = "UPDATE proceedings SET title='$title', publisher=$publisher, volume='$volume', series='$series', organization=$organization, pub_month='$pub_month', pub_year=$pub_year, note='$note' WHERE ID_Proceedings=$idProceedings";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Éxito al actualizar el procedimiento
        $response = ["success" => true, "message" => "Procedimiento actualizado correctamente."];
    } else {
        // Error al actualizar el procedimiento
        $response = ["success" => false, "message" => "Error al actualizar procedimiento: " . $conn->error];
    }

    // Devolver la respuesta como JSON
    echo json_encode($response);

    // Cerrar la conexión a la base de datos aquí
    $conn->close();
}
?>
