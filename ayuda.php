<?php
// Incluir archivo de conexión
include('db_connection.php');

// Obtener los grupos de la base de datos y mostrarlos como enlaces
function obtenerGrupos() {
    global $conn;
    $sql = "SELECT id_grupo, nombre_grupo FROM grupos";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            // Crear un enlace para cada grupo que redirija a grupodetalles.php con el id_grupo en la URL
            echo "<li><a href='grupodetalles.php?id_grupo=" . $row['id_grupo'] . "'>" . $row['nombre_grupo'] . "</a></li>";
        }
    } else {
        echo "<li>No hay grupos disponibles</li>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Perfil</title>
    <link rel="stylesheet" href="ayuda.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h4>Bienvenido</h4>
            <div class="profile">
                <img src="icon.jpg" alt="Foto de Perfil" class="profile-img">
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="ayuda.html">Inicio</a></li>
                    <li><a href="chats.php">Mensajes</a></li>
                    <li><a href="grupodetalles.php">Grupos</a></li>
                    <li><a href="#tareas">Tareas</a></li>
                    <li><a href="#cerrar-sesion" class="logout">Cerrar sesión</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">

            <section id="todos-mis-grupos">
                <h2>Todos Mis Grupos</h2>
                <ul class="all-groups">
                    <li><a href="ayuda.html">Ver todos</a></li>
                    <?php obtenerGrupos(); ?>
                </ul>
            </section>

            <section id="tareas">
                <h3>Tareas Pendientes</h3>
                <ul class="task-list">
                    <li>
                        <input type="checkbox" id="tarea1">
                        <label for="tarea1">Tarea 1</label>
                    </li>
                    <li>
                        <input type="checkbox" id="tarea2">
                        <label for="tarea2">Tarea 2</label>
                    </li>
                    <li>
                        <input type="checkbox" id="tarea3">
                        <label for="tarea3">Tarea 3</label>
                    </li>
                </ul>
            </section>
        </main>
    </div>
</body>
</html>
