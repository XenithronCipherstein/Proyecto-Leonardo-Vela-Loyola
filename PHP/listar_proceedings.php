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

$sql = "SELECT * FROM proceedings";
$result = $conn->query($sql);

// Inicializar un array para almacenar los proceedings
$proceedings = [];

if ($result->num_rows > 0) {
    // Almacenar los proceedings en un array asociativo
    while ($row = $result->fetch_assoc()) {
        $proceeding = [
            "ID_Proceedings" => $row["ID_Proceedings"],
            "title" => $row["title"],
            "publisher" => $row["publisher"],
            "volume" => $row["volume"],
            "series" => $row["series"],
            "organization" => $row["organization"],
            "pub_month" => $row["pub_month"],
            "pub_year" => $row["pub_year"],
            "note" => $row["note"]
        ];

        // Agregar el proceeding al array
        $proceedings[] = $proceeding;
    }
}

// Liberar el conjunto de resultados
$result->free_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Proceedings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <!-- Agrega cualquier otro enlace a hojas de estilo necesario -->

    <style>
        /* Estilos generales */
        body {
            padding: 20px;
        }

        .container {
            margin-top: 20px;
            max-width: 1200px; /* Ajusta el valor según tus preferencias */
            margin-left: auto;
            margin-right: auto;
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
        #agregarProceedingBtn {
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
        <h2 class="mb-4">Listado de Proceedings</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>ID de Proceedings</th>
                    <th>Título</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los resultados en la tabla
                if (!empty($proceedings)) {
                    foreach ($proceedings as $proceeding) {
                        echo "<tr>";
                        echo "<td>" . $proceeding["ID_Proceedings"] . "</td>";
                        echo "<td>" . $proceeding["title"] . "</td>";
                        echo "<td>
                                <button class='btn btn-info btn-sm' onclick='verProceeding(" . $proceeding["ID_Proceedings"] . ")'>Ver</button>
                                <button class='btn btn-warning btn-sm' onclick='editarProceeding(" . $proceeding["ID_Proceedings"] . ")'>Editar</button>
                                <button class='btn btn-danger btn-sm' onclick='eliminarProceeding(" . $proceeding["ID_Proceedings"] . ")'>Eliminar</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay proceedings</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button id="agregarProceedingBtn" class="btn btn-primary mb-3" onclick="mostrarFormularioAgregarProceeding()">Agregar Proceedings</button>
    </div>

    <!-- Ejemplo de ventana modal para mostrar detalles del proceeding -->
    <div class="modal fade" id="modalInfoProceeding" tabindex="-1" role="dialog" aria-labelledby="modalInfoProceedingLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoProceedingLabel">Detalles del Proceedings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalleProceeding">
                    <!-- Contenido dinámico del modal -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Ejemplo de formulario para agregar/editar proceeding -->
    <!-- Formulario para agregar un nuevo proceeding (inicialmente oculto) -->
    <div class="container mt-3" id="formularioAgregarProceeding" style="display: none;">
        <h3>Agregar Proceedings</h3>
        <form>
            <!-- Campos específicos para proceedings -->
            <div class="form-group">
                <label for="idProceeding">ID de Proceedings:</label>
                <select class="form-control" id="idProceeding">
                    <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                </select>
            </div>

            <div class="form-group">
                <label for="titleProceeding">Título:</label>
                <input type="text" class="form-control" id="titleProceeding" />
            </div>

            <div class="form-group">
                <label for="publisherProceeding">Editor:</label>
                <select class="form-control" id="publisherProceeding">
                    <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                </select>
            </div>

            <div class="form-group">
                <label for="volumeProceeding">Volumen:</label>
                <input type="text" class="form-control" id="volumeProceeding" />
            </div>

            <div class="form-group">
                <label for="seriesProceeding">Serie:</label>
                <input type="text" class="form-control" id="seriesProceeding" />
            </div>

            <div class="form-group">
                <label for="organizationProceeding">Organización:</label>
                <select class="form-control" id="organizationProceeding">
                    <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                </select>
            </div>

            <div class="form-group">
                <label for="pubMonthProceeding">Mes de Publicación:</label>
                <input type="text" class="form-control" id="pubMonthProceeding" />
            </div>

            <div class="form-group">
                <label for="pubYearProceeding">Año de Publicación:</label>
                <input type="number" class="form-control" id="pubYearProceeding" />
            </div>

            <div class="form-group">
                <label for="noteProceeding">Nota:</label>
                <input type="text" class="form-control" id="noteProceeding" />
            </div>

            <button type="button" class="btn btn-primary" onclick="agregarProceeding()">Guardar</button>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Agrega cualquier otro enlace a scripts necesarios -->
    <script>
        function mostrarFormularioAgregarProceeding() {
            $("#formularioAgregarProceeding").toggle();
        }

        function agregarProceeding() {
            // Obtener los valores del formulario
            var ID_Proceedings = $("#idProceeding").val();
            var title = $("#titleProceeding").val();
            var publisher = $("#publisherProceeding").val();
            var volume = $("#volumeProceeding").val();
            var series = $("#seriesProceeding").val();
            var organization = $("#organizationProceeding").val();
            var pubMonth = $("#pubMonthProceeding").val();
            var pubYear = $("#pubYearProceeding").val();
            var note = $("#noteProceeding").val();

            // Realizar una solicitud AJAX para enviar los datos al servidor
            $.ajax({
                type: "POST",
                url: "agregar_proceeding.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de agregar proceeding
                data: {
                    ID_Proceedings: ID_Proceedings,
                    title: title,
                    publisher: publisher,
                    volume: volume,
                    series: series,
                    organization: organization,
                    pubMonth: pubMonth,
                    pubYear: pubYear,
                    note: note
                },
                dataType: "json",
                success: function (response) {
                    // Aquí puedes manejar la respuesta del servidor
                    console.log(response);

                    // Cerrar el formulario o realizar otras acciones según sea necesario
                    $("#formularioAgregarProceeding").hide();
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error("Error al agregar proceeding:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }

        $.ajax({
            type: "GET",
            url: "script_combo.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#idProceeding").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos:", error);
            }
        });
        $.ajax({
            type: "GET",
            url: "script_combo_publisher.php",
            dataType: "json",
            success: function (response) {
                // Limpiar opciones anteriores
                $("#publisherProceeding").empty();

                // Agregar las opciones al combo de editoriales
                for (var i = 0; i < response.length; i++) {
                    $("#publisherProceeding").append(
                        $("<option>", {
                            value: response[i].ID_Publisher,
                            text: response[i].name
                        })
                    );
                }
            },
            error: function (error) {
                console.error("Error al obtener datos de editoriales:", error);
            }
        });
        $.ajax({
            type: "GET",
            url: "script_combo_institution.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#organizationProceeding").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos:", error);
            }
        });
    </script>
    <script>
        function verProceeding(idProceedings) {
            // Realizar una solicitud AJAX para obtener los detalles del procedimiento con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_proceeding.php", // Ajusta la ruta al archivo PHP que procesará la solicitud para obtener detalles
                data: { idProceedings: idProceedings },
                dataType: "json",
                success: function (detallesProceeding) {
                    // Verifica si los detalles del procedimiento son válidos antes de mostrar el modal
                    if (detallesProceeding && !detallesProceeding.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoProceeding").modal("show");

                        // Llenar el contenido de la ventana modal con los detalles del procedimiento
                        $("#detalleProceeding").html(`
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>ID: ${detallesProceeding.ID_Proceedings}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Título: ${detallesProceeding.title}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Editor: ${detallesProceeding.publisher}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Volumen: ${detallesProceeding.volume}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Serie: ${detallesProceeding.series}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Organización: ${detallesProceeding.publisher}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Mes de Publicación: ${detallesProceeding.pub_month}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Año de Publicación: ${detallesProceeding.pub_year}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Nota: ${detallesProceeding.note}</h4>
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
                    console.error("Error al obtener detalles del procedimiento:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }
        function editarProceeding(idProceedings) {
            // Realizar una solicitud AJAX para obtener los detalles del procedimiento con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_proceeding.php",
                data: { idProceedings: idProceedings },
                dataType: "json",
                success: function (detallesProceeding) {
                    if (detallesProceeding && !detallesProceeding.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoProceeding").modal("show");

                        // Llenar el contenido de la ventana modal con el formulario de edición del Procedimiento
                        var contenidoDetalle = `
                            <form id="formularioEditarProceeding">
                                <input type="hidden" id="editIdProceedings" value="${detallesProceeding.ID_Proceedings}">

                                <div class="form-group">
                                    <label for="editTitleProceeding">Título:</label>
                                    <input type="text" class="form-control" id="editTitleProceeding" value="${detallesProceeding.title}">
                                </div>

                                <div class="form-group">
                                    <label for="editIdEditorial">Editor:</label>
                                    <select class="form-control" name="ID_Publisher" id="editIdEditorial"></select>
                                </div>

                                <div class="form-group">
                                    <label for="editVolumeProceeding">Volumen:</label>
                                    <input type="text" class="form-control" id="editVolumeProceeding" value="${detallesProceeding.volume}">
                                </div>

                                <div class="form-group">
                                    <label for="editSeriesProceeding">Serie:</label>
                                    <input type="text" class="form-control" id="editSeriesProceeding" value="${detallesProceeding.series}">
                                </div>

                                <div class="form-group">
                                    <label for="editIdInstitution">Organización:</label>
                                    <select class="form-control" name="ID_Institution" id="editIdInstitution"></select>
                                </div>

                                <div class="form-group">
                                    <label for="editPubMonthProceeding">Mes de Publicación:</label>
                                    <input type="text" class="form-control" id="editPubMonthProceeding" value="${detallesProceeding.pub_month}">
                                </div>

                                <div class="form-group">
                                    <label for="editPubYearProceeding">Año de Publicación:</label>
                                    <input type="number" class="form-control" id="editPubYearProceeding" value="${detallesProceeding.pub_year}">
                                </div>

                                <div class="form-group">
                                    <label for="editNoteProceeding">Nota:</label>
                                    <textarea class="form-control" id="editNoteProceeding">${detallesProceeding.note}</textarea>
                                </div>

                                <!-- Agrega más campos según sea necesario -->

                                <button type="button" class="btn btn-primary" onclick="guardarEdicionProceeding()">Guardar</button>
                            </form>
                        `;

                        // Mostrar los detalles en la ventana modal
                        $("#detalleProceeding").html(contenidoDetalle);
                        cargarOpcionesPublisher("#editIdEditorial", detallesProceeding.publisher);
                        cargarOpcionesInstituciones("#editIdInstitution", detallesProceeding.organization);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del Procedimiento:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoProceeding").modal("show");
                    $("#detalleProceeding").html(`<p>Error al obtener detalles del Procedimiento. Inténtalo nuevamente.</p>`);
                }
            });
        }

        function cargarOpcionesPublisher(selector, idEditorialSeleccionada) {
            $.ajax({
                type: "GET",
                url: "script_combo_publisher.php",
                success: function (response) {
                    var editoriales = JSON.parse(response);

                    // Limpiar opciones anteriores
                    $(selector).empty();

                    // Agregar opciones al combo
                    for (var i = 0; i < editoriales.length; i++) {
                        var selected = "";
                        if (editoriales[i].ID_Publisher == idEditorialSeleccionada) {
                            selected = "selected";
                        }
                        $(selector).append(`
                            <option value="${editoriales[i].ID_Publisher}" ${selected}>
                                ${editoriales[i].name}
                            </option>
                        `);
                    }
                },
                error: function (error) {
                    console.error("Error al obtener editoriales:", error);
                }
            });
        }

        function cargarOpcionesInstituciones(selector, idInstitucionSeleccionada) {
            $.ajax({
                type: "GET",
                url: "script_combo_institution.php",
                success: function (response) {
                    $(selector).html(response);

                    // Seleccionar la institución correspondiente
                    $(selector).val(idInstitucionSeleccionada);
                },
                error: function (error) {
                    console.error("Error al obtener datos de las instituciones:", error);
                }
            });
        }

        // Esperar a que el DOM esté listo
        $(document).ready(function () {
            // Cargar opciones del combo de instituciones al cargar la página
            cargarOpcionesInstituciones("#editIdInstitution", /* valor por defecto o null */);
        });

        function guardarEdicionProceeding() {
            // Obtener los valores actualizados del formulario de edición
            var idProceedings = $("#editIdProceedings").val();
            var title = $("#editTitleProceeding").val();
            var publisher = $("#editIdEditorial").val();
            var volume = $("#editVolumeProceeding").val();
            var series = $("#editSeriesProceeding").val();
            var organization = $("#editIdInstitution").val();
            var pub_month = $("#editPubMonthProceeding").val();
            var pub_year = $("#editPubYearProceeding").val();
            var note = $("#editNoteProceeding").val();

            // Verificar que los campos requeridos no estén vacíos
            if (title === "" || publisher === "" || volume === "" || series === "" || organization === "" || pub_month === "" || pub_year === "" || note === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            // Enviar los datos actualizados al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_edicion_proceeding.php",
                data: {
                    idProceedings: idProceedings,
                    title: title,
                    publisher: publisher,
                    volume: volume,
                    series: series,
                    organization: organization,
                    pub_month: pub_month,
                    pub_year: pub_year,
                    note: note
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar la ventana modal después de guardar
                    $("#modalInfoProceeding").modal("hide");

                    // Recargar la página o realizar otras acciones necesarias después de guardar
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }

        ///////////////////////////////////////////////////////////////////////////////
        function eliminarProceeding(idProceedings) {
            // Confirmar con el usuario antes de eliminar
            var confirmacion = confirm("¿Estás seguro de que quieres eliminar este procedimiento?");

            if (!confirmacion) {
                // El usuario canceló la eliminación
                return;
            }

            // Realizar una solicitud AJAX para eliminar el procedimiento con el ID dado
            $.ajax({
                type: "POST",
                url: "eliminar_proceeding.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de eliminación
                data: { idProceedings: idProceedings },
                dataType: "json",
                success: function (response) {
                    // Manejar la respuesta del servidor después de la eliminación
                    if (response.success) {
                        // Procedimiento eliminado con éxito
                        alert("Procedimiento eliminado correctamente.");
                        // Puedes recargar la página o actualizar la lista de procedimientos según tus necesidades
                        location.reload();
                    } else {
                        // Error al eliminar el procedimiento
                        alert("Error al eliminar procedimiento: " + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al eliminar procedimiento:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }

    </script>
</body>
</html>