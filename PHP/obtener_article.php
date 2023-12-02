<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

// Recibir el ID del artículo desde la solicitud GET
$id = $_GET['id'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Preparar la consulta SQL para obtener detalles del artículo por ID
$sql = "SELECT * FROM article WHERE ID_Article = $id";

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Obtener los datos del artículo como un array asociativo
    $row = $result->fetch_assoc();

    // Crear un array asociativo para la respuesta
    $response = array(
        'id' => $row['ID_Article'],
        'title' => $row['title'],
        'journal' => $row['journal'],
        'volume' => $row['volume'],
        'number' => $row['number'],
        'pages' => $row['pages'],
        'pub_month' => $row['pub_month'],
        'pub_year' => $row['pub_year'],
        'note' => $row['note']
        // Agrega más campos según sea necesario
    );

    // Convertir el array asociativo a formato JSON y enviarlo como respuesta
    echo json_encode($response);
} else {
    // Si no se encontraron resultados, enviar una respuesta indicando que no se encontró el artículo
    echo "No se encontró el artículo con el ID proporcionado.";
}

// Cerrar la conexión
$conn->close();
?>
