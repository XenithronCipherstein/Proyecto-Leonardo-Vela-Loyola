<?php
// eliminar_institucion.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos (reemplaza estos valores con los tuyos)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trabajo_final_editorial";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener el ID de la institución a eliminar
    $id = $_POST['id'];

    // Preparar y ejecutar la consulta para eliminar la institución
    $sql = "DELETE FROM institution WHERE ID_Institution = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Eliminación exitosa";
    } else {
        echo "Error al eliminar la institución: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si la solicitud no es POST, devolver un mensaje de error
    echo "Acceso no autorizado";
}
?>
