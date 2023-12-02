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

// Obtener el ID del autor de la solicitud GET
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id !== null) {
    // Consulta para obtener detalles del autor según el ID
    $sql = "SELECT ID_Publication, ID_Person FROM author WHERE ID_Publication = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Obtener los detalles del autor como un array asociativo
        $row = $result->fetch_assoc();

        // Devolver los detalles del autor como respuesta en formato JSON
        echo json_encode($row);
    } else {
        // Si no se encuentran resultados
        echo json_encode(array('error' => 'No se encontraron detalles para el autor con el ID proporcionado.'));
    }
} else {
    // Si no se proporciona un ID válido
    echo json_encode(array('error' => 'ID de autor no válido.'));
}

// Cerrar la conexión
$conn->close();
?>
