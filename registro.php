<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la conexión a la base de datos
include 'db_connection.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['name'];
    $correo = $_POST['email'];
    $contraseña = $_POST['password'];
    $fecha_nacimiento = $_POST['birthdate'];

    // Procesar la imagen de la foto de perfil
    $perfil_imagen = $_FILES['profile-pic'];

    // Validar que no falte ningún dato
    if (empty($nombre) || empty($correo) || empty($contraseña) || empty($fecha_nacimiento) || empty($perfil_imagen['tmp_name'])) {
        die("Por favor, completa todos los campos.");
    }

    // Validar la contraseña (opcional)
    if (strlen($contraseña) < 6) {
        die("La contraseña debe tener al menos 6 caracteres.");
    }

    // Verificar si la imagen fue cargada correctamente
    if ($perfil_imagen['error'] !== UPLOAD_ERR_OK) {
        die("Error al subir la imagen de perfil.");
    }

    // Encriptar la contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Leer el contenido del archivo de imagen
    $imagen_blob = file_get_contents($perfil_imagen['tmp_name']);

    // Preparar la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, correo, contraseña, fecha_nacimiento, foto_perfil) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $correo, $contraseña_hash, $fecha_nacimiento, $imagen_blob);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro exitoso. Puedes iniciar sesión ahora.";
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
