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
$dbname = "trabajo_final_editorial"; // Reemplaza con el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM misc";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Misceláneos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <!-- Agrega cualquier otro enlace a hojas de estilo necesario -->

    <style>
        /* Estilos generales */
        body {
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }


        /* Estilos para la tabla */
        .table {
            width: 100%;
            margin-top: 20px;
        }

        .table th, .table td {
            text-align: center;
        }

        /* Estilos para los botones */
        .btn {
            margin-right: 10px;
        }

        /* Estilos para los formularios */
        form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        /* Estilos para la ventana modal */
        .modal {
            text-align: left;
        }

        .modal-title {
            font-size: 24px;
        }

        .modal-body {
            font-size: 16px;
        }

        /* Estilos para el botón de agregar */
        #agregarMiscBtn {
            margin-bottom: 20px;
        }

        /* Estilos para la cabecera principal */
        h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        /* Estilos para los mensajes de error */
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4">Listado de Misceláneos</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>ID del Misceláneo</th>
                    <th>Título</th>
                    <th>Dirección</th>
                    <th>Publicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los resultados en la tabla
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Misc"] . "</td>";
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>" . $row["address"] . "</td>";
                        echo "<td>" . $row["howpublished"] . "</td>";
                        echo "<td>
                                <button class='btn btn-info btn-sm' onclick='verMisc(" . $row["ID_Misc"] . ")'>Ver</button>
                                <button class='btn btn-warning btn-sm' onclick='editarMisc(" . $row["ID_Misc"] . ")'>Editar</button>
                                <button class='btn btn-danger btn-sm' onclick='eliminarMisc(" . $row["ID_Misc"] . ")'>Eliminar</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay misceláneos</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button id="agregarMiscBtn" class="btn btn-primary mb-3" onclick="mostrarFormularioAgregarMisc()">Agregar Misceláneo</button>
    </div>

    <!-- Ejemplo de ventana modal para mostrar detalles del misceláneo -->
    <div class="modal fade" id="modalInfoMisc" tabindex="-1" role="dialog" aria-labelledby="modalInfoMiscLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoMiscLabel">Detalles del Misceláneo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalleMisc">
                    <!-- Contenido dinámico del modal -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Ejemplo de formulario para agregar/editar misceláneo -->
    <!-- Formulario para agregar un nuevo misceláneo (inicialmente oculto) -->
    <div class="container mt-3" id="formularioAgregarMisc" style="display: none;">
        <h3>Agregar Misceláneo</h3>
        <form>
            <!-- Campos específicos para misceláneos -->
            <div class="form-group">
                <label for="idMisc">ID de Misceláneo:</label>
                <select class="form-control" id="idMisc">
                    <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                </select>
            </div>

            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" class="form-control" id="title" />
            </div>

            <div class="form-group">
                <label for="address">Dirección:</label>
                <input type="text" class="form-control" id="address" />
            </div>

            <div class="form-group">
                <label for="howpublished">Cómo se Publicó:</label>
                <input type="text" class="form-control" id="howpublished" />
            </div>

            <div class="form-group">
                <label for="pub_month">Mes de Publicación:</label>
                <input type="text" class="form-control" id="pub_month" />
            </div>

            <div class="form-group">
                <label for="pub_year">Año de Publicación:</label>
                <input type="number" class="form-control" id="pub_year" />
            </div>

            <div class="form-group">
                <label for="note">Nota:</label>
                <input type="text" class="form-control" id="note" />
            </div>

            <button type="button" class="btn btn-primary" onclick="agregarMisc()">Guardar</button>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Agrega cualquier otro enlace a scripts necesarios -->
    <script>
        function mostrarFormularioAgregarMisc() {
            $("#formularioAgregarMisc").toggle();
        }
        function agregarMisc() {
            // Obtener los valores del formulario
            var ID_Misc = $("#idMisc").val();
            var title = $("#title").val();
            var address = $("#address").val();
            var howpublished = $("#howpublished").val();
            var pub_month = $("#pub_month").val();
            var pub_year = $("#pub_year").val();
            var note = $("#note").val();

            // Realizar una solicitud AJAX para enviar los datos al servidor
            $.ajax({
                type: "POST",
                url: "agregar_misc.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de agregar misceláneo
                data: {
                    ID_Misc: ID_Misc,
                    title: title,
                    address: address,
                    howpublished: howpublished,
                    pub_month: pub_month,
                    pub_year: pub_year,
                    note: note
                },
                dataType: "json",
                success: function (response) {
                    // Aquí puedes manejar la respuesta del servidor
                    console.log(response);

                    // Cerrar el formulario o realizar otras acciones según sea necesario
                    $("#formularioAgregarMisc").hide();
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error("Error al agregar misceláneo:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }
        $.ajax({
            type: "GET",
            url: "script_combo.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#idMisc").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos de publicaciones:", error);
            }
        });
    </script>
    <script>

        // Funciones JavaScript para interactuar con la página
        function verMisc(idMisc) {
            // Realizar una solicitud AJAX para obtener los detalles del misc con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_misc.php",
                data: { idMisc: idMisc },
                dataType: "json", // Especifica el tipo de datos esperado como JSON
                success: function (detallesMisc) {
                    // Verifica si los detalles del misc son válidos antes de mostrar el modal
                    if (detallesMisc && !detallesMisc.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoMisc").modal("show");

                        // Llenar el contenido de la ventana modal con los detalles del misc
                        $("#detalleMisc").html(`
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>ID: ${detallesMisc.ID_Misc}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Título: ${detallesMisc.title}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Dirección: ${detallesMisc.address}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Cómo se Publicó: ${detallesMisc.howpublished}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Mes de Publicación: ${detallesMisc.pub_month}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Año de Publicación: ${detallesMisc.pub_year}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Nota: ${detallesMisc.note}</h4>
                                    </div>
                                    <!-- Agrega más campos según sea necesario -->
                                </div>
                            </div>
                        `);

                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del misc:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }

//////////////////////////////////////////////////////////////////////////////////////////////////////////
        function editarMisc(idMisc) {
            // Realizar una solicitud AJAX para obtener los detalles del misceláneo con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_misc.php",
                data: { idMisc: idMisc },
                dataType: "json",
                success: function (detallesMisc) {
                    if (detallesMisc && !detallesMisc.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoMisc").modal("show");

                        // Llenar el contenido de la ventana modal con el formulario de edición del Misceláneo
                        var contenidoDetalle = `
                            <form id="formularioEditarMisc">
                                <input type="hidden" id="editIdMisc" value="${detallesMisc.ID_Misc}">

                                <div class="form-group">
                                    <label for="editTitleMisc">Título:</label>
                                    <input type="text" class="form-control" id="editTitleMisc" value="${detallesMisc.title}">
                                </div>

                                <div class="form-group">
                                    <label for="editAddress">Dirección:</label>
                                    <input type="text" class="form-control" id="editAddress" value="${detallesMisc.address}">
                                </div>

                                <div class="form-group">
                                    <label for="editHowPublished">Cómo se Publicó:</label>
                                    <input type="text" class="form-control" id="editHowPublished" value="${detallesMisc.howpublished}">
                                </div>

                                <div class="form-group">
                                    <label for="editPubMonthMisc">Mes de Publicación:</label>
                                    <input type="text" class="form-control" id="editPubMonthMisc" value="${detallesMisc.pub_month}">
                                </div>

                                <div class="form-group">
                                    <label for="editPubYearMisc">Año de Publicación:</label>
                                    <input type="number" class="form-control" id="editPubYearMisc" value="${detallesMisc.pub_year}">
                                </div>

                                <div class="form-group">
                                    <label for="editNoteMisc">Nota:</label>
                                    <textarea class="form-control" id="editNoteMisc">${detallesMisc.note}</textarea>
                                </div>

                                <!-- Agrega más campos según sea necesario -->

                                <button type="button" class="btn btn-primary" onclick="guardarEdicionMisc()">Guardar</button>
                            </form>
                        `;

                        // Mostrar los detalles en la ventana modal
                        $("#detalleMisc").html(contenidoDetalle);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del Misceláneo:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoMisc").modal("show");
                    $("#detalleMisc").html(`<p>Error al obtener detalles del Misceláneo. Inténtalo nuevamente.</p>`);
                }
            });
        }

        function guardarEdicionMisc() {
            // Obtener los valores actualizados del formulario de edición
            var idMisc = $("#editIdMisc").val();
            var title = $("#editTitleMisc").val();
            var address = $("#editAddress").val();
            var howpublished = $("#editHowPublished").val();
            var pub_month = $("#editPubMonthMisc").val();
            var pub_year = $("#editPubYearMisc").val();
            var note = $("#editNoteMisc").val();

            // Verificar que los campos requeridos no estén vacíos
            if (title === "" || address === "" || howpublished === "" || pub_month === "" || pub_year === "" || note === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            // Enviar los datos actualizados al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_edicion_misc.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de edición
                data: {
                    idMisc: idMisc,
                    title: title,
                    address: address,
                    howpublished: howpublished,
                    pub_month: pub_month,
                    pub_year: pub_year,
                    note: note
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar la ventana modal después de guardar
                    $("#modalInfoMisc").modal("hide");

                    // Recargar la página o realizar otras acciones necesarias después de guardar
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }

//////////////////////////////////////////////////////////////////////////////////////////////////////
        function eliminarMisc(idMisc) {
            // Lógica para confirmar la eliminación y luego hacer una solicitud AJAX para eliminar el misceláneo
            var confirmar = confirm("¿Seguro que quieres eliminar el misceláneo con ID " + idMisc + "?");

            if (confirmar) {
                // Realizar una solicitud AJAX para eliminar el misceláneo con el ID dado
                $.ajax({
                    type: "POST",
                    url: "eliminar_misc.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de eliminación
                    data: {
                        idMisc: idMisc
                    },
                    dataType: "json",
                    success: function (response) {
                        // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        alert(response.message);

                        // Recargar la página o realizar otras acciones necesarias después de eliminar
                        location.reload();
                    },
                    error: function (error) {
                        // Manejar errores, si es necesario
                        console.error("Error al eliminar misceláneo:", error);
                    }
                });
            }
        }

    </script>
</body>
</html>
