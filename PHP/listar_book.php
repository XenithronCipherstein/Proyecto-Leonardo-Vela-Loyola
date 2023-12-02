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

$sql = "SELECT * FROM book";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Libros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            padding: 20px;
        }



        .table {
            margin-top: 20px;
        }

        .table th,
        .table td {
            text-align: center;
        }

        #formularioAgregarLibro {
            display: none;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #formularioAgregarLibro h3 {
            text-align: center;
            color: #007bff;
        }

        #formularioAgregarLibro label {
            margin-top: 10px;
        }

        #formularioAgregarLibro input,
        #formularioAgregarLibro select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        #agregarLibroBtn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #agregarLibroBtn:hover {
            background-color: #0056b3;
        }

        #modalInfoLibro .modal-content {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #modalInfoLibro .modal-title {
            color: #007bff;
        }

        #modalInfoLibro .modal-body {
            text-align: left;
        }

        #infoLibro {
            display: none;
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

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
        .detalle-libro {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }

        .detalle-titulo {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
        }

        .detalle-item {
            margin-bottom: 10px;
        }

    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <h2>Listado de Libros</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
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
                    echo "<td>" . $row["ID_Book"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>
                            <button class='btn btn-info' onclick='verLibro(" . $row["ID_Book"] . ")'>Ver</button>
                            <button class='btn btn-warning' onclick='editarLibro(" . $row["ID_Book"] . ")'>Editar</button>
                            <button class='btn btn-danger' onclick='eliminarLibro(" . $row["ID_Book"] . ")'>Eliminar</button>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No hay libros</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="modal fade" id="modalInfoLibro" tabindex="-1" role="dialog" aria-labelledby="modalInfoLibroLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoLibroLabel">Detalles del Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenido de la modal -->
                    <div id="detalleLibro">
                        <!-- Aquí se llenarán dinámicamente los detalles del libro -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resto del código HTML y scripts JavaScript existentes ... -->

    <button id="agregarLibroBtn" onclick="mostrarFormularioAgregarLibro()">Agregar Libro</button>
    <!-- Formulario para agregar un nuevo libro (inicialmente oculto) -->
    <div id="formularioAgregarLibro">
        <h3>Agregar Libro</h3>
        <!-- Campos comunes para todos los tipos de libros -->
        <label for="idBook">ID de publication:</label>
        <select id="idBook">
            <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
        </select>

        <label for="tituloLibro">Título:</label>
        <input type="text" id="tituloLibro" />

        <label for="idPublisher">ID de Editorial:</label>
        <select id="idPublisher">
            <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
        </select>

        <label for="volumen">Volumen:</label>
        <input type="text" id="volumen" />

        <label for="serie">Serie:</label>
        <input type="text" id="serie" />

        <label for="edicion">Edición:</label>
        <input type="text" id="edicion" />

        <label for="mesPublicacion">Mes de Publicación:</label>
        <input type="text" id="mesPublicacion" />

        <label for="anioPublicacion">Año de Publicación:</label>
        <input type="number" id="anioPublicacion" />

        <label for="nota">Nota:</label>
        <input type="text" id="nota" />

        <button onclick="agregarLibro()">Guardar</button>
    </div>

    <div id="infoLibro" class="hidden">
        <!-- Contenido de la información detallada del libro -->
    </div>

    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script>
    function mostrarFormularioAgregarLibro() {
        $("#formularioAgregarLibro").toggle();
    }

    function agregarLibro() {
        // Obtener los valores de los campos del formulario
        var idPublication = $("#idBook").val();
        var titulo = $("#tituloLibro").val();
        var idPublisher = $("#idPublisher").val();  // Cambiado aquí
        var volumen = $("#volumen").val();
        var serie = $("#serie").val();
        var edicion = $("#edicion").val();
        var mesPublicacion = $("#mesPublicacion").val();
        var anioPublicacion = $("#anioPublicacion").val();
        var nota = $("#nota").val();

        // Verificar que los campos requeridos no estén vacíos
        if (idPublication === "" || titulo === "" || idPublisher === "" || volumen === "" || mesPublicacion === "" || anioPublicacion === "") {
            alert("Por favor, completa todos los campos obligatorios.");
            return;
        }

        // Enviar los datos al servidor usando AJAX
        $.ajax({
            type: "POST",
            url: "guardar_libro.php",
            data: {
                idPublication: idPublication,
                titulo: titulo,
                idPublisher: idPublisher,
                volumen: volumen,
                serie: serie,
                edicion: edicion,
                mesPublicacion: mesPublicacion,
                anioPublicacion: anioPublicacion,
                nota: nota
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

    // Lugar en tu script donde realizas la solicitud AJAX para obtener las publicaciones
    $.ajax({
        type: "GET",
        url: "script_combo.php",
        success: function (response) {
            // Agregar las opciones al combo
            $("#idBook").html(response);
        },
        error: function (error) {
            console.error("Error al obtener datos de publicaciones:", error);
        }
    });

    // Lugar en tu script donde realizas la solicitud AJAX para obtener las editoriales
    $.ajax({
        type: "GET",
        url: "script_combo_publisher.php",
        dataType: "json",
        success: function (response) {
            // Limpiar opciones anteriores
            $("#idPublisher").empty();

            // Agregar las opciones al combo de editoriales
            for (var i = 0; i < response.length; i++) {
                $("#idPublisher").append(
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


    ////////////////////////////////////////////////////////////////
    function verLibro(idLibro) {
        // Realizar una solicitud AJAX para obtener los detalles del libro con el ID dado
        $.ajax({
            type: "POST",
            url: "obtener_libro.php",
            data: {
                idLibro: idLibro
            },
            dataType: "json", // Especifica el tipo de datos esperado como JSON
            success: function (detallesLibro) {
                // Verifica si los detalles del libro son válidos antes de mostrar el modal
                if (detallesLibro && !detallesLibro.error) {
                    // Mostrar la ventana modal
                    $("#modalInfoLibro").modal("show");

                    // Llenar el contenido de la ventana modal con los detalles del libro
                    var contenidoDetalle = `
                        <div class="detalle-libro">
                            <div class="detalle-titulo">Detalles: </div>
                            <div class="detalle-item"><strong>ID:</strong> ${detallesLibro.ID_Book}</div>
                            <div class="detalle-item"><strong>Título:</strong> ${detallesLibro.title}</div>
                            <div class="detalle-item"><strong>ID de Editorial:</strong> ${detallesLibro.ID_Publisher}</div>
                            <div class="detalle-item"><strong>Volumen:</strong> ${detallesLibro.volume}</div>
                            <div class="detalle-item"><strong>Serie:</strong> ${detallesLibro.series}</div>
                            <div class="detalle-item"><strong>Edición:</strong> ${detallesLibro.edition}</div>
                            <div class="detalle-item"><strong>Mes de Publicación:</strong> ${detallesLibro.pub_month}</div>
                            <div class="detalle-item"><strong>Año de Publicación:</strong> ${detallesLibro.pub_year}</div>
                            <div class="detalle-item"><strong>Nota:</strong> ${detallesLibro.note}</div>
                        </div>`;


                    // Agrega más campos según sea necesario

                    // Mostrar los detalles en la ventana modal
                    $("#detalleLibro").html(contenidoDetalle);
                } else {
                    console.error("La respuesta del servidor está vacía o no es válida.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error al obtener detalles del libro:", error);

                // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                $("#modalInfoLibro").modal("show");
                $("#detalleLibro").html(`<p>Error al obtener detalles del libro. Inténtalo nuevamente.</p>`);
            }
        });
    }

/////////////////////////////////////////////////////////////////////////////
        function editarLibro(idLibro) {
            // Realizar una solicitud AJAX para obtener los detalles del libro con el ID dado
            // Realizar una solicitud AJAX para obtener los detalles del libro con el ID dado
            $.ajax({
                type: "POST", // Cambiado a POST según obtener_libro.php
                url: "obtener_libro.php",
                data: {
                    idLibro: idLibro
                },
                dataType: "json", // Especifica el tipo de datos esperado como JSON
                success: function (detallesLibro) {
                    // Verifica si los detalles del libro son válidos antes de mostrar el modal
                    if (detallesLibro && !detallesLibro.error) {
                        // Mostrar la ventana modal
                        $("#modalInfoLibro").modal("show");

                        // Llenar el contenido de la ventana modal con los detalles del libro
                        var contenidoDetalle = `
                            <form id="formularioEdicionLibro" class="formulario-edicion">
                                <input type="hidden" name="idLibro" value="${detallesLibro.ID_Book}">
                                
                                <div class="form-group">
                                    <label for="editTitulo">Título:</label>
                                    <input type="text" class="form-control" name="titulo" value="${detallesLibro.title}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="editPublisher">ID de Editorial:</label>
                                    <select class="form-control" name="idEditorial" id="editIdEditorial"></select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="editVolumen">Volumen:</label>
                                    <input type="text" class="form-control" name="volumen" value="${detallesLibro.volume}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="editSerie">Serie:</label>
                                    <input type="text" class="form-control" name="serie" value="${detallesLibro.series}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="editEdicion">Edición:</label>
                                    <input type="text" class="form-control" name="edicion" value="${detallesLibro.edition}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="editMesPublicacion">Mes de Publicación:</label>
                                    <input type="text" class="form-control" name="mesPublicacion" value="${detallesLibro.pub_month}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="editAnioPublicacion">Año de Publicación:</label>
                                    <input type="text" class="form-control" name="anioPublicacion" value="${detallesLibro.pub_year}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="editNota">Nota:</label>
                                    <textarea class="form-control" name="nota">${detallesLibro.note}</textarea>
                                </div>

                                <!-- Agrega más campos según sea necesario -->

                                <button type="button" class="btn btn-primary" onclick="guardarEdicionLibro()">Guardar</button>
                            </form>
                        `;

                            // Asegúrate de que hayas incluido Bootstrap para aprovechar las clases como "form-group" y "form-control".


                        // Mostrar los detalles en la ventana modal
                        $("#detalleLibro").html(contenidoDetalle);

                        // Cargar opciones del combo de editoriales y seleccionar la opción correcta
                        cargarEditorialesCombo2(detallesLibro.ID_Publisher);
                    } else {
                        console.error("La respuesta del servidor está vacía o no es válida.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener detalles del libro:", error);

                    // Puedes mostrar un mensaje de error al usuario aquí si lo deseas
                    $("#modalInfoLibro").modal("show");
                    $("#detalleLibro").html(`<p>Error al obtener detalles del libro. Inténtalo nuevamente.</p>`);
                }
            });

        }
        // Función para cargar opciones del combo de editoriales
        function cargarEditorialesCombo2(idEditorialSeleccionada) {
            // Realizar una solicitud AJAX para obtener las editoriales
            $.ajax({
                type: "GET",
                url: "script_combo_publisher.php", // Reemplaza con el nombre de tu script para obtener editoriales
                success: function (response) {
                    var editoriales = JSON.parse(response);

                    // Limpiar opciones anteriores
                    $("#editIdEditorial").empty();

                    // Agregar opciones al combo
                    for (var i = 0; i < editoriales.length; i++) {
                        var selected = "";
                        if (editoriales[i].ID_Publisher == idEditorialSeleccionada) {
                            selected = "selected";
                        }
                        $("#editIdEditorial").append(`
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
        function guardarEdicionLibro() {
            // Obtener los valores actualizados del formulario de edición
            var idLibro = $("#formularioEdicionLibro input[name='idLibro']").val();
            var titulo = $("#formularioEdicionLibro input[name='titulo']").val();
            var idEditorial = $("#formularioEdicionLibro select[name='idEditorial']").val();
            var volumen = $("#formularioEdicionLibro input[name='volumen']").val();
            var serie = $("#formularioEdicionLibro input[name='serie']").val();
            var edicion = $("#formularioEdicionLibro input[name='edicion']").val();
            var mesPublicacion = $("#formularioEdicionLibro input[name='mesPublicacion']").val();
            var anioPublicacion = $("#formularioEdicionLibro input[name='anioPublicacion']").val();
            var nota = $("#formularioEdicionLibro textarea[name='nota']").val();

            // Realizar una solicitud AJAX para enviar los datos actualizados al servidor
            $.ajax({
                type: "POST",
                url: "guardar_edicion_libro.php", // Reemplaza con la ruta correcta a tu script PHP de guardado
                data: {
                    idLibro: idLibro,
                    titulo: titulo,
                    idEditorial: idEditorial,
                    volumen: volumen,
                    serie: serie,
                    edicion: edicion,
                    mesPublicacion: mesPublicacion,
                    anioPublicacion: anioPublicacion,
                    nota: nota
                    // Agrega más campos según sea necesario
                },
                success: function (response) {
                    var jsonResponse = JSON.parse(response);

                    // Mostrar el mensaje de éxito
                    alert(jsonResponse.success);

                    // Cerrar la ventana modal después de guardar la edición
                    $("#modalInfoLibro").modal("hide");

                    // Recargar la página o realizar otras acciones necesarias después de guardar la edición
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }
////////////////////////////////////////////////////////////////////////////////////////////////////
        function eliminarLibro(idLibro) {
            // Confirmar con el usuario antes de proceder con la eliminación
            var confirmacion = confirm("¿Estás seguro de que deseas eliminar este libro?");

            if (confirmacion) {
                // Realizar una solicitud AJAX para eliminar el libro con el ID dado
                $.ajax({
                    type: "POST", // Puedes usar POST o DELETE según tus preferencias y configuración del servidor
                    url: "eliminar_libro.php", // Reemplaza con la ruta correcta a tu script PHP para eliminar libros
                    data: {
                        idLibro: idLibro
                    },
                    success: function (response) {
                        // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        alert(response);

                        // Recargar la página o realizar otras acciones necesarias después de eliminar
                        location.reload();
                    },
                    error: function (error) {
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

<?php
// Cierra la conexión a la base de datos después de usarla
$conn->close();
?>
