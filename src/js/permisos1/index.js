import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const formPermiso = document.getElementById('formPermiso');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarPermisos = document.getElementById('BtnBuscarPermisos');
const SelectAplicacion = document.getElementById('permiso_app_id');
const inputBuscar = document.getElementById('inputBuscar');
const BtnBuscarTexto = document.getElementById('BtnBuscarTexto');
const seccionTabla = document.getElementById('seccionTabla');

// Función para validar formulario
const validarFormulario = (aplicacion, nombre, clave, descripcion) => {
    if (!aplicacion) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe seleccionar una aplicación",
            showConfirmButton: true,
        });
        return false;
    }

    if (!nombre || nombre.length < 2) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "El nombre del permiso debe tener más de 1 carácter",
            showConfirmButton: true,
        });
        document.getElementById('permiso_nombre').focus();
        return false;
    }

    if (!clave || clave.length < 2) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "La clave del permiso debe tener más de 1 carácter",
            showConfirmButton: true,
        });
        document.getElementById('permiso_clave').focus();
        return false;
    }

    if (!descripcion || descripcion.length < 5) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "La descripción debe tener más de 4 caracteres",
            showConfirmButton: true,
        });
        document.getElementById('permiso_desc').focus();
        return false;
    }

    return true;
};

// Cargar aplicaciones
const cargarAplicaciones = async () => {
    const url = '/inicio_pmlx/permisos1/buscarAplicacionesAPI';
    
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectAplicacion.innerHTML = '<option value="">Seleccione una aplicación</option>';
            
            data.forEach(app => {
                const option = document.createElement('option');
                option.value = app.app_id;
                option.textContent = app.app_nombre_corto;
                SelectAplicacion.appendChild(option);
            });

            // También cargar el filtro si existe
            const filtroApp = document.getElementById('filtroApp');
            if (filtroApp) {
                filtroApp.innerHTML = '<option value="">Todas las aplicaciones</option>';
                data.forEach(app => {
                    const option = document.createElement('option');
                    option.value = app.app_id;
                    option.textContent = app.app_nombre_corto;
                    filtroApp.appendChild(option);
                });
            }
        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log('Error cargando aplicaciones:', error);
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudieron cargar las aplicaciones",
            showConfirmButton: true,
        });
    }
};

// Guardar permiso
const guardarPermiso = async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    const aplicacion = document.getElementById('permiso_app_id').value;
    const nombre = document.getElementById('permiso_nombre').value.trim();
    const clave = document.getElementById('permiso_clave').value.trim();
    const descripcion = document.getElementById('permiso_desc').value.trim();

    if (!validarFormulario(aplicacion, nombre, clave, descripcion)) {
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(formPermiso);
    const url = "/inicio_pmlx/permisos1/guardarAPI";

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body: body
        });

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

            limpiarTodo();
            if (seccionTabla.style.display !== 'none') {
                BuscarPermisos();
            }
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
        console.log('Error guardando permiso:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    
    BtnGuardar.disabled = false;
};

// Buscar permisos
const BuscarPermisos = async (filtros = {}) => {
    let url = '/inicio_pmlx/permisos1/buscarAPI';
    const params = new URLSearchParams();

    if (filtros.app_id) {
        params.append('app_id', filtros.app_id);
    }

    if (filtros.buscar) {
        params.append('buscar', filtros.buscar);
    }

    if (params.toString()) {
        url += '?' + params.toString();
    }

    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Permisos encontrados:', data.length);

            if (datatable) {
                datatable.clear().draw();
                if (data.length > 0) {
                    datatable.rows.add(data).draw();
                }
            }
        } else {
            console.log('Error al buscar permisos:', mensaje);
            if (datatable) {
                datatable.clear().draw();
            }
        }

    } catch (error) {
        console.log('Error en BuscarPermisos:', error);
        if (datatable) {
            datatable.clear().draw();
        }
    }
};

// Mostrar/ocultar tabla
const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarPermisos();
    } else {
        seccionTabla.style.display = 'none';
    }
};

// Limpiar formulario
const limpiarTodo = () => {
    formPermiso.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
};

