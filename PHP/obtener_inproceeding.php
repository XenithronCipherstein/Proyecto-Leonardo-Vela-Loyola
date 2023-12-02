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

// Verificar si se proporcionó un ID de InProceedings válido
if (isset($_GET['idInProceedings'])) {
    $idInProceedings = $_GET['idInProceedings'];

    // Consulta para obtener detalles del InProceeding
    $sql = "SELECT * FROM inproceedings WHERE ID_InProceedings = $idInProceedings";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Obtener los detalles del InProceeding
        $row = $result->fetch_assoc();

        // Devolver detalles del InProceeding como respuesta al cliente (puede ser JSON dependiendo de tu necesidad)
        echo json_encode($row);
    } else {
        // Si no se encuentra el InProceeding, devolver un mensaje de error
        echo json_encode(array('error' => 'InProceeding no encontrado'));
    }
} else {
    // Si no se proporcionó un ID de InProceedings válido, devolver un mensaje de error
    echo json_encode(array('error' => 'ID de InProceedings no proporcionado'));
}

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
    