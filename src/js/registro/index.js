import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormRegistro = document.getElementById('registroForm');
const BtnRegistrar = document.querySelector('.btn-primary');
const BtnLimpiar = document.querySelector('.btn-secondary');
const InputPassword = document.getElementById('usuario_contra');
const InputConfirmarPassword = document.getElementById('confirmar_contra');
const InputDPI = document.getElementById('usuario_dpi');
const InputCorreo = document.getElementById('usuario_correo');
const InputTelefono = document.getElementById('usuario_tel');
const TogglePassword = document.getElementById('togglePassword');

// Validar contraseña en tiempo real
const ValidarPassword = () => {
    const password = InputPassword.value;
    const feedback = InputPassword.parentElement.nextElementSibling;

    if (password.length < 1) {
        InputPassword.classList.remove('is-valid', 'is-invalid');
        return;
    }

    const hasLength = password.length >= 10;
    const hasUpper = /[A-Z]/.test(password);
    const hasSign = /[,.]/.test(password);

    let message = 'Requisitos: ';
    message += hasLength ? '✅ 10+ caracteres ' : '❌ 10+ caracteres ';
    message += hasUpper ? '✅ 1 mayúscula ' : '❌ 1 mayúscula ';
    message += hasSign ? '✅ 1 signo (,.)' : '❌ 1 signo (,.)';

    feedback.innerHTML = message;
    feedback.style.color = (hasLength && hasUpper && hasSign) ? 'green' : 'orange';

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
        
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Contraseñas no coinciden",
            text: "Las contraseñas deben ser idénticas",
            showConfirmButton: true,
        });
    }
}

// Validar DPI
const ValidarDPI = () => {
    const dpi = InputDPI.value.replace(/\D/g, ''); // Solo números
    InputDPI.value = dpi; // Actualizar el campo

    if (dpi.length < 1) {
        InputDPI.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (dpi.length === 13) {
        // Verificar si el DPI ya existe
        verificarDuplicado('dpi', dpi);
    } else {
        InputDPI.classList.remove('is-valid');
        InputDPI.classList.add('is-invalid');
        
        Swal.fire({
            position: "center",
            icon: "error",
            title: "DPI inválido",
            text: "El DPI debe tener exactamente 13 dígitos",
            showConfirmButton: true,
        });
    }
}

// Validar teléfono
const ValidarTelefono = () => {
    const telefono = InputTelefono.value.replace(/\D/g, ''); // Solo números
    InputTelefono.value = telefono; // Actualizar el campo

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
        
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Teléfono inválido",
            text: "El teléfono debe tener exactamente 8 dígitos",
            showConfirmButton: true,
        });
    }
}

// Validar correo electrónico
const ValidarCorreo = () => {
    const correo = InputCorreo.value.trim();

    if (correo.length < 1) {
        InputCorreo.classList.remove('is-valid', 'is-invalid');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (emailRegex.test(correo)) {
        // Verificar si el correo ya existe
        verificarDuplicado('correo', correo);
    } else {
        InputCorreo.classList.remove('is-valid');
        InputCorreo.classList.add('is-invalid');
        
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Correo inválido",
            text: "Ingrese un correo electrónico válido",
            showConfirmButton: true,
        });
    }
}

// Verificar duplicados (DPI y correo)
const verificarDuplicado = async (campo, valor) => {
    const url = '/registro/validarDuplicados';
    const formData = new FormData();
    formData.append('campo', campo);
    formData.append('valor', valor);

    const config = {
        method: 'POST',
        body: formData
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            if (datos.existe) {
                if (campo === 'dpi') {
                    InputDPI.classList.remove('is-valid');
                    InputDPI.classList.add('is-invalid');
                } else {
                    InputCorreo.classList.remove('is-valid');
                    InputCorreo.classList.add('is-invalid');
                }
                
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Ya existe",
                    text: datos.mensaje,
                    showConfirmButton: true,
                });
            } else {
                if (campo === 'dpi') {
                    InputDPI.classList.remove('is-invalid');
                    InputDPI.classList.add('is-valid');
                } else {
                    InputCorreo.classList.remove('is-invalid');
                    InputCorreo.classList.add('is-valid');
                }
            }
        }
    } catch (error) {
        console.log('Error al verificar duplicado:', error);
    }
}

// Toggle mostrar/ocultar contraseña
const togglePasswordVisibility = () => {
    const icon = TogglePassword.querySelector('i');
    
    if (InputPassword.type === 'password') {
        InputPassword.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        InputPassword.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Guardar usuario
const GuardarUsuario = async (event) => {
    event.preventDefault();
    BtnRegistrar.disabled = true;

    // Cambiar texto del botón
    const originalText = BtnRegistrar.innerHTML;
    BtnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registrando...';

    // Validar que las contraseñas coincidan
    if (InputPassword.value !== InputConfirmarPassword.value) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Las contraseñas no coinciden",
            showConfirmButton: true,
        });
        BtnRegistrar.disabled = false;
        BtnRegistrar.innerHTML = originalText;
        return;
    }

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

    const body = new FormData(FormRegistro);

    const url = '/registro/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarFormulario();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log('Error:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }

    BtnRegistrar.disabled = false;
    BtnRegistrar.innerHTML = originalText;
}

// Limpiar formulario
const limpiarFormulario = () => {
    FormRegistro.reset();
    
    // Limpiar vista previa de foto
    const photoPreview = document.getElementById('photoPreview');
    if (photoPreview) {
        photoPreview.innerHTML = '';
    }

    // Remover clases de validación
    const inputs = FormRegistro.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    // Resetear feedback de contraseña
    const feedback = InputPassword.parentElement.nextElementSibling;
    feedback.innerHTML = 'Mínimo 10 caracteres, 1 mayúscula y un signo (,.)';
    feedback.style.color = '';

    // Scroll al inicio
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Vista previa de fotografía
const mostrarVistaPrevia = (event) => {
    const file = event.target.files[0];
    const preview = document.getElementById('photoPreview');
    
    if (file) {
        // Validar tamaño (2MB máximo)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Archivo muy grande",
                text: "La imagen no debe superar los 2MB",
                showConfirmButton: true,
            });
            event.target.value = '';
            return;
        }

        // Validar tipo de archivo
        if (!file.type.startsWith('image/')) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Tipo de archivo inválido",
                text: "Solo se permiten archivos de imagen",
                showConfirmButton: true,
            });
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="photo-preview" alt="Vista previa">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
}

// Event Listeners
FormRegistro.addEventListener('submit', GuardarUsuario);
InputPassword.addEventListener('input', ValidarPassword);
InputConfirmarPassword.addEventListener('input', ValidarConfirmarPassword);
InputDPI.addEventListener('input', ValidarDPI);
InputCorreo.addEventListener('blur', ValidarCorreo);
InputTelefono.addEventListener('input', ValidarTelefono);
TogglePassword.addEventListener('click', togglePasswordVisibility);
BtnLimpiar.addEventListener('click', limpiarFormulario);

// Event listener para fotografía
const inputFotografia = document.getElementById('usuario_fotografia');
if (inputFotografia) {
    inputFotografia.addEventListener('change', mostrarVistaPrevia);
}

// Validaciones en tiempo real para nombres y apellidos
document.querySelectorAll('#usuario_nom1, #usuario_nom2, #usuario_ape1, #usuario_ape2').forEach(input => {
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
});

// Validación para dirección
document.getElementById('usuario_direc').addEventListener('input', function() {
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