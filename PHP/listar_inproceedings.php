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

$sql = "SELECT * FROM inproceedings";
$result = $conn->query($sql);

// Inicializar un array para almacenar los inproceedings
$inproceedings = [];

if ($result->num_rows > 0) {
    // Almacenar los inproceedings en un array asociativo
    while ($row = $result->fetch_assoc()) {
        $inproceeding = [
            "ID_InProceedings" => $row["ID_InProceedings"],
            "Proceedings_ID" => $row["Proceedings_ID"],
            "title" => $row["title"],
            "pages" => $row["pages"],
            "type" => $row["type"],
            "note" => $row["note"]
        ];

        // Agregar el inproceeding al array
        $inproceedings[] = $inproceeding;
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
    <title>Listar InProceedings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        #agregarInProceedingBtn {
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
        <h2 class="mb-4">Listado de InProceedings</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>ID de InProceedings</th>
                    <th>ID de Proceedings</th>
                    <th>Título</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los resultados en la tabla
                if (!empty($inproceedings)) {
                    foreach ($inproceedings as $inproceeding) {
                        echo "<tr>";
                        echo "<td>" . $inproceeding["ID_InProceedings"] . "</td>";
                        echo "<td>" . $inproceeding["Proceedings_ID"] . "</td>";
                        echo "<td>" . $inproceeding["title"] . "</td>";
                        echo "<td>
                                <button class='btn btn-info btn-sm' onclick='verInProceeding(" . $inproceeding["ID_InProceedings"] . ")'>Ver</button>
                                <button class='btn btn-warning btn-sm' onclick='editarInProceeding(" . $inproceeding["ID_InProceedings"] . ")'>Editar</button>
                                <button class='btn btn-danger btn-sm' onclick='eliminarInProceeding(" . $inproceeding["ID_InProceedings"] . ")'>Eliminar</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay InProceedings</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button id="agregarInProceedingBtn" class="btn btn-primary mb-3" onclick="mostrarFormularioAgregarInProceeding()">Agregar InProceedings</button>
    </div>

    <!-- Ejemplo de ventana modal para mostrar detalles del inproceeding -->
    <div class="modal fade" id="modalInfoInProceeding" tabindex="-1" role="dialog" aria-labelledby="modalInfoInProceedingLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoInProceedingLabel">Detalles del InProceeding</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalleInProceeding">
                    <!-- Contenido dinámico del modal -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ejemplo de formulario para agregar/editar inproceeding -->
    <!-- Formulario para agregar un nuevo inproceeding (inicialmente oculto) -->
    <div class="container mt-3" id="formularioAgregarInProceeding" style="display: none;">
        <h3>Agregar InProceedings</h3>
        <form>
            <!-- Campos específicos para inproceedings -->
            <div class="form-group">
                <label for="idInProceeding">ID de InProceedings:</label>
                <select class="form-control" id="idInProceeding">
                    <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                </select>
            </div>

            <div class="form-group">
                <label for="idProceeding">ID de Proceedings:</label>
                <select class="form-control" id="idProceeding">
                    <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                </select>
            </div>

            <div class="form-group">
                <label for="titleInProceeding">Título:</label>
                <input type="text" class="form-control" id="titleInProceeding" />
            </div>

            <div class="form-group">
                <label for="pagesInProceeding">Páginas:</label>
                <input type="text" class="form-control" id="pagesInProceeding" />
            </div>

            <div class="form-group">
                <label for="typeInProceeding">Tipo:</label>
                <input type="text" class="form-control" id="typeInProceeding" />
            </div>

            <div class="form-group">
                <label for="noteInProceeding">Nota:</label>
                <input type="text" class="form-control" id="noteInProceeding" />
            </div>

            <button type="button" class="btn btn-primary" onclick="agregarInProceeding()">Guardar</button>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Agrega cualquier otro enlace a scripts necesarios -->
    <script>
        function mostrarFormularioAgregarInProceeding() {
            $("#formularioAgregarInProceeding").toggle();
        }
        function agregarInProceeding() {
            // Obtener los valores del formulario
            var idInProceeding = $("#idInProceeding").val();
            var idProceeding = $("#idProceeding").val();
            var titleInProceeding = $("#titleInProceeding").val();
            var pagesInProceeding = $("#pagesInProceeding").val();
            var typeInProceeding = $("#typeInProceeding").val();
            var noteInProceeding = $("#noteInProceeding").val();

            // Realizar una solicitud AJAX para enviar los datos al servidor
            $.ajax({
                type: "POST",
                url: "agregar_inproceeding.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de agregar inproceeding
                data: {
                    idInProceeding: idInProceeding,
                    idProceeding: idProceeding,
                    titleInProceeding: titleInProceeding,
                    pagesInProceeding: pagesInProceeding,
                    typeInProceeding: typeInProceeding,
                    noteInProceeding: noteInProceeding
                },
                dataType: "json",
                success: function (response) {
                    // Aquí puedes manejar la respuesta del servidor
                    console.log(response);

                    // Cerrar el formulario o realizar otras acciones según sea necesario
                    $("#formularioAgregarInProceeding").hide();
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error("Error al agregar inproceeding:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }
        $.ajax({
            type: "GET",
            url: "script_combo.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#idInProceeding").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos:", error);
            }
        });
        $.ajax({
            type: "GET", // Cambia a "POST" si es necesario
            url: "script_combo_proceedings.php", // Ajusta la ruta a tu script PHP que obtiene las opciones de proceedings
            dataType: "html", // Cambia a "json" si tu script PHP devuelve JSON
            success: function (response) {
                // Rellenar el select con las opciones obtenidas
                $("#idProceeding").html(response);
            },
            error: function (xhr, status, error) {
                console.error("Error al obtener opciones de proceedings:", error);
                // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
            }
        });
    </script>
    <script>
        // Puedes agregar funciones JavaScript específicas para esta página si es necesario

        function verInProceeding(idInProceedings) {
            // Realizar una solicitud AJAX para obtener detalles del InProceeding
            $.ajax({
                type: "GET",
                url: "obtener_inproceeding.php", // Ajusta la ruta al archivo PHP que obtendrá los detalles del InProceeding
                data: { idInProceedings: idInProceedings },
                dataType: "json",
                success: function (response) {
                    // Verifica si los detalles del InProceeding son válidos antes de mostrar el modal
                    if (response && !response.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoInProceeding").modal("show");

                        // Llenar el contenido de la ventana modal con los detalles del InProceeding
                        $("#detalleInProceeding").html(`
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>ID de InProceedings: ${response.ID_InProceedings}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>ID de Proceedings: ${response.Proceedings_ID}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Título: ${response.title}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Páginas: ${response.pages}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Tipo: ${response.type}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Nota: ${response.note}</h4>
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
                    console.error("Error al obtener detalles del InProceeding:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }


        function editarInProceeding(idInProceedings) {
            // Realizar una solicitud AJAX para obtener los detalles del InProceeding con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_inproceeding.php",
                data: { idInProceedings: idInProceedings },
                dataType: "json",
                success: function (detallesInProceeding) {
                    if (detallesInProceeding && !detallesInProceeding.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoInProceeding").modal("show");

                        // Llenar el contenido de la ventana modal con el formulario de edición del InProceeding
                        var contenidoDetalle = `
                            <form id="formularioEditarInProceeding">
                                <input type="hidden" id="editIdInProceedings" value="${detallesInProceeding.ID_InProceedings}">

                                <div class="form-group">
                                    <label for="editIdProceeding">ID de Proceedings:</label>
                                    <select class="form-control" id="editIdProceeding"></select>
                                </div>

                                <div class="form-group">
                                    <label for="editTitleInProceeding">Título:</label>
                                    <input type="text" class="form-control" id="editTitleInProceeding" value="${detallesInProceeding.title}">
                                </div>

                                <div class="form-group">
                                    <label for="editPagesInProceeding">Páginas:</label>
                                    <input type="text" class="form-control" id="editPagesInProceeding" value="${detallesInProceeding.pages}">
                                </div>

                                <div class="form-group">
                                    <label for="editTypeInProceeding">Tipo:</label>
                                    <input type="text" class="form-control" id="editTypeInProceeding" value="${detallesInProceeding.type}">
                                </div>

                                <div class="form-group">
                                    <label for="editNoteInProceeding">Nota:</label>
                                    <textarea class="form-control" id="editNoteInProceeding">${detallesInProceeding.note}</textarea>
                                </div>

                                <!-- Agrega más campos según sea necesario -->

                                <button type="button" class="btn btn-primary" onclick="guardarEdicionInProceeding()">Guardar</button>
                            </form>
                        `;

                        // Mostrar los detalles en la ventana modal
                        $("#detalleInProceeding").html(contenidoDetalle);
                        cargarOpcionesProceedings("#editIdProceeding", detallesInProceeding.Proceedings_ID);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del InProceeding:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoInProceeding").modal("show");
                    $("#detalleInProceeding").html(`<p>Error al obtener detalles del InProceeding. Inténtalo nuevamente.</p>`);
                }
            });
        }
        function cargarOpcionesProceedings(selectId, selectedValue) {
            // Realizar una solicitud AJAX para obtener las opciones de Proceedings
            $.ajax({
                type: "GET",
                url: "script_combo_proceedings.php", // Ajusta la ruta al archivo PHP que procesará la solicitud para obtener las opciones
                dataType: "html", // Puedes cambiar a "json" si devuelves JSON desde el servidor
                success: function (options) {
                    // Llenar el select con las opciones obtenidas
                    $(selectId).html(options);

                    // Si se proporciona un valor seleccionado, establecerlo
                    if (selectedValue !== null) {
                        $(selectId).val(selectedValue);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al cargar opciones de Proceedings:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }
        function guardarEdicionInProceeding() {
            // Obtener los valores del formulario de edición
            var idInProceedings = $("#editIdInProceedings").val();
            var idProceeding = $("#editIdProceeding").val();
            var title = $("#editTitleInProceeding").val();
            var pages = $("#editPagesInProceeding").val();
            var type = $("#editTypeInProceeding").val();
            var note = $("#editNoteInProceeding").val();

            // Realizar una solicitud AJAX para enviar los datos al servidor
            $.ajax({
                type: "POST",
                url: "guardar_edicion_inproceeding.php", // Ajusta la ruta al archivo PHP que procesará la solicitud para editar InProceeding
                data: {
                    idInProceedings: idInProceedings,
                    idProceeding: idProceeding,
                    title: title,
                    pages: pages,
                    type: type,
                    note: note
                },
                dataType: "json",
                success: function (response) {
                    // Manejar la respuesta del servidor según sea necesario
                    console.log(response);

                    // Cerrar la ventana modal o realizar otras acciones según sea necesario
                    $("#modalInfoInProceeding").modal("hide");
                    location.reload(); // Recargar la página o realizar otras acciones de actualización
                },
                error: function (xhr, status, error) {
                    console.error("Error al guardar la edición del InProceeding:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }


        function eliminarInProceeding(idInProceedings) {
            // Confirmar con el usuario antes de realizar la eliminación
            if (confirm("¿Estás seguro de que quieres eliminar este InProceeding?")) {
                // Realizar una solicitud AJAX para eliminar el InProceeding con el ID dado
                $.ajax({
                    type: "POST",
                    url: "eliminar_inproceeding.php", // Ajusta la ruta al archivo PHP que procesará la solicitud para eliminar
                    data: { idInProceedings: idInProceedings },
                    dataType: "json",
                    success: function (response) {
                        // Manejar la respuesta del servidor
                        console.log(response);

                        if (response.success) {
                            // Recargar la página o actualizar la tabla después de la eliminación
                            location.reload();
                        } else {
                            // Mostrar un mensaje de error al usuario si la eliminación falla
                            alert("Error al eliminar InProceeding: " + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al eliminar InProceeding:", error);
                        // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    }
                });
            }
        }

    </script>
</body>
</html>
