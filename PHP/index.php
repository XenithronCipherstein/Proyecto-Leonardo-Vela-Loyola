<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Forms</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener lista de tablas en la base de datos
$tables_query = "SHOW TABLES";
$tables_result = $conn->query($tables_query);

if ($tables_result->num_rows > 0) {
    while ($row = $tables_result->fetch_assoc()) {
        $table_name = $row["Tables_in_$dbname"];

        // Generar formulario para cada tabla
        echo "<h2>Form for $table_name</h2>";
        echo "<form action='process_form.php' method='post'>";
        
        // Obtener información de columnas de la tabla
        $columns_query = "SHOW COLUMNS FROM $table_name";
        $columns_result = $conn->query($columns_query);

        if ($columns_result->num_rows > 0) {
            while ($column = $columns_result->fetch_assoc()) {
                $column_name = $column["Field"];
                echo "<label for='$column_name'>$column_name:</label>";
                echo "<input type='text' name='$column_name' id='$column_name' required><br>";
            }
            echo "<input type='submit' value='Insert'>";
            echo "</form><br>";
        } else {
            echo "There are no columns in the table $table_name";
        }
    }
} else {
    echo "There are no tables in the database";
}

$conn->close();
?>

</body>
</html>
