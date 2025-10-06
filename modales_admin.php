<?php
// modales_admin.php
?>

<!-- Modal para Citas -->
<div class="modal fade" id="modalCita" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCitaTitle">Nueva Cita</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="tabla" value="citas">
                <input type="hidden" name="action" value="crear" id="citaAction">
                <input type="hidden" name="id" id="citaId">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="citaPaciente" class="form-label">Paciente</label>
                            <select class="form-select" id="citaPaciente" name="paciente_id" required>
                                <option value="">Seleccionar paciente</option>
                                <?php foreach ($pacientes as $paciente): ?>
                                    <option value="<?php echo $paciente['id']; ?>"><?php echo htmlspecialchars($paciente['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="citaMedico" class="form-label">Médico</label>
                            <select class="form-select" id="citaMedico" name="medico_id" required>
                                <option value="">Seleccionar médico</option>
                                <?php foreach ($medicos as $medico): ?>
                                    <option value="<?php echo $medico['id']; ?>"><?php echo htmlspecialchars($medico['nombre']); ?> - <?php echo htmlspecialchars($medico['especialidad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="citaFecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="citaFecha" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="citaHora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="citaHora" name="hora" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="citaEstado" class="form-label">Estado</label>
                            <select class="form-select" id="citaEstado" name="estado" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="atendida">Atendida</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="citaMotivo" class="form-label">Motivo</label>
                        <textarea class="form-control" id="citaMotivo" name="motivo" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Médicos -->
<div class="modal fade" id="modalMedico" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalMedicoTitle">Nuevo Médico</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="tabla" value="medicos">
                <input type="hidden" name="action" value="crear" id="medicoAction">
                <input type="hidden" name="id" id="medicoId">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="medicoNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="medicoNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="medicoEspecialidad" class="form-label">Especialidad</label>
                        <select class="form-select" id="medicoEspecialidad" name="especialidad_id" required>
                            <option value="">Seleccionar especialidad</option>
                            <?php foreach ($especialidades as $especialidad): ?>
                                <option value="<?php echo $especialidad['id']; ?>"><?php echo htmlspecialchars($especialidad['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="medicoUsuario" class="form-label">Usuario Asociado</label>
                        <select class="form-select" id="medicoUsuario" name="usuario_id" required>
                            <option value="">Seleccionar usuario</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <?php if ($usuario['rol_id'] == 2): ?>
                                    <option value="<?php echo $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['nombre']); ?> (<?php echo htmlspecialchars($usuario['email']); ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Pacientes -->
<div class="modal fade" id="modalPaciente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalPacienteTitle">Nuevo Paciente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="tabla" value="pacientes">
                <input type="hidden" name="action" value="crear" id="pacienteAction">
                <input type="hidden" name="id" id="pacienteId">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pacienteNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="pacienteNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="pacienteDni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="pacienteDni" name="dni" required>
                    </div>
                    <div class="mb-3">
                        <label for="pacienteTelefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="pacienteTelefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="pacienteCorreo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="pacienteCorreo" name="correo">
                    </div>
                    <div class="mb-3">
                        <label for="pacienteUsuario" class="form-label">Usuario Asociado</label>
                        <select class="form-select" id="pacienteUsuario" name="usuario_id">
                            <option value="">Seleccionar usuario</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <?php if ($usuario['rol_id'] == 3): ?>
                                    <option value="<?php echo $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['nombre']); ?> (<?php echo htmlspecialchars($usuario['email']); ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Especialidades -->
<div class="modal fade" id="modalEspecialidad" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEspecialidadTitle">Nueva Especialidad</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="tabla" value="especialidades">
                <input type="hidden" name="action" value="crear" id="especialidadAction">
                <input type="hidden" name="id" id="especialidadId">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="especialidadNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="especialidadNombre" name="nombre" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>