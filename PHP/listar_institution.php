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

// Consulta para obtener todas las instituciones
$sql = "SELECT * FROM institution";
$result = $conn->query($sql);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Instituciones</title>
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
<h2>Listado de Instituciones</h2>
<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Mostrar los resultados en la tabla
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>
                        <button class='btn btn-info' onclick='verInstitucion(" . $row["ID_Institution"] . ")'>Ver</button>
                        <button class='btn btn-warning' onclick='editarInstitucion(" . $row["ID_Institution"] . ")'>Editar</button>
                        <button class='btn btn-danger' onclick='eliminarInstitucion(" . $row["ID_Institution"] . ")'>Eliminar</button>
                        </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay instituciones</td></tr>";
        }
        ?>
    </tbody>
</table>
<!-- Agrega esta estructura HTML para mostrar la información detallada de la institución -->
<div class="modal" id="modalInfoInstitucion" tabindex="-1" role="dialog" aria-labelledby="modalInfoInstitucionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInfoInstitucionLabel">Detalles de la Institución</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleInstitucion">
                <!-- Aquí se mostrarán los detalles de la institución -->
            </div>
        </div>
    </div>
</div>
    <!-- Botón para agregar una nueva institución -->
    <button id="agregarBtn" onclick="mostrarFormularioAgregar()">Agregar Institución</button>

    <!-- Formulario para agregar una nueva institución (inicialmente oculto) -->
    <div id="formularioAgregar" class="hidden">
        <h3>Agregar Institución</h3>
        <form>
            <!-- Campos comunes para todos los tipos de instituciones -->
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" required>
            </div>
            <div class="form-group">
                <label for="calle">Calle:</label>
                <input type="text" class="form-control" id="calle" required>
            </div>
            <div class="form-group">
                <label for="ciudad">Ciudad:</label>
                <input type="text" class="form-control" id="ciudad" required>
            </div>
            <div class="form-group">
                <label for="provincia">Provincia:</label>
                <input type="text" class="form-control" id="provincia" required>
            </div>
            <div class="form-group">
                <label for="codigoPostal">Código Postal:</label>
                <input type="text" class="form-control" id="codigoPostal" required>
            </div>
            <div class="form-group">
                <label for="pais">País:</label>
                <input type="text" class="form-control" id="pais" required>
            </div>
            <button type="button" class="btn btn-success" onclick="agregarInstitucion()">Guardar</button>
        </form>
    </div>



<!-- Div para mostrar la información detallada de una institución (inicialmente oculto) -->
<div id="infoInstitucion" class="hidden">
    <!-- Contenido de la información detallada de la institución -->
    <!-- ... -->
</div>

