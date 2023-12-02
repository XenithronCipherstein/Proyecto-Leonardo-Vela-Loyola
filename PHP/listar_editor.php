<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    header("Location: index.php");
    exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabajo_final_editorial";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM editor";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Editores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa; /* Nuevo fondo de la página */
        }

        .table {
            margin-top: 20px;
        }

        #formularioAgregarEditor {
            display: none;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #formularioAgregarEditor h3 {
            text-align: center;
            color: #007bff;
        }

        #formularioAgregarEditor label {
            display: block;
            margin-top: 10px;
        }

        #formularioAgregarEditor input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        #agregarEditorBtn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #agregarEditorBtn:hover {
            background-color: #0056b3;
        }

        /* Animación de fadeIn */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Estilos para la tabla de editores */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff; /* Nuevo color de fondo */
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Estilos para los botones en la tabla */
        table button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        table button:hover {
            background-color: #218838;
        }

        /* Estilos para el div de información detallada */
        #infoEditor {
            display: none;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f8f9fa; /* Nuevo color de fondo */
        }

        /* Estilos para el botón de volver en la información detallada */
        #volverBtn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #volverBtn:hover {
            background-color: #c82333;
        }

    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <h2>Listado de Editores</h2>
    <table border="1">
        <tr>
            <th>ID de Publicación</th>
            <th>ID de Persona</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ID_Publication"] . "</td>";
                echo "<td>" . $row["ID_Person"] . "</td>";
                echo "<td>
                        <button class='btn btn-danger' onclick='eliminarEditor(" . $row["ID_Publication"] . "," . $row["ID_Person"] . ")'>Eliminar</button>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay editores</td></tr>";
        }
        ?>
    </table>

    <!-- Resto del código HTML y scripts JavaScript existentes ... -->
    <!-- Agrega este código al cuerpo de tu HTML -->
    <div class="modal fade" id="modalInfoEditor" tabindex="-1" role="dialog" aria-labelledby="modalInfoEditorLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoEditorLabel">Detalles del Editor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenido de la modal -->
                    <div id="detalleEditor">
                        <!-- Aquí se llenarán dinámicamente los detalles del editor -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button id="agregarEditorBtn" onclick="mostrarFormularioAgregarEditor()">Agregar Editor</button>
    <div id="formularioAgregarEditor">
        <h3>Agregar Editor</h3>

        <label for="idPublication">ID de Publicación:</label>
        <select id="idPublication">
            <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
        </select>

        <label for="idPerson">ID de Persona:</label>
        <select id="idPerson">
            <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
        </select>

        <button onclick="agregarEditor()">Guardar</button>
    </div>

    <div id="infoEditor" class="hidden">
        <!-- Contenido de la información detallada del editor -->
        <!-- ... -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function mostrarFormularioAgregarEditor() {
            $("#formularioAgregarEditor").toggle();
        }

        function agregarEditor() {
            var idPublication = $("#idPublication").val();
            var idPerson = $("#idPerson").val();

            if (idPublication === "" || idPerson === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            $.ajax({
                type: "POST",
                url: "guardar_editor.php", // Cambiado a "guardar_editor.php"
                data: {
                    idPublication: idPublication,
                    idPerson: idPerson
                },
                success: function (response) {
                    alert(response);
                    // Puedes recargar la lista de editores después de agregar uno, si es necesario
                    // location.reload();
                },
                error: function (error) {
                    console.error("Error al enviar datos al servidor:", error);
                }
            });
        }

        // Función para actualizar dinámicamente las opciones de los combos
        $.ajax({
            type: "GET",
            url: "script_combo_author.php", // Cambiado a "script_combo_editor.php"
            success: function (response) {
                // Parsear la respuesta si es JSON
                var options = JSON.parse(response);

                // Agregar las opciones a los combos
                $("#idPublication").html(options.publications);
                $("#idPerson").html(options.persons);
            },
            error: function (error) {
                console.error("Error al obtener datos de publicaciones y personas:", error);
            }
        });


        function eliminarEditor(idPublication, idPerson) {
            // Confirmar con el usuario antes de eliminar
            var confirmacion = confirm("¿Estás seguro de que quieres eliminar este editor?");
            
            if (confirmacion) {
                // Realizar una solicitud AJAX para eliminar el editor con los IDs dados
                $.ajax({
                    type: "POST",
                    url: "eliminar_editor.php",
                    data: {
                        idPublication: idPublication,
                        idPerson: idPerson
                    },
                    success: function (response) {
                        // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        alert(response);

                        // Recargar la página o realizar otras acciones necesarias después de eliminar el editor
                        location.reload();
                    },
                    error: function (error) {
                        // Manejar errores, si es necesario
                        console.error("Error al eliminar el editor:", error);
                    }
                });
            }
        }
    </script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
