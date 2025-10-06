<?php
session_start();
include("conexion.php");

// Obtener especialidades desde la base
$especialidades = $conn->query("SELECT * FROM especialidades");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST['nombre']);
    $correo   = trim($_POST['correo']);
    $password = $_POST['password'];
    $rol      = $_POST['rol'];
    $especialidad_id = isset($_POST['especialidad']) ? intval($_POST['especialidad']) : null;
    
    // Campos adicionales para pacientes
    $dni = isset($_POST['dni']) ? trim($_POST['dni']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';

    // --- ⚙️ Ajuste del rol a su ID correspondiente ---
    switch ($rol) {
        case 'admin':
            $rol_id = 1;
            break;
        case 'medico':
            $rol_id = 2;
            break;
        default:
            $rol_id = 3; // paciente
            break;
    }

    // --- 🔍 Verificar si el correo ya existe ---
    $check = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $check->bind_param("s", $correo);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('⚠️ El correo ya está registrado.'); window.history.back();</script>";
    } else {
        // --- 🧠 Hash de la contraseña ---
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // --- 📝 Registrar usuario ---
        $insert = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
        $insert->bind_param("sssi", $nombre, $correo, $hashed_password, $rol_id);

        if ($insert->execute()) {
            $usuario_id = $conn->insert_id;

            // --- 🩺 Si es médico, registrar en tabla medicos ---
            if ($rol === 'medico' && $especialidad_id) {
                $insert_medico = $conn->prepare("INSERT INTO medicos (nombre, usuario_id, especialidad_id) VALUES (?, ?, ?)");
                $insert_medico->bind_param("sii", $nombre, $usuario_id, $especialidad_id);
                if ($insert_medico->execute()) {
                    echo "<script>console.log('Médico registrado con ID: " . $conn->insert_id . "');</script>";
                }
            }
            
            // --- 👤 Si es paciente, registrar en tabla pacientes ---
            if ($rol === 'paciente') {
                // Generar DNI automático si no se proporcionó
                if (empty($dni)) {
                    $dni = date('Ymd') . rand(1000, 9999);
                }
                
                $insert_paciente = $conn->prepare("INSERT INTO pacientes (nombre, dni, telefono, correo, usuario_id) VALUES (?, ?, ?, ?, ?)");
                $insert_paciente->bind_param("ssssi", $nombre, $dni, $telefono, $correo, $usuario_id);
                if ($insert_paciente->execute()) {
                    echo "<script>console.log('Paciente registrado con ID: " . $conn->insert_id . "');</script>";
                }
            }

            echo "<script>
                    alert('✅ Registro exitoso. Ahora puedes iniciar sesión.'); 
                    window.location.href='login.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('❌ Error al registrar el usuario: " . $conn->error . "');</script>";
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
  <link rel="icon" href="img/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
    .medical-container { max-width: 480px; width: 100%; margin: 0 auto; }
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
    .dynamic-field { display: none; }
  </style>
</head>
<body>
  <div class="medical-container">
    <div class="medical-card">
      <div class="medical-header">
        <div class="medical-title">
          <i class="bi bi-heart-pulse me-2"></i>
          Registro
        </div>
        <div class="medical-subtitle">Crea tu cuenta</div>
      </div>
      <div class="medical-body">
        <form method="POST" action="">
          <div class="mb-4">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
          </div>
          <div class="mb-4">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="correo" id="correo" required>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="password" id="password" required minlength="6">
          </div>
          <div class="mb-4">
            <label for="rol" class="form-label">Selecciona tu rol</label>
            <select class="form-control" name="rol" id="rol" required>
              <option value="">Selecciona un rol</option>
              <option value="paciente">Paciente</option>
              <option value="medico">Médico</option>
              <option value="admin">Administrador</option>
            </select>
          </div>

          <!-- Campos para PACIENTES -->
          <div class="dynamic-field" id="paciente-fields">
            <div class="mb-4">
              <label for="dni" class="form-label">DNI (Opcional)</label>
              <input type="text" class="form-control" name="dni" id="dni" placeholder="Si no ingresas DNI, se generará automáticamente">
            </div>
            <div class="mb-4">
              <label for="telefono" class="form-label">Teléfono (Opcional)</label>
              <input type="tel" class="form-control" name="telefono" id="telefono" placeholder="Número de contacto">
            </div>
          </div>

          <!-- Campo para MÉDICOS -->
          <div class="dynamic-field" id="medico-fields">
            <div class="mb-4">
              <label for="especialidad" class="form-label">Especialidad médica</label>
              <select class="form-control" name="especialidad" id="especialidad">
                <option value="">Selecciona una especialidad</option>
                <?php 
                if ($especialidades->num_rows > 0) {
                    // Reiniciar el puntero del resultado
                    $especialidades->data_seek(0);
                    while ($esp = $especialidades->fetch_assoc()): 
                ?>
                  <option value="<?= $esp['id'] ?>"><?= $esp['nombre'] ?></option>
                <?php 
                    endwhile;
                } else {
                    echo "<option value=''>No hay especialidades disponibles</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-medical">
              <i class="bi bi-person-plus me-2"></i>Registrarse
            </button>
          </div>
        </form>
      </div>
      <div class="medical-footer">
        <p>¿Ya tienes cuenta? <a href="login.php" class="medical-link">Inicia sesión aquí</a></p>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('rol').addEventListener('change', function () {
      const rol = this.value;
      const pacienteFields = document.getElementById('paciente-fields');
      const medicoFields = document.getElementById('medico-fields');
      
      // Ocultar todos los campos primero
      pacienteFields.style.display = 'none';
      medicoFields.style.display = 'none';
      
      // Mostrar campos según el rol seleccionado
      if (rol === 'paciente') {
        pacienteFields.style.display = 'block';
      } else if (rol === 'medico') {
        medicoFields.style.display = 'block';
      }
    });

    // Validación del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
      const rol = document.getElementById('rol').value;
      const especialidad = document.getElementById('especialidad').value;
      
      if (rol === 'medico' && !especialidad) {
        e.preventDefault();
        alert('⚠️ Los médicos deben seleccionar una especialidad.');
        return false;
      }
    });
  </script>
</body>
</html>