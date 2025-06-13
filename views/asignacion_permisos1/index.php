<div class="container py-5">
    <!-- Formulario Principal -->
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h3 class="fw-bold text-primary mb-0">ASIGNACIÓN DE PERMISOS A USUARIOS</h3>
                    </div>
                    <form id="formAsignacion" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="asignacion_id" name="asignacion_id">
                        <input type="hidden" id="asignacion_fecha" name="asignacion_fecha" value="">
                        <input type="hidden" id="asignacion_situacion" name="asignacion_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="asignacion_usuario_id" class="form-label">Usuario *</label>
                                <select class="form-control form-control-lg" id="asignacion_usuario_id" name="asignacion_usuario_id" required>
                                    <option value="">Seleccione un usuario</option>
                                </select>
                                <div class="form-text">Usuario al que se le asignará el permiso</div>
                            </div>
                            <div class="col-md-6">
                                <label for="asignacion_app_id" class="form-label">Aplicación *</label>
                                <select class="form-control form-control-lg" id="asignacion_app_id" name="asignacion_app_id" required>
                                    <option value="">Seleccione una aplicación</option>
                                </select>
                                <div class="form-text">Aplicación donde se aplicará el permiso</div>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="asignacion_permiso_id" class="form-label">Permiso *</label>
                                <select class="form-control form-control-lg" id="asignacion_permiso_id" name="asignacion_permiso_id" required>
                                    <option value="">Seleccione primero una aplicación</option>
                                </select>
                                <div class="form-text">Los permisos se cargarán automáticamente al seleccionar una aplicación</div>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="asignacion_usuario_asigno" class="form-label">Usuario que Asigna *</label>
                                <select class="form-control form-control-lg" id="asignacion_usuario_asigno" name="asignacion_usuario_asigno" required>
                                    <option value="">Seleccione quién asigna</option>
                                </select>
                                <div class="form-text">Usuario responsable de la asignación</div>
                            </div>
                            <div class="col-md-6">
                                <label for="asignacion_motivo" class="form-label">Motivo</label>
                                <input type="text" class="form-control form-control-lg" id="asignacion_motivo" name="asignacion_motivo" placeholder="Motivo de la asignación (opcional)">
                                <div class="form-text">Campo opcional - razón de la asignación</div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Asignar Permiso
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar Asignación
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarAsignaciones">
                                <i class="bi bi-search me-2"></i>Ver Asignaciones
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección de ayuda -->
    <div class="row mb-4 justify-content-center">
        <div class="col-lg-10">
            <div class="alert alert-info border-0 rounded-3 shadow-sm">
                                    <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                    <div>
                        <h6 class="mb-1">Instrucciones para Asignar Permisos:</h6>
                        <small>1. Seleccione el usuario al que desea asignar el permiso<br>
                        2. Elija la aplicación correspondiente<br>
                        3. Seleccione el permiso específico de la lista (se cargan automáticamente)<br>
                        4. Indique quién realiza la asignación y el motivo (opcional)<br>
                        5. Haga clic en "Asignar Permiso" para completar la acción</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección de Tabla -->
    <div class="row justify-content-center mt-5" id="seccionTabla" style="display: none;">
        <div class="col-lg-12">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-primary mb-0">Asignaciones de Permisos Registradas</h3>
                        <div class="badge bg-primary fs-6 px-3 py-2">
                            <i class="bi bi-diagram-3 me-2"></i>Gestión de Asignaciones
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="filtroUsuario" class="form-label">Filtrar por Usuario:</label>
                            <select class="form-control" id="filtroUsuario">
                                <option value="">Todos los usuarios</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filtroApp" class="form-label">Filtrar por Aplicación:</label>
                            <select class="form-control" id="filtroApp">
                                <option value="">Todas las aplicaciones</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" type="button" onclick="limpiarFiltros()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Limpiar Filtros
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableAsignaciones" style="width: 100% !important;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>No.</th>
                                    <th>Usuario</th>
                                    <th>Aplicación</th>
                                    <th>Permiso</th>
                                    <th>Descripción del Permiso</th>
                                    <th>Fecha Asignación</th>
                                    <th>Asignado por</th>
                                    <th>Situación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección de estadísticas -->
    <div class="row mt-4 justify-content-center" id="seccionEstadisticas" style="display: none;">
        <div class="col-lg-12">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card bg-primary text-white border-0 rounded-3">
                        <div class="card-body text-center">
                            <i class="bi bi-diagram-3-fill fs-1 mb-2"></i>
                            <h5 class="card-title">Total Asignaciones</h5>
                            <h3 class="mb-0" id="totalAsignaciones">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white border-0 rounded-3">
                        <div class="card-body text-center">
                            <i class="bi bi-people-fill fs-1 mb-2"></i>
                            <h5 class="card-title">Usuarios con Permisos</h5>
                            <h3 class="mb-0" id="usuariosConPermisos">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white border-0 rounded-3">
                        <div class="card-body text-center">
                            <i class="bi bi-app-indicator fs-1 mb-2"></i>
                            <h5 class="card-title">Apps con Asignaciones</h5>
                            <h3 class="mb-0" id="appsConAsignaciones">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Herramientas Adicionales -->
    <div class="row mt-5 justify-content-center">
        <div class="col-lg-10">
            <div class="card border-warning rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="bi bi-tools me-2"></i>Herramientas Adicionales
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-dark">Consultas Rápidas:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success me-2"></i>Ver todos los permisos de un usuario</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Verificar si un usuario tiene un permiso específico</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Listar usuarios con acceso a una aplicación</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-dark">Acciones de Gestión:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-gear text-primary me-2"></i>Modificar asignaciones existentes</li>
                                <li><i class="bi bi-trash text-danger me-2"></i>Eliminar asignaciones caducadas</li>
                                <li><i class="bi bi-graph-up text-info me-2"></i>Ver estadísticas de uso</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Ayuda Avanzada -->
    <div class="row mt-4 justify-content-center">
        <div class="col-lg-10">
            <div class="accordion" id="accordionAyuda">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAyuda">
                            <i class="bi bi-question-circle me-2"></i>
                            Preguntas Frecuentes sobre Asignación de Permisos
                        </button>
                    </h2>
                    <div id="collapseAyuda" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><strong>¿Qué pasa si asigno un permiso duplicado?</strong></h6>
                                    <p class="small">El sistema automáticamente detecta y previene asignaciones duplicadas para el mismo usuario, aplicación y permiso.</p>
                                    
                                    <h6><strong>¿Puedo modificar una asignación existente?</strong></h6>
                                    <p class="small">Sí, utiliza el botón "Modificar" en la tabla para cambiar los detalles de una asignación.</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><strong>¿Cómo elimino una asignación?</strong></h6>
                                    <p class="small">Usa el botón "Eliminar" en la tabla. Esta acción requerirá confirmación y no se puede deshacer.</p>
                                    
                                    <h6><strong>¿Puedo ver todos los permisos de un usuario?</strong></h6>
                                    <p class="small">Sí, utiliza los filtros en la tabla para ver solo las asignaciones de un usuario específico.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/asignacion_permisos1/index.js') ?>"></script>