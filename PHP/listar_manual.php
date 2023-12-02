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
$dbname = "trabajo_final_editorial";// Reemplaza 'tu_base_de_datos' con el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM manual";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Resto de las etiquetas head (meta, title, estilos) -->

    <title>Listar Manuales</title>

    <!-- Enlaces a estilos Bootstrap y estilos personalizados -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de incluir tu archivo de estilos personalizados -->

    <!-- Estilos específicos para la página -->
    <style>
        body {
            padding: 20px;
        }

        .container {
            margin-top: 20px;
            max-width: 1200px; /* Ajusta el valor según tus preferencias */
            margin-left: auto;
            margin-right: auto;
        }

        .btn {
            margin-right: 5px;
        }

        .table {
            margin-top: 20px;
        }

        #formularioManual {
            margin-top: 20px;
        }

        /* Estilo del botón de cierre del modal */
        .modal-header .close {
            color: #ffffff; /* Color blanco */
        }

        /* Estilo del botón Guardar en el formulario */
        #formularioManual button.btn-success {
            background-color: #28a745; /* Verde Bootstrap */
            border-color: #28a745;
        }

        #formularioManual button.btn-success:hover {
            background-color: #218838; /* Cambia el color al pasar el ratón por encima */
            border-color: #1e7e34;
        }

        /* Estilo del botón Cancelar en el formulario */
        #formularioManual button.btn-secondary {
            background-color: #6c757d; /* Gris Bootstrap */
            border-color: #6c757d;
        }

        #formularioManual button.btn-secondary:hover {
            background-color: #5a6268; /* Cambia el color al pasar el ratón por encima */
            border-color: #545b62;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h2 class="mt-5">Listado de Manuales</h2>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID del Manual</th>
                    <th>Título</th>
                    <th>Organización</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los resultados en la tabla
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Manual"] . "</td>";
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>" . $row["organization"] . "</td>";
                        echo "<td>
                                <button class='btn btn-info' onclick='verManual(" . $row["ID_Manual"] . ")'>Ver</button>
                                <button class='btn btn-warning' onclick='editarManual(" . $row["ID_Manual"] . ")'>Editar</button>
                                <button class='btn btn-danger' onclick='eliminarManual(" . $row["ID_Manual"] . ")'>Eliminar</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay manuales</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button id="agregarManualBtn" class="btn btn-primary mb-3" onclick="mostrarFormularioAgregarManual()">Agregar Manual</button>

        <!-- Modal para ver detalles del Manual -->
        <div class="modal fade" id="modalInfoManual" tabindex="-1" role="dialog" aria-labelledby="modalInfoManualLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInfoManualLabel">Detalles del Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Contenido de la modal -->
                        <div id="detalleManual">
                            <!-- Aquí se llenarán dinámicamente los detalles del Manual -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario para agregar o editar un Manual -->
        <div id="formularioManual" class="hidden mt-3" style="display: none;">
            <h3>Detalles del Manual</h3>
            <form id="formularioAgregarManual">

                <div class="form-group">
                    <label for="idManual">ID del manual:</label>
                    <select class="form-control" id="idManual">
                        <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="tituloManual">Título:</label>
                    <input type="text" id="tituloManual" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="idOrganization">Organización:</label>
                    <select class="form-control" id="idOrganization">
                        <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="edicionManual">Edición:</label>
                    <input type="text" id="edicionManual" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="mesPublicacion">Mes de Publicación:</label>
                    <input type="text" id="mesPublicacion" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="anoPublicacion">Año de Publicación:</label>
                    <input type="text" id="anoPublicacion" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="notaManual">Nota:</label>
                    <textarea id="notaManual" class="form-control"></textarea>
                </div>

                <button type="button" class="btn btn-success" onclick="guardarManual()">Guardar</button>
            </form>
        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Agrega cualquier otro enlace a scripts necesarios -->
    <script>
        function mostrarFormularioAgregarManual() {
            $("#formularioManual").toggle();
        }
        function guardarManual() {
            // Obtener los valores de los campos del formulario
            var idManual = $("#idManual").val();
            var tituloManual = $("#tituloManual").val();
            var idOrganization = $("#idOrganization").val();
            var edicionManual = $("#edicionManual").val();
            var mesPublicacion = $("#mesPublicacion").val();
            var anoPublicacion = $("#anoPublicacion").val();
            var notaManual = $("#notaManual").val();

            // Verificar que los campos requeridos no estén vacíos
            if (tituloManual === "" || edicionManual === "" || mesPublicacion === "" || anoPublicacion === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_manual.php", // Ajusta la ruta al archivo PHP que procesará la solicitud
                data: {
                    idManual: idManual,
                    title: tituloManual,
                    idOrganization: idOrganization,
                    edition: edicionManual,
                    pub_month: mesPublicacion,
                    pub_year: anoPublicacion,
                    note: notaManual
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar el formulario después de guardar
                    $("#formularioManual").hide();

                    // Recargar la página o realizar otras acciones necesarias después de guardar
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos al servidor:", error);
                }
            });
        }
        $.ajax({
            type: "GET",
            url: "script_combo.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#idManual").html(response);
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
                $("#idOrganization").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos:", error);
            }
        });
    </script>
    <script>
        function verManual(idManual) {
            // Realizar una solicitud AJAX para obtener los detalles del Manual con el ID dado
            $.ajax({
                type: "POST",
                url: "obtener_manual.php", // Ajusta la ruta al archivo PHP que procesará la solicitud
                data: {
                    idManual: idManual
                },
                dataType: "json", // Especifica el tipo de datos esperado como JSON
                success: function (detallesManual) {
                    // Verifica si los detalles del Manual son válidos antes de mostrar el modal
                    if (detallesManual && !detallesManual.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoManual").modal("show");

                        // Llenar el contenido de la ventana modal con los detalles del Manual
                        var contenidoDetalle = `
                            <div class="detalle-manual">
                                <div class="detalle-titulo">Detalles: </div>
                                <div class="detalle-item"><strong>ID del Manual:</strong> ${detallesManual.ID_Manual}</div>
                                <div class="detalle-item"><strong>Título:</strong> ${detallesManual.title}</div>
                                <div class="detalle-item"><strong>Organización:</strong> ${detallesManual.organization}</div>
                                <div class="detalle-item"><strong>Edición:</strong> ${detallesManual.edition}</div>
                                <div class="detalle-item"><strong>Mes de Publicación:</strong> ${detallesManual.pub_month}</div>
                                <div class="detalle-item"><strong>Año de Publicación:</strong> ${detallesManual.pub_year}</div>
                                <div class="detalle-item"><strong>Nota:</strong> ${detallesManual.note}</div>
                            </div>`;

                        // Agrega más campos según sea necesario

                        // Mostrar los detalles en la ventana modal
                        $("#detalleManual").html(contenidoDetalle);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del Manual:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoManual").modal("show");
                    $("#detalleManual").html(`<p>Error al obtener detalles del Manual. Inténtalo nuevamente.</p>`);
                }
            });
        }
        function editarManual(idManual) {
            // Realizar una solicitud AJAX para obtener los detalles del Manual con el ID dado
            $.ajax({
                type: "POST",
                url: "obtener_manual.php",
                data: {
                    idManual: idManual
                },
                dataType: "json",
                success: function (detallesManual) {
                    if (detallesManual && !detallesManual.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoManual").modal("show");

                        // Llenar el contenido de la ventana modal con el formulario de edición del Manual
                        var contenidoDetalle = `
                            <form id="formularioEditarManual">
                                <input type="hidden" id="idManualEditar" value="${detallesManual.ID_Manual}">

                                <div class="form-group">
                                    <label for="editTituloManual">Título:</label>
                                    <input type="text" class="form-control" id="editTituloManual" value="${detallesManual.title}">
                                </div>

                                <div class="form-group">
                                    <label for="editIdOrganization">Organización:</label>
                                    <select class="form-control" id="editIdOrganization"></select>
                                </div>

                                <div class="form-group">
                                    <label for="editEdicionManual">Edición:</label>
                                    <input type="text" class="form-control" id="editEdicionManual" value="${detallesManual.edition}">
                                </div>

                                <div class="form-group">
                                    <label for="editMesPublicacion">Mes de Publicación:</label>
                                    <input type="text" class="form-control" id="editMesPublicacion" value="${detallesManual.pub_month}">
                                </div>

                                <div class="form-group">
                                    <label for="editAnoPublicacion">Año de Publicación:</label>
                                    <input type="text" class="form-control" id="editAnoPublicacion" value="${detallesManual.pub_year}">
                                </div>

                                <div class="form-group">
                                    <label for="editNotaManual">Nota:</label>
                                    <textarea class="form-control" id="editNotaManual">${detallesManual.note}</textarea>
                                </div>

                                <!-- Agrega más campos según sea necesario -->

                                <button type="button" class="btn btn-primary" onclick="guardarEdicionManual()">Guardar</button>
                            </form>
                        `;

                        // Mostrar los detalles en la ventana modal
                        $("#detalleManual").html(contenidoDetalle);

                        // Cargar opciones del combo de organizaciones
                        cargarOpcionesOrganizaciones("#editIdOrganization", detallesManual.organization);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del Manual:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoManual").modal("show");
                    $("#detalleManual").html(`<p>Error al obtener detalles del Manual. Inténtalo nuevamente.</p>`);
                }
            });
        }

        function guardarEdicionManual() {
            // Obtener los valores actualizados del formulario de edición
            var idManual = $("#idManualEditar").val();
            var tituloManual = $("#editTituloManual").val();
            var idOrganization = $("#editIdOrganization").val();
            var edicionManual = $("#editEdicionManual").val();
            var mesPublicacion = $("#editMesPublicacion").val();
            var anoPublicacion = $("#editAnoPublicacion").val();
            var notaManual = $("#editNotaManual").val();

            // Verificar que los campos requeridos no estén vacíos
            if (tituloManual === "" || edicionManual === "" || mesPublicacion === "" || anoPublicacion === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            // Enviar los datos actualizados al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_edicion_manual.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de edición
                data: {
                    idManual: idManual,
                    title: tituloManual,
                    idOrganization: idOrganization,
                    edition: edicionManual,
                    pub_month: mesPublicacion,
                    pub_year: anoPublicacion,
                    note: notaManual
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar la ventana modal después de guardar
                    $("#modalInfoManual").modal("hide");

                    // Recargar la página o realizar otras acciones necesarias después de guardar
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }
        function cargarOpcionesOrganizaciones(selector,idInstitucionSeleccionada){
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
        $(document).ready(function () {
            // Cargar opciones del combo de instituciones al cargar la página
            cargarOpcionesOrganizaciones("#editIdOrganization", /* valor por defecto o null */);
        });
        function eliminarManual(idManual) {
            // Confirmar con el usuario antes de proceder con la eliminación
            var confirmacion = confirm("¿Estás seguro de que quieres eliminar este Manual?");

            if (confirmacion) {
                // Realizar una solicitud AJAX para eliminar el Manual con el ID dado
                $.ajax({
                    type: "POST",
                    url: "eliminar_manual.php",
                    data: {
                        idManual: idManual
                    },
                    success: function (response) {
                        // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        alert(response);

                        // Recargar la página o realizar otras acciones necesarias después de eliminar
                        location.reload();
                    },
                    error: function (error) {
                        // Manejar errores, si es necesario
                        console.error("Error al enviar la solicitud de eliminación:", error);
                    }
                });
            }
        }

    </script>
    <?php
    // Cierra la conexión a la base de datos después de usarla
    $conn->close();
    ?>
</body>
</html>
