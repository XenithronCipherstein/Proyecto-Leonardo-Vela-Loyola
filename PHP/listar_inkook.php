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

$sql = "SELECT * FROM inbook";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Inbook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de incluir tu archivo de estilos personalizados -->
    <style>
        /* Agrega tus estilos personalizados aquí */

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

        #formularioInbook {
            margin-top: 20px;
        }

        /* Personalización del formulario */
        #formularioInbook form {
            max-width: 600px;
            margin: auto;
        }

        #formularioInbook h3 {
            margin-bottom: 20px;
        }

        #formularioInbook .form-group {
            margin-bottom: 20px;
        }

        /* Alineación de botones en el formulario */
        #formularioInbook button {
            margin-right: 10px;
        }

        /* Estilos del modal */
        .modal-body {
            max-height: 500px;
            overflow-y: auto;
        }

        /* Estilos del botón Agregar Inbook */
        #agregarInbookBtn {
            margin-top: 20px;
        }

        /* Estilo del menú de navegación */
        .navbar {
            background-color: #007bff; /* Color azul Bootstrap */
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: #ffffff; /* Color blanco para el texto del menú */
        }

        .navbar-brand:hover,
        .navbar-nav .nav-link:hover {
            color: #f8f9fa; /* Cambia el color del texto al pasar el ratón por encima */
        }

        /* Estilo del botón de cierre del modal */
        .modal-header .close {
            color: #ffffff; /* Color blanco */
        }

        /* Estilo del botón Guardar en el formulario */
        #formularioInbook button.btn-success {
            background-color: #28a745; /* Verde Bootstrap */
            border-color: #28a745;
        }

        #formularioInbook button.btn-success:hover {
            background-color: #218838; /* Cambia el color al pasar el ratón por encima */
            border-color: #1e7e34;
        }

        /* Estilo del botón Cancelar en el formulario */
        #formularioInbook button.btn-secondary {
            background-color: #6c757d; /* Gris Bootstrap */
            border-color: #6c757d;
        }

        #formularioInbook button.btn-secondary:hover {
            background-color: #5a6268; /* Cambia el color al pasar el ratón por encima */
            border-color: #545b62;
        }
        .detalle-inbook {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
        }

        .detalle-titulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .detalle-item {
            margin-bottom: 5px;
        }

    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h2 class="mt-5">Listado de Inbook</h2>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID del Inbook</th>
                    <th>ID del Libro</th>
                    <th>Título</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los resultados en la tabla
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Inbook"] . "</td>";
                        echo "<td>" . $row["ID_book"] . "</td>";
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>
                                <button class='btn btn-info' onclick='verInbook(" . $row["ID_Inbook"] . ")'>Ver</button>
                                <button class='btn btn-warning' onclick='editarInbook(" . $row["ID_Inbook"] . ")'>Editar</button>
                                <button class='btn btn-danger' onclick='eliminarInbook(" . $row["ID_Inbook"] . ")'>Eliminar</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay inbooks</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <button id="agregarInbookBtn" class="btn btn-primary" onclick="mostrarFormularioAgregarInbook()">Agregar Inbook</button>

        <!-- Modal para ver detalles del Inbook -->
        <div class="modal fade" id="modalInfoInbook" tabindex="-1" role="dialog" aria-labelledby="modalInfoInbookLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInfoInbookLabel">Detalles del Inbook</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Contenido de la modal -->
                        <div id="detalleInbook">
                            <!-- Aquí se llenarán dinámicamente los detalles del Inbook -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario para agregar o editar un Inbook -->
        <div id="formularioInbook" class="hidden mt-3"style="display: none;">
            <h3>Detalles del Inbook</h3>
            <form id="formularioAgregarInbook">
                <div class="form-group">
                    <label for="idInBook">ID del Libro:</label>
                    <select id="idInBook" class="form-control">
                        <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="idBook">ID del Libro:</label>
                    <select id="idBook" class="form-control">
                        <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="tituloInbook">Título:</label>
                    <input type="text" id="tituloInbook" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="chapter">Capítulo:</label>
                    <input type="text" id="chapter" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="pages">Páginas:</label>
                    <input type="text" id="pages" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="type">Tipo:</label>
                    <input type="text" id="type" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="nota">Nota:</label>
                    <input type="text" id="nota" class="form-control" />
                </div>

                <button type="button" class="btn btn-success" onclick="guardarInbook()">Guardar</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function mostrarFormularioAgregarInbook() {
            $("#formularioInbook").toggle();
        }

        function guardarInbook() {
            // Obtener los valores de los campos del formulario
            var idInBook = $("#idInBook").val();
            var idBook = $("#idBook").val();
            var tituloInbook = $("#tituloInbook").val();
            var chapter = $("#chapter").val();
            var pages = $("#pages").val();
            var type = $("#type").val();
            var nota = $("#nota").val();

            // Verificar que los campos requeridos no estén vacíos
            if (idBook === "" || tituloInbook === "" || chapter === "" || pages === "" || type === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_inbook.php", // Ajusta la ruta al archivo PHP que procesará la solicitud
                data: {
                    idInBook: idInBook,
                    idBook: idBook,
                    title: tituloInbook,
                    chapter: chapter,
                    pages: pages,
                    type: type,
                    note: nota
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Recargar la página o realizar otras acciones necesarias después de guardar
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos al servidor:", error);
                }
            });
        }

        // Carga dinámica de opciones para los combos
        $.ajax({
            type: "GET",
            url: "script_combo.php",
            success: function (response) {
                $("#idInBook").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos de publicaciones:", error);
            }
        });

        $.ajax({
            type: "GET",
            url: "script_combo_book.php",
            success: function (response) {
                $("#idBook").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos de publicaciones:", error);
            }
        });
    </script>

    <!-- Funciones JavaScript para ver, editar y eliminar Inbooks -->
    <script>
        function verInbook(idInbook) {
            // Realizar una solicitud AJAX para obtener los detalles del Inbook con el ID dado
            $.ajax({
                type: "POST",
                url: "obtener_inbook.php",
                data: {
                    idInbook: idInbook
                },
                dataType: "json", // Especifica el tipo de datos esperado como JSON
                success: function (detallesInbook) {
                    // Verifica si los detalles del Inbook son válidos antes de mostrar el modal
                    if (detallesInbook && !detallesInbook.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoInbook").modal("show");

                        // Llenar el contenido de la ventana modal con los detalles del Inbook
                        var contenidoDetalle = `
                            <div class="detalle-inbook">
                                <div class="detalle-titulo">Detalles: </div>
                                <div class="detalle-item"><strong>ID del Inbook:</strong> ${detallesInbook.ID_Inbook}</div>
                                <div class="detalle-item"><strong>ID del Libro:</strong> ${detallesInbook.ID_book}</div>
                                <div class="detalle-item"><strong>Título:</strong> ${detallesInbook.title}</div>
                                <div class="detalle-item"><strong>Capítulo:</strong> ${detallesInbook.chapter}</div>
                                <div class="detalle-item"><strong>Páginas:</strong> ${detallesInbook.pages}</div>
                                <div class="detalle-item"><strong>Tipo:</strong> ${detallesInbook.type}</div>
                                <div class="detalle-item"><strong>Nota:</strong> ${detallesInbook.note}</div>
                            </div>`;


                        // Agrega más campos según sea necesario

                        // Mostrar los detalles en la ventana modal
                        $("#detalleInbook").html(contenidoDetalle);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del Inbook:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoInbook").modal("show");
                    $("#detalleInbook").html(`<p>Error al obtener detalles del Inbook. Inténtalo nuevamente.</p>`);
                }
            });
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function editarInbook(idInbook) {
            // Realizar una solicitud AJAX para obtener los detalles del Inbook con el ID dado
            $.ajax({
                type: "POST",
                url: "obtener_inbook.php",
                data: {
                    idInbook: idInbook
                },
                dataType: "json",
                success: function (detallesInbook) {
                    if (detallesInbook && !detallesInbook.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoInbook").modal("show");

                        // Llenar el contenido de la ventana modal con el formulario de edición del Inbook
                        var contenidoDetalle = `
                            <form id="formularioEditarInbook">
                                <input type="hidden" id="idInBookEditar" value="${detallesInbook.ID_Inbook}">
                                
                                <div class="form-group">
                                    <label for="editIdLibro">ID de Libro:</label>
                                    <select class="form-control" name="idLibro" id="editIdLibro"></select>
                                </div>

                                <div class="form-group">
                                    <label for="tituloInbookEditar">Título:</label>
                                    <input type="text" class="form-control" id="tituloInbookEditar" value="${detallesInbook.title}">
                                </div>

                                <div class="form-group">
                                    <label for="chapterEditar">Capítulo:</label>
                                    <input type="text" class="form-control" id="chapterEditar" value="${detallesInbook.chapter}">
                                </div>

                                <div class="form-group">
                                    <label for="pagesEditar">Páginas:</label>
                                    <input type="text" class="form-control" id="pagesEditar" value="${detallesInbook.pages}">
                                </div>

                                <div class="form-group">
                                    <label for="typeEditar">Tipo:</label>
                                    <input type="text" class="form-control" id="typeEditar" value="${detallesInbook.type}">
                                </div>

                                <div class="form-group">
                                    <label for="notaEditar">Nota:</label>
                                    <textarea class="form-control" id="notaEditar">${detallesInbook.note}</textarea>
                                </div>

                                <!-- Agrega más campos según sea necesario -->

                                <button type="button" class="btn btn-primary" onclick="guardarEdicionInbook()">Guardar</button>
                            </form>
                        `;


                        // Mostrar los detalles en la ventana modal
                        $("#detalleInbook").html(contenidoDetalle);

                        // Cargar opciones del combo de libros
                        cargarOpcionesLibros("#editIdLibro", detallesInbook.ID_book);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del Inbook:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoInbook").modal("show");
                    $("#detalleInbook").html(`<p>Error al obtener detalles del Inbook. Inténtalo nuevamente.</p>`);
                }
            });
        }
        function guardarEdicionInbook() {
            // Obtener los valores actualizados del formulario de edición
            var idInBook = $("#idInBookEditar").val();
            var idLibro = $("#editIdLibro").val();
            var tituloInbook = $("#tituloInbookEditar").val();
            var chapter = $("#chapterEditar").val();
            var pages = $("#pagesEditar").val();
            var type = $("#typeEditar").val();
            var nota = $("#notaEditar").val();

            // Verificar que los campos requeridos no estén vacíos
            if (idLibro === "" || tituloInbook === "" || chapter === "" || pages === "" || type === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            // Enviar los datos actualizados al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_edicion_inbook.php", // Ajusta la ruta al archivo PHP que procesará la solicitud de edición
                data: {
                    idInBook: idInBook,
                    idLibro: idLibro,
                    title: tituloInbook,
                    chapter: chapter,
                    pages: pages,
                    type: type,
                    note: nota
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar la ventana modal después de guardar
                    $("#modalInfoInbook").modal("hide");

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
        function cargarOpcionesLibros(selector, libroSeleccionado) {
            $.ajax({
                type: "GET",
                url: "script_combo_book.php",
                success: function (response) {
                    $(selector).html(response);
                    
                    // Seleccionar el libro correspondiente
                    $(selector).val(libroSeleccionado);
                },
                error: function (error) {
                    console.error("Error al obtener datos de libros:", error);
                }
            });
        }

        // Esperar a que el DOM esté listo
        $(document).ready(function () {
            // Cargar opciones del combo de libros al cargar la página
            cargarOpcionesLibros("#editIdLibro", /* valor por defecto o null */);
        });

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function eliminarInbook(idInbook) {
            // Confirmar con el usuario antes de proceder con la eliminación
            var confirmacion = confirm("¿Estás seguro de que quieres eliminar este Inbook?");

            if (confirmacion) {
                // Realizar una solicitud AJAX para eliminar el Inbook con el ID dado
                $.ajax({
                    type: "POST",
                    url: "eliminar_inbook.php",
                    data: {
                        idInbook: idInbook
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
</body>
</html>

<?php
// Cierra la conexión a la base de datos después de usarla
$conn->close();
?>
