<?php
// admin_panel.php
session_start();

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Configuración de la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "sistema_citas";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener estadísticas
$stats = [];
$stats['total_citas'] = $conn->query("SELECT COUNT(*) FROM citas")->fetchColumn();
$stats['citas_pendientes'] = $conn->query("SELECT COUNT(*) FROM citas WHERE estado = 'pendiente'")->fetchColumn();
$stats['total_medicos'] = $conn->query("SELECT COUNT(*) FROM medicos")->fetchColumn();
$stats['total_pacientes'] = $conn->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();

// Procesar acciones del CRUD
$action = $_GET['action'] ?? '';
$tabla = $_GET['tabla'] ?? 'citas';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $tabla = $_POST['tabla'] ?? 'citas';
    
    switch($tabla) {
        case 'citas':
            procesarCitas($conn, $action);
            break;
        case 'medicos':
            procesarMedicos($conn, $action);
            break;
        case 'pacientes':
            procesarPacientes($conn, $action);
            break;
        case 'especialidades':
            procesarEspecialidades($conn, $action);
            break;
    }
}

// Funciones de procesamiento
function procesarCitas($conn, $action) {
    switch($action) {
        case 'crear':
            $paciente_id = $_POST['paciente_id'];
            $medico_id = $_POST['medico_id'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $motivo = $_POST['motivo'];
            
            $sql = "INSERT INTO citas (paciente_id, medico_id, fecha, hora, estado, motivo) 
                    VALUES (?, ?, ?, ?, 'pendiente', ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$paciente_id, $medico_id, $fecha, $hora, $motivo]);
            break;
            
        case 'editar':
            $id = $_POST['id'];
            $paciente_id = $_POST['paciente_id'];
            $medico_id = $_POST['medico_id'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $estado = $_POST['estado'];
            $motivo = $_POST['motivo'];
            
            $sql = "UPDATE citas SET paciente_id=?, medico_id=?, fecha=?, hora=?, estado=?, motivo=? 
                    WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$paciente_id, $medico_id, $fecha, $hora, $estado, $motivo, $id]);
            break;
            
        case 'eliminar':
            $id = $_POST['id'];
            $sql = "DELETE FROM citas WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            break;
    }
    header("Location: admin_panel.php?tabla=citas&success=1");
    exit();
}

function procesarMedicos($conn, $action) {
    switch($action) {
        case 'crear':
            $nombre = $_POST['nombre'];
            $especialidad_id = $_POST['especialidad_id'];
            $usuario_id = $_POST['usuario_id'];
            
            $sql = "INSERT INTO medicos (nombre, especialidad_id, usuario_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $especialidad_id, $usuario_id]);
            break;
            
        case 'editar':
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $especialidad_id = $_POST['especialidad_id'];
            $usuario_id = $_POST['usuario_id'];
            
            $sql = "UPDATE medicos SET nombre=?, especialidad_id=?, usuario_id=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $especialidad_id, $usuario_id, $id]);
            break;
            
        case 'eliminar':
            $id = $_POST['id'];
            $sql = "DELETE FROM medicos WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            break;
    }
    header("Location: admin_panel.php?tabla=medicos&success=1");
    exit();
}

function procesarPacientes($conn, $action) {
    switch($action) {
        case 'crear':
            $nombre = $_POST['nombre'];
            $dni = $_POST['dni'];
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $usuario_id = $_POST['usuario_id'];
            
            $sql = "INSERT INTO pacientes (nombre, dni, telefono, correo, usuario_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $dni, $telefono, $correo, $usuario_id]);
            break;
            
        case 'editar':
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $dni = $_POST['dni'];
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $usuario_id = $_POST['usuario_id'];
            
            $sql = "UPDATE pacientes SET nombre=?, dni=?, telefono=?, correo=?, usuario_id=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $dni, $telefono, $correo, $usuario_id, $id]);
            break;
            
        case 'eliminar':
            $id = $_POST['id'];
            $sql = "DELETE FROM pacientes WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            break;
    }
    header("Location: admin_panel.php?tabla=pacientes&success=1");
    exit();
}

function procesarEspecialidades($conn, $action) {
    switch($action) {
        case 'crear':
            $nombre = $_POST['nombre'];
            $sql = "INSERT INTO especialidades (nombre) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre]);
            break;
            
        case 'editar':
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $sql = "UPDATE especialidades SET nombre=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $id]);
            break;
            
        case 'eliminar':
            $id = $_POST['id'];
            $sql = "DELETE FROM especialidades WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            break;
    }
    header("Location: admin_panel.php?tabla=especialidades&success=1");
    exit();
}

