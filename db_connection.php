<?php
// Configuración de conexión a la base de datos
$host = "localhost"; // Servidor de la base de datos
$usuario = "root"; // Nombre de usuario
$contraseña = ""; // Contraseña (deja vacío si no hay)
$base_datos = "poi"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $usuario, $contraseña, $base_datos);

?>
