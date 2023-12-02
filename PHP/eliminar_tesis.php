<?php
// Verificar si se ha proporcionado un ID de tesis
if (isset($_POST['idTesis'])) {
    // Obtener el ID de tesis desde la solicitud
    $idTesis = $_POST['idTesis'];

    // Conectar a la base de datos (ajusta los valores según tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trabajo_final_editorial";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("La conexión a la base de datos falló: " . $conn->connect_error);
    }

    // Preparar y ejecutar la consulta para eliminar la tesis
    $sql = "DELETE FROM `thesis` WHERE `ID_Thesis` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idTesis);

    if ($stmt->execute()) {
        // La tesis se eliminó correctamente
        $response = array('success' => true, 'message' => 'Tesis eliminada correctamente');
        echo json_encode($response);
    } else {
        // Hubo un error al eliminar la tesis
        $response = array('success' => false, 'message' => 'Error al eliminar la tesis');
        echo json_encode($response);
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    // No se proporcionó un ID de tesis válido
    $response = array('success' => false, 'message' => 'ID de tesis no válido');
    echo json_encode($response);
}
?>
    