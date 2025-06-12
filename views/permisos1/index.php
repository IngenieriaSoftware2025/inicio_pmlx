<div class="container py-5">
    <!-- Formulario Principal -->
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Gestión de Permisos!</h5>
                        <h3 class="fw-bold text-primary mb-0">CREAR/EDITAR PERMISOS</h3>
                    </div>
                    <form id="formPermiso" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="permiso_id" name="permiso_id">
                        <input type="hidden" id="permiso_fecha" name="permiso_fecha" value="">
                        <input type="hidden" id="permiso_situacion" name="permiso_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="permiso_app_id" class="form-label">Aplicación *</label>
                                <select class="form-control form-control-lg" id="permiso_app_id" name="permiso_app_id" required>
                                    <option value="">Seleccione una aplicación</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="permiso_nombre" class="form-label">Nombre del Permiso *</label>
                                <input type="text" class="form-control form-control-lg" id="permiso_nombre" name="permiso_nombre" placeholder="Ej: Gestionar Usuarios" required>
                                <div class="form-text">Se generará automáticamente la clave al salir del campo</div>
                            </div>
                            <div class="col-md-6">
                                <label for="permiso_clave" class="form-label">Clave del Permiso *</label>
                                <input type="text" class="form-control form-control-lg" id="permiso_clave" name="permiso_clave" placeholder="Ej: GESTIONAR_USUARIOS" required>
                                <div class="form-text">Debe ser única para cada aplicación</div>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="permiso_desc" class="form-label">Descripción *</label>
                                <textarea class="form-control form-control-lg" id="permiso_desc" name="permiso_desc" rows="3" placeholder="Describe qué acciones permite este permiso..." required></textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Crear Permiso
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Actualizar Permiso
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarPermisos">
                                <i class="bi bi-search me-2"></i>Ver Permisos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección de Instrucciones -->
    <div class="row mb-4 justify-content-center">
        <div class="col-lg-8">
            <div class="alert alert-info border-0 rounded-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                    <div>
                        <h6 class="mb-1">Instrucciones:</h6>
                        <small>
                            1. Seleccione la aplicación a la que pertenecerá el permiso<br>
                            2. Ingrese un nombre descriptivo (se generará automáticamente la clave)<br>
                            3. Modifique la clave si es necesario (debe ser única por aplicación)<br>
                            4. Escriba una descripción clara de qué acciones permite el permiso
                        </small>
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
                        <h3 class="text-primary mb-0">Lista de Permisos</h3>
                        <div class="badge bg-primary fs-6 px-3 py-2">
                            <i class="bi bi-shield-lock me-2"></i>Gestión de Permisos
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="filtroApp" class="form-label">Filtrar por Aplicación:</label>
                            <select class="form-control" id="filtroApp">
                                <option value="">Todas las aplicaciones</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="inputBuscar" class="form-label">Buscar:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="inputBuscar" placeholder="Buscar por nombre, clave o descripción...">
                                <button class="btn btn-outline-primary" type="button" id="BtnBuscarTexto">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" type="button" onclick="limpiarFiltros()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TablePermisos" style="width: 100% !important;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>No.</th>
                                    <th>Aplicación</th>
                                    <th>Nombre del Permiso</th>
                                    <th>Clave del Permiso</th>
                                    <th>Descripción</th>
                                    <th>Fecha Creación</th>
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
    
    <!-- Sección de Estadísticas -->
    <div class="row mt-4 justify-content-center" id="seccionEstadisticas" style="display: none;">
        <div class="col-lg-12">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white border-0 rounded-3">
                        <div class="card-body text-center">
                            <i class="bi bi-app-indicator fs-1 mb-2"></i>
                            <h5 class="card-title">Total Aplicaciones</h5>
                            <h3 class="mb-0" id="totalApps">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white border-0 rounded-3">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check fs-1 mb-2"></i>
                            <h5 class="card-title">Permisos Activos</h5>
                            <h3 class="mb-0" id="totalPermisosActivos">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white border-0 rounded-3">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history fs-1 mb-2"></i>
                            <h5 class="card-title">Creados Hoy</h5>
                            <h3 class="mb-0" id="permisosHoy">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white border-0 rounded-3">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up fs-1 mb-2"></i>
                            <h5 class="card-title">Esta Semana</h5>
                            <h3 class="mb-0" id="permisosSemana">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Ayuda -->
    <div class="row mt-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card border-warning rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="bi bi-lightbulb me-2"></i>Consejos para crear permisos
                    </h5>
                    <ul class="mb-0">
                        <li><strong>Nombres descriptivos:</strong> Use nombres claros como "Crear Usuario", "Eliminar Producto"</li>
                        <li><strong>Claves consistentes:</strong> Use un formato como "CREAR_USUARIO", "ELIMINAR_PRODUCTO"</li>
                        <li><strong>Descripción detallada:</strong> Explique exactamente qué acciones permite el permiso</li>
                        <li><strong>Organización:</strong> Agrupe permisos relacionados en la misma aplicación</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/permisos1/index.js') ?>"></script>