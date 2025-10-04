<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = $_POST['nombre'];
    $email    = $_POST['email'];
    $password = md5($_POST['password']); // ⚠️ MD5 no es seguro en producción, solo para este ejemplo
    
    // Verificar si el correo ya existe
    $check = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($check);
    
    if ($result->num_rows > 0) {
        echo "<script>alert('El correo ya está registrado. Intenta con otro.'); window.history.back();</script>";
    } else {
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['usuario'] = $nombre;
            header("Location: login.php");
            exit();
        } else {
            echo "<script>alert('Error al registrar el usuario.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Citas Médicas - Registro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-blue: #1a73e8;
      --secondary-blue: #4285f4;
      --light-blue: #e8f0fe;
      --dark-blue: #0d47a1;
      --accent-green: #34a853;
      --medical-white: #f8fdff;
    }
    body {
      background: linear-gradient(135deg, var(--light-blue) 0%, #ffffff 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
    }
    .medical-container { max-width: 450px; width: 100%; margin: 0 auto; }
    .medical-card { background-color: white; border-radius: 16px; box-shadow: 0 10px 30px rgba(66, 133, 244, 0.15); overflow: hidden; border: none; }
    .medical-header { background: linear-gradient(to right, var(--primary-blue), var(--secondary-blue)); color: white; padding: 25px 20px; text-align: center; position: relative; }
    .medical-header::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--accent-green); }
    .medical-title { font-weight: 600; font-size: 1.5rem; margin-bottom: 5px; }
    .medical-body { padding: 30px; }
    .form-label { font-weight: 500; color: #444; margin-bottom: 8px; }
    .form-control { padding: 12px 15px; border-radius: 8px; border: 1px solid #e0e0e0; transition: all 0.3s; }
    .form-control:focus { border-color: var(--secondary-blue); box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.2); }
    .btn-medical { background: linear-gradient(to right, var(--primary-blue), var(--secondary-blue)); border: none; padding: 12px; font-weight: 600; border-radius: 8px; transition: all 0.3s; color: white; }
    .btn-medical:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(66, 133, 244, 0.4); }
    .medical-footer { text-align: center; padding: 20px; border-top: 1px solid #f0f0f0; background-color: var(--medical-white); }
    .medical-link { color: var(--primary-blue); text-decoration: none; font-weight: 500; transition: color 0.3s; }
    .medical-link:hover { color: var(--dark-blue); text-decoration: underline; }
  </style>
</head>
<body>
  <div class="medical-container">
    <div class="medical-card">
      <div class="medical-header">
        <div class="medical-title">Registro</div>
        <div class="medical-subtitle">Crea tu cuenta</div>
      </div>
      <div class="medical-body">
        <form method="POST" action="">
          <div class="mb-4">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
          </div>
          <div class="mb-4">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="email" id="email" required>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="password" id="password" required>
          </div>
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-medical">Registrarse</button>
          </div>
        </form>
      </div>
      <div class="medical-footer">
        <p>¿Ya tienes cuenta? <a href="login.php" class="medical-link">Inicia sesión aquí</a></p>
      </div>
    </div>
  </div>
</body>
</html>
