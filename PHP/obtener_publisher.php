<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

// Recibir el ID de la editorial desde la solicitud GET
$id = $_GET['id'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Preparar la consulta SQL para obtener detalles de la editorial por ID
$sql = "SELECT * FROM publisher WHERE ID_Publisher = $id";

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Obtener los datos de la editorial como un array asociativo
    $row = $result->fetch_assoc();

    // Crear un array asociativo para la respuesta
    $response = array(
        'id' => $row['ID_Publisher'],
        'nombre' => $row['name'],
        'calle' => $row['street'],
        'ciudad' => $row['city'],
        'provincia' => $row['province'],
        'codigoPostal' => $row['postal_code'],
        'pais' => $row['country']
        // Agrega más campos según sea necesario
    );
    

    // Convertir el array asociativo a formato JSON y enviarlo como respuesta
    echo json_encode($response);
} else {
    // Si no se encontraron resultados, enviar una respuesta indicando que no se encontró la editorial
    echo "No se encontró la editorial con el ID proporcionado.";
}

// Cerrar la conexión
$conn->close();
?>
