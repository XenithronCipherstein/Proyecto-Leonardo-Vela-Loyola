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

// Obtener el ID del informe técnico enviado por GET
$idTechreport = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verificar que el ID del informe técnico sea un entero válido
if ($idTechreport <= 0) {
    echo json_encode(["error" => "ID de informe técnico no válido."]);
    exit();
}

// Consulta para obtener los detalles del informe técnico de forma segura
$sql = "SELECT * FROM techreport WHERE ID_Techreport = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idTechreport);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener los detalles del informe técnico como un array asociativo
    $detallesTechreport = $result->fetch_assoc();

    // Devolver detalles del informe técnico como JSON
    echo json_encode($detallesTechreport);
} else {
    // Si no se encuentran resultados, puedes devolver un mensaje o un objeto vacío
    echo json_encode(["error" => "No se encontraron detalles para este informe técnico."]);
}

// Cerrar la conexión a la base de datos aquí
$stmt->close();
$conn->close();
?>
