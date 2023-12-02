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

// Consulta para obtener todos los artículos
$sql = "SELECT * FROM article";
$result = $conn->query($sql);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Artículos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
body {
    padding: 20px;
}   

.table {
    margin-top: 20px;
}

#formularioAgregarArticulo {
    display: none;
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#formularioAgregarArticulo h3 {
    text-align: center;
    color: #007bff;
}

#formularioAgregarArticulo label {
    display: block;
    margin-top: 10px;
}

#formularioAgregarArticulo input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    box-sizing: border-box;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

#agregarArticuloBtn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#agregarArticuloBtn:hover {
    background-color: #0056b3;
}

/* Animación de fadeIn */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Estilos para la tabla de artículos */
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

/* Estilos para los botones en la tabla */
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

/* Estilos para el div de información detallada */
#infoArticulo {
    display: none;
    margin-top: 20px;
    padding: 10px;
    border: 1px solid #ddd;
}

/* Estilos para el botón de volver en la información detallada */
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
</style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <h2>Listado de Artículos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Acciones</th>
        </tr>
        <?php
        // Mostrar los resultados en la tabla
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ID_Article"] . "</td>";
                echo "<td>" . $row["title"] . "</td>";
                echo "<td>
                        <button class='btn btn-info' onclick='verArticulo(" . $row["ID_Article"] . ")'>Ver</button>
                        <button class='btn btn-warning'  onclick='editarArticulo(" . $row["ID_Article"] . ")'>Editar</button>
                        <button class='btn btn-danger' onclick='eliminarArticulo(" . $row["ID_Article"] . ")'>Eliminar</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay artículos</td></tr>";
        }
        ?>
    </table>
    <!-- Modal para detalles de artículo -->
