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

// Verificar si se enviaron datos por GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Obtener el ID del procedimiento desde la solicitud
    $idProceedings = $_GET['idProceedings'];

    // Preparar la consulta SQL para obtener los detalles del procedimiento
    $sql = "SELECT * FROM proceedings WHERE ID_Proceedings = $idProceedings";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se obtuvieron resultados
    if ($result->num_rows > 0) {
        // Obtener los detalles del procedimiento como un array asociativo
        $detallesProceeding = $result->fetch_assoc();

        // Devolver los detalles como JSON
        echo json_encode($detallesProceeding);
    } else {
        // No se encontraron detalles para el ID proporcionado
        $response = ["error" => true, "message" => "No se encontraron detalles para el procedimiento con ID $idProceedings."];
        echo json_encode($response);
    }

    // Cerrar la conexión a la base de datos aquí
    $conn->close();
}
?>
