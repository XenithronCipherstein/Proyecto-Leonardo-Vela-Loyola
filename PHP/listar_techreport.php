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

$sql = "SELECT * FROM techreport";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Informes Técnicos</title>
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
        #agregarTechreportBtn {
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
        <h2 class="mb-4">Listado de Informes Técnicos</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>ID del Informe Técnico</th>
                    <th>Título</th>
                    <th>ID de la Institución</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los resultados en la tabla
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Techreport"] . "</td>";
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>" . $row["ID_Institution"] . "</td>";
                        echo "<td>
                                <button class='btn btn-info btn-sm' onclick='verTechreport(" . $row["ID_Techreport"] . ")'>Ver</button>
                                <button class='btn btn-warning btn-sm' onclick='editarTechreport(" . $row["ID_Techreport"] . ")'>Editar</button>
                                <button class='btn btn-danger btn-sm' onclick='eliminarTechreport(" . $row["ID_Techreport"] . ")'>Eliminar</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay informes técnicos</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button id="agregarTechreportBtn" class="btn btn-primary mb-3" onclick="mostrarFormularioAgregarTechreport()">Agregar Informe Técnico</button>
    </div>

    <!-- Ejemplo de ventana modal para mostrar detalles del informe técnico -->
    <div class="modal fade" id="modalInfoTechreport" tabindex="-1" role="dialog" aria-labelledby="modalInfoTechreportLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoTechreportLabel">Detalles del Informe Técnico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenido de la modal -->
                    <div id="detalleTechreport">
                        <!-- Aquí se llenarán dinámicamente los detalles del informe técnico -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario para agregar un nuevo informe técnico (inicialmente oculto) -->
    <div class="container mt-3" id="formularioAgregarTechreport" style="display: none;">
        <h3>Agregar Informe Técnico</h3>
        <form>
            <!-- Campos específicos para informes técnicos -->
            <div class="form-group">
                <label for="idTechreport">ID de Informes tecnicos:</label>
                <select class="form-control" id="idTechreport">
                    <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                </select>
            </div>

            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" class="form-control" id="title" />
            </div>

            <div class="form-group">
                <label for="ID_Institution">ID de la Institución:</label>
                <select class="form-control" id="ID_Institution">
                    <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                </select>
            </div>

            <div class="form-group">
                <label for="type">Tipo:</label>
                <input type="text" class="form-control" id="type" />
            </div>

            <div class="form-group">
                <label for="number">Número:</label>
                <input type="text" class="form-control" id="number" />
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

            <button type="button" class="btn btn-primary" onclick="agregarTechreport()">Guardar</button>
        </form>
    </div>

    <!-- Agrega cualquier otro campo necesario según tu estructura de base de datos -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function mostrarFormularioAgregarTechreport() {
            $("#formularioAgregarTechreport").toggle();
        }

        // Función para agregar un nuevo informe técnico
        function agregarTechreport() {
            // Obtener los valores del formulario
            var ID_Techreport = $("#idTechreport").val(); // Asegúrate de tener el campo en tu formulario
            var title = $("#title").val();
            var ID_Institution = $("#ID_Institution").val();
            var type = $("#type").val();
            var number = $("#number").val();
            var pub_month = $("#pub_month").val();
            var pub_year = $("#pub_year").val();
            var note = $("#note").val();

            // Realizar una solicitud AJAX para enviar los datos al servidor
            $.ajax({
                type: "POST",
                url: "agregar_techreport.php", // Asegúrate de tener un archivo PHP para procesar la solicitud
                data: {
                    ID_Techreport: ID_Techreport,
                    title: title,
                    ID_Institution: ID_Institution,
                    type: type,
                    number: number,
                    pub_month: pub_month,
                    pub_year: pub_year,
                    note: note
                },
                dataType: "json",
                success: function (response) {
                    // Aquí puedes manejar la respuesta del servidor
                    console.log(response);

                    // Cerrar el formulario o realizar otras acciones según sea necesario
                    $("#formularioAgregarTechreport").hide();
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error("Error al agregar informe técnico:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }

        // Lugar en tu script donde realizas la solicitud AJAX para obtener las publicaciones
        $.ajax({
            type: "GET",
            url: "script_combo.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#idTechreport").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos de publicaciones:", error);
            }
        });

        // Lugar en tu script donde realizas la solicitud AJAX para obtener las instituciones
        $.ajax({
            type: "GET",
            url: "script_combo_institution.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#ID_Institution").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos de instituciones:", error);
            }
        });
    </script>
    <script>
        function verTechreport(id) {
            // Realizar una solicitud AJAX para obtener los detalles del informe técnico con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_techreport.php",
                data: { id: id },
                dataType: "json", // Especifica el tipo de datos esperado como JSON
                success: function (detallesTechreport) {
                    // Verifica si los detalles del informe técnico son válidos antes de mostrar el modal
                    if (detallesTechreport && !detallesTechreport.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoTechreport").modal("show");

                        // Llenar el contenido de la ventana modal con los detalles del informe técnico
                        $("#detalleTechreport").html(`
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>ID: ${detallesTechreport.ID_Techreport}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Título: ${detallesTechreport.title}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>ID de la Institución: ${detallesTechreport.ID_Institution}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Tipo: ${detallesTechreport.type}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Número: ${detallesTechreport.number}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Mes de Publicación: ${detallesTechreport.pub_month}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Año de Publicación: ${detallesTechreport.pub_year}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Nota: ${detallesTechreport.note}</h4>
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
                    console.error("Error al obtener detalles del informe técnico:", error);
                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                }
            });
        }
///////////////////////////////////////////////////////////////////////////////
        function editarTechreport(idTechreport) {
            // Realizar una solicitud AJAX para obtener los detalles del Techreport con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_techreport.php",
                data: {
                    id: idTechreport  // Cambiado de idTechreport a id
                },
                dataType: "json",
                success: function (detallesTechreport) {
                    if (detallesTechreport && !detallesTechreport.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoTechreport").modal("show");

                        // Llenar el contenido de la ventana modal con el formulario de edición del Techreport
                        var contenidoDetalle = `
                            <form id="formularioEditarTechreport">
                                <input type="hidden" id="idTechreportEditar" value="${detallesTechreport.ID_Techreport}">

                                <div class="form-group">
                                    <label for="editTitle">Título:</label>
                                    <input type="text" class="form-control" id="editTitle" value="${detallesTechreport.title}">
                                </div>

                                <div class="form-group">
                                    <label for="editIdInstitution">ID de Institución:</label>
                                    <select class="form-control" name="ID_Institution" id="editIdInstitution"></select>
                                </div>

                                <div class="form-group">
                                    <label for="editType">Tipo:</label>
                                    <input type="text" class="form-control" id="editType" value="${detallesTechreport.type}">
                                </div>

                                <div class="form-group">
                                    <label for="editNumber">Número:</label>
                                    <input type="text" class="form-control" id="editNumber" value="${detallesTechreport.number}">
                                </div>

                                <div class="form-group">
                                    <label for="editPubMonth">Mes de Publicación:</label>
                                    <input type="text" class="form-control" id="editPubMonth" value="${detallesTechreport.pub_month}">
                                </div>

                                <div class="form-group">
                                    <label for="editPubYear">Año de Publicación:</label>
                                    <input type="number" class="form-control" id="editPubYear" value="${detallesTechreport.pub_year}">
                                </div>

                                <div class="form-group">
                                    <label for="editNote">Nota:</label>
                                    <textarea class="form-control" id="editNote">${detallesTechreport.note}</textarea>
                                </div>

                                <!-- Agrega más campos según sea necesario -->

                                <button type="button" class="btn btn-primary" onclick="guardarEdicionTechreport()">Guardar</button>
                            </form>
                        `;

                        // Mostrar los detalles en la ventana modal
                        $("#detalleTechreport").html(contenidoDetalle);

                        // Cargar opciones del combo de instituciones
                        cargarOpcionesInstituciones("#editIdInstitution", detallesTechreport.ID_Institution);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del Techreport:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoTechreport").modal("show");
                    $("#detalleTechreport").html(`<p>Error al obtener detalles del Techreport. Inténtalo nuevamente.</p>`);
                }
            });
        }

        function guardarEdicionTechreport() {
            // Obtener los valores actualizados del formulario de edición
            var idTechreport = $("#idTechreportEditar").val();
            var ID_Institution = $("#editIdInstitution").val();
            var title = $("#editTitle").val();
            var type = $("#editType").val();
            var number = $("#editNumber").val();
            var pub_month = $("#editPubMonth").val();
            var pub_year = $("#editPubYear").val();
            var note = $("#editNote").val();

            // Verificar que los campos requeridos no estén vacíos
            if (ID_Institution === "" || title === "" || type === ""|| number === ""|| pub_month === ""|| pub_year === ""|| note === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            // Enviar los datos actualizados al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_edicion_techreport.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de edición
                data: {
                    idTechreport: idTechreport,
                    title: title,
                    ID_Institution: ID_Institution,
                    type: type,
                    number: number,
                    pub_month: pub_month,
                    pub_year: pub_year,
                    note: note
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar la ventana modal después de guardar
                    $("#modalInfoTechreport").modal("hide");

                    // Recargar la página o realizar otras acciones necesarias después de guardar
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }
        // Función para cargar opciones del combo de libros
        function cargarOpcionesInstituciones(selector, selectortechreport) {
            $.ajax({
                type: "GET",
                url: "script_combo_institution.php",
                success: function (response) {
                    $(selector).html(response);
                    
                    // Seleccionar el libro correspondiente
                    $(selector).val(selectortechreport);
                },
                error: function (error) {
                    console.error("Error al obtener datos de las instituciones:", error);
                }
            });
        }

        // Esperar a que el DOM esté listo
        $(document).ready(function () {
            // Cargar opciones del combo de libros al cargar la página
                cargarOpcionesInstituciones("#editIdInstitution", /* valor por defecto o null */);
        });
        ///////////////////////////////////////////////////////////////////////////////////
        function eliminarTechreport(idTechreport) {
            // Confirmar con el usuario antes de realizar la eliminación
            var confirmacion = confirm("¿Estás seguro de que quieres eliminar este informe técnico?");

            if (confirmacion) {
                // Realizar una solicitud AJAX para eliminar el informe técnico con el ID dado
                $.ajax({
                    type: "POST",
                    url: "eliminar_techreport.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de eliminación
                    data: {
                        idTechreport: idTechreport
                    },
                    success: function (response) {
                        // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        alert(response);

                        // Recargar la página o realizar otras acciones necesarias después de eliminar
                        location.reload();
                    },
                    error: function (error) {
                        // Manejar errores, si es necesario
                        console.error("Error al enviar solicitud de eliminación al servidor:", error);
                    }
                });
            }
        }

    </script>
</body>
</html>