<div class="modal fade" id="modalInfoArticle" tabindex="-1" role="dialog" aria-labelledby="modalInfoArticleLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInfoArticleLabel">Detalles del Artículo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenido de la modal -->
                <div id="detalleArticle">
                    <!-- Aquí se llenarán dinámicamente los detalles del artículo -->
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Botón para agregar un nuevo artículo -->
    <button id="agregarArticuloBtn" onclick="mostrarFormularioAgregarArticulo()">Agregar Artículo</button>

    <!-- Formulario para agregar un nuevo artículo (inicialmente oculto) -->
    <div id="formularioAgregarArticulo">
        <h3>Agregar Artículo</h3>
        <!-- Campos comunes para todos los tipos de artículos -->
        <label for="idPublication">ID de Publicación:</label>
        <select id="idPublication">
            <!-- Opciones se cargarán dinámicamente con datos de la base de datos -->
        </select>
        <label for="tituloArticulo">Título:</label>
        <input type="text" id="tituloArticulo" />

        <label for="revista">Revista:</label>
        <input type="text" id="revista" />

        <label for="volumen">Volumen:</label>
        <input type="text" id="volumen" />

        <label for="numero">Número:</label>
        <input type="number" id="numero" />

        <label for="paginas">Páginas:</label>
        <input type="text" id="paginas" />

        <label for="mesPublicacion">Mes de Publicación:</label>
        <input type="text" id="mesPublicacion" />

        <label for="anioPublicacion">Año de Publicación:</label>
        <input type="number" id="anioPublicacion" />

        <label for="nota">Nota:</label>
        <input type="text" id="nota" />

        <button onclick="agregarArticulo()">Guardar</button>
    </div>



    <!-- Div para mostrar la información detallada de un artículo (inicialmente oculto) -->
    <div id="infoArticulo" class="hidden">
        <!-- Contenido de la información detallada del artículo -->
        <!-- ... -->
    </div>

    <!-- Script para gestionar las acciones con AJAX -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        function mostrarFormularioAgregarArticulo() {
            $("#formularioAgregarArticulo").toggle();
        }

        function agregarArticulo() {
            // Obtener los valores de los campos del formulario
            var idPublication = $("#idPublication").val();
            var titulo = $("#tituloArticulo").val();
            var revista = $("#revista").val();
            var volumen = $("#volumen").val();
            var numero = $("#numero").val();
            var paginas = $("#paginas").val();
            var mesPublicacion = $("#mesPublicacion").val();
            var anioPublicacion = $("#anioPublicacion").val();
            var nota = $("#nota").val();

            // Verificar que los campos requeridos no estén vacíos
            if (idPublication === "" || titulo === "" || revista === "" || volumen === "" || numero === "" || paginas === "" || mesPublicacion === "" || anioPublicacion === "") {
                alert("Por favor, completa todos los campos obligatorios.");
                return;
            }

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "guardar_article.php", // Reemplaza con la ruta correcta de tu script PHP
                data: {
                    idPublication: idPublication,
                    titulo: titulo,
                    revista: revista,
                    volumen: volumen,
                    numero: numero,
                    paginas: paginas,
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
    </script>
    <script>
        // Lugar en tu script donde realizas la solicitud AJAX para obtener las publicaciones
        $.ajax({
            type: "GET",
            url: "script_combo.php",
            success: function (response) {
                // Agregar las opciones al combo
                $("#idPublication").html(response);
            },
            error: function (error) {
                console.error("Error al obtener datos de publicaciones:", error);
            }
        });
    </script>
    <script>
        function verArticulo(id){
            //logica para mostrar la informacion detallada del articulo con el id a un lado
            $.ajax({
                tipe: "GET",
                url:"obtener_article.php",
                data:{ id: id},
                success: function (responce){
                    var detallesArticle = JSON.parse(responce);
                    //Mostrar la pantalla modal con los detalles
                    $("#modalInfoArticle").modal("show");
                    //Llenar el contenido de la pantalla modal con los detalles de la editorial
                    //Mostrar los detalles en la vetana modal
                    $("#detalleArticle").html(`
                    <p>ID: ${detallesArticle.id}<p>
                    <p>title: ${detallesArticle.title}<p>
                    <p>journal: ${detallesArticle.journal}<p>
                    <p>volume: ${detallesArticle.volume}<p>
                    <p>number: ${detallesArticle.number}<p>
                    <p>pages: ${detallesArticle.pages}<p>
                    <p>pub_month: ${detallesArticle.pub_month}<p>
                    <p>pub_year: ${detallesArticle.pub_year}<p>
                    <p>note: ${detallesArticle.note}<p>`
                    );
                },
                error: function (error) {
                    //Manejar errores, si es necesario
                    console.error("Error al obtener detalles de la editorial:", error);
                }
            });
        }
        function editarArticulo(id) {
            // Realizar una solicitud AJAX para obtener los detalles del artículo con el ID dado
            $.ajax({
                type: "GET",
                url: "obtener_article.php",
                data: {
                    id: id
                },
                success: function (response) {
                    // Parsear la respuesta JSON
                    var detallesArticle = JSON.parse(response);

                    // Mostrar los detalles en el formulario de edición en la misma ventana modal
                    $("#detalleArticle").html(`
                        <form id="formularioEdicionArticle">
                            <input type="hidden" name="id" value="${detallesArticle.id}">
                            <label for="editTitle">Título:</label>
                            <input type="text" name="title" value="${detallesArticle.title}">
                            <label for="editJournal">Revista:</label>
                            <input type="text" name="journal" value="${detallesArticle.journal}">
                            <label for="editVolume">Volumen:</label>
                            <input type="text" name="volume" value="${detallesArticle.volume}">
                            <label for="editNumber">Número:</label>
                            <input type="text" name="number" value="${detallesArticle.number}">
                            <label for="editPages">Páginas:</label>
                            <input type="text" name="pages" value="${detallesArticle.pages}">
                            <label for="editPubMonth">Mes de Publicación:</label>
                            <input type="text" name="pub_month" value="${detallesArticle.pub_month}">
                            <label for="editPubYear">Año de Publicación:</label>
                            <input type="text" name="pub_year" value="${detallesArticle.pub_year}">
                            <label for="editNote">Nota:</label>
                            <textarea name="note">${detallesArticle.note}</textarea>
                            <!-- Agrega más campos según sea necesario -->
                            <button type="button" onclick="guardarEdicionArticulo()">Guardar</button>
                        </form>
                    `);

                    // Mostrar la ventana modal
                    $("#modalInfoArticle").modal("show");
                },
                error: function (error) {
                    console.error("Error al obtener detalles del artículo:", error);
                }
            });
        }

        function guardarEdicionArticulo() {
            // Obtener los valores actualizados del formulario de edición
            var id = $("#formularioEdicionArticle input[name='id']").val();
            var title = $("#formularioEdicionArticle input[name='title']").val();
            var journal = $("#formularioEdicionArticle input[name='journal']").val();
            var volume = $("#formularioEdicionArticle input[name='volume']").val();
            var number = $("#formularioEdicionArticle input[name='number']").val();
            var pages = $("#formularioEdicionArticle input[name='pages']").val();
            var pub_month = $("#formularioEdicionArticle input[name='pub_month']").val();
            var pub_year = $("#formularioEdicionArticle input[name='pub_year']").val();
            var note = $("#formularioEdicionArticle textarea[name='note']").val();

            // Realizar una solicitud AJAX para enviar los datos actualizados al servidor
            $.ajax({
                type: "POST",
                url: "guardar_edicion_article.php", // Reemplaza con la ruta correcta a tu script PHP de guardado
                data: {
                    id: id,
                    title: title,
                    journal: journal,
                    volume: volume,
                    number: number,
                    pages: pages,
                    pub_month: pub_month,
                    pub_year: pub_year,
                    note: note
                    // Agrega más campos según sea necesario
                },
                success: function (response) {
                    // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                    alert(response);

                    // Cerrar la ventana modal después de guardar la edición
                    $("#modalInfoArticle").modal("hide");

                    // Recargar la página o realizar otras acciones necesarias después de guardar la edición
                    location.reload();
                },
                error: function (error) {
                    // Manejar errores, si es necesario
                    console.error("Error al enviar datos de edición al servidor:", error);
                }
            });
        }

        // Función para eliminar un artículo
        function eliminarArticulo(id) {
            // Confirmar con el usuario antes de eliminar
            var confirmacion = confirm("¿Estás seguro de que quieres eliminar este artículo?");
            
            if (confirmacion) {
                // Realizar una solicitud AJAX para eliminar el artículo con el ID dado
                $.ajax({
                    type: "POST", // Puedes cambiar a GET si prefieres
                    url: "eliminar_article.php", // Reemplaza con la ruta correcta a tu script PHP de eliminación
                    data: {
                        id: id
                    },
                    success: function (response) {
                        // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        alert(response);

                        // Recargar la página o realizar otras acciones necesarias después de eliminar el artículo
                        location.reload();
                    },
                    error: function (error) {
                        // Manejar errores, si es necesario
                        console.error("Error al eliminar el artículo:", error);
                    }
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
