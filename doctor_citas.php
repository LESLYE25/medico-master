<?php
// doctor_citas.php
session_start();

// Verificar si el usuario está logueado y es doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 2) {
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

// Obtener datos del doctor usando su usuario_id
$usuario_id = $_SESSION['usuario_id'];
$sql_doctor = "SELECT m.id, m.nombre, e.nombre as especialidad 
               FROM medicos m 
               JOIN especialidades e ON m.especialidad_id = e.id 
               WHERE m.usuario_id = ?";
$stmt_doctor = $conn->prepare($sql_doctor);
$stmt_doctor->execute([$usuario_id]);
$doctor = $stmt_doctor->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    die("Doctor no encontrado en la base de datos. Usuario ID: " . $usuario_id);
}

// Obtener citas del doctor
$estado = $_GET['estado'] ?? 'todas';
$fecha = $_GET['fecha'] ?? '';

$sql_citas = "SELECT c.id, c.fecha, c.hora, c.estado, c.motivo, 
                     p.nombre as paciente_nombre, p.dni, p.telefono, p.correo
              FROM citas c 
              JOIN pacientes p ON c.paciente_id = p.id 
              WHERE c.medico_id = ?";
$params = [$doctor['id']];

if ($estado != 'todas') {
    $sql_citas .= " AND c.estado = ?";
    $params[] = $estado;
}

if ($fecha) {
    $sql_citas .= " AND c.fecha = ?";
    $params[] = $fecha;
}

$sql_citas .= " ORDER BY c.fecha DESC, c.hora DESC";

$stmt_citas = $conn->prepare($sql_citas);
$stmt_citas->execute($params);
$citas = $stmt_citas->fetchAll(PDO::FETCH_ASSOC);

