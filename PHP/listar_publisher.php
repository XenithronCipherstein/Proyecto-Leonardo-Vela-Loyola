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

$sql = "SELECT * FROM publisher";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Editoriales</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    padding: 20px;
    }

    .table {
        margin-top: 20px;
    }

    /* Agrega estilos para el formulario */
    #formularioAgregarEditorial {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: none;
        animation: fadeIn 0.5s ease;
    }

    /* Agrega estilos para el botón de agregar */
    #agregarEditorialBtn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #agregarEditorialBtn:hover {
        background-color: #0056b3;
    }

    /* Agrega estilos para la tabla de editoriales */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
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

    /* Agrega estilos para los botones en la tabla */
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

    /* Agrega estilos para la información detallada */
    #infoEditorial {
        display: none;
        margin-top: 20px;
        padding: 10px;
        border: 1px solid #ddd;
    }

    /* Agrega estilos para el botón de volver en la información detallada */
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

    /* Agrega animación de fadeIn */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <h2>Listado de Editoriales</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Country</th>
            <th>Actions</th>

        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["country"] . "</td>";
                echo "<td>
                        <button class='btn btn-info' onclick='verEditorial(" . $row["ID_Publisher"] . ")'>Ver</button>
                        <button class='btn btn-warning' onclick='editarEditorial(" . $row["ID_Publisher"] . ")'>Editar</button>
                        <button class='btn btn-danger' onclick='eliminarEditorial(" . $row["ID_Publisher"] . ")'>Eliminar</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No hay editoriales</td></tr>";
        }
        ?>
    </table>
<!-- Agrega este código al cuerpo de tu HTML -->
<div class="modal fade" id="modalInfoEditorial" tabindex="-1" role="dialog" aria-labelledby="modalInfoEditorialLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInfoEditorialLabel">Detalles de la Editorial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenido de la modal -->
                <div id="detalleEditorial">
                    <!-- Aquí se llenarán dinámicamente los detalles de la editorial -->
                </div>
            </div>
        </div>
    </div>
</div>

<button id="agregarEditorialBtn" onclick="mostrarFormularioAgregarEditorial()">Agregar Editorial</button>

<div id="formularioAgregarEditorial" style="display: none;">
    <h3>Agregar Editorial</h3>
    
    <div class="form-group">
        <label for="nombreEditorial">Nombre:</label>
        <input type="text" class="form-control" id="nombreEditorial" />
    </div>

    <div class="form-group">
        <label for="calleEditorial">Calle:</label>
        <input type="text" class="form-control" id="calleEditorial" />
    </div>

    <div class="form-group">
        <label for="ciudadEditorial">Ciudad:</label>
        <input type="text" class="form-control" id="ciudadEditorial" />
    </div>

    <div class="form-group">
        <label for="provinciaEditorial">Provincia:</label>
        <input type="text" class="form-control" id="provinciaEditorial" />
    </div>

    <div class="form-group">
        <label for="codigoPostalEditorial">Código Postal:</label>
        <input type="text" class="form-control" id="codigoPostalEditorial" />
    </div>

    <div class="form-group">
        <label for="paisEditorial">País:</label>
        <input type="text" class="form-control" id="paisEditorial" />
    </div>

    <button type="button" class="btn btn-primary" onclick="agregarEditorial()">Guardar</button>
