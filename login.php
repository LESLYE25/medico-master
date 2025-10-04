<?php
session_start();

// Configuración de conexión
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "sistema_citas";

$conn = new mysqli($servername, $username, $password_db, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash MD5

    $sql = "SELECT * FROM usuarios WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        $_SESSION['rol_id'] = $user['rol_id'];

        // Redirigir según el rol
        switch ($user['rol_id']) {
            case 1:
                header("Location: admin_dashboard.php");
                break;
            case 2:
                header("Location: doctor_dashboard.php");
                break;
            case 3:
            default:
                header("Location: index1.html");
                break;
        }
        exit();
    } else {
        echo "<script>alert('Correo o contraseña incorrectos'); window.history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Citas Médicas - Login</title>
    <link rel="icon" href="img/favicon.png">
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
    
    .medical-container {
      max-width: 420px;
      width: 100%;
      margin: 0 auto;
    }
    
    .medical-card {
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(66, 133, 244, 0.15);
      overflow: hidden;
      border: none;
    }
    
    .medical-header {
      background: linear-gradient(to right, var(--primary-blue), var(--secondary-blue));
      color: white;
      padding: 25px 20px;
      text-align: center;
      position: relative;
    }
    
    .medical-header::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--accent-green);
    }
    
    .medical-icon {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }
    
    .medical-title {
      font-weight: 600;
      font-size: 1.5rem;
      margin-bottom: 5px;
    }
    
    .medical-subtitle {
      font-size: 0.9rem;
      opacity: 0.9;
    }
    
    .medical-body {
      padding: 30px;
    }
    
    .form-label {
      font-weight: 500;
      color: #444;
      margin-bottom: 8px;
    }
    
    .form-control {
      padding: 12px 15px;
      border-radius: 8px;
      border: 1px solid #e0e0e0;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--secondary-blue);
      box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.2);
    }
    
    .btn-medical {
      background: linear-gradient(to right, var(--primary-blue), var(--secondary-blue));
      border: none;
      padding: 12px;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s;
    }
    
    .btn-medical:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(66, 133, 244, 0.4);
    }
    
    .medical-footer {
      text-align: center;
      padding: 20px;
      border-top: 1px solid #f0f0f0;
      background-color: var(--medical-white);
    }
    
    .medical-link {
      color: var(--primary-blue);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }
    
    .medical-link:hover {
      color: var(--dark-blue);
      text-decoration: underline;
    }
    
    .medical-features {
      display: flex;
      justify-content: space-around;
      margin-top: 20px;
      padding: 15px;
      background-color: var(--medical-white);
      border-radius: 10px;
      font-size: 0.85rem;
    }
    
    .feature-item {
      text-align: center;
      color: #555;
    }
    
    .feature-icon {
      color: var(--primary-blue);
      font-size: 1.2rem;
      margin-bottom: 5px;
    }
    
    @media (max-width: 576px) {
      .medical-container {
        padding: 0 15px;
      }
      
      .medical-body {
        padding: 25px 20px;
      }
    }
    </style>
</head>

<body>
    <div class="medical-container">
        <div class="medical-card">
            <div class="medical-header">
                <h3 class="medical-title">Sistema de Citas Médicas</h3>
                <p class="medical-subtitle">Acceso seguro al portal de pacientes</p>
            </div>

            <div class="medical-body">
                <form id="loginForm" action="login.php" method="POST">
                    <div class="mb-4">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-medical">Ingresar al Sistema</button>
                    </div>
                </form>


                <div class="medical-features">
                    <div class="feature-item">
                        <div class="feature-icon">📅</div>
                        <div>Reserva de Citas</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">👨‍⚕️</div>
                        <div>Especialistas</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <div>Seguro</div>
                    </div>
                </div>
            </div>



            <div class="medical-footer">
                <p>¿No tienes cuenta? <a href="register.php" class="medical-link" onclick="showRegister()">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</body>

</html>