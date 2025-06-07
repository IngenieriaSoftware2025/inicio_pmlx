import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// IMPORTANTE: Esperar a que cargue el DOM
document.addEventListener('DOMContentLoaded', function() {
    
    const FormRegistro = document.getElementById('registroForm');
    const BtnRegistrar = document.querySelector('.btn-primary');
    const BtnLimpiar = document.querySelector('.btn-secondary');
    const InputPassword = document.getElementById('usuario_contra');
    const InputConfirmarPassword = document.getElementById('confirmar_contra');
    const InputDPI = document.getElementById('usuario_dpi');
    const InputCorreo = document.getElementById('usuario_correo');
    const InputTelefono = document.getElementById('usuario_tel');
    const TogglePassword = document.getElementById('togglePassword');

    // Verificar que los elementos existen
    if (!FormRegistro) {
        console.log('No se encontró el formulario');
        return;
    }

    // Validar contraseña en tiempo real
    const ValidarPassword = () => {
        if (!InputPassword) return;
        
        const password = InputPassword.value;
        const feedback = InputPassword.parentElement.nextElementSibling;

        if (password.length < 1) {
            InputPassword.classList.remove('is-valid', 'is-invalid');
            return;
        }

        const hasLength = password.length >= 10;
        const hasUpper = /[A-Z]/.test(password);
        const hasSign = /[,.]/.test(password);

        if (feedback) {
            let message = 'Requisitos: ';
            message += hasLength ? '✅ 10+ caracteres ' : '❌ 10+ caracteres ';
            message += hasUpper ? '✅ 1 mayúscula ' : '❌ 1 mayúscula ';
            message += hasSign ? '✅ 1 signo (,.)' : '❌ 1 signo (,.)';

            feedback.innerHTML = message;
            feedback.style.color = (hasLength && hasUpper && hasSign) ? 'green' : 'orange';
        }

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
            
            // Solo mostrar alerta si el usuario ha terminado de escribir
            if (confirmar.length > 0) {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Contraseñas no coinciden",
                    text: "Las contraseñas deben ser idénticas",
                    showConfirmButton: true,
                });
            }
        }
    }

    // Validar DPI (sin verificar duplicados por ahora)
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

    // Validar correo electrónico (sin verificar duplicados por ahora)
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

    // Guardar usuario
    const GuardarUsuario = async (event) => {
        event.preventDefault();
        
        if (!BtnRegistrar) return;
        
        BtnRegistrar.disabled = true;
        const originalText = BtnRegistrar.innerHTML;
        BtnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registrando...';

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
       const url = '/inicio_pmlx/registro/guardarAPI';
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

        if (InputPassword) {
            const feedback = InputPassword.parentElement.nextElementSibling;
            if (feedback) {
                feedback.innerHTML = 'Mínimo 10 caracteres, 1 mayúscula y un signo (,.)';
                feedback.style.color = '';
            }
        }

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Event Listeners - Solo si los elementos existen
    if (FormRegistro) FormRegistro.addEventListener('submit', GuardarUsuario);
    if (InputPassword) InputPassword.addEventListener('input', ValidarPassword);
    if (InputConfirmarPassword) InputConfirmarPassword.addEventListener('blur', ValidarConfirmarPassword);
    if (InputDPI) InputDPI.addEventListener('input', ValidarDPI);
    if (InputCorreo) InputCorreo.addEventListener('blur', ValidarCorreo);
    if (InputTelefono) InputTelefono.addEventListener('input', ValidarTelefono);
    if (TogglePassword) TogglePassword.addEventListener('click', togglePasswordVisibility);
    if (BtnLimpiar) BtnLimpiar.addEventListener('click', limpiarFormulario);

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

}); // Fin de DOMContentLoaded