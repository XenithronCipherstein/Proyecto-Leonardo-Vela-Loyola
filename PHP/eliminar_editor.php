<?php
// Configuración de conexión a la base de datos
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

// Obtener los IDs del autor a eliminar
$idPublication = isset($_POST['idPublication']) ? $_POST['idPublication'] : null;
$idPerson = isset($_POST['idPerson']) ? $_POST['idPerson'] : null;

if ($idPublication !== null && $idPerson !== null) {
    // Consulta para eliminar el autor
    $sql = "DELETE FROM editor WHERE ID_Publication = $idPublication AND ID_Person = $idPerson";

    if ($conn->query($sql) === TRUE) {
        echo "Autor eliminado correctamente.";
    } else {
        echo "Error al eliminar autor: " . $conn->error;
    }
} else {
    echo "ID de autor no válido.";
}

// Cerrar la conexión
$conn->close();
?>
