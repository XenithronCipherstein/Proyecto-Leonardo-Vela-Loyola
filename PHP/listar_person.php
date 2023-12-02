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

$sql = "SELECT * FROM person";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Personas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    padding: 20px;
    background-color: #f8f9fa; /* Nuevo fondo de la página */
    }

    .table {
        margin-top: 20px;
    }

    #formularioAgregarPersona {
        display: none;
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #formularioAgregarPersona h3 {
        text-align: center;
        color: #007bff;
    }

    #formularioAgregarPersona label {
        display: block;
        margin-top: 10px;
    }

    #formularioAgregarPersona input {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        box-sizing: border-box;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    #agregarPersonaBtn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #agregarPersonaBtn:hover {
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

    /* Estilos para la tabla de personas */
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
    #infoPersona {
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
    <h2>Listado de Personas</h2>
    <table border="1">
        <tr>
            <th>ID de Persona</th>
            <th>Sumame</th>
            <th>Given Names</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ID_Person"] . "</td>";
                echo "<td>" . $row["sumame"] . "</td>";
                echo "<td>" . $row["given_names"] . "</td>";
                echo "<td>
                        <button class='btn btn-warning' onclick='editarPersona(" . $row["ID_Person"] . ")'>Editar</button>
                        <button class='btn btn-danger' onclick='eliminarPersona(" . $row["ID_Person"] . ")'>Eliminar</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay personas</td></tr>";
        }
        ?>
    </table>
    <!-- Resto del código HTML y scripts JavaScript existentes ... -->
    <!-- Agrega este código al cuerpo de tu HTML -->
    <div class="modal fade" id="modalInfoPersona" tabindex="-1" role="dialog" aria-labelledby="modalInfoPersonaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoPersonaLabel">Detalles de la Persona</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenido de la modal -->
                    <div id="detallePersona">
                        <!-- Aquí se llenarán dinámicamente los detalles de la persona -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button id="agregarPersonaBtn" onclick="mostrarFormularioAgregarPersona()">Agregar Persona</button>
    <div id="formularioAgregarPersona">
        <h3>Agregar Persona</h3>
        <label for="sumame">Sumame:</label>
        <input type="text" id="sumame" />
        <label for="givenNames">Given Names:</label>
        <input type="text" id="givenNames" />
        <button onclick="agregarPersona()">Guardar</button>
    </div>
    <div id="infoPersona" class="hidden">
        <!-- Contenido de la información detallada de la persona -->
        <!-- ... -->
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function mostrarFormularioAgregarPersona() {
            $("#formularioAgregarPersona").toggle();
        }

        function agregarPersona() {
            var sumame = $("#sumame").val();
            var givenNames = $("#givenNames").val();

            if (sumame === "" || givenNames === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            $.ajax({
                type: "POST",
                url: "guardar_persona.php",
                data: {
                    sumame: sumame,
                    givenNames: givenNames
                },
                success: function (response) {
                    alert(response);
                    location.reload();
                },
                error: function (error) {
                    console.error("Error al enviar datos al servidor:", error);
                }
            });
        }

        function editarPersona(id) {
            $.ajax({
                type: "GET",
                url: "obtener_persona.php",
                data: {
                    id: id
                },
                success: function (response) {
                    var detallesPersona = JSON.parse(response);

                    $("#detallePersona").html(`
                        <form id="formularioEdicionPersona">
                            <input type="hidden" name="id" value="${detallesPersona.ID_Person}">
                            <label for="sumame">Sumame:</label>
                            <input type="text" name="sumame" value="${detallesPersona.sumame}">
                            <label for="givenNames">Given Names:</label>
                            <input type="text" name="givenNames" value="${detallesPersona.given_names}">
                            <!-- Agrega más campos según sea necesario -->
                            <button type="button" onclick="guardarEdicionPersona()">Guardar</button>
                        </form>
                    `);

                    $("#modalInfoPersona").modal("show");
                },
                error: function (error) {
                    console.error("Error al obtener detalles de la persona:", error);
                }
            });
        }

        function guardarEdicionPersona() {
            var id = $("#formularioEdicionPersona input[name='id']").val();
            var sumame = $("#formularioEdicionPersona input[name='sumame']").val();
            var givenNames = $("#formularioEdicionPersona input[name='givenNames']").val();

            $.ajax({
                type: "POST",
                url: "guardar_edicion_persona.php",
                data: {
                    id: id,
                    sumame: sumame,
                    givenNames: givenNames
                },
                success: function (response) {
                    alert(response);
                    $("#modalInfoPersona").modal("hide");
                    location.reload();
                },
                error: function (error) {
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }

        function eliminarPersona(id) {
            var confirmacion = confirm("¿Estás seguro de que quieres eliminar esta persona?");

            if (confirmacion) {
                $.ajax({
                    type: "POST",
                    url: "eliminar_persona.php",
                    data: { id: id },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error("Error al enviar datos al servidor:", error);
                    }
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