// Generar clave automáticamente
const generarClave = () => {
    const nombre = document.getElementById('permiso_nombre').value.trim();
    if (nombre) {
        const clave = nombre.toUpperCase()
            .replace(/[ÁÉÍÓÚÑ]/g, (match) => {
                const acentos = { 'Á': 'A', 'É': 'E', 'Í': 'I', 'Ó': 'O', 'Ú': 'U', 'Ñ': 'N' };
                return acentos[match] || match;
            })
            .replace(/[^A-Z0-9\s]/g, '')
            .replace(/\s+/g, '_')
            .substring(0, 50);
        
        document.getElementById('permiso_clave').value = clave;
    }
};

// Llenar formulario para modificar
const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('permiso_id').value = datos.id;
    document.getElementById('permiso_app_id').value = datos.app;
    document.getElementById('permiso_nombre').value = datos.nombre;
    document.getElementById('permiso_clave').value = datos.clave;
    document.getElementById('permiso_desc').value = datos.desc;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

// Modificar permiso
const ModificarPermiso = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const aplicacion = document.getElementById('permiso_app_id').value;
    const nombre = document.getElementById('permiso_nombre').value.trim();
    const clave = document.getElementById('permiso_clave').value.trim();
    const descripcion = document.getElementById('permiso_desc').value.trim();

    if (!validarFormulario(aplicacion, nombre, clave, descripcion)) {
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(formPermiso);
    const url = '/inicio_pmlx/permisos1/modificarAPI';

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body: body
        });

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

            limpiarTodo();
            BuscarPermisos();
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
        console.log('Error modificando permiso:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    
    BtnModificar.disabled = false;
};

// Eliminar permiso
const EliminarPermisos = async (e) => {
    const idPermiso = e.currentTarget.dataset.id;

    const confirmacion = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: 'Está completamente seguro que desea eliminar este permiso',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (confirmacion.isConfirmed) {
        const url = `/inicio_pmlx/permisos1/eliminar?id=${idPermiso}`;

        try {
            const respuesta = await fetch(url);
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
                
                BuscarPermisos();
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
            console.log('Error eliminando permiso:', error);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo conectar con el servidor",
                showConfirmButton: true,
            });
        }
    }
};

// Ver detalles del permiso
const verDetalles = async (event) => {
    const id = event.currentTarget.dataset.id;
    
    try {
        const url = `/inicio_pmlx/permisos1/obtenerPermisoPorIdAPI?id=${id}`;
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        
        if (datos.codigo == 1 && datos.data) {
            const permiso = datos.data;
            await Swal.fire({
                title: 'Detalles del Permiso',
                html: `
                    <div class="text-start">
                        <p><strong>ID:</strong> ${permiso.permiso_id}</p>
                        <p><strong>Aplicación:</strong> ${permiso.app_nombre_corto}</p>
                        <p><strong>Nombre:</strong> ${permiso.permiso_nombre}</p>
                        <p><strong>Clave:</strong> <code>${permiso.permiso_clave}</code></p>
                        <p><strong>Descripción:</strong> ${permiso.permiso_desc}</p>
                        <p><strong>Fecha Creación:</strong> ${new Date(permiso.permiso_fecha).toLocaleString('es-GT')}</p>
                        <p><strong>Situación:</strong> ${permiso.permiso_situacion == 1 ? 'Activo' : 'Inactivo'}</p>
                    </div>
                `,
                width: 600,
                confirmButtonText: 'Cerrar'
            });
        } else {
            Swal.fire('Error', 'No se encontró el permiso', 'error');
        }
    } catch (error) {
        console.log('Error obteniendo detalles:', error);
        Swal.fire('Error', 'No se pudo obtener los detalles del permiso', 'error');
    }
};

// Funciones de filtrado
const BuscarPorTexto = () => {
    const textoBuscar = inputBuscar ? inputBuscar.value.trim() : '';
    const filtroApp = document.getElementById('filtroApp');
    const app_id = filtroApp ? filtroApp.value : '';
    
    BuscarPermisos({
        buscar: textoBuscar,
        app_id: app_id
    });
};

