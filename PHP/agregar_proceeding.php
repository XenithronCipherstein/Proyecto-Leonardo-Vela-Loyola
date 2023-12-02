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

    // Verificar si se enviaron datos por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $idProceeding = $_POST['ID_Proceedings'];
        $title = $_POST['title'];
        $publisher = $_POST['publisher'];
        $volume = $_POST['volume'];
        $series = $_POST['series'];
        $organization = $_POST['organization'];
        $pub_month = $_POST['pubMonth'];  // Fíjate en el cambio aquí
        $pub_year = $_POST['pubYear'];    // Fíjate en el cambio aquí
        $note = $_POST['note'];


        // Preparar la consulta SQL para agregar el procedimiento
        $sql = "INSERT INTO proceedings (ID_Proceedings, title, publisher, volume, series, organization, pub_month, pub_year, note) VALUES ('$idProceeding', '$title', $publisher, '$volume', '$series', $organization, '$pub_month', $pub_year, '$note')";

        // Ejecutar la consulta
        if ($conn->query($sql) === TRUE) {
            // Éxito al agregar el procedimiento
            $response = ["success" => true, "message" => "Procedimiento agregado correctamente."];
        } else {
            // Error al agregar el procedimiento
            $response = ["success" => false, "message" => "Error al agregar procedimiento: " . $conn->error];
        }

        // Devolver la respuesta como JSON
        echo json_encode($response);

        // Cerrar la conexión a la base de datos aquí
        $conn->close();
    }
    ?>
