<?php
// obtener_institucion.php

if (isset($_GET['id'])) {
    $id = $_GET['id'];

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

    // Preparar y ejecutar la consulta para obtener los detalles de la institución
    $sql = "SELECT * FROM institution WHERE ID_Institution = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Obtener los detalles de la institución
        $row = $result->fetch_assoc();

        // Devolver la respuesta en formato JSON
        $response = array(
            'id' => $row['ID_Institution'],
            'nombre' => $row['name'],
            'calle' => $row['street'],
            'ciudad' => $row['city'],
            'provincia' => $row['province'],
            'codigoPostal' => $row['postal_code'],
            'pais' => $row['country']
            // Agrega más campos según sea necesario
        );

        echo json_encode($response);
    } else {
        // Si no se encuentra la institución, devolver un mensaje de error
        echo json_encode(array('error' => 'Institución no encontrada'));
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se proporciona el parámetro 'id', devuelve un mensaje de error
    echo json_encode(array('error' => 'ID no proporcionado'));
}
?>
