<?php
session_start();
if ($_SESSION['rol_id'] != 2) {
    header("Location: login.php");
    exit();
}
echo "<h1>Bienvenido Doctor, " . $_SESSION['usuario_nombre'] . "</h1>";
?>
