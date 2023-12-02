<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

// Obtener datos del formulario POST
$id = $_POST['id'];
$title = $_POST['title'];
$journal = $_POST['journal'];
$volume = $_POST['volume'];
$number = $_POST['number'];
$pages = $_POST['pages'];
$pub_month = $_POST['pub_month'];
$pub_year = $_POST['pub_year'];
$note = $_POST['note'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Preparar la consulta SQL para actualizar los detalles del artículo
$sql = "UPDATE article 
        SET title = '$title', journal = '$journal', volume = '$volume', number = '$number', 
            pages = '$pages', pub_month = '$pub_month', pub_year = '$pub_year', note = '$note' 
        WHERE ID_Article = $id";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Cambios guardados con éxito.";
} else {
    echo "Error al guardar cambios: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
