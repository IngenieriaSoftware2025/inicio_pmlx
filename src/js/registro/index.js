// JavaScript completo para registro y listado con auto-refresh

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

// Función para cargar usuarios (mejorada)
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
        console.log('Cargando usuarios...');
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
                        console.log('Procesando usuario:', usuario); // Debug
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

// Función para crear tarjeta de usuario (mejorada)
function crearTarjetaUsuario(usuario) {
    const col = document.createElement('div');
    col.className = 'col-md-6 col-lg-4 mb-4';
    
    // Manejar valores undefined
    const nom1 = usuario.usuario_nom1 || 'Sin nombre';
    const nom2 = usuario.usuario_nom2 || '';
    const ape1 = usuario.usuario_ape1 || 'Sin apellido';
    const ape2 = usuario.usuario_ape2 || '';
    const correo = usuario.usuario_correo || 'Sin correo';
    const telefono = usuario.usuario_tel || 'Sin teléfono';
    const dpi = usuario.usuario_dpi || 'Sin DPI';
    const direccion = usuario.usuario_direc || 'Sin dirección';
    const fechaCreacion = usuario.usuario_fecha_creacion || new Date().toISOString().split('T')[0];
    
    const estadoBadge = usuario.usuario_situacion == 1 ? 
        '<span class="badge bg-success badge-estado">Activo</span>' : 
        '<span class="badge bg-danger badge-estado">Inactivo</span>';
    
    const fotoHTML = usuario.tiene_foto ? 
        `<img src="${usuario.foto_base64}" alt="Foto de ${nom1}" class="foto-usuario rounded-circle">` :
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
                <h5 class="card-title">${nom1} ${nom2}</h5>
                <h6 class="card-subtitle mb-2 text-muted">${ape1} ${ape2}</h6>
                <div class="text-start mt-3">
                    <p class="card-text mb-1">
                        <i class="bi bi-envelope-fill text-primary me-2"></i>
                        <small>${correo}</small>
                    </p>
                    <p class="card-text mb-1">
                        <i class="bi bi-telephone-fill text-success me-2"></i>
                        <small>${telefono}</small>
                    </p>
                    <p class="card-text mb-1">
                        <i class="bi bi-card-text text-info me-2"></i>
                        <small>DPI: ${dpi}</small>
                    </p>
                    <p class="card-text">
                        <i class="bi bi-geo-alt-fill text-warning me-2"></i>
                        <small>${direccion}</small>
                    </p>
                </div>
            </div>
            <div class="card-footer text-muted text-center">
                <small>Registrado: ${new Date(fechaCreacion).toLocaleDateString()}</small>
            </div>
        </div>
    `;
    
    return col;
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

    // Si estamos en la página de listado, cargar usuarios
    if (document.getElementById('usuariosContainer')) {
        cargarUsuarios();
        return; // No continuar con el resto del código del formulario
    }

    // Verificar que los elementos del formulario existen
    if (!FormRegistro) {
        console.log('No se encontró el formulario de registro');
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

    // FUNCIÓN GUARDAR USUARIO CON AUTO-REFRESH
    const GuardarUsuario = async (event) => {
        event.preventDefault();
        
        console.log('Iniciando proceso de registro...');
        
        if (!BtnRegistrar) return;
        
        BtnRegistrar.disabled = true;
        const originalText = BtnRegistrar.innerHTML;
        BtnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registrando...';

        // Validar formulario
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

        const body = new FormData(FormRegistro);
        const url = '/inicio_pmlx/registro/guardarAPI';
        
        try {
            const respuesta = await fetch(url, {
                method: 'POST',
                body: body
            });
            
            const textoRespuesta = await respuesta.text();
            
            let datos;
            try {
                datos = JSON.parse(textoRespuesta);
            } catch (jsonError) {
                throw new Error(`El servidor no devolvió JSON válido. Respuesta: ${textoRespuesta.substring(0, 200)}`);
            }
            
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

                // Limpiar formulario
                limpiarFormulario();
                
                // AUTO-REFRESH: Si hay un contenedor de usuarios, recargar la lista
                if (window.cargarUsuarios && document.getElementById('usuariosContainer')) {
                    console.log('Recargando lista de usuarios...');
                    setTimeout(() => {
                        cargarUsuarios();
                    }, 500);
                }
                
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