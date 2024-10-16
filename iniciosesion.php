<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir la conexión a la base de datos
    include_once 'db_connection.php';

    // Recoger los datos del formulario
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Preparar la consulta para verificar el usuario
    $sql = "SELECT id_usuario, contraseña, nombre FROM usuarios WHERE correo = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        // Obtener los datos del usuario
        mysqli_stmt_bind_result($stmt, $id_usuario, $contraseña_almacenada, $nombre);
        mysqli_stmt_fetch($stmt);

        // Verificar si la contraseña ingresada coincide con la almacenada
        if ($contraseña === $contraseña_almacenada) {
            // Iniciar sesión
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre'] = $nombre;

            echo '<script>';
            echo 'alert("Sesión iniciada correctamente.");';
            echo 'window.location.href = "ayuda.php";';
            echo '</script>';
            exit();
        } else {
            echo '<script>';
            echo 'alert("Contraseña incorrecta.");';
            echo '</script>';
        }
    } else {
        echo '<script>';
        echo 'alert("Correo no encontrado.");';
        echo '</script>';
    }

    // Cerrar las conexiones
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
