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

$sql = "SELECT * FROM thesis";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Tesis</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Agrega los enlaces a tus archivos CSS y Bootstrap aquí -->
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

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle; /* Alineación vertical centrada */
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
        #agregarThesisBtn {
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

    <div class="container">
        <h2 class="mt-5">Listado de Tesis</h2>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID de la Tesis</th>
                    <th>Título</th>
                    <th>ID de la Escuela</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los resultados en la tabla
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Thesis"] . "</td>";
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>" . $row["school"] . "</td>";
                        echo "<td>
                                <button class='btn btn-info' onclick='verTesis(" . $row["ID_Thesis"] . ")'>Ver</button>
                                <button class='btn btn-warning' onclick='editarTesis(" . $row["ID_Thesis"] . ")'>Editar</button>
                                <button class='btn btn-danger' onclick='eliminarTesis(" . $row["ID_Thesis"] . ")'>Eliminar</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay tesis</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button id="agregarProceedingBtn" class="btn btn-primary mb-3" onclick="mostrarFormularioAgregarThesis()">Agregar Thesis</button>

        <!-- Ejemplo de ventana modal para mostrar detalles de la tesis -->
        <div class="modal fade" id="modalInfoThesis" tabindex="-1" role="dialog" aria-labelledby="modalInfoThesisLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInfoThesisLabel">Detalles de la Tesis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="detalleThesis">
                        <!-- Contenido dinámico del modal -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ejemplo de formulario para agregar/editar tesis -->
        <!-- Formulario para agregar una nueva tesis (inicialmente oculto) -->
        <div class="container mt-3" id="formularioAgregarThesis" style="display: none;">
            <h3>Agregar Tesis</h3>
            <form>
                <!-- Campos específicos para tesis -->
                <div class="form-group">
                    <label for="idThesis">ID de Tesis:</label>
                    <select class="form-control" id="idThesis">
                        <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="titleThesis">Título:</label>
                    <input type="text" class="form-control" id="titleThesis" />
                </div>

                <div class="form-group">
                    <label for="schoolThesis">Escuela:</label>
                    <select class="form-control" id="schoolThesis">
                        <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="typeThesis">Tipo:</label>
                    <input type="text" class="form-control" id="typeThesis" />
                </div>

                <div class="form-group">
                    <label for="pubMonthThesis">Mes de Publicación:</label>
                    <input type="text" class="form-control" id="pubMonthThesis" />
                </div>

                <div class="form-group">
                    <label for="pubYearThesis">Año de Publicación:</label>
                    <input type="number" class="form-control" id="pubYearThesis" />
                </div>

                <div class="form-group">
                    <label for="noteThesis">Nota:</label>
                    <input type="text" class="form-control" id="noteThesis" />
                </div>

                <button type="button" class="btn btn-primary" onclick="agregarThesis()">Guardar</button>
            </form>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Agrega cualquier otro enlace a scripts necesarios -->
    <script>
        function mostrarFormularioAgregarThesis() {
            $("#formularioAgregarThesis").toggle();
        }
        function agregarThesis() {
            // Obtener los valores del formulario
            var ID_Thesis = $("#idThesis").val();
            var title = $("#titleThesis").val();
            var school = $("#schoolThesis").val();
            var type = $("#typeThesis").val();
            var pubMonth = $("#pubMonthThesis").val();
            var pubYear = $("#pubYearThesis").val();
            var note = $("#noteThesis").val();

            // Realizar una solicitud AJAX para enviar los datos al servidor
            $.ajax({
                type: "POST",
                url: "agregar_thesis.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de agregar tesis
                data: {
                    ID_Thesis: ID_Thesis,
                    title: title,
                    school: school,
                    type: type,
                    pubMonth: pubMonth,
                    pubYear: pubYear,
                    note: note
                },
                dataType: "json",
                success: function (response) {
                    // Aquí puedes manejar la respuesta del servidor
                    console.log(response);

                    // Cerrar el formulario o realizar otras acciones según sea necesario
                    $("#formularioAgregarThesis").hide();
                    location.reload(); // Esto recargará la página, ajusta según tus necesidades
                },
                error: function (xhr, status, error) {
                    console.error("Error al agregar tesis:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }
        $.ajax({
            type: "GET",
            url: "script_combo.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#idThesis").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos:", error);
            }
        });
        $.ajax({
            type: "GET",
            url: "script_combo_institution.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#schoolThesis").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos:", error);
            }
        });
    </script>
    <script>
        // Funciones JavaScript para ver, editar y eliminar Tesis
        function verTesis(idTesis) {
            // Realizar una solicitud AJAX para obtener detalles de la tesis
            $.ajax({
                type: "GET",
                url: "obtener_tesis.php", // Ajusta la ruta al archivo PHP que procesará la solicitud para obtener detalles
                data: { idTesis: idTesis },
                dataType: "json",
                success: function (detallesTesis) {
                    // Verifica si los detalles de la tesis son válidos antes de mostrar el modal
                    if (detallesTesis && !detallesTesis.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoThesis").modal("show");

                        // Llenar el contenido de la ventana modal con los detalles de la tesis
                        $("#detalleThesis").html(`
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>ID: ${detallesTesis.ID_Thesis}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Título: ${detallesTesis.title}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Escuela: ${detallesTesis.school}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Tipo: ${detallesTesis.type}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Mes de Publicación: ${detallesTesis.pub_month}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Año de Publicación: ${detallesTesis.pub_year}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Nota: ${detallesTesis.note}</h4>
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
                    console.error("Error al obtener detalles de la tesis:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }

        function editarTesis(idTesis) {
            // Realizar una solicitud AJAX para obtener los detalles de la tesis con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_tesis.php",
                data: { idTesis: idTesis },
                dataType: "json",
                success: function (detallesTesis) {
                    if (detallesTesis && !detallesTesis.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoThesis").modal("show");

                        // Llenar el contenido de la ventana modal con el formulario de edición de la Tesis
                        var contenidoDetalle = `
                            <form id="formularioEditarTesis">
                                <input type="hidden" id="editIdTesis" value="${detallesTesis.ID_Thesis}">

                                <div class="form-group">
                                    <label for="editTituloTesis">Título:</label>
                                    <input type="text" class="form-control" id="editTituloTesis" value="${detallesTesis.title}">
                                </div>

                                <div class="form-group">
                                    <label for="editIdEscuela">Escuela:</label>
                                    <select class="form-control" name="ID_School" id="editIdEscuela"></select>
                                </div>

                                <div class="form-group">
                                    <label for="editTipoTesis">Tipo:</label>
                                    <input type="text" class="form-control" id="editTipoTesis" value="${detallesTesis.type}">
                                </div>

                                <div class="form-group">
                                    <label for="editMesPublicacionTesis">Mes de Publicación:</label>
                                    <input type="text" class="form-control" id="editMesPublicacionTesis" value="${detallesTesis.pub_month}">
                                </div>

                                <div class="form-group">
                                    <label for="editAnoPublicacionTesis">Año de Publicación:</label>
                                    <input type="number" class="form-control" id="editAnoPublicacionTesis" value="${detallesTesis.pub_year}">
                                </div>

                                <div class="form-group">
                                    <label for="editNotaTesis">Nota:</label>
                                    <textarea class="form-control" id="editNotaTesis">${detallesTesis.note}</textarea>
                                </div>

                                <!-- Agrega más campos según sea necesario -->

                                <button type="button" class="btn btn-primary" onclick="guardarEdicionTesis()">Guardar</button>
                            </form>
                        `;

                        // Mostrar los detalles en la ventana modal
                        $("#detalleThesis").html(contenidoDetalle);
                        cargarOpcionesEscuela("#editIdEscuela", detallesTesis.school);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles de la Tesis:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoTesis").modal("show");
                    $("#detalleThesis").html(`<p>Error al obtener detalles de la Tesis. Inténtalo nuevamente.</p>`);
                }
            });
        }

        function cargarOpcionesEscuela(selector, idInstitucionSeleccionada) {
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
            cargarOpcionesEscuela("#editIdEscuela", /* valor por defecto o null */);
        });
        function guardarEdicionTesis() {
            // Obtener los valores editados del formulario
            var idTesis = $("#editIdTesis").val();
            var nuevoTitulo = $("#editTituloTesis").val();
            var nuevaEscuela = $("#editIdEscuela").val();
            var nuevoTipo = $("#editTipoTesis").val();
            var nuevoMesPublicacion = $("#editMesPublicacionTesis").val();
            var nuevoAnoPublicacion = $("#editAnoPublicacionTesis").val();
            var nuevaNota = $("#editNotaTesis").val();

            // Realizar una solicitud AJAX para enviar los datos al servidor
            $.ajax({
                type: "POST",
                url: "guardar_edicion_tesis.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de guardar edición
                data: {
                    idTesis: idTesis,
                    nuevoTitulo: nuevoTitulo,
                    nuevaEscuela: nuevaEscuela,
                    nuevoTipo: nuevoTipo,
                    nuevoMesPublicacion: nuevoMesPublicacion,
                    nuevoAnoPublicacion: nuevoAnoPublicacion,
                    nuevaNota: nuevaNota
                    // Agrega más campos según sea necesario
                },
                dataType: "json",
                success: function (response) {
                    // Aquí puedes manejar la respuesta del servidor
                    console.log(response);

                    // Cerrar el modal o realizar otras acciones según sea necesario
                    $("#modalInfoTesis").modal("hide");
                    location.reload(); // Recargar la página o actualizar la lista de tesis
                },
                error: function (xhr, status, error) {
                    console.error("Error al guardar la edición de la tesis:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }

        function eliminarTesis(idTesis) {
            // Confirmar con el usuario antes de realizar la eliminación
            if (confirm("¿Estás seguro de que deseas eliminar esta tesis?")) {
                // Realizar una solicitud AJAX para eliminar la tesis con el ID dado
                $.ajax({
                    type: "POST",
                    url: "eliminar_tesis.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de eliminación
                    data: { idTesis: idTesis },
                    dataType: "json",
                    success: function (response) {
                        // Manejar la respuesta del servidor
                        console.log(response);

                        // Recargar la página o realizar otras acciones según sea necesario
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al eliminar la tesis:", error);
                        // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    }
                });
            }
        }

    </script>
</body>
</html>

<?php
// Cierra la conexión a la base de datos después de usarla
$conn->close();
?>
