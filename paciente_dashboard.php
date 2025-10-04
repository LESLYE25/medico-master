<?php
session_start();
if ($_SESSION['rol_id'] != 3) {
    header("Location: login.php");
    exit();
}
echo "<h1>Bienvenido Paciente, " . $_SESSION['usuario_nombre'] . "</h1>";
?>
