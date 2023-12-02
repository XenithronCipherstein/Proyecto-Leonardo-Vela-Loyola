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

// Obtener el ID del informe técnico enviado por POST
$idTechreport = isset($_POST['idTechreport']) ? intval($_POST['idTechreport']) : 0;

// Verificar que el ID del informe técnico sea un entero válido
if ($idTechreport <= 0) {
    echo json_encode(["error" => "ID de informe técnico no válido."]);
    exit();
}

// Consulta para eliminar el informe técnico de forma segura
$sql = "DELETE FROM techreport WHERE ID_Techreport = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idTechreport);

if ($stmt->execute()) {
    // Éxito al eliminar
    echo "Informe técnico eliminado con éxito.";
} else {
    // Error al eliminar
    echo "Error al eliminar informe técnico actualizado.";
}

// Cerrar la conexión a la base de datos aquí
$stmt->close();
$conn->close();
?>