const FiltrarPorApp = () => {
    const filtroApp = document.getElementById('filtroApp');
    const app_id = filtroApp ? filtroApp.value : '';
    const textoBuscar = inputBuscar ? inputBuscar.value.trim() : '';
    
    BuscarPermisos({
        app_id: app_id,
        buscar: textoBuscar
    });
};

const limpiarFiltros = () => {
    const filtroApp = document.getElementById('filtroApp');
    if (filtroApp) filtroApp.value = '';
    if (inputBuscar) inputBuscar.value = '';
    BuscarPermisos();
};

// Inicializar DataTable
const datatable = new DataTable('#TablePermisos', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'permiso_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Aplicación', 
            data: 'app_nombre_corto',
            width: '15%'
        },
        { 
            title: 'Nombre del Permiso', 
            data: 'permiso_nombre',
            width: '20%'
        },
        { 
            title: 'Clave del Permiso', 
            data: 'permiso_clave',
            width: '15%',
            render: (data, type, row) => {
                return `<code class="bg-light text-dark px-2 py-1 rounded">${data}</code>`;
            }
        },
        { 
            title: 'Descripción', 
            data: 'permiso_desc',
            width: '25%'
        },
        { 
            title: 'Fecha Creación', 
            data: 'permiso_fecha',
            width: '12%',
            render: (data, type, row) => {
                if (data) {
                    const fecha = new Date(data);
                    return fecha.toLocaleDateString('es-GT') + '<br><small>' + fecha.toLocaleTimeString('es-GT', {hour: '2-digit', minute: '2-digit'}) + '</small>';
                }
                return '';
            }
        },
        {
            title: 'Situación',
            data: 'permiso_situacion',
            width: '8%',
            render: (data, type, row) => {
                return data == 1 ? 
                    '<span class="badge bg-success">ACTIVO</span>' : 
                    '<span class="badge bg-danger">INACTIVO</span>';
            }
        },
        {
            title: 'Acciones',
            data: 'permiso_id',
            width: '15%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-info btn-sm ver mx-1' 
                         data-id="${data}" 
                         title="Ver Detalles">
                         <i class='bi bi-eye me-1'></i> Ver
                     </button>
                     <button class='btn btn-warning btn-sm modificar mx-1' 
                         data-id="${data}" 
                         data-app="${row.permiso_app_id || ''}"  
                         data-nombre="${row.permiso_nombre || ''}"  
                         data-clave="${row.permiso_clave || ''}"  
                         data-desc="${row.permiso_desc || ''}"
                         title="Modificar">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger btn-sm eliminar mx-1' 
                         data-id="${data}"
                         title="Eliminar">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar
    cargarAplicaciones();
    
    // Event listeners principales
    if (formPermiso) {
        formPermiso.addEventListener('submit', guardarPermiso);
    }

    if (BtnLimpiar) {
        BtnLimpiar.addEventListener('click', limpiarTodo);
    }

    if (BtnModificar) {
        BtnModificar.addEventListener('click', ModificarPermiso);
    }

    if (BtnBuscarPermisos) {
        BtnBuscarPermisos.addEventListener('click', MostrarTabla);
    }

    // Event listeners para la tabla
    datatable.on('click', '.eliminar', EliminarPermisos);
    datatable.on('click', '.modificar', llenarFormulario);
    datatable.on('click', '.ver', verDetalles);

    // Event listeners para filtros
    if (BtnBuscarTexto) {
        BtnBuscarTexto.addEventListener('click', BuscarPorTexto);
    }

    if (inputBuscar) {
        inputBuscar.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                BuscarPorTexto();
            }
        });
    }

    // Event listener para filtro de aplicación
    const filtroApp = document.getElementById('filtroApp');
    if (filtroApp) {
        filtroApp.addEventListener('change', FiltrarPorApp);
    }

    // Event listener para generar clave automáticamente
    const nombreInput = document.getElementById('permiso_nombre');
    if (nombreInput) {
        nombreInput.addEventListener('blur', generarClave);
    }
});

// Hacer función global para limpiar filtros
window.limpiarFiltros = limpiarFiltros;