// Obtener datos para las tablas
$citas = $conn->query("
    SELECT c.*, p.nombre as paciente_nombre, m.nombre as medico_nombre, e.nombre as especialidad
    FROM citas c 
    LEFT JOIN pacientes p ON c.paciente_id = p.id 
    LEFT JOIN medicos m ON c.medico_id = m.id 
    LEFT JOIN especialidades e ON m.especialidad_id = e.id 
    ORDER BY c.fecha DESC, c.hora DESC
")->fetchAll(PDO::FETCH_ASSOC);

$medicos = $conn->query("
    SELECT m.*, e.nombre as especialidad, u.email 
    FROM medicos m 
    LEFT JOIN especialidades e ON m.especialidad_id = e.id 
    LEFT JOIN usuarios u ON m.usuario_id = u.id 
    ORDER BY m.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$pacientes = $conn->query("
    SELECT p.*, u.email 
    FROM pacientes p 
    LEFT JOIN usuarios u ON p.usuario_id = u.id 
    ORDER BY p.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$especialidades = $conn->query("SELECT * FROM especialidades ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$usuarios = $conn->query("SELECT * FROM usuarios WHERE rol_id IN (2,3) ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - MediReserva</title>
    <link rel="icon" href="img/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0065e1;
            --secondary-color: #242429;
            --accent-color: #649bff;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }
        
        .sidebar {
            background: white;
            min-height: calc(100vh - 76px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        
        .stats-card {
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--secondary-color);
            background-color: #f8f9fa;
        }
        
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-confirmada { background-color: #28a745; }
        .badge-cancelada { background-color: #dc3545; }
        .badge-atendida { background-color: #17a2b8; }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
        }
        
        .action-buttons {
            white-space: nowrap;
        }
        
        .tab-content {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index1.php">
                <i class="bi bi-heart-pulse me-2"></i>
                MediReserva - Panel Admin
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle me-1"></i>
                    Administrador
                </span>
                <a class="nav-link" href="lagout.php">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="d-flex flex-column flex-shrink-0 p-3">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $tabla == 'citas' ? 'active' : ''; ?>" 
                               href="?tabla=citas">
                                <i class="bi bi-calendar-check"></i>
                                Gestión de Citas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $tabla == 'medicos' ? 'active' : ''; ?>" 
                               href="?tabla=medicos">
                                <i class="bi bi-person-badge"></i>
                                Gestión de Médicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $tabla == 'pacientes' ? 'active' : ''; ?>" 
                               href="?tabla=pacientes">
                                <i class="bi bi-people"></i>
                                Gestión de Pacientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $tabla == 'especialidades' ? 'active' : ''; ?>" 
                               href="?tabla=especialidades">
                                <i class="bi bi-bookmark"></i>
                                Gestión de Especialidades
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ml-sm-auto p-4">
                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card stats-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $stats['total_citas']; ?></h4>
                                        <p class="mb-0">Total Citas</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stats-card bg-warning text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $stats['citas_pendientes']; ?></h4>
                                        <p class="mb-0">Citas Pendientes</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stats-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $stats['total_medicos']; ?></h4>
                                        <p class="mb-0">Médicos</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-person-badge" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stats-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $stats['total_pacientes']; ?></h4>
                                        <p class="mb-0">Pacientes</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje de éxito -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> Operación realizada correctamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Contenido de las tablas -->
                <div class="tab-content">
                    <?php if ($tabla == 'citas'): ?>
                        <!-- Gestión de Citas -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-calendar-check me-2"></i>Gestión de Citas</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCita">
                                <i class="bi bi-plus-circle me-1"></i> Nueva Cita
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Paciente</th>
                                        <th>Médico</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Estado</th>
                                        <th>Motivo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($citas as $cita): ?>
                                        <tr>
                                            <td><?php echo $cita['id']; ?></td>
                                            <td><?php echo htmlspecialchars($cita['paciente_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($cita['medico_nombre']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($cita['hora'])); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $cita['estado']; ?>">
                                                    <?php echo ucfirst($cita['estado']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                                    <?php echo htmlspecialchars($cita['motivo']); ?>
                                                </span>
                                            </td>
                                            <td class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalCita"
                                                        onclick="editarCita(<?php echo htmlspecialchars(json_encode($cita)); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?')">
                                                    <input type="hidden" name="tabla" value="citas">
                                                    <input type="hidden" name="action" value="eliminar">
                                                    <input type="hidden" name="id" value="<?php echo $cita['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php elseif ($tabla == 'medicos'): ?>
                        <!-- Gestión de Médicos -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-person-badge me-2"></i>Gestión de Médicos</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMedico">
                                <i class="bi bi-plus-circle me-1"></i> Nuevo Médico
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Especialidad</th>
                                        <th>Usuario ID</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medicos as $medico): ?>
                                        <tr>
                                            <td><?php echo $medico['id']; ?></td>
                                            <td><?php echo htmlspecialchars($medico['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($medico['especialidad']); ?></td>
                                            <td><?php echo $medico['usuario_id']; ?></td>
                                            <td><?php echo htmlspecialchars($medico['email']); ?></td>
                                            <td class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalMedico"
                                                        onclick="editarMedico(<?php echo htmlspecialchars(json_encode($medico)); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este médico?')">
                                                    <input type="hidden" name="tabla" value="medicos">
                                                    <input type="hidden" name="action" value="eliminar">
                                                    <input type="hidden" name="id" value="<?php echo $medico['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php elseif ($tabla == 'pacientes'): ?>
                        <!-- Gestión de Pacientes -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-people me-2"></i>Gestión de Pacientes</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPaciente">
                                <i class="bi bi-plus-circle me-1"></i> Nuevo Paciente
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Usuario ID</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pacientes as $paciente): ?>
                                        <tr>
                                            <td><?php echo $paciente['id']; ?></td>
                                            <td><?php echo htmlspecialchars($paciente['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($paciente['dni']); ?></td>
                                            <td><?php echo htmlspecialchars($paciente['telefono']); ?></td>
                                            <td><?php echo htmlspecialchars($paciente['correo']); ?></td>
                                            <td><?php echo $paciente['usuario_id']; ?></td>
                                            <td class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalPaciente"
                                                        onclick="editarPaciente(<?php echo htmlspecialchars(json_encode($paciente)); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este paciente?')">
                                                    <input type="hidden" name="tabla" value="pacientes">
                                                    <input type="hidden" name="action" value="eliminar">
                                                    <input type="hidden" name="id" value="<?php echo $paciente['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php elseif ($tabla == 'especialidades'): ?>
                        <!-- Gestión de Especialidades -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4><i class="bi bi-bookmark me-2"></i>Gestión de Especialidades</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEspecialidad">
                                <i class="bi bi-plus-circle me-1"></i> Nueva Especialidad
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($especialidades as $especialidad): ?>
                                        <tr>
                                            <td><?php echo $especialidad['id']; ?></td>
                                            <td><?php echo htmlspecialchars($especialidad['nombre']); ?></td>
                                            <td class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEspecialidad"
                                                        onclick="editarEspecialidad(<?php echo htmlspecialchars(json_encode($especialidad)); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta especialidad?')">
                                                    <input type="hidden" name="tabla" value="especialidades">
                                                    <input type="hidden" name="action" value="eliminar">
                                                    <input type="hidden" name="id" value="<?php echo $especialidad['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales (se muestran al final del body) -->
    <?php include 'modales_admin.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Funciones para editar
        function editarCita(cita) {
            document.getElementById('citaId').value = cita.id;
            document.getElementById('citaPaciente').value = cita.paciente_id;
            document.getElementById('citaMedico').value = cita.medico_id;
            document.getElementById('citaFecha').value = cita.fecha;
            document.getElementById('citaHora').value = cita.hora.substring(0,5);
            document.getElementById('citaEstado').value = cita.estado;
            document.getElementById('citaMotivo').value = cita.motivo;
            document.getElementById('citaAction').value = 'editar';
            document.getElementById('modalCitaTitle').textContent = 'Editar Cita';
        }

        function editarMedico(medico) {
            document.getElementById('medicoId').value = medico.id;
            document.getElementById('medicoNombre').value = medico.nombre;
            document.getElementById('medicoEspecialidad').value = medico.especialidad_id;
            document.getElementById('medicoUsuario').value = medico.usuario_id;
            document.getElementById('medicoAction').value = 'editar';
            document.getElementById('modalMedicoTitle').textContent = 'Editar Médico';
        }

        function editarPaciente(paciente) {
            document.getElementById('pacienteId').value = paciente.id;
            document.getElementById('pacienteNombre').value = paciente.nombre;
            document.getElementById('pacienteDni').value = paciente.dni;
            document.getElementById('pacienteTelefono').value = paciente.telefono;
            document.getElementById('pacienteCorreo').value = paciente.correo;
            document.getElementById('pacienteUsuario').value = paciente.usuario_id;
            document.getElementById('pacienteAction').value = 'editar';
            document.getElementById('modalPacienteTitle').textContent = 'Editar Paciente';
        }

        function editarEspecialidad(especialidad) {
            document.getElementById('especialidadId').value = especialidad.id;
            document.getElementById('especialidadNombre').value = especialidad.nombre;
            document.getElementById('especialidadAction').value = 'editar';
            document.getElementById('modalEspecialidadTitle').textContent = 'Editar Especialidad';
        }

        // Limpiar formularios al cerrar modales
        document.addEventListener('DOMContentLoaded', function() {
            var modales = ['modalCita', 'modalMedico', 'modalPaciente', 'modalEspecialidad'];
            modales.forEach(function(modalId) {
                var modal = document.getElementById(modalId);
                modal.addEventListener('hidden.bs.modal', function () {
                    var form = this.querySelector('form');
                    if (form) {
                        form.reset();
                        form.querySelector('input[name="action"]').value = 'crear';
                        var title = this.querySelector('.modal-title');
                        if (title) title.textContent = title.textContent.replace('Editar', 'Nueva').replace('Nuevo', 'Nueva');
                    }
                });
            });
        });
    </script>
</body>
</html>