<!-- Script para gestionar las acciones con AJAX -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    function mostrarFormularioAgregar() {
        $("#formularioAgregar").toggle();
    }

    function agregarInstitucion() {
    // Obtener los valores de los campos del formulario
    var nombre = $("#nombre").val();
    var calle = $("#calle").val();
    var ciudad = $("#ciudad").val();
    var provincia = $("#provincia").val();
    var codigoPostal = $("#codigoPostal").val();
    var pais = $("#pais").val();

    // Enviar los datos al servidor usando AJAX
    $.ajax({
        type: "POST",
        url: "guardar_institucion.php",
        data: {
            nombre: nombre,
            calle: calle,
            ciudad: ciudad,
            provincia: provincia,
            codigoPostal: codigoPostal,
            pais: pais
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

    function verInstitucion(id) {
        // Realizar una solicitud AJAX para obtener los detalles de la institución con el ID dado
        $.ajax({
            type: "GET",
            url: "obtener_institucion.php",
            data: {
                id: id
            },
            success: function (response) {
                // Parsear la respuesta JSON
                var detallesInstitucion = JSON.parse(response);

                // Mostrar los detalles en la ventana modal
                $("#detalleInstitucion").html(`
                    <p>ID: ${detallesInstitucion.id}</p>
                    <p>Nombre: ${detallesInstitucion.nombre}</p>
                    <p>Calle: ${detallesInstitucion.calle}</p>
                    <p>Ciudad: ${detallesInstitucion.ciudad}</p>
                    <p>Provincia: ${detallesInstitucion.provincia}</p>
                    <p>Código Postal: ${detallesInstitucion.codigoPostal}</p>
                    <p>País: ${detallesInstitucion.pais}</p>
                    <!-- Agrega más campos según sea necesario -->
                `);

                // Mostrar la ventana modal
                $("#modalInfoInstitucion").modal("show");
            },
            error: function (error) {
                console.error("Error al obtener detalles de la institución:", error);
            }
        });
    }



    function editarInstitucion(id) {
        // Realizar una solicitud AJAX para obtener los detalles de la institución con el ID dado
        $.ajax({
            type: "GET",
            url: "obtener_institucion.php",
            data: {
                id: id
            },
            success: function (response) {
                // Parsear la respuesta JSON
                var detallesInstitucion = JSON.parse(response);

                // Mostrar los detalles en el formulario de edición en la misma ventana modal
                $("#detalleInstitucion").html(`
                    <form id="formularioEdicion">
                        <input type="hidden" name="id" value="${detallesInstitucion.id}">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" value="${detallesInstitucion.nombre}">
                        <label for="calle">Calle:</label>
                        <input type="text" name="calle" value="${detallesInstitucion.calle}">
                        <label for="ciudad">Ciudad:</label>
                        <input type="text" name="ciudad" value="${detallesInstitucion.ciudad}">
                        <label for="provincia">Provincia:</label>
                        <input type="text" name="provincia" value="${detallesInstitucion.provincia}">
                        <label for="codigoPostal">Código Postal:</label>
                        <input type="text" name="codigoPostal" value="${detallesInstitucion.codigoPostal}">
                        <label for="pais">País:</label>
                        <input type="text" name="pais" value="${detallesInstitucion.pais}">
                        <!-- Agrega más campos según sea necesario -->
                        <button type="button" onclick="guardarEdicion()">Guardar</button>
                    </form>
                `);

                // Mostrar la ventana modal
                $("#modalInfoInstitucion").modal("show");
            },
            error: function (error) {
                console.error("Error al obtener detalles de la institución:", error);
            }
        });
    }

    function guardarEdicion() {
    // Obtener los valores actualizados del formulario de edición
    var id = $("#formularioEdicion input[name='id']").val();
    var nombre = $("#formularioEdicion input[name='nombre']").val();
    var calle = $("#formularioEdicion input[name='calle']").val();
    var ciudad = $("#formularioEdicion input[name='ciudad']").val();
    var provincia = $("#formularioEdicion input[name='provincia']").val();
    var codigoPostal = $("#formularioEdicion input[name='codigoPostal']").val();
    var pais = $("#formularioEdicion input[name='pais']").val();

    // Realizar una solicitud AJAX para enviar los datos actualizados al servidor
    $.ajax({
        type: "POST",
        url: "guardar_edicion_institucion.php", // Reemplaza con la ruta correcta a tu script PHP de guardado
        data: {
            id: id,
            nombre: nombre,
            calle: calle,
            ciudad: ciudad,
            provincia: provincia,
            codigoPostal: codigoPostal,
            pais: pais
            // Agrega más campos según sea necesario
        },
        success: function (response) {
            // Mostrar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
            alert(response);

            // Cerrar la ventana modal después de guardar la edición
            $("#modalInfoInstitucion").modal("hide");

            // Recargar la página o realizar otras acciones necesarias después de guardar la edición
            location.reload();
        },
        error: function (error) {
            // Manejar errores, si es necesario
            console.error("Error al enviar datos de edición al servidor:", error);
        }
    });
}

function eliminarInstitucion(id) {
    // Confirmar con el usuario antes de proceder con la eliminación
    var confirmacion = confirm("¿Estás seguro de que deseas eliminar esta institución?");

    if (confirmacion) {
        // Realizar una solicitud AJAX para eliminar la institución con el ID dado
        $.ajax({
            type: "POST",
            url: "eliminar_institucion.php", // Reemplaza con la ruta correcta a tu script PHP de eliminación
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
