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

// Verificar si se recibió el ID del InProceeding a eliminar
if (isset($_POST['idInProceedings'])) {
    $idInProceedings = $_POST['idInProceedings'];

    // Consulta para eliminar el InProceeding con el ID dado
    $sql = "DELETE FROM inproceedings WHERE ID_InProceedings = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idInProceedings);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Si la eliminación fue exitosa, devuelve una respuesta con éxito
        $response = array('success' => true);
    } else {
        // Si hubo un error al eliminar, devuelve un mensaje de error
        $response = array('success' => false, 'message' => 'Error al eliminar el InProceeding');
    }

    // Cerrar la conexión y devolver la respuesta
    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    // Si no se proporcionó el ID del InProceeding, devuelve un mensaje de error
    echo json_encode(array('success' => false, 'message' => 'ID de InProceeding no proporcionado'));
}
?>
