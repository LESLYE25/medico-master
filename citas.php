<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MediReserva</title>
    <link rel="icon" href="img/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
    <style>
        :root {
            --primary-color: #0065e1;
            --secondary-color: #242429;
            --accent-color: #649bff;
            --light-color: #ffffff;   /* estaba #f9f9ff */
            --text-color: #666666;
                }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;  /* ← blanco */
            color: var(--text-color);
            line-height: 1.929;
        }

        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            color: var(--secondary-color);
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--secondary-color) !important;
            font-family: 'Playfair Display', serif;
        }
        
        .btn_1 {
            display: inline-block;
            padding: 10.5px 36px;
            font-size: 14px;
            color: #000;
            -o-transition: all .4s ease-in-out;
            -webkit-transition: all .4s ease-in-out;
            transition: all .4s ease-in-out;
            text-transform: capitalize;
            border: 1px solid #e4e6ea;
            border-radius: 2px;
            font-family: "Playfair Display", serif;
            background: linear-gradient(to right, #649bff, #0070fa, #649bff);
            background-size: 200% auto;
            color: white;
        }
        
        .btn_1:hover {
            background-color: #0065e1 !important;
            color: #fff;
            background-position: right center;
        }
        
        .btn_2 {
            display: inline-block;
            padding: 16px 45px;
            text-align: center;
            font-size: 14px;
            color: #fff;
            -o-transition: all .4s ease-in-out;
            -webkit-transition: all .4s ease-in-out;
            transition: all .4s ease-in-out;
            text-transform: uppercase;
            border-radius: 5px;
            font-family: "Roboto", sans-serif;
            background: linear-gradient(to right, #649bff, #0070fa, #649bff);
            background-size: 200% auto;
            display: inline-block;
        }
        
        .btn_2:hover {
            color: #fff;
            background-position: right center;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0px 10px 20px 0px rgba(221, 221, 221, 0.3);
            transition: transform 0.3s;
            border: 1px solid #f0e9ff;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .calendar-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 10px 20px 0px rgba(221, 221, 221, 0.3);
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: var(--light-color);
            padding: 10px 0;
            border-radius: 5px;
        }
        
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        
        .calendar-day {
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        
        .calendar-day:hover {
            background-color: var(--light-color);
            border-color: var(--accent-color);
        }
        
        .calendar-day.selected {
            background-color: var(--primary-color);
            color: white;
        }
        
        .calendar-day.disabled {
            color: #ccc;
            cursor: not-allowed;
            background-color: #f5f5f5;
        }
        
        .time-slot {
            border: 1px solid #e4e6ea;
            border-radius: 5px;
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            flex: 1 0 30%;
            max-width: 30%;
            background-color: white;
        }
        
        .time-slot:hover {
            background-color: var(--light-color);
            border-color: var(--accent-color);
        }
        
        .time-slot.selected {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .time-slot.unavailable {
            background-color: #f8d7da;
            color: #721c24;
            cursor: not-allowed;
            border-color: #f5c6cb;
        }
        
        .section-title {
            color: var(--secondary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
            margin-bottom: 30px;
            font-size: 36px;
            font-weight: 700;
        }
        
        .doctor-card {
            text-align: center;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .doctor-card:hover {
            transform: translateY(-5px);
        }
        
        .doctor-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            border: 3px solid var(--primary-color);
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .step-indicator:before {
            content: '';
            position: absolute;
            top: 20px;
            left: 10%;
            right: 10%;
            height: 2px;
            background-color: #e4e6ea;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 20px;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 10px;
            border: 2px solid white;
        }
        
        .step.active .step-number {
            background-color: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: var(--accent-color);
            color: white;
        }
        
        .hidden {
            display: none;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        #time-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .form-control {
            border: 1px solid #e4e6ea;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 101, 225, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }
        
        .appointment-summary {
            background-color: var(--light-color);
            border-radius: 10px;
            padding: 25px;
            border-left: 4px solid var(--primary-color);
        }
        
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .step-indicator:before {
                display: none;
            }
            
            .step {
                margin-bottom: 20px;
            }
            
            .time-slot {
                flex: 1 0 45%;
                max-width: 45%;
            }
        }
    </style>
</head>
<body>

    <!-- Main Content -->
    <div class="container my-5">
        <h2 class="section-title text-center">Agendar Cita Médica</h2>
        
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" id="step-1-indicator">
                <div class="step-number">1</div>
                <div class="step-text text-center">Especialidad & Médico</div>
            </div>
            <div class="step" id="step-2-indicator">
                <div class="step-number">2</div>
                <div class="step-text text-center">Fecha & Hora</div>
            </div>
            <div class="step" id="step-3-indicator">
                <div class="step-number">3</div>
                <div class="step-text text-center">Confirmar</div>
            </div>
        </div>
        
        <!-- Step 1: Select Specialty and Doctor -->
        <div id="step-1">
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="specialty" class="form-label">Especialidad Médica</label>
                    <select class="form-select" id="specialty">
                        <option value="" selected disabled>Selecciona una especialidad</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="doctor" class="form-label">Médico</label>
                    <select class="form-select" id="doctor" disabled>
                        <option value="" selected disabled>Primero selecciona una especialidad</option>
                    </select>
                </div>
            </div>
            
            <div id="doctors-container" class="row mb-4 hidden">
                <h4 class="mb-3">Médicos Disponibles</h4>
                <!-- Doctors will be populated here by JavaScript -->
            </div>
            
            <div class="text-end">
                <button class="btn_2" id="to-step-2" disabled>Continuar <i class="bi bi-arrow-right ms-2"></i></button>
            </div>
        </div>
        
        <!-- Step 2: Select Date and Time -->
        <div id="step-2" class="hidden">
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <h4 class="mb-3">Selecciona una Fecha</h4>
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button class="btn btn-sm btn-outline-secondary" id="prev-month">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <h4 id="current-month" class="mb-0">Mes Año</h4>
                            <button class="btn btn-sm btn-outline-secondary" id="next-month">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                        <div class="calendar-weekdays">
                            <div>Dom</div>
                            <div>Lun</div>
                            <div>Mar</div>
                            <div>Mié</div>
                            <div>Jue</div>
                            <div>Vie</div>
                            <div>Sáb</div>
                        </div>
                        <div class="calendar-days" id="calendar-days">
                            <!-- Calendar days will be generated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h4 class="mb-3">Horarios Disponibles</h4>
                    <div id="time-slots">
                        <!-- Time slots will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <button class="btn_1" id="back-to-step-1"><i class="bi bi-arrow-left me-2"></i> Atrás</button>
                <button class="btn_2" id="to-step-3" disabled>Continuar <i class="bi bi-arrow-right ms-2"></i></button>
            </div>
        </div>
        
        <!-- Step 3: Confirm Appointment -->
        <div id="step-3" class="hidden">
            <div class="appointment-summary">
                <h4 class="mb-4"><i class="bi bi-check-circle me-2"></i> Resumen de tu Cita</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Paciente:</strong> <span id="confirm-patient" class="ms-2">Usuario Demo</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Especialidad:</strong> <span id="confirm-specialty" class="ms-2">-</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Médico:</strong> <span id="confirm-doctor" class="ms-2">-</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Fecha:</strong> <span id="confirm-date" class="ms-2">-</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Hora:</strong> <span id="confirm-time" class="ms-2">-</span>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <button class="btn_1" id="back-to-step-2"><i class="bi bi-arrow-left me-2"></i> Atrás</button>
                <button class="btn_2" id="confirm-appointment"><i class="bi bi-calendar-check me-2"></i> Confirmar Cita</button>
            </div>
        </div>
        
        <!-- Success Message -->
        <div id="appointment-success" class="hidden text-center py-5">
            <div class="card border-0 shadow">
                <div class="card-body py-5">
                    <i class="bi bi-check-circle success-icon"></i>
                    <h3 class="text-success mb-3">¡Cita Agendada Exitosamente!</h3>
                    <p class="mb-4">Tu cita ha sido confirmada. Recibirás un correo electrónico con los detalles.</p>
                    <button class="btn_2" id="new-appointment"><i class="bi bi-plus-circle me-2"></i> Agendar Nueva Cita</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    
    <!-- Custom JavaScript -->
    <script>
        // Current state
        let currentStep = 1;
        let selectedSpecialty = null;
        let selectedDoctor = null;
        let selectedDate = null;
        let selectedTime = null;
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        
        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            // Load specialties from database
            loadSpecialties();
            
            // Set up appointment flow
            setupAppointmentFlow();
            
            // Generate initial calendar
            generateCalendar(currentMonth, currentYear);
        });
        
        // Load specialties from database
        function loadSpecialties() {
            fetch('api.php?action=get_specialties')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const specialtySelect = document.getElementById('specialty');
                        specialtySelect.innerHTML = '<option value="" selected disabled>Selecciona una especialidad</option>';
                        
                        data.specialties.forEach(specialty => {
                            const option = document.createElement('option');
                            option.value = specialty.id;
                            option.textContent = specialty.nombre;
                            specialtySelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading specialties:', error);
                    // Fallback to mock data if API fails
                    const specialties = [
                        { id: 1, nombre: 'Cardiología' },
                        { id: 2, nombre: 'Pediatría' },
                        { id: 3, nombre: 'Dermatología' }
                    ];
                    
                    const specialtySelect = document.getElementById('specialty');
                    specialties.forEach(specialty => {
                        const option = document.createElement('option');
                        option.value = specialty.id;
                        option.textContent = specialty.nombre;
                        specialtySelect.appendChild(option);
                    });
                });
        }
        
        // Set up the appointment flow
        function setupAppointmentFlow() {
            // Specialty selection
            const specialtySelect = document.getElementById('specialty');
            specialtySelect.addEventListener('change', function() {
                selectedSpecialty = this.value;
                updateDoctorsList(selectedSpecialty);
                document.getElementById('to-step-2').disabled = false;
            });
            
            // Doctor selection
            const doctorSelect = document.getElementById('doctor');
            doctorSelect.addEventListener('change', function() {
                selectedDoctor = this.value;
                updateDoctorCards(selectedDoctor);
            });
            
            // Step navigation
            document.getElementById('to-step-2').addEventListener('click', function() {
                if (selectedSpecialty && selectedDoctor) {
                    goToStep(2);
                }
            });
            
            document.getElementById('back-to-step-1').addEventListener('click', function() {
                goToStep(1);
            });
            
            document.getElementById('back-to-step-2').addEventListener('click', function() {
                goToStep(2);
            });
            
            document.getElementById('to-step-3').addEventListener('click', function() {
                if (selectedDate && selectedTime) {
                    goToStep(3);
                    
                    // Update confirmation details
                    document.getElementById('confirm-patient').textContent = 'Usuario Demo';
                    
                    // Get specialty name
                    const specialtySelect = document.getElementById('specialty');
                    const specialtyName = specialtySelect.options[specialtySelect.selectedIndex].text;
                    document.getElementById('confirm-specialty').textContent = specialtyName;
                    
                    // Get doctor name
                    const doctorSelect = document.getElementById('doctor');
                    const doctorName = doctorSelect.options[doctorSelect.selectedIndex].text;
                    document.getElementById('confirm-doctor').textContent = doctorName;
                    
                    document.getElementById('confirm-date').textContent = formatDate(selectedDate);
                    document.getElementById('confirm-time').textContent = selectedTime;
                }
            });
            
            // Confirm appointment
            document.getElementById('confirm-appointment').addEventListener('click', function() {
                // Prepare appointment data - using demo user from your DB (ID 1)
                const appointmentData = {
                    usuario_id: 1, // Demo user from your DB
                    medico_id: selectedDoctor,
                    fecha: selectedDate.toISOString().split('T')[0],
                    hora: selectedTime + ':00', // Add seconds for TIME format
                    estado: 'pendiente'
                };
                
                console.log('Sending appointment:', appointmentData);
                
                // Send appointment to server
                fetch('api.php?action=create_appointment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(appointmentData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);
                    if (data.success) {
                        document.getElementById('step-3').classList.add('hidden');
                        document.getElementById('appointment-success').classList.remove('hidden');
                    } else {
                        alert('Error al agendar la cita: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión al agendar la cita');
                });
            });
            
            // New appointment
            document.getElementById('new-appointment').addEventListener('click', function() {
                document.getElementById('appointment-success').classList.add('hidden');
                resetAppointmentForm();
            });
            
            // Calendar navigation
            document.getElementById('prev-month').addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generateCalendar(currentMonth, currentYear);
            });
            
            document.getElementById('next-month').addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generateCalendar(currentMonth, currentYear);
            });
        }
        
        // Update doctors list based on selected specialty
        function updateDoctorsList(specialtyId) {
            const doctorSelect = document.getElementById('doctor');
            doctorSelect.innerHTML = '<option value="" selected disabled>Cargando médicos...</option>';
            doctorSelect.disabled = false;
            
            // Fetch doctors from server
            fetch(`api.php?action=get_doctors&specialty_id=${specialtyId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        doctorSelect.innerHTML = '<option value="" selected disabled>Selecciona un médico</option>';
                        
                        data.doctors.forEach(doctor => {
                            const option = document.createElement('option');
                            option.value = doctor.id;
                            option.textContent = doctor.nombre;
                            doctorSelect.appendChild(option);
                        });
                        
                        // Show doctor cards container
                        document.getElementById('doctors-container').classList.remove('hidden');
                        updateDoctorCards();
                    } else {
                        // Fallback to mock data if API fails
                        const doctors = {
                            '1': [
                                { id: 1, name: 'Dr. García' }
                            ],
                            '2': [
                                { id: 2, name: 'Dra. López' }
                            ],
                            '3': [
                                { id: 3, name: 'Dr. Fernández' }
                            ]
                        };
                        
                        if (doctors[specialtyId]) {
                            doctorSelect.innerHTML = '<option value="" selected disabled>Selecciona un médico</option>';
                            doctors[specialtyId].forEach(doctor => {
                                const option = document.createElement('option');
                                option.value = doctor.id;
                                option.textContent = doctor.name;
                                doctorSelect.appendChild(option);
                            });
                            
                            // Show doctor cards container
                            document.getElementById('doctors-container').classList.remove('hidden');
                            updateDoctorCards();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading doctors:', error);
                    // Fallback to mock data if API fails
                    const doctors = {
                        '1': [
                            { id: 1, name: 'Dr. García' }
                        ],
                        '2': [
                            { id: 2, name: 'Dra. López' }
                        ],
                        '3': [
                            { id: 3, name: 'Dr. Fernández' }
                        ]
                    };
                    
                    if (doctors[specialtyId]) {
                        doctorSelect.innerHTML = '<option value="" selected disabled>Selecciona un médico</option>';
                        doctors[specialtyId].forEach(doctor => {
                            const option = document.createElement('option');
                            option.value = doctor.id;
                            option.textContent = doctor.name;
                            doctorSelect.appendChild(option);
                        });
                        
                        // Show doctor cards container
                        document.getElementById('doctors-container').classList.remove('hidden');
                        updateDoctorCards();
                    }
                });
        }
        
        // Update doctor cards display
        function updateDoctorCards(selectedDoctorId = null) {
            const container = document.getElementById('doctors-container');
            container.innerHTML = '<h4 class="mb-3">Médicos Disponibles</h4>';
            
            const doctorSelect = document.getElementById('doctor');
            const doctors = Array.from(doctorSelect.options).slice(1); // Skip the first option
            
            doctors.forEach(doctorOption => {
                const doctorId = doctorOption.value;
                const doctorName = doctorOption.textContent;
                
                const col = document.createElement('div');
                col.className = 'col-md-6 col-lg-4 mb-4';
                
                const card = document.createElement('div');
                card.className = `card doctor-card ${selectedDoctorId === doctorId ? 'border-primary' : ''}`;
                card.style.cursor = 'pointer';
                
                // Mock doctor data
                const specialties = {
                    '1': 'Cardiólogo con 10 años de experiencia',
                    '2': 'Pediatra especializada en neonatología',
                    '3': 'Dermatólogo con enfoque en estética'
                };
                
                card.innerHTML = `
                    <div class="card-body">
                        <img src="https://via.placeholder.com/120" class="doctor-img" alt="${doctorName}">
                        <h5 class="card-title">${doctorName}</h5>
                        <p class="card-text text-muted">${specialties[doctorId] || 'Especialista médico'}</p>
                        <button class="btn btn-sm ${selectedDoctorId === doctorId ? 'btn-primary' : 'btn-outline-primary'} select-doctor" data-id="${doctorId}">
                            ${selectedDoctorId === doctorId ? '<i class="bi bi-check-circle me-1"></i> Seleccionado' : 'Seleccionar'}
                        </button>
                    </div>
                `;
                
                col.appendChild(card);
                container.appendChild(col);
            });
            
            // Add event listeners to doctor selection buttons
            document.querySelectorAll('.select-doctor').forEach(button => {
                button.addEventListener('click', function() {
                    const doctorId = this.getAttribute('data-id');
                    document.getElementById('doctor').value = doctorId;
                    selectedDoctor = doctorId;
                    updateDoctorCards(doctorId);
                });
            });
        }
        
        // Generate calendar for the specified month and year
        function generateCalendar(month, year) {
            const calendarDays = document.getElementById('calendar-days');
            calendarDays.innerHTML = '';
            
            const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                               'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            
            // Update current month display
            document.getElementById('current-month').textContent = `${monthNames[month]} ${year}`;
            
            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            
            // Add empty cells for days before the first day of the month
            for (let i = 0; i < firstDay.getDay(); i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day disabled';
                calendarDays.appendChild(emptyDay);
            }
            
            // Add days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                
                // Disable past dates
                if (date < new Date().setHours(0, 0, 0, 0)) {
                    dayElement.classList.add('disabled');
                } else {
                    dayElement.addEventListener('click', function() {
                        // Remove selected class from all days
                        document.querySelectorAll('.calendar-day').forEach(d => {
                            d.classList.remove('selected');
                        });
                        
                        // Add selected class to clicked day
                        this.classList.add('selected');
                        
                        // Store selected date
                        selectedDate = new Date(year, month, day);
                        
                        // Enable time selection
                        document.getElementById('to-step-3').disabled = false;
                        
                        // Update available time slots
                        generateTimeSlots();
                    });
                }
                
                calendarDays.appendChild(dayElement);
            }
        }
        
        // Generate time slots for selected date
        function generateTimeSlots() {
            const timeSlotsContainer = document.getElementById('time-slots');
            timeSlotsContainer.innerHTML = '';
            
            if (!selectedDate) return;
            
            // Mock available time slots
            const availableSlots = [
                '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
                '11:00', '11:30', '14:00', '14:30', '15:00', '15:30',
                '16:00', '16:30', '17:00'
            ];
            
            // Check for booked appointments for the selected doctor and date
            if (selectedDoctor && selectedDate) {
                const formattedDate = selectedDate.toISOString().split('T')[0];
                fetch(`api.php?action=get_booked_slots&doctor_id=${selectedDoctor}&date=${formattedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        const bookedSlots = data.success ? data.booked_slots : [];
                        
                        availableSlots.forEach(slot => {
                            const slotElement = document.createElement('div');
                            slotElement.className = `time-slot ${bookedSlots.includes(slot) ? 'unavailable' : ''}`;
                            slotElement.textContent = slot;
                            
                            if (!bookedSlots.includes(slot)) {
                                slotElement.addEventListener('click', function() {
                                    // Remove selected class from all time slots
                                    document.querySelectorAll('.time-slot').forEach(s => {
                                        s.classList.remove('selected');
                                    });
                                    
                                    // Add selected class to clicked time slot
                                    this.classList.add('selected');
                                    
                                    // Store selected time
                                    selectedTime = slot;
                                    
                                    // Enable confirmation button
                                    document.getElementById('to-step-3').disabled = false;
                                });
                            }
                            
                            timeSlotsContainer.appendChild(slotElement);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading booked slots:', error);
                        // If there's an error, show all slots as available
                        availableSlots.forEach(slot => {
                            const slotElement = document.createElement('div');
                            slotElement.className = 'time-slot';
                            slotElement.textContent = slot;
                            slotElement.addEventListener('click', function() {
                                document.querySelectorAll('.time-slot').forEach(s => {
                                    s.classList.remove('selected');
                                });
                                this.classList.add('selected');
                                selectedTime = slot;
                                document.getElementById('to-step-3').disabled = false;
                            });
                            timeSlotsContainer.appendChild(slotElement);
                        });
                    });
            }
        }
        
        // Navigate to step
        function goToStep(step) {
            // Hide all steps
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.add('hidden');
            document.getElementById('step-3').classList.add('hidden');
            
            // Show current step
            document.getElementById(`step-${step}`).classList.remove('hidden');
            
            // Update step indicators
            document.querySelectorAll('.step').forEach((stepElement, index) => {
                stepElement.classList.remove('active', 'completed');
                if (index + 1 < step) {
                    stepElement.classList.add('completed');
                } else if (index + 1 === step) {
                    stepElement.classList.add('active');
                } 
            });
            
            currentStep = step;
        }
        
        // Reset appointment form to initial state
        function resetAppointmentForm() {
            selectedSpecialty = null;
            selectedDoctor = null;
            selectedDate = null;
            selectedTime = null;
            
            document.getElementById('specialty').value = '';
            document.getElementById('doctor').innerHTML = '<option value="" selected disabled>Primero selecciona una especialidad</option>';
            document.getElementById('doctor').disabled = true;
            document.getElementById('doctors-container').classList.add('hidden');
            document.getElementById('to-step-2').disabled = true;
            
            goToStep(1);
        }
        
        // Format date for display
        function formatDate(date) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('es-ES', options);
        }
        
    </script>
    
</body>
</html>