<?php
session_start(); // Asegúrate de que la sesión se haya iniciado
include 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_emisor = $_SESSION['id_usuario']; // El ID del usuario logueado 
    $id_receptor = $_POST['id_receptor'];
    $contenido = $_POST['contenido'];

    if (!empty($contenido) && !empty($id_receptor)) {
        $sql = "INSERT INTO mensajes (id_emisor, id_receptor, contenido, fecha_envio) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iis', $id_emisor, $id_receptor, $contenido);

        if ($stmt->execute()) {
            echo "Mensaje enviado"; // Mensaje de éxito
        } else {
            echo "Error al enviar mensaje: " . $conn->error; // Mensaje de error
        }
    } else {
        echo "El mensaje no puede estar vacío.";
    }
}
?>
