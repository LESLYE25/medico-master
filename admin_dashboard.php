<?php
session_start();
if ($_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}
echo "<h1>Bienvenido Administrador, " . $_SESSION['usuario_nombre'] . "</h1>";
?>
