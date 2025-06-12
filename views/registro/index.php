<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 20px 0;
        }
        
        .section-title {
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .photo-upload {
            border: 2px dashed #007bff;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .photo-upload:hover {
            background: #e3f2fd;
            border-color: #0056b3;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        
        .usuario-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .usuario-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .foto-usuario {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        
        .sin-foto {
            width: 80px;
            height: 80px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #6c757d;
            margin: 0 auto;
        }
        
        .badge-estado {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="form-container">
                    <div class="form-header">
                        <i class="fas fa-user-plus"></i>
                        <div class="text-center mb-4">
                            <h2><i class="bi bi-person-plus-fill"></i> Registro de Usuario</h2>
                            <p class="text-muted">Complete todos los campos para crear su cuenta</p>
                        </div>
                    </div>

                    <form id="registroForm" enctype="multipart/form-data">
                        <!-- Información Personal -->
                        <h5 class="section-title">
                            <i class="fas fa-user me-2"></i>Información Personal
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_nom1" class="form-label">Primer Nombre *</label>
                                <input type="text" class="form-control" id="usuario_nom1" name="usuario_nom1" required maxlength="50">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_nom2" class="form-label">Segundo Nombre *</label>
                                <input type="text" class="form-control" id="usuario_nom2" name="usuario_nom2" required maxlength="50">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_ape1" class="form-label">Primer Apellido *</label>
                                <input type="text" class="form-control" id="usuario_ape1" name="usuario_ape1" required maxlength="50">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_ape2" class="form-label">Segundo Apellido *</label>
                                <input type="text" class="form-control" id="usuario_ape2" name="usuario_ape2" required maxlength="50">
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-address-book me-2"></i>Información de Contacto
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_tel" class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control" id="usuario_tel" name="usuario_tel" required placeholder="12345678">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_correo" class="form-label">Correo Electrónico *</label>
                                <input type="email" class="form-control" id="usuario_correo" name="usuario_correo" required maxlength="100">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="usuario_direc" class="form-label">Dirección *</label>
                            <textarea class="form-control" id="usuario_direc" name="usuario_direc" rows="3" required maxlength="150"></textarea>
                        </div>

                        <!-- Información de Identificación -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-id-card me-2"></i>Información de Identificación
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_dpi" class="form-label">DPI *</label>
                                <input type="text" class="form-control" id="usuario_dpi" name="usuario_dpi" required maxlength="13" placeholder="1234567890123">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_contra" class="form-label">Contraseña *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="usuario_contra" name="usuario_contra" required minlength="10">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 10 caracteres, 1 mayúscula y un signo (,.)</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="confirmar_contra" class="form-label">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" id="confirmar_contra" name="confirmar_contra" required>
                            </div>
                        </div>

                        <!-- Fotografía -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-camera me-2"></i>Fotografía (Opcional)
                        </h5>
                        <div class="mb-3">
                            <div class="photo-upload" onclick="document.getElementById('usuario_fotografia').click()">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="mb-0">Haga clic para subir una fotografía</p>
                                <small class="text-muted">JPG, PNG o GIF (máx. 2MB)</small>
                            </div>
                            <input type="file" class="form-control d-none" id="usuario_fotografia" name="usuario_fotografia" accept="image/*">
                            <div id="photoPreview"></div>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="button" class="btn btn-secondary me-md-2" onclick="limpiarFormulario()">
                                <i class="fas fa-broom me-2"></i>Limpiar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Registrar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado obtenido -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1><i class="bi bi-people-fill me-3"></i>Lista de Usuarios</h1>
                    <p class="lead mb-0">Usuarios registrados en el sistema</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Total de usuarios: <span id="totalUsuarios" class="badge bg-primary">0</span></h3>
                    <div>
                        <button class="btn btn-outline-primary" onclick="cargarUsuarios()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Actualizar
                        </button>
                        <a href="/inicio_pmlx/registro" class="btn btn-success">
                            <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3">Cargando usuarios...</p>
        </div>

        <!-- Contenedor de usuarios -->
        <div id="usuariosContainer" class="row" style="display: none;">
            <!-- Los usuarios se cargarán aquí dinámicamente -->
        </div>

        <!-- Mensaje cuando no hay usuarios -->
        <div id="sinUsuarios" class="text-center py-5" style="display: none;">
            <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">No hay usuarios registrados</h4>
            <p class="text-muted">Registra el primer usuario para verlo aquí</p>
            <a href="/inicio_pmlx/registro" class="btn btn-primary">
                <i class="bi bi-person-plus me-2"></i>Registrar Usuario
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // JavaScript para el formulario de registro - Sin módulos ES6

        // Función para validar formulario
        function validarFormulario(formulario, excluir = []) {
            const inputsRequeridos = formulario.querySelectorAll('input[required], textarea[required], select[required]');
            
            for (let input of inputsRequeridos) {
                if (excluir.includes(input.name) || excluir.includes(input.id)) {
                    continue;
                }
                
                if (!input.value || input.value.trim() === '') {
                    input.focus();
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            }
            
            return true;
        }

        // Función global para limpiar formulario
        function limpiarFormulario() {
            const FormRegistro = document.getElementById('registroForm');
            if (!FormRegistro) return;
            
            FormRegistro.reset();
            
            const photoPreview = document.getElementById('photoPreview');
            if (photoPreview) {
                photoPreview.innerHTML = '';
            }

            const inputs = FormRegistro.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
            });

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Esperar a que cargue el DOM
        document.addEventListener('DOMContentLoaded', function() {
            
            const FormRegistro = document.getElementById('registroForm');
            const BtnRegistrar = document.querySelector('button[type="submit"]');
            const InputPassword = document.getElementById('usuario_contra');
            const InputConfirmarPassword = document.getElementById('confirmar_contra');
            const InputDPI = document.getElementById('usuario_dpi');
            const InputCorreo = document.getElementById('usuario_correo');
            const InputTelefono = document.getElementById('usuario_tel');
            const TogglePassword = document.getElementById('togglePassword');
            const InputFotografia = document.getElementById('usuario_fotografia');

            // Verificar que los elementos existen
            if (!FormRegistro) {
                console.log('No se encontró el formulario');
                return;
            }

            console.log('Formulario encontrado, inicializando eventos...');

            // Preview de fotografía
            const mostrarPreviewFoto = () => {
                if (!InputFotografia) return;
                
                const file = InputFotografia.files[0];
                const photoPreview = document.getElementById('photoPreview');
                
                if (file && photoPreview) {
                    // Validar tipo de archivo
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Tipo de archivo no válido",
                            text: "Solo se permiten archivos JPG, JPEG, PNG o GIF",
                            showConfirmButton: true,
                        });
                        InputFotografia.value = '';
                        photoPreview.innerHTML = '';
                        return;
                    }
                    
                    // Validar tamaño (2MB)
                    if (file.size > 2097152) {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Archivo muy grande",
                            text: "La imagen debe pesar menos de 2MB",
                            showConfirmButton: true,
                        });
                        InputFotografia.value = '';
                        photoPreview.innerHTML = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.innerHTML = `
                            <div class="text-center mt-3">
                                <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                <p class="mt-2 text-muted">Vista previa de la fotografía</p>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                    
                    InputFotografia.classList.remove('is-invalid');
                    InputFotografia.classList.add('is-valid');
                } else if (photoPreview) {
                    photoPreview.innerHTML = '';
                    InputFotografia.classList.remove('is-valid', 'is-invalid');
                }
            }

            // Validar contraseña en tiempo real
            const ValidarPassword = () => {
                if (!InputPassword) return;
                
                const password = InputPassword.value;

                if (password.length < 1) {
                    InputPassword.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                // Requerimientos: mínimo 10 caracteres, 1 mayúscula y un signo (,.)
                const hasLength = password.length >= 10;
                const hasUpper = /[A-Z]/.test(password);
                const hasSign = /[,.]/.test(password);

                if (hasLength && hasUpper && hasSign) {
                    InputPassword.classList.remove('is-invalid');
                    InputPassword.classList.add('is-valid');
                } else {
                    InputPassword.classList.remove('is-valid');
                    InputPassword.classList.add('is-invalid');
                }
            }

            // Validar confirmación de contraseña
            const ValidarConfirmarPassword = () => {
                if (!InputPassword || !InputConfirmarPassword) return;
                
                const password = InputPassword.value;
                const confirmar = InputConfirmarPassword.value;

                if (confirmar.length < 1) {
                    InputConfirmarPassword.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                if (password === confirmar) {
                    InputConfirmarPassword.classList.remove('is-invalid');
                    InputConfirmarPassword.classList.add('is-valid');
                } else {
                    InputConfirmarPassword.classList.remove('is-valid');
                    InputConfirmarPassword.classList.add('is-invalid');
                }
            }

            // Validar DPI
            const ValidarDPI = () => {
                if (!InputDPI) return;
                
                const dpi = InputDPI.value.replace(/\D/g, '');
                InputDPI.value = dpi;

                if (dpi.length < 1) {
                    InputDPI.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                if (dpi.length === 13) {
                    InputDPI.classList.remove('is-invalid');
                    InputDPI.classList.add('is-valid');
                } else {
                    InputDPI.classList.remove('is-valid');
                    InputDPI.classList.add('is-invalid');
                }
            }

            // Validar teléfono
            const ValidarTelefono = () => {
                if (!InputTelefono) return;
                
                const telefono = InputTelefono.value.replace(/\D/g, '');
                InputTelefono.value = telefono;

                if (telefono.length < 1) {
                    InputTelefono.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                if (telefono.length === 8) {
                    InputTelefono.classList.remove('is-invalid');
                    InputTelefono.classList.add('is-valid');
                } else {
                    InputTelefono.classList.remove('is-valid');
                    InputTelefono.classList.add('is-invalid');
                }
            }

            // Validar correo electrónico
            const ValidarCorreo = () => {
                if (!InputCorreo) return;
                
                const correo = InputCorreo.value.trim();

                if (correo.length < 1) {
                    InputCorreo.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (emailRegex.test(correo)) {
                    InputCorreo.classList.remove('is-invalid');
                    InputCorreo.classList.add('is-valid');
                } else {
                    InputCorreo.classList.remove('is-valid');
                    InputCorreo.classList.add('is-invalid');
                }
            }

            // Toggle mostrar/ocultar contraseña
            const togglePasswordVisibility = () => {
                if (!TogglePassword || !InputPassword) return;
                
                const icon = TogglePassword.querySelector('i');
                
                if (InputPassword.type === 'password') {
                    InputPassword.type = 'text';
                    if (icon) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                } else {
                    InputPassword.type = 'password';
                    if (icon) {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            }

            // FUNCIÓN CORREGIDA DE GUARDAR USUARIO
            const GuardarUsuario = async (event) => {
                event.preventDefault();
                
                console.log('Iniciando proceso de registro...');
                
                if (!BtnRegistrar) return;
                
                BtnRegistrar.disabled = true;
                const originalText = BtnRegistrar.innerHTML;
                BtnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registrando...';

                // Validar formulario - La fotografía es OPCIONAL
                if (!validarFormulario(FormRegistro, ['usuario_fotografia'])) {
                    Swal.fire({
                        position: "center",
                        icon: "info",
                        title: "FORMULARIO INCOMPLETO",
                        text: "Debe completar todos los campos obligatorios",
                        showConfirmButton: true,
                    });
                    BtnRegistrar.disabled = false;
                    BtnRegistrar.innerHTML = originalText;
                    return;
                }

                // Validar que las contraseñas coincidan
                if (InputPassword.value !== InputConfirmarPassword.value) {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error en contraseñas",
                        text: "Las contraseñas no coinciden",
                        showConfirmButton: true,
                    });
                    BtnRegistrar.disabled = false;
                    BtnRegistrar.innerHTML = originalText;
                    return;
                }

                // Crear FormData y mostrar contenido para debug
                const body = new FormData(FormRegistro);
                
                // Debug: Mostrar qué datos se están enviando
                console.log('Datos del formulario:');
                for (let [key, value] of body.entries()) {
                    if (value instanceof File) {
                        console.log(key, ':', `Archivo: ${value.name} (${value.size} bytes)`);
                    } else {
                        console.log(key, ':', value);
                    }
                }
                
                const url = '/inicio_pmlx/registro/guardarAPI';
                console.log('Enviando datos a:', url);

                try {
                    const respuesta = await fetch(url, {
                        method: 'POST',
                        body: body
                    });
                    
                    console.log('Respuesta recibida. Status:', respuesta.status);
                    console.log('Content-Type:', respuesta.headers.get('content-type'));
                    
                    // Obtener el texto de la respuesta primero
                    const textoRespuesta = await respuesta.text();
                    console.log('Respuesta cruda:', textoRespuesta);
                    
                    // Verificar si la respuesta es JSON válido
                    let datos;
                    try {
                        datos = JSON.parse(textoRespuesta);
                    } catch (jsonError) {
                        console.error('Error al parsear JSON:', jsonError);
                        console.error('Texto recibido:', textoRespuesta);
                        
                        throw new Error(`El servidor no devolvió JSON válido. Status: ${respuesta.status}. Respuesta: ${textoRespuesta.substring(0, 200)}`);
                    }
                    
                    console.log('Datos procesados:', datos);
                    
                    // Verificar si la respuesta HTTP fue exitosa
                    if (!respuesta.ok) {
                        throw new Error(`Error HTTP ${respuesta.status}: ${datos.mensaje || textoRespuesta}`);
                    }
                    
                    const { codigo, mensaje } = datos;

                    if (codigo == 1) {
                        await Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "¡Éxito!",
                            text: mensaje,
                            showConfirmButton: true,
                        });

                        // Usar la función global de limpiar
                        limpiarFormulario();
                        
                    } else {
                        await Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Error",
                            text: mensaje || 'Error desconocido en el servidor',
                            showConfirmButton: true,
                        });
                    }

                } catch (error) {
                    console.error('Error completo:', error);
                    
                    // Mensaje de error más específico
                    let mensajeError = 'Error de conexión desconocido';
                    
                    if (error.message.includes('Failed to fetch')) {
                        mensajeError = 'No se pudo conectar con el servidor. Verifique su conexión a internet.';
                    } else if (error.message.includes('JSON')) {
                        mensajeError = 'El servidor devolvió una respuesta inválida. Contacte al administrador.';
                    } else {
                        mensajeError = error.message;
                    }
                    
                    await Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error de conexión",
                        text: mensajeError,
                        showConfirmButton: true,
                    });
                }

                BtnRegistrar.disabled = false;
                BtnRegistrar.innerHTML = originalText;
            }

            // Event Listeners
            if (FormRegistro) FormRegistro.addEventListener('submit', GuardarUsuario);
            if (InputPassword) InputPassword.addEventListener('input', ValidarPassword);
            if (InputConfirmarPassword) {
                InputConfirmarPassword.addEventListener('blur', ValidarConfirmarPassword);
                InputConfirmarPassword.addEventListener('input', ValidarConfirmarPassword);
            }
            if (InputDPI) InputDPI.addEventListener('input', ValidarDPI);
            if (InputCorreo) InputCorreo.addEventListener('blur', ValidarCorreo);
            if (InputTelefono) InputTelefono.addEventListener('input', ValidarTelefono);
            if (TogglePassword) TogglePassword.addEventListener('click', togglePasswordVisibility);
            if (InputFotografia) InputFotografia.addEventListener('change', mostrarPreviewFoto);

            // Validaciones para nombres y apellidos
            document.querySelectorAll('#usuario_nom1, #usuario_nom2, #usuario_ape1, #usuario_ape2').forEach(input => {
                if (input) {
                    input.addEventListener('input', function() {
                        const valor = this.value.trim();
                        
                        if (valor.length < 1) {
                            this.classList.remove('is-valid', 'is-invalid');
                        } else if (valor.length >= 2 && valor.length <= 50) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else {
                            this.classList.remove('is-valid');
                            this.classList.add('is-invalid');
                        }
                    });
                }
            });

            // Validación para dirección
            const inputDireccion = document.getElementById('usuario_direc');
            if (inputDireccion) {
                inputDireccion.addEventListener('input', function() {
                    const valor = this.value.trim();
                    
                    if (valor.length < 1) {
                        this.classList.remove('is-valid', 'is-invalid');
                    } else if (valor.length >= 10 && valor.length <= 150) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                });
            }

            console.log('Eventos registrados correctamente');

        });

        // FUNCIÓN CORREGIDA DE CARGAR USUARIOS
        async function cargarUsuarios() {
            const loading = document.getElementById('loading');
            const container = document.getElementById('usuariosContainer');
            const sinUsuarios = document.getElementById('sinUsuarios');
            const totalUsuarios = document.getElementById('totalUsuarios');
            
            // Mostrar loading
            if (loading) loading.style.display = 'block';
            if (container) container.style.display = 'none';
            if (sinUsuarios) sinUsuarios.style.display = 'none';
            
            try {
                const response = await fetch('/inicio_pmlx/usuarios/obtenerAPI');
                
                console.log('Response status:', response.status);
                console.log('Content-Type:', response.headers.get('content-type'));
                
                // Obtener texto primero para debug
                const textoRespuesta = await response.text();
                console.log('Respuesta cruda:', textoRespuesta);
                
                // Intentar parsear JSON
                let data;
                try {
                    data = JSON.parse(textoRespuesta);
                } catch (jsonError) {
                    console.error('Error parsing JSON:', jsonError);
                    throw new Error(`Respuesta inválida del servidor: ${textoRespuesta.substring(0, 100)}`);
                }
                
                if (data.codigo === 1 && data.usuarios) {
                    const usuarios = data.usuarios;
                    
                    if (usuarios.length === 0) {
                        // No hay usuarios
                        if (loading) loading.style.display = 'none';
                        if (sinUsuarios) sinUsuarios.style.display = 'block';
                        if (totalUsuarios) totalUsuarios.textContent = '0';
                    } else {
                        // Renderizar usuarios
                        if (container) {
                            container.innerHTML = '';
                            
                            usuarios.forEach(usuario => {
                                const userCard = crearTarjetaUsuario(usuario);
                                container.appendChild(userCard);
                            });
                            
                            container.style.display = 'flex';
                        }
                        
                        if (loading) loading.style.display = 'none';
                        if (totalUsuarios) totalUsuarios.textContent = usuarios.length;
                    }
                } else {
                    throw new Error(data.mensaje || 'Error al cargar usuarios');
                }
                
            } catch (error) {
                console.error('Error:', error);
                
                if (loading) loading.style.display = 'none';
                
                if (container) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                            <h4 class="text-danger mt-3">Error al cargar usuarios</h4>
                            <p class="text-muted">${error.message}</p>
                            <button class="btn btn-outline-primary" onclick="cargarUsuarios()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Intentar de nuevo
                            </button>
                        </div>
                    `;
                    container.style.display = 'block';
                }
            }
        }

        // Función para crear tarjeta de usuario
        function crearTarjetaUsuario(usuario) {
            const col = document.createElement('div');
            col.className = 'col-md-6 col-lg-4 mb-4';
            
            const estadoBadge = usuario.usuario_situacion == 1 ? 
                '<span class="badge bg-success badge-estado">Activo</span>' : 
                '<span class="badge bg-danger badge-estado">Inactivo</span>';
            
            const fotoHTML = usuario.tiene_foto ? 
                `<img src="${usuario.foto_base64}" alt="Foto de ${usuario.usuario_nom1}" class="foto-usuario rounded-circle">` :
                `<div class="sin-foto rounded-circle">
                    <i class="bi bi-person-fill"></i>
                </div>`;
            
            col.innerHTML = `
                <div class="card usuario-card position-relative">
                    ${estadoBadge}
                    <div class="card-body text-center">
                        <div class="mb-3">
                            ${fotoHTML}
                        </div>
                        <h5 class="card-title">${usuario.usuario_nom1} ${usuario.usuario_nom2}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">${usuario.usuario_ape1} ${usuario.usuario_ape2}</h6>
                        <div class="text-start mt-3">
                            <p class="card-text mb-1">
                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                <small>${usuario.usuario_correo}</small>
                            </p>
                            <p class="card-text mb-1">
                                <i class="bi bi-telephone-fill text-success me-2"></i>
                                <small>${usuario.usuario_tel}</small>
                            </p>
                            <p class="card-text mb-1">
                                <i class="bi bi-card-text text-info me-2"></i>
                                <small>DPI: ${usuario.usuario_dpi}</small>
                            </p>
                            <p class="card-text">
                                <i class="bi bi-geo-alt-fill text-warning me-2"></i>
                                <small>${usuario.usuario_direc}</small>
                            </p>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center">
                        <small>Registrado: ${new Date(usuario.usuario_fecha_creacion).toLocaleDateString()}</small>
                    </div>
                </div>
            `;
            
            return col;
        }

        // Cargar usuarios al cargar la página (solo si existe el contenedor)
        document.addEventListener('DOMContentLoaded', function() {
            // Solo ejecutar si estamos en la página de listado de usuarios
            if (document.getElementById('usuariosContainer')) {
                cargarUsuarios();
            }
        });
    </script>
</body>
</html>