</div>

    <div id="infoEditorial" class="hidden">
        <!-- Contenido de la información detallada de la editorial -->
        <!-- ... -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function mostrarFormularioAgregarEditorial() {
            $("#formularioAgregarEditorial").toggle();
        }

        function agregarEditorial() {
            var nombre = $("#nombreEditorial").val();
            var calle = $("#calleEditorial").val();
            var ciudad = $("#ciudadEditorial").val();
            var provincia = $("#provinciaEditorial").val();
            var codigoPostal = $("#codigoPostalEditorial").val();
            var pais = $("#paisEditorial").val();

            if (nombre === "" || calle === "" || ciudad === "" || provincia === "" || codigoPostal === "" || pais === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            $.ajax({
                type: "POST",
                url: "guardar_publisher.php",
                data: {
                    nombre: nombre,
                    calle: calle,
                    ciudad: ciudad,
                    provincia: provincia,
                    codigoPostal: codigoPostal,
                    pais: pais
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
        function verEditorial(id) {
            // Lógica para obtener y mostrar la información detallada de la editorial con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_publisher.php",
                data: { id: id },
                success: function (response) {
                    var detallesEditorial = JSON.parse(response);

                    // Mostrar la pantalla modal con los detalles
                    $("#modalInfoEditorial").modal("show");

                    // Llenar el contenido de la pantalla modal con los detalles de la editorial
                    // Mostrar los detalles en la ventana modal
                    $("#detalleEditorial").html(`
                        <p>ID: ${detallesEditorial.id}</p>
                        <p>Nombre: ${detallesEditorial.nombre}</p>
                        <p>Calle: ${detallesEditorial.calle}</p>
                        <p>Ciudad: ${detallesEditorial.ciudad}</p>
                        <p>Provincia: ${detallesEditorial.provincia}</p>
                        <p>Código Postal: ${detallesEditorial.codigoPostal}</p>
                        <p>País: ${detallesEditorial.pais}</p>
                        <!-- Agrega más campos según sea necesario -->
                    `);

                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al obtener detalles de la editorial:", error);
                }
            });
        }
        function editarEditorial(id) {
            // Realizar una solicitud AJAX para obtener los detalles de la editorial con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_publisher.php",
                data: {
                    id: id
                },
                success: function (response) {
                    // Parsear la respuesta JSON
                    var detallesEditorial = JSON.parse(response);

                    // Mostrar los detalles en el formulario de edición en la misma ventana modal
                    $("#detalleEditorial").html(`
                        <form id="formularioEdicionEditorial">
                            <input type="hidden" name="id" value="${detallesEditorial.id}">
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" value="${detallesEditorial.nombre}">
                            <label for="calle">Calle:</label>
                            <input type="text" name="calle" value="${detallesEditorial.calle}">
                            <label for="ciudad">Ciudad:</label>
                            <input type="text" name="ciudad" value="${detallesEditorial.ciudad}">
                            <label for="provincia">Provincia:</label>
                            <input type="text" name="provincia" value="${detallesEditorial.provincia}">
                            <label for="codigoPostal">Código Postal:</label>
                            <input type="text" name="codigoPostal" value="${detallesEditorial.codigoPostal}">
                            <label for="pais">País:</label>
                            <input type="text" name="pais" value="${detallesEditorial.pais}">
                            <!-- Agrega más campos según sea necesario -->
                            <button type="button" onclick="guardarEdicionEditorial()">Guardar</button>
                        </form>
                    `);

                    // Mostrar la ventana modal
                    $("#modalInfoEditorial").modal("show");
                },
                error: function (error) {
                    console.error("Error al obtener detalles de la editorial:", error);
                }
            });
        }

        function guardarEdicionEditorial() {
            // Obtener los valores actualizados del formulario de edición
            var id = $("#formularioEdicionEditorial input[name='id']").val();
            var nombre = $("#formularioEdicionEditorial input[name='nombre']").val();
            var calle = $("#formularioEdicionEditorial input[name='calle']").val();
            var ciudad = $("#formularioEdicionEditorial input[name='ciudad']").val();
            var provincia = $("#formularioEdicionEditorial input[name='provincia']").val();
            var codigoPostal = $("#formularioEdicionEditorial input[name='codigoPostal']").val();
            var pais = $("#formularioEdicionEditorial input[name='pais']").val();

            // Realizar una solicitud AJAX para enviar los datos actualizados al servidor
            $.ajax({
                type: "POST",
                url: "guardar_edicion_editorial.php", // Reemplaza con la ruta correcta a tu script PHP de guardado
                data: {
                    id: id,
                    nombre: nombre,
                    calle: calle,
                    ciudad: ciudad,
                    provincia: provincia,
                    codigoPostal: codigoPostal,
                    pais: pais
                    // Agrega más campos según sea necesario
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar la ventana modal después de guardar la edición
                    $("#modalInfoEditorial").modal("hide");

                    // Recargar la página o realizar otras acciones necesarias después de guardar la edición
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }
        function eliminarEditorial(id) {
            // Confirmar con el usuario antes de proceder con la eliminación
            var confirmacion = confirm("¿Estás seguro de que quieres eliminar esta editorial?");
            
            if (confirmacion) {
                // Realizar la solicitud AJAX
                $.ajax({
                    type: "POST",
                    url: "eliminar_editorial.php",
                    data: { id: id },
                    success: function(response) {
                        // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        alert(response);

                        // Recargar la página o realizar otras acciones necesarias después de eliminar
                        location.reload();
                    },
                    error: function(error) {
                        // Manejar errores, si es necesario
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
