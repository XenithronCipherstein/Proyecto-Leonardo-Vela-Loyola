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

// Obtener los datos del formulario de edición
$idInProceedings = $_POST['idInProceedings'];
$idProceeding = $_POST['idProceeding'];
$title = $_POST['title'];
$pages = $_POST['pages'];
$type = $_POST['type'];
$note = $_POST['note'];

// Realizar la actualización en la base de datos
$sql = "UPDATE inproceedings SET
        Proceedings_ID = '$idProceeding',
        title = '$title',
        pages = '$pages',
        type = '$type',
        note = '$note'
        WHERE ID_InProceedings = '$idInProceedings'";

if ($conn->query($sql) === TRUE) {
    // Éxito en la actualización
    $response = array('success' => true, 'message' => 'InProceeding actualizado con éxito');
} else {
    // Error en la actualización
    $response = array('success' => false, 'message' => 'Error al actualizar InProceeding: ' . $conn->error);
}

// Devolver la respuesta como JSON
echo json_encode($response);

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
