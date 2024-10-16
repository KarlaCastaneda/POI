<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (isset($_SESSION['id_usuario'])) {
    // Asignar el ID de usuario de la sesión a una variable de PHP
    $idUsuario = $_SESSION['id_usuario'];
} else {
    // Si no hay sesión, redirigir o mostrar un error
    echo "Error: Usuario no autenticado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat 1 a 1</title>
    <link rel="stylesheet" href="chats.css"> 
</head>
<body>
    <div class="container">
        <!-- Barra lateral de navegación -->
        <aside class="sidebar-left">
            <h4>Mensajes</h4>
            <div class="profile">
                <img src="icon.jpg" alt="Foto de Perfil" class="profile-img">
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#mensajes">Mensajes</a></li>
                    <li><a href="#grupos">Grupos</a></li>
                    <li><a href="#tareas">Tareas</a></li>
                    <li><a href="#cerrar-sesion" class="logout">Cerrar sesión</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <section id="chat">
                <div class="recent-chats">
                    <h3>Chats Recientes</h3>
                    <ul>
                        <li>
                            <img src="icon1.jpg" alt="Usuario 1" class="chat-profile-img">
                            <a href="#chat1">Chat con Usuario 1</a>
                        </li>
                        <li>
                            <img src="icon2.jpg" alt="Usuario 2" class="chat-profile-img">
                            <a href="#chat2">Chat con Usuario 2</a>
                        </li>
                        <li>
                            <img src="icon3.jpg" alt="Usuario 3" class="chat-profile-img">
                            <a href="#chat3">Chat con Usuario 3</a>
                        </li>
                    </ul>
                </div>

                <div class="chat-header">
                    <h2>Chat con [Nombre del Usuario]</h2>
                    <button class="video-call-btn">Videollamada</button>
                </div>
                <div class="chat-window">
                    <div class="chat-messages"></div>
                </div>
                <div class="chat-input">
                    <input type="text" placeholder="Escribe un mensaje..." />
                    <button class="send-btn">Enviar</button>
                </div>
            </section>
        </main>

        <!-- Sidebar derecha con lista de usuarios -->
        <aside class="sidebar-right">
            <div class="search-bar">
                <input type="text" placeholder="Buscar usuario...">
            </div>
            <ul class="user-list">
                <li>
                    <img src="icon.hh" alt="Usuario 1" class="user-profile-img">
                    <span class="user-status status-online"></span>
                    <span class="user-name">Usuario 1</span>
                </li>
                <li>
                    <img src="profile2.jpg" alt="Usuario 2" class="user-profile-img">
                    <span class="user-status status-offline"></span>
                    <span class="user-name">Usuario 2</span>
                </li>
                <li>
                    <img src="profile3.jpg" alt="Usuario 3" class="user-profile-img">
                    <span class="user-status status-online"></span>
                    <span class="user-name">Usuario 3</span>
                </li>
                <li>
                    <img src="profile4.jpg" alt="Usuario 4" class="user-profile-img">
                    <span class="user-status status-offline"></span>
                    <span class="user-name">Usuario 4</span>
                </li>
            </ul>
        </aside>
        <script>
            let idUsuario = <?php echo json_encode($idUsuario); ?>;
            let idReceptor = 1; // Cambiar dinámicamente según el receptor actual

            document.querySelector('.send-btn').addEventListener('click', function() {
    let mensaje = document.querySelector('.chat-input input').value;

    if (mensaje.trim() !== '') {
        let formData = new FormData();
        formData.append('contenido', mensaje);
        formData.append('id_receptor', idReceptor);

        fetch('enviarmensaje.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.text();
        })
        .then(data => {
            console.log("Respuesta del servidor:", data);
            document.querySelector('.chat-input input').value = ''; // Limpiar el input
            actualizarMensajes(idReceptor); // Llamar para obtener los mensajes actualizados
        })
        .catch(error => console.error('Error:', error));
    }
});

            function actualizarMensajes(idReceptor) {
                fetch(`obtenermensajes.php?id_receptor=${idReceptor}`)
                    .then(response => response.json())
                    .then(mensajes => {
                        let chatWindow = document.querySelector('.chat-messages');
                        chatWindow.innerHTML = ''; // Limpia los mensajes antiguos

                        mensajes.forEach(mensaje => {
                            let messageDiv = document.createElement('div');
                            messageDiv.classList.add('message');
                            messageDiv.textContent = mensaje.contenido;

                            // Diferencia entre mensajes enviados y recibidos
                            if (mensaje.id_emisor == idUsuario) {
                                messageDiv.classList.add('sent');
                            } else {
                                messageDiv.classList.add('received');
                            }

                            chatWindow.appendChild(messageDiv);
                        });

                        // Desplazar la ventana del chat al último mensaje
                        chatWindow.scrollTop = chatWindow.scrollHeight;
                    })
                    .catch(error => console.error('Error al obtener mensajes:', error));
            }

            // Llamar la función para actualizar mensajes cada 2 segundos
            setInterval(function() {
                actualizarMensajes(idReceptor);
            }, 2000);
        </script>
    </div>
</body>
</html>
