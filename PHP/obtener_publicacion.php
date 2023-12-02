<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

// Recibir el ID de la publicación desde la solicitud GET
$id = $_GET['id'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Preparar la consulta SQL para obtener detalles de la publicación por ID
$sql = "SELECT * FROM publication WHERE ID_Publication = ?";

// Preparar y ejecutar la consulta SQL con un enlace de parámetro para evitar la inyección de SQL
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Obtener los datos de la publicación como un array asociativo
    $row = $result->fetch_assoc();

    // Crear un array asociativo para la respuesta
    $response = array(
        'id' => $row['ID_Publication'],
        'tipo' => $row['type']
        // Agrega más campos según sea necesario
    );

    // Convertir el array asociativo a formato JSON y enviarlo como respuesta
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Si no se encontraron resultados, enviar una respuesta indicando que no se encontró la publicación
    echo json_encode(array('error' => 'No se encontró la publicación con el ID proporcionado.'));
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conn->close();
?>
