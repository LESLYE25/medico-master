<?php
$host = "localhost";
$user = "root";   // cambia si usas otro
$pass = "";       // pon tu contraseña si tienes
$db   = "sistema_citas";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
