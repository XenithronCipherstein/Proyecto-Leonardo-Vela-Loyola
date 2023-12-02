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
    // Obtener el ID del procedimiento a eliminar
    $idProceedings = $_POST['idProceedings'];

    // Preparar la consulta SQL para eliminar el procedimiento
    $sql = "DELETE FROM proceedings WHERE ID_Proceedings = $idProceedings";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Éxito al eliminar el procedimiento
        $response = ["success" => true, "message" => "Procedimiento eliminado correctamente."];
    } else {
        // Error al eliminar el procedimiento
        $response = ["success" => false, "message" => "Error al eliminar procedimiento: " . $conn->error];
    }

    // Devolver la respuesta como JSON
    echo json_encode($response);

    // Cerrar la conexión a la base de datos aquí
    $conn->close();
}
?>