// Procesar acciones (confirmar, cancelar, atender)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $cita_id = $_POST['cita_id'] ?? '';
    
    if ($cita_id && in_array($action, ['confirmar', 'cancelar', 'atender'])) {
        $nuevo_estado = '';
        switch ($action) {
            case 'confirmar': $nuevo_estado = 'confirmada'; break;
            case 'cancelar': $nuevo_estado = 'cancelada'; break;
            case 'atender': $nuevo_estado = 'atendida'; break;
        }
        
        $sql_update = "UPDATE citas SET estado = ? WHERE id = ? AND medico_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        if ($stmt_update->execute([$nuevo_estado, $cita_id, $doctor['id']])) {
            header("Location: doctor_citas.php?success=1");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas - MediReserva</title>
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
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-confirmada { background-color: #28a745; }
        .badge-cancelada { background-color: #dc3545; }
        .badge-atendida { background-color: #17a2b8; }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .doctor-header {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .filters {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--secondary-color);
            background-color: #f8f9fa;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 101, 225, 0.05);
        }
        
        .today-row {
            background-color: rgba(255, 193, 7, 0.1) !important;
            border-left: 3px solid #ffc107;
        }
        
        .action-buttons {
            white-space: nowrap;
        }
        
        .estado-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.7rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index1.php">
                <i class="bi bi-heart-pulse me-2"></i>
                MediReserva - Panel Doctor
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle me-1"></i>
                    <?php echo htmlspecialchars($doctor['nombre']); ?>
                </span>
                <a class="nav-link" href="lagout.php">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header del Doctor -->
        <div class="doctor-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-1"><?php echo htmlspecialchars($doctor['nombre']); ?></h2>
                    <p class="text-muted mb-0">Especialidad: <?php echo htmlspecialchars($doctor['especialidad']); ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-2">
                            <h5 class="mb-0"><?php echo count($citas); ?></h5>
                            <small>Citas <?php echo $estado != 'todas' ? $estado : 'totales'; ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de Estados -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="bg-warning rounded-circle p-2 me-3">
                                        <i class="bi bi-clock text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-warning"><?php echo contarCitasPorEstado($citas, 'pendiente'); ?></h4>
                                        <small class="text-muted">Pendientes</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="bg-success rounded-circle p-2 me-3">
                                        <i class="bi bi-check-circle text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-success"><?php echo contarCitasPorEstado($citas, 'confirmada'); ?></h4>
                                        <small class="text-muted">Confirmadas</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="bg-info rounded-circle p-2 me-3">
                                        <i class="bi bi-person-check text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-info"><?php echo contarCitasPorEstado($citas, 'atendida'); ?></h4>
                                        <small class="text-muted">Atendidas</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="bg-danger rounded-circle p-2 me-3">
                                        <i class="bi bi-x-circle text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-danger"><?php echo contarCitasPorEstado($citas, 'cancelada'); ?></h4>
                                        <small class="text-muted">Canceladas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filters">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" 
                           value="<?php echo htmlspecialchars($fecha); ?>">
                </div>
                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="todas" <?php echo $estado == 'todas' ? 'selected' : ''; ?>>Todas las citas</option>
                        <option value="pendiente" <?php echo $estado == 'pendiente' ? 'selected' : ''; ?>>Pendientes</option>
                        <option value="confirmada" <?php echo $estado == 'confirmada' ? 'selected' : ''; ?>>Confirmadas</option>
                        <option value="atendida" <?php echo $estado == 'atendida' ? 'selected' : ''; ?>>Atendidas</option>
                        <option value="cancelada" <?php echo $estado == 'cancelada' ? 'selected' : ''; ?>>Canceladas</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                    <a href="doctor_citas.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Mensaje de éxito -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> Estado de la cita actualizado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tabla de Citas -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Lista de Citas</h4>
                <span class="text-muted">Total: <?php echo count($citas); ?> citas</span>
            </div>
            
            <?php if (empty($citas)): ?>
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h5>No hay citas programadas</h5>
                    <p class="text-muted">No se encontraron citas con los filtros seleccionados.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>DNI</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Motivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($citas as $cita): ?>
                                <?php 
                                $esHoy = $cita['fecha'] == date('Y-m-d');
                                $claseFila = $esHoy ? 'today-row' : '';
                                ?>
                                <tr class="<?php echo $claseFila; ?>">
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($cita['paciente_nombre']); ?></strong>
                                            <?php if ($esHoy): ?>
                                                <span class="badge bg-warning ms-1">Hoy</span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted"><?php echo htmlspecialchars($cita['telefono']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($cita['dni']); ?></td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($cita['fecha'])); ?>
                                    </td>
                                    <td>
                                        <strong><?php echo date('H:i', strtotime($cita['hora'])); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge estado-badge badge-<?php echo $cita['estado']; ?>">
                                            <?php echo ucfirst($cita['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                              title="<?php echo htmlspecialchars($cita['motivo']); ?>">
                                            <?php echo htmlspecialchars($cita['motivo']); ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <!-- Botones de acción según el estado -->
                                        <?php if ($cita['estado'] == 'pendiente'): ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="cita_id" value="<?php echo $cita['id']; ?>">
                                                <button type="submit" name="action" value="confirmar" 
                                                        class="btn btn-success btn-sm mb-1">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                                <button type="submit" name="action" value="cancelar" 
                                                        class="btn btn-danger btn-sm mb-1">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        <?php elseif ($cita['estado'] == 'confirmada'): ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="cita_id" value="<?php echo $cita['id']; ?>">
                                                <button type="submit" name="action" value="atender" 
                                                        class="btn btn-info btn-sm mb-1">
                                                    <i class="bi bi-person-check"></i>
                                                </button>
                                                <button type="submit" name="action" value="cancelar" 
                                                        class="btn btn-danger btn-sm mb-1">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <!-- Botón para ver detalles -->
                                        <button class="btn btn-outline-primary btn-sm mb-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detalleCita<?php echo $cita['id']; ?>"
                                                title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal de Detalles -->
                                <div class="modal fade" id="detalleCita<?php echo $cita['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-calendar2-check me-2"></i>
                                                    Detalles de la Cita
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-4">
                                                        <div class="card border-0 bg-light">
                                                            <div class="card-body">
                                                                <h6 class="card-title text-primary mb-3">
                                                                    <i class="bi bi-person-circle me-2"></i>
                                                                    Información del Paciente
                                                                </h6>
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <div class="bg-primary rounded-circle p-2 me-3">
                                                                        <i class="bi bi-person text-white"></i>
                                                                    </div>
                                                                    <div>
                                                                        <strong class="d-block"><?php echo htmlspecialchars($cita['paciente_nombre']); ?></strong>
                                                                        <small class="text-muted">Paciente</small>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="bi bi-card-text text-muted me-2" style="width: 20px;"></i>
                                                                    <span><strong>DNI:</strong> <?php echo htmlspecialchars($cita['dni']); ?></span>
                                                                </div>
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="bi bi-telephone text-muted me-2" style="width: 20px;"></i>
                                                                    <span><strong>Teléfono:</strong> <?php echo htmlspecialchars($cita['telefono']); ?></span>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="bi bi-envelope text-muted me-2" style="width: 20px;"></i>
                                                                    <span><strong>Email:</strong> <?php echo htmlspecialchars($cita['correo']); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-4">
                                                        <div class="card border-0 bg-light">
                                                            <div class="card-body">
                                                                <h6 class="card-title text-primary mb-3">
                                                                    <i class="bi bi-calendar-event me-2"></i>
                                                                    Información de la Cita
                                                                </h6>
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <div class="bg-success rounded-circle p-2 me-3">
                                                                        <i class="bi bi-calendar-date text-white"></i>
                                                                    </div>
                                                                    <div>
                                                                        <strong class="d-block"><?php echo date('d/m/Y', strtotime($cita['fecha'])); ?></strong>
                                                                        <small class="text-muted">Fecha</small>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <div class="bg-info rounded-circle p-2 me-3">
                                                                        <i class="bi bi-clock text-white"></i>
                                                                    </div>
                                                                    <div>
                                                                        <strong class="d-block"><?php echo date('H:i', strtotime($cita['hora'])); ?> hrs</strong>
                                                                        <small class="text-muted">Hora</small>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="rounded-circle p-2 me-3 
                                                                        <?php 
                                                                        switch($cita['estado']) {
                                                                            case 'pendiente': echo 'bg-warning'; break;
                                                                            case 'confirmada': echo 'bg-success'; break;
                                                                            case 'atendida': echo 'bg-info'; break;
                                                                            case 'cancelada': echo 'bg-danger'; break;
                                                                            default: echo 'bg-secondary';
                                                                        }
                                                                        ?>">
                                                                        <i class="bi bi-circle-fill text-white"></i>
                                                                    </div>
                                                                    <div>
                                                                        <strong class="d-block text-capitalize"><?php echo $cita['estado']; ?></strong>
                                                                        <small class="text-muted">Estado</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Motivo de la Consulta -->
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card border-0 bg-light">
                                                            <div class="card-body">
                                                                <h6 class="card-title text-primary mb-3">
                                                                    <i class="bi bi-chat-left-text me-2"></i>
                                                                    Motivo de la Consulta
                                                                </h6>
                                                                <div class="bg-white rounded p-3 border">
                                                                    <p class="mb-0"><?php echo htmlspecialchars($cita['motivo']); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle me-1"></i> Cerrar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Función para contar citas por estado
function contarCitasPorEstado($citas, $estado) {
    $contador = 0;
    foreach ($citas as $cita) {
        if ($cita['estado'] == $estado) {
            $contador++;
        }
    }
    return $contador;
}
?>