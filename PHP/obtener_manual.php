<?php
// Asegúrate de tener la conexión a la base de datos y las credenciales configuradas

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idManual"])) {
    // Obtener el ID del Manual desde la solicitud POST
    $idManual = $_POST["idManual"];

    // Realizar la consulta para obtener los detalles del Manual desde la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trabajo_final_editorial";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener los detalles del Manual por su ID
    $sql = "SELECT * FROM manual WHERE ID_Manual = $idManual";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Obtener los detalles del Manual
        $detallesManual = $result->fetch_assoc();

        // Devolver los detalles como JSON
        echo json_encode($detallesManual);
    } else {
        // Si no se encuentra el manual, devolver un mensaje de error
        echo json_encode(array("error" => "Manual no encontrado"));
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si la solicitud no es de tipo POST o no se proporcionó el ID del Manual, devolver un mensaje de error
    echo json_encode(array("error" => "Solicitud no válida"));
}
?>
