<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    header("Location: index.php");
    exit;
}
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

// Consulta para obtener todos los tipos de publicación
$sql = "SELECT * FROM publication";
$result = $conn->query($sql);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Publicaciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            padding: 20px;
        }

        .table {
            margin-top: 20px;
        }

        #formularioAgregar {
            margin-top: 20px;
        }

        /* Agrega estilos para el botón */
        #agregarBtn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #agregarBtn:hover {
            background-color: #0056b3;
        }

        /* Agrega estilos para el formulario */
        #formularioAgregar {
            display: none;
            animation: fadeIn 0.5s ease;
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
    <h2>Listado de Publicaciones</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar los resultados en la tabla
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ID_Publication"] . "</td>";
                    echo "<td>" . $row["type"] . "</td>";
                    echo "<td>
                            <button class='btn btn-info' onclick='verPublicacion(" . $row["ID_Publication"] . ")'>Ver</button>
                            <button class='btn btn-warning' onclick='editarPublicacion(" . $row["ID_Publication"] . ")'>Editar</button>
                            <button class='btn btn-danger' onclick='eliminarPublicacion(" . $row["ID_Publication"] . ")'>Eliminar</button>
                            </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No hay publicaciones</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Agrega esta estructura HTML para mostrar la información detallada de la publicación -->
    <div class="modal" id="modalInfoPublicacion" tabindex="-1" role="dialog" aria-labelledby="modalInfoPublicacionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoPublicacionLabel">Detalles de la Publicación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detallePublicacion">
                    <!-- Aquí se mostrarán los detalles de la publicación -->
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para agregar una nueva publicación -->
    <button id="agregarBtn" onclick="mostrarFormularioAgregar()">Agregar Publicación</button>

    <!-- Formulario para agregar una nueva publicación (inicialmente oculto) -->
    <div id="formularioAgregar" class="hidden">
        <h3>Agregar Publicación</h3>
        <form>
            <!-- Campos comunes para todos los tipos de publicaciones -->
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <input type="text" class="form-control" id="tipo" required>
            </div>
            <button type="button" class="btn btn-success" onclick="agregarPublicacion()">Guardar</button>
        </form>
    </div>

    <!-- Div para mostrar la información detallada de una publicación (inicialmente oculto) -->
    <div id="infoPublicacion" class="hidden">
        <!-- Contenido de la información detallada de la publicación -->
        <!-- ... -->
    </div>

    <!-- Script para gestionar las acciones con AJAX -->
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script>
        function mostrarFormularioAgregar() {
            $("#formularioAgregar").toggle();
        }

        function agregarPublicacion() {
            // Obtener los valores de los campos del formulario
            var tipo = $("#tipo").val();

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_publicacion.php",
                data: {
                    tipo: tipo
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Recargar la página o realizar otras acciones necesarias después de agregar
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos al servidor:", error);
                }
            });
        }
        function verPublicacion(id) {
            // Realizar una solicitud AJAX para obtener los detalles de la publicación con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_publicacion.php",
                data: {
                    id: id
                },
                success: function (response) {
                    try {
                        // Verificar si la respuesta ya es un objeto JavaScript
                        var detallesPublicacion = typeof response === 'object' ? response : JSON.parse(response);

                        // Verificar si las propiedades existen antes de acceder a ellas
                        if (detallesPublicacion && detallesPublicacion.id !== undefined && detallesPublicacion.tipo !== undefined) {
                            // Mostrar los detalles en la ventana modal
                            $("#detallePublicacion").html(`
                                <p>ID: ${detallesPublicacion.id}</p>
                                <p>Tipo: ${detallesPublicacion.tipo}</p>
                                <!-- Agrega más campos según sea necesario -->
                            `);

                            // Mostrar la ventana modal
                            $("#modalInfoPublicacion").modal("show");
                        } else {
                            console.error("Formato de respuesta incorrecto. Faltan propiedades necesarias.");
                        }
                    } catch (error) {
                        console.error("Error al manejar la respuesta:", error);
                    }
                },
                error: function (error) {
                    console.error("Error al obtener detalles de la publicación:", error);
                }
            });
        }
        function editarPublicacion(id) {
            // Realizar una solicitud AJAX para obtener los detalles de la publicación con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_publicacion.php",
                data: {
                    id: id
                },
                success: function (response) {
                    console.log("Respuesta del servidor:", response);

                    // Parsear la respuesta JSON
                    var detallesPublicacion =response;

                    // Mostrar los detalles en el formulario de edición en la misma ventana modal
                    $("#detallePublicacion").html(`
                        <form id="formularioEdicionPublicacion">
                            <input type="hidden" name="id" value="${detallesPublicacion.id}">
                            <label for="tipo">Tipo:</label>
                            <input type="text" name="tipo" value="${detallesPublicacion.tipo}">
                            <!-- Agrega más campos según sea necesario -->
                            <button type="button" onclick="guardarEdicionPublicacion()">Guardar</button>
                        </form>
                    `);

                    // Mostrar la ventana modal
                    $("#modalInfoPublicacion").modal("show");
                },
                error: function (error) {
                    console.error("Error al obtener detalles de la publicación:", error);
                }
            });
        }

        function guardarEdicionPublicacion() {
            // Obtener los valores actualizados del formulario de edición
            var id = $("#formularioEdicionPublicacion input[name='id']").val();
            var tipo = $("#formularioEdicionPublicacion input[name='tipo']").val();

            // Realizar una solicitud AJAX para enviar los datos actualizados al servidor
            $.ajax({
                type: "POST",
                url: "guardar_edicion_publicacion.php", // Reemplaza con la ruta correcta a tu script PHP de guardado
                data: {
                    id: id,
                    tipo: tipo
                    // Agrega más campos según sea necesario
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar la ventana modal después de guardar la edición
                    $("#modalInfoPublicacion").modal("hide");

                    // Recargar la página o realizar otras acciones necesarias después de guardar la edición
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }

        function eliminarPublicacion(id) {
            // Confirmar con el usuario antes de proceder con la eliminación
            var confirmacion = confirm("¿Estás seguro de que deseas eliminar esta publicación?");

            if (confirmacion) {
                // Realizar una solicitud AJAX para eliminar la publicación con el ID dado
                $.ajax({
                    type: "POST",
                    url: "eliminar_publicacion.php", // Reemplaza con la ruta correcta a tu script PHP de eliminación
                    data: {
                        id: id
                    },
                    success: function (response) {
                        // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        alert(response);

                        // Recargar la página o realizar otras acciones necesarias después de eliminar
                        location.reload();
                    },
                    error: function (error) {
                        // Manejar errores, si es necesario
                        console.error("Error al enviar datos de eliminación al servidor:", error);
                    }
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

