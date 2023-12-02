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

// Obtener los datos enviados por POST
$idTechreport = isset($_POST['idTechreport']) ? intval($_POST['idTechreport']) : 0;
$ID_Institution = isset($_POST['ID_Institution']) ? intval($_POST['ID_Institution']) : 0;
$title = isset($_POST['title']) ? $_POST['title'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : '';
$number = isset($_POST['number']) ? $_POST['number'] : '';
$pub_month = isset($_POST['pub_month']) ? $_POST['pub_month'] : '';
$pub_year = isset($_POST['pub_year']) ? intval($_POST['pub_year']) : 0;
$note = isset($_POST['note']) ? $_POST['note'] : '';

// Verificar que los campos requeridos no estén vacíos
if ($ID_Institution === "" || $title === "" || $type === "" || $number === "" || $pub_month === "" || $pub_year === "" || $note === "") {
    echo "Por favor, completa todos los campos obligatorios.";
    exit();
}

// Actualizar los datos del informe técnico en la base de datos
$sql = "UPDATE techreport SET ID_Institution=?, title=?, type=?, number=?, pub_month=?, pub_year=?, note=? WHERE ID_Techreport=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ississsi", $ID_Institution, $title, $type, $number, $pub_month, $pub_year, $note, $idTechreport);
$result = $stmt->execute();

if ($result) {
    echo "Informe técnico actualizado con éxito.";
} else {
    echo "Error al actualizar el informe técnico.";
}

// Cerrar la conexión a la base de datos aquí
$stmt->close();
$conn->close();
?>
