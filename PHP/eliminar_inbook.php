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

// Obtener el ID del Inbook a eliminar desde la solicitud POST
$idInbook = $_POST['idInbook'];

// Consulta para eliminar el Inbook
$sql = "DELETE FROM inbook WHERE ID_Inbook = $idInbook";

if ($conn->query($sql) === TRUE) {
    echo "Inbook eliminado exitosamente";
} else {
    echo "Error al eliminar el Inbook: " . $conn->error;
}

// Cerrar la conexión a la base de datos aquí
$conn->close();
?>
