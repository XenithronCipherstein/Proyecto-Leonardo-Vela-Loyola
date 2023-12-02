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

// Obtener el ID del misc enviado por la solicitud GET
$idMisc = $_GET['idMisc'];

// Preparar la consulta SQL para obtener los detalles del misc
$sql = "SELECT * FROM misc WHERE ID_Misc = '$idMisc'";

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Obtener los detalles del misc como un array asociativo
    $detallesMisc = $result->fetch_assoc();

    // Devolver los detalles como JSON
    echo json_encode($detallesMisc);
} else {
    // Si no se encontraron resultados, devolver un mensaje de error
    echo json_encode(["error" => "No se encontraron detalles para el misc con ID $idMisc"]);
}

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
