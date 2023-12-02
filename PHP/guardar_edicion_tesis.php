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

// Obtener datos del formulario
$idTesis = $_POST['idTesis'];
$nuevoTitulo = $_POST['nuevoTitulo'];
$nuevaEscuela = $_POST['nuevaEscuela'];
$nuevoTipo = $_POST['nuevoTipo'];
$nuevoMesPublicacion = $_POST['nuevoMesPublicacion'];
$nuevoAnoPublicacion = $_POST['nuevoAnoPublicacion'];
$nuevaNota = $_POST['nuevaNota'];

// Realizar la actualización en la base de datos
$sql = "UPDATE thesis
        SET title = '$nuevoTitulo',
            school = '$nuevaEscuela',
            type = '$nuevoTipo',
            pub_month = '$nuevoMesPublicacion',
            pub_year = '$nuevoAnoPublicacion',
            note = '$nuevaNota'
        WHERE ID_Thesis = $idTesis";

if ($conn->query($sql) === TRUE) {
    $response = array('success' => true, 'message' => 'Edición guardada con éxito');
} else {
    $response = array('error' => true, 'message' => 'Error al guardar la edición: ' . $conn->error);
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión
$conn->close();
?>
