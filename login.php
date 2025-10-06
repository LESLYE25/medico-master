<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// Configuración de la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistema_citas";

// Conexión a MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validar campos vacíos
    if (empty($email) || empty($password)) {
        echo "<script>alert('Por favor ingresa tu correo y contraseña.'); window.history.back();</script>";
        exit();
    }

    // Buscar usuario por email y contraseña (texto plano)
    $stmt = $conn->prepare("SELECT id, nombre, rol_id FROM usuarios WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Iniciar sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol_id'] = $usuario['rol_id'];

        // Redirigir según el rol
        switch ($usuario['rol_id']) {
            case 1:
                header("Location: admin_panel.php");
                break;
            case 2:
                header("Location: doctor_citas.php");
                break;
            case 3:
            default:
                header("Location: index1.php");
                break;
        }
        exit();
    } else {
        echo "<script>alert('Correo o contraseña incorrectos.'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
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

        .medical-title {
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 5px;
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
            color: white;
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
    </style>
</head>

<body>
    <div class="medical-container">
        <div class="medical-card">
            <div class="medical-header">
                <h3 class="medical-title">Sistema de Citas Médicas</h3>
                <p class="medical-subtitle">Acceso seguro al portal</p>
            </div>

            <div class="medical-body">
                <form action="login.php" method="POST">
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
            </div>

            <div class="medical-footer">
                <p>¿No tienes cuenta? <a href="register.php" class="medical-link">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</body>

</html>