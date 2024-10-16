<?php
session_start();
include 'db_connection.php';

// Asegúrate de que la sesión esté activa
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "Usuario no autenticado."]);
    exit;
}

// Verificar si el ID receptor se ha enviado
if (!isset($_GET['id_receptor'])) {
    echo json_encode(["error" => "ID receptor no proporcionado."]);
    exit;
}

$id_emisor = $_SESSION['id_usuario']; // Usuario actual
$id_receptor = $_GET['id_receptor'];  // Usuario con el que está chateando

$sql = "SELECT * FROM mensajes 
        WHERE (id_emisor = ? AND id_receptor = ?) 
        OR (id_emisor = ? AND id_receptor = ?)
        ORDER BY fecha_envio ASC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
    exit;
}

$stmt->bind_param('iiii', $id_emisor, $id_receptor, $id_receptor, $id_emisor);

if (!$stmt->execute()) {
    echo json_encode(["error" => "Error en la consulta: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $mensajes[] = $row;
}

header('Content-Type: application/json'); // Asegúrate de establecer el tipo de contenido
echo json_encode($mensajes);
?>
