<?php
// Incluir el archivo de conexión a la base de datos
include('db_connection.php'); // Usar el archivo db_connection.php

// Inicializar el nombre del grupo y el id_grupo
$nombre_grupo = '';
$id_grupo = 0;

// Obtener el ID del grupo de la URL
if (isset($_GET['id_grupo'])) {
    $id_grupo = $_GET['id_grupo'];

    // Consultar el nombre del grupo usando el id_grupo
    $sql = "SELECT nombre_grupo FROM grupos WHERE id_grupo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_grupo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($row = $resultado->fetch_assoc()) {
        $nombre_grupo = $row['nombre_grupo'];
    } else {
        // Manejar el caso en que el grupo no se encuentre
        $nombre_grupo = 'Grupo no encontrado';
    }
}

// Función para obtener los mensajes del grupo
function obtenerMensajes($id_grupo) {
    global $conn;
    $sql = "SELECT mp.mensaje, mp.fecha_envio, u.nombre 
            FROM mensajespublicaciones mp
            JOIN usuarios u ON mp.id_usuario = u.id_usuario
            WHERE mp.id_grupo = ?
            ORDER BY mp.fecha_envio ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_grupo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while($row = $resultado->fetch_assoc()) {
        echo "<div class='message'>
                <span class='message-info'>
                    <span class='sender-name'>{$row['nombre']}</span>
                    <span class='message-time'>{$row['fecha_envio']}</span>
                </span>
                <p>{$row['mensaje']}</p>
              </div>";
    }
}

// Manejar el envío de mensajes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario']; // ID del usuario que envía el mensaje
    $mensaje = $_POST['mensaje'];

    if (!empty($mensaje)) {
        $sql = "INSERT INTO mensajespublicaciones (id_usuario, mensaje, id_grupo) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $id_usuario, $mensaje, $id_grupo); // Cambié 'iss' a 'isi'
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupo detalles</title>
    <link rel="stylesheet" href="gdetalles.css">
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
                    <li><a href="#grupos">Grupos</a></li>
                    <li><a href="#tareas">Tareas</a></li>
                    <li><a href="#cerrar-sesion" class="logout">Cerrar sesión</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <div class="chat-header">
                <h2><?php echo $nombre_grupo; ?></h2> <!-- Muestra el nombre del grupo -->
            </div>
            <div class="chat-window">
                <div class="chat-messages">
                    <?php obtenerMensajes($id_grupo); ?>
                </div>
            </div>

            <div class="chat-input">
                <form action="grupodetalles.php?id_grupo=<?php echo $id_grupo; ?>" method="POST">
                    <input type="hidden" name="id_usuario" value="1"> <!-- Cambiar por la ID del usuario real -->
                    <input type="text" name="mensaje" placeholder="Escribe un mensaje...">
                    <button type="submit" class="send-btn">Enviar</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
