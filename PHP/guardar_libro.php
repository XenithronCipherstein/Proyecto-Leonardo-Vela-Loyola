<?php
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

// Verifica si se enviaron datos mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos del formulario
    $idPublication = $_POST["idPublication"];
    $titulo = $_POST["titulo"];
    $idPublisher = $_POST["idPublisher"];
    $volumen = $_POST["volumen"];
    $serie = $_POST["serie"];
    $edicion = $_POST["edicion"];
    $mesPublicacion = $_POST["mesPublicacion"];
    $anioPublicacion = $_POST["anioPublicacion"];
    $nota = $_POST["nota"];

    // Prepara la consulta SQL para insertar un nuevo libro
    $sql = "INSERT INTO book (ID_Book, title, ID_Publisher, volume, series, edition, pub_month, pub_year, note)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara la declaración y vincula los parámetros
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isississi", $idPublication, $titulo, $idPublisher, $volumen, $serie, $edicion, $mesPublicacion, $anioPublicacion, $nota);

    // Ejecuta la declaración
    if ($stmt->execute()) {
        echo "Libro agregado exitosamente.";
    } else {
        echo "Error al agregar el libro: " . $stmt->error;
    }

    // Cierra la declaración y la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    // Si no se recibieron datos por POST, muestra un mensaje de error
    echo "Error: Se esperaban datos por el método POST.";
}
?>
