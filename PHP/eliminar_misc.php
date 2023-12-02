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

// Obtener el ID del misceláneo a eliminar
$idMisc = $_POST['idMisc'];

// Preparar la consulta SQL para eliminar el misceláneo
$sql = "DELETE FROM misc WHERE ID_Misc='$idMisc'";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    // Éxito en la eliminación
    $response = ["success" => true, "message" => "Misceláneo eliminado correctamente."];
} else {
    // Error en la eliminación
    $response = ["success" => false, "message" => "Error al eliminar misceláneo: " . $conn->error];
}

// Devolver la respuesta como JSON
echo json_encode($response);

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
