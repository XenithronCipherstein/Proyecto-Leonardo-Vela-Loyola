<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el nombre de la tabla desde el formulario
$table_name = $_POST['table_name'];

// Crear la consulta INSERT
$insert_query = "INSERT INTO $table_name (";

foreach ($_POST as $key => $value) {
    if ($key != 'table_name') {
        $insert_query .= "`$key`, ";
    }
}

$insert_query = rtrim($insert_query, ', ');
$insert_query .= ") VALUES (";

foreach ($_POST as $key => $value) {
    if ($key != 'table_name') {
        $insert_query .= "'$value', ";
    }
}

$insert_query = rtrim($insert_query, ', ');
$insert_query .= ")";

// Ejecutar la consulta
if ($conn->query($insert_query) === TRUE) {
    echo "Datos insertados correctamente en la tabla $table_name.";
} else {
    echo "Error al insertar datos: " . $conn->error;
}

$conn->close();
?>
