<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistema_citas";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
    exit();
}

// Configurar charset
$conn->set_charset("utf8mb4");

// Manejar preflight para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$action = $_GET['action'] ?? '';

// Log para debugging (puedes remover esto después)
error_log("API Action: " . $action);

switch($action) {
    case 'get_specialties':
        getSpecialties($conn);
        break;
    case 'get_doctors':
        getDoctors($conn);
        break;
    case 'get_booked_slots':
        getBookedSlots($conn);
        break;
    case 'create_appointment':
        createAppointment($conn);
        break;
    case 'test_connection':
        testConnection($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida: ' . $action]);
}

function getSpecialties($conn) {
    $sql = "SELECT id, nombre FROM especialidades";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $specialties = [];
        while($row = $result->fetch_assoc()) {
            $specialties[] = $row;
        }
        echo json_encode(['success' => true, 'specialties' => $specialties]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron especialidades', 'error' => $conn->error]);
    }
}

function getDoctors($conn) {
    $specialtyId = $_GET['specialty_id'] ?? '';
    
    if (empty($specialtyId)) {
        echo json_encode(['success' => false, 'message' => 'ID de especialidad requerido']);
        return;
    }
    
    // Primero verifiquemos que la especialidad existe
    $checkSql = "SELECT id FROM especialidades WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $specialtyId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Especialidad no encontrada']);
        return;
    }
    
    $sql = "SELECT id, nombre FROM medicos WHERE especialidad_id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $conn->error]);
        return;
    }
    
    $stmt->bind_param("i", $specialtyId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $doctors = [];
    while($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
    
    echo json_encode(['success' => true, 'doctors' => $doctors]);
}

function getBookedSlots($conn) {
    $doctorId = $_GET['doctor_id'] ?? '';
    $date = $_GET['date'] ?? '';
    
    if (empty($doctorId) || empty($date)) {
        echo json_encode(['success' => false, 'message' => 'ID de médico y fecha requeridos']);
        return;
    }
    
    $sql = "SELECT TIME_FORMAT(hora, '%H:%i') as hora FROM citas WHERE medico_id = ? AND fecha = ? AND estado != 'cancelada'";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $conn->error]);
        return;
    }
    
    $stmt->bind_param("is", $doctorId, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookedSlots = [];
    while($row = $result->fetch_assoc()) {
        $bookedSlots[] = $row['hora'];
    }
    
    echo json_encode(['success' => true, 'booked_slots' => $bookedSlots]);
}

function createAppointment($conn) {
    // Para debugging
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Error en el formato JSON: ' . json_last_error_msg()]);
        return;
    }
    
    $userId = $input['usuario_id'] ?? '';
    $doctorId = $input['medico_id'] ?? '';
    $date = $input['fecha'] ?? '';
    $time = $input['hora'] ?? '';
    $status = $input['estado'] ?? 'pendiente';
    
    // Validaciones
    if (empty($userId) || empty($doctorId) || empty($date) || empty($time)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos', 'data' => $input]);
        return;
    }
    
    // Verificar que el usuario existe
    $checkUser = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
    $checkUser->bind_param("i", $userId);
    $checkUser->execute();
    
    if ($checkUser->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        return;
    }
    
    // Verificar que el médico existe
    $checkDoctor = $conn->prepare("SELECT id FROM medicos WHERE id = ?");
    $checkDoctor->bind_param("i", $doctorId);
    $checkDoctor->execute();
    
    if ($checkDoctor->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Médico no encontrado']);
        return;
    }
    
    // Check if the slot is already booked
    $checkSql = "SELECT id FROM citas WHERE medico_id = ? AND fecha = ? AND hora = ? AND estado != 'cancelada'";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("iss", $doctorId, $date, $time);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El horario seleccionado ya está ocupado']);
        return;
    }
    
    // Insert new appointment
    $sql = "INSERT INTO citas (usuario_id, medico_id, fecha, hora, estado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
        return;
    }
    
    $stmt->bind_param("iisss", $userId, $doctorId, $date, $time, $status);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'appointment_id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agendar la cita: ' . $stmt->error]);
    }
}

function testConnection($conn) {
    if ($conn->ping()) {
        echo json_encode(['success' => true, 'message' => 'Conexión a BD exitosa']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la conexión: ' . $conn->error]);
    }
}

function getDoctorsWithImages($conn) {
    $specialtyId = $_GET['specialty_id'] ?? '';
    
    if (empty($specialtyId)) {
        echo json_encode(['success' => false, 'message' => 'ID de especialidad requerido']);
        return;
    }
    
    // Consulta que incluye la imagen del médico
    $sql = "SELECT id, nombre, imagen, especialidad FROM medicos WHERE especialidad_id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $conn->error]);
        return;
    }
    
    $stmt->bind_param("i", $specialtyId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $doctors = [];
    while($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
    
    echo json_encode(['success' => true, 'doctors' => $doctors]);
}

// Cerrar conexión
$conn->close();
?>