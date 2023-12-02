<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID de la tesis desde la solicitud GET
$idTesis = isset($_GET['idTesis']) ? $_GET['idTesis'] : '';

// Utilizar una sentencia preparada para proteger contra la inyección de SQL
$sql = "SELECT * FROM thesis WHERE ID_Thesis = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idTesis);
$stmt->execute();

// Verificar si se obtuvieron resultados
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Convertir los resultados a un array asociativo
    $row = $result->fetch_assoc();

    // Crear un array con los detalles de la tesis
    $detallesTesis = array(
        'ID_Thesis' => $row['ID_Thesis'],
        'title' => $row['title'],
        'school' => $row['school'],
        'type' => $row['type'],
        'pub_month' => $row['pub_month'],
        'pub_year' => $row['pub_year'],
        'note' => $row['note']
        // Agrega más campos según sea necesario
    );

    // Devolver detalles de la tesis como JSON
    echo json_encode($detallesTesis);
} else {
    // No se encontraron resultados
    echo json_encode(array('error' => 'No se encontraron detalles de la tesis.'));
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
