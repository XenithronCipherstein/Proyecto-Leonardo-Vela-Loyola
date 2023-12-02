<?php
// guardar_edicion_institucion.php

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

    // Obtener los datos del formulario de edición
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $provincia = $_POST['provincia'];
    $codigoPostal = $_POST['codigoPostal'];
    $pais = $_POST['pais'];

    // Preparar y ejecutar la consulta para actualizar la institución
    $sql = "UPDATE institution SET
            name = '$nombre',
            street = '$calle',
            city = '$ciudad',
            province = '$provincia',
            postal_code = '$codigoPostal',
            country = '$pais'
            WHERE ID_Institution = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Edición exitosa";
    } else {
        echo "Error al editar la institución: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si la solicitud no es POST, devolver un mensaje de error
    echo "Acceso no autorizado";
}
?>
