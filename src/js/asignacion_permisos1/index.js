import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const formAsignacion = document.getElementById('formAsignacion');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarAsignaciones = document.getElementById('BtnBuscarAsignaciones');
const SelectUsuario = document.getElementById('asignacion_usuario_id');
const SelectAplicacion = document.getElementById('asignacion_app_id');
const SelectPermiso = document.getElementById('asignacion_permiso_id');
const SelectUsuarioAsigno = document.getElementById('asignacion_usuario_asigno');
const seccionTabla = document.getElementById('seccionTabla');

// Función para validar formulario
const validarFormulario = (usuario, aplicacion, permiso, usuarioAsigno) => {
    if (!usuario || usuario <= 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe seleccionar un usuario",
            showConfirmButton: true,
        });
        return false;
    }

    if (!aplicacion || aplicacion <= 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe seleccionar una aplicación",
            showConfirmButton: true,
        });
        return false;
    }

    if (!permiso || permiso <= 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe seleccionar un permiso",
            showConfirmButton: true,
        });
        return false;
    }

    if (!usuarioAsigno || usuarioAsigno <= 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe especificar quién asigna el permiso",
            showConfirmButton: true,
        });
        return false;
    }

    return true;
};

// Cargar usuarios
const cargarUsuarios = async () => {
    const url = '/inicio_pmlx/asignacion_permisos1/buscarUsuariosAPI';
    
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione un usuario</option>';
            SelectUsuarioAsigno.innerHTML = '<option value="">Seleccione quién asigna</option>';
            
            data.forEach(usuario => {
                const option = document.createElement('option');
                option.value = usuario.usuario_id;
                option.textContent = `${usuario.usuario_nom1} ${usuario.usuario_ape1}`;
                SelectUsuario.appendChild(option);
                
                const option2 = document.createElement('option');
                option2.value = usuario.usuario_id;
                option2.textContent = `${usuario.usuario_nom1} ${usuario.usuario_ape1}`;
                SelectUsuarioAsigno.appendChild(option2);
            });

            // También cargar el filtro si existe
            const filtroUsuario = document.getElementById('filtroUsuario');
            if (filtroUsuario) {
                filtroUsuario.innerHTML = '<option value="">Todos los usuarios</option>';
                data.forEach(usuario => {
                    const option = document.createElement('option');
                    option.value = usuario.usuario_id;
                    option.textContent = `${usuario.usuario_nom1} ${usuario.usuario_ape1}`;
                    filtroUsuario.appendChild(option);
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
        console.log('Error cargando usuarios:', error);
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudieron cargar los usuarios",
            showConfirmButton: true,
        });
    }
};

// Cargar aplicaciones
const cargarAplicaciones = async () => {
    const url = '/inicio_pmlx/asignacion_permisos1/buscarAplicacionesAPI';
    
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

// Cargar permisos por aplicación
const cargarPermisos = async (app_id = null) => {
    if (!app_id) {
        SelectPermiso.innerHTML = '<option value="">Seleccione primero una aplicación</option>';
        return;
    }

    const url = `/inicio_pmlx/asignacion_permisos1/buscarPermisosAPI?app_id=${app_id}`;
    
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectPermiso.innerHTML = '<option value="">Seleccione un permiso</option>';
            
            data.forEach(permiso => {
                const option = document.createElement('option');
                option.value = permiso.permiso_id;
                option.textContent = `${permiso.permiso_nombre} - ${permiso.permiso_desc}`;
                SelectPermiso.appendChild(option);
            });
        } else {
            SelectPermiso.innerHTML = '<option value="">No hay permisos disponibles</option>';
        }

    } catch (error) {
        console.log('Error cargando permisos:', error);
        SelectPermiso.innerHTML = '<option value="">Error al cargar permisos</option>';
    }
};

// Event listener para cargar permisos cuando cambie la aplicación
if (SelectAplicacion) {
    SelectAplicacion.addEventListener('change', (e) => {
        const app_id = e.target.value;
        cargarPermisos(app_id);
    });
}

// Guardar asignación
const guardarAsignacion = async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    const usuario = document.getElementById('asignacion_usuario_id').value;
    const aplicacion = document.getElementById('asignacion_app_id').value;
    const permiso = document.getElementById('asignacion_permiso_id').value;
    const usuarioAsigno = document.getElementById('asignacion_usuario_asigno').value;

    if (!validarFormulario(usuario, aplicacion, permiso, usuarioAsigno)) {
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(formAsignacion);
    const url = "/inicio_pmlx/asignacion_permisos1/guardarAPI";

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
                BuscarAsignaciones();
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
        console.log('Error guardando asignación:', error);
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

// Buscar asignaciones
const BuscarAsignaciones = async (filtros = {}) => {
    let url = '/inicio_pmlx/asignacion_permisos1/buscarAPI';
    const params = new URLSearchParams();

    if (filtros.usuario_id) {
        params.append('usuario_id', filtros.usuario_id);
    }

    if (filtros.app_id) {
        params.append('app_id', filtros.app_id);
    }

    if (params.toString()) {
        url += '?' + params.toString();
    }

    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Asignaciones encontradas:', data.length);

            if (datatable) {
                datatable.clear().draw();
                if (data.length > 0) {
                    datatable.rows.add(data).draw();
                }
            }
        } else {
            console.log('Error al buscar asignaciones:', mensaje);
            if (datatable) {
                datatable.clear().draw();
            }
        }

    } catch (error) {
        console.log('Error en BuscarAsignaciones:', error);
        if (datatable) {
            datatable.clear().draw();
        }
    }
};

// Mostrar/ocultar tabla
const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarAsignaciones();
        cargarEstadisticas();
        
        // Mostrar sección de estadísticas
        const seccionEstadisticas = document.getElementById('seccionEstadisticas');
        if (seccionEstadisticas) {
            seccionEstadisticas.style.display = 'block';
        }
    } else {
        seccionTabla.style.display = 'none';
        
        // Ocultar sección de estadísticas
        const seccionEstadisticas = document.getElementById('seccionEstadisticas');
        if (seccionEstadisticas) {
            seccionEstadisticas.style.display = 'none';
        }
    }
};

// Cargar estadísticas
const cargarEstadisticas = async () => {
    try {
        const url = '/inicio_pmlx/asignacion_permisos1/estadisticasAPI';
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        
        if (datos.codigo == 1 && datos.data) {
            const stats = datos.data;
            
            const totalAsignaciones = document.getElementById('totalAsignaciones');
            const usuariosConPermisos = document.getElementById('usuariosConPermisos');
            const appsConAsignaciones = document.getElementById('appsConAsignaciones');
            
            if (totalAsignaciones) totalAsignaciones.textContent = stats.total_asignaciones || 0;
            if (usuariosConPermisos) usuariosConPermisos.textContent = stats.usuarios_con_permisos || 0;
            if (appsConAsignaciones) appsConAsignaciones.textContent = stats.apps_con_asignaciones || 0;
        }
    } catch (error) {
        console.log('Error cargando estadísticas:', error);
    }
};

// Limpiar formulario
const limpiarTodo = () => {
    formAsignacion.reset();
    SelectPermiso.innerHTML = '<option value="">Seleccione primero una aplicación</option>';
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
};

// Llenar formulario para modificar
const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('asignacion_id').value = datos.id;
    document.getElementById('asignacion_usuario_id').value = datos.usuario;
    document.getElementById('asignacion_app_id').value = datos.app;
    document.getElementById('asignacion_usuario_asigno').value = datos.asigno;
    document.getElementById('asignacion_motivo').value = datos.motivo;

    // Cargar permisos de la aplicación seleccionada y luego seleccionar el permiso
    if (datos.app) {
        await cargarPermisos(datos.app);
        document.getElementById('asignacion_permiso_id').value = datos.permiso;
    }

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

// Modificar asignación
const ModificarAsignacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const usuario = document.getElementById('asignacion_usuario_id').value;
    const aplicacion = document.getElementById('asignacion_app_id').value;
    const permiso = document.getElementById('asignacion_permiso_id').value;
    const usuarioAsigno = document.getElementById('asignacion_usuario_asigno').value;

    if (!validarFormulario(usuario, aplicacion, permiso, usuarioAsigno)) {
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(formAsignacion);
    const url = '/inicio_pmlx/asignacion_permisos1/modificarAPI';

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
            BuscarAsignaciones();
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
        console.log('Error modificando asignación:', error);
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

// Eliminar asignación
const EliminarAsignacion = async (e) => {
    const idAsignacion = e.currentTarget.dataset.id;

    const confirmacion = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: 'Está completamente seguro que desea eliminar esta asignación de permiso',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (confirmacion.isConfirmed) {
        const url = `/inicio_pmlx/asignacion_permisos1/eliminar?id=${idAsignacion}`;

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
                
                BuscarAsignaciones();
                cargarEstadisticas();
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
            console.log('Error eliminando asignación:', error);
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

// Funciones de filtrado
const FiltrarPorUsuario = () => {
    const filtroUsuario = document.getElementById('filtroUsuario');
    const usuario_id = filtroUsuario ? filtroUsuario.value : '';
    const filtroApp = document.getElementById('filtroApp');
    const app_id = filtroApp ? filtroApp.value : '';
    
    BuscarAsignaciones({
        usuario_id: usuario_id,
        app_id: app_id
    });
};

const FiltrarPorApp = () => {
    const filtroApp = document.getElementById('filtroApp');
    const app_id = filtroApp ? filtroApp.value : '';
    const filtroUsuario = document.getElementById('filtroUsuario');
    const usuario_id = filtroUsuario ? filtroUsuario.value : '';
    
    BuscarAsignaciones({
        app_id: app_id,
        usuario_id: usuario_id
    });
};

const limpiarFiltros = () => {
    const filtroUsuario = document.getElementById('filtroUsuario');
    const filtroApp = document.getElementById('filtroApp');
    
    if (filtroUsuario) filtroUsuario.value = '';
    if (filtroApp) filtroApp.value = '';
    
    BuscarAsignaciones();
};

// Inicializar DataTable
const datatable = new DataTable('#TableAsignaciones', {
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
            data: 'asignacion_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Usuario', 
            data: 'usuario_nom1',
            width: '15%',
            render: (data, type, row) => {
                return `${row.usuario_nom1} ${row.usuario_ape1}`;
            }
        },
        { 
            title: 'Aplicación', 
            data: 'app_nombre_corto',
            width: '12%'
        },
        { 
            title: 'Permiso', 
            data: 'permiso_nombre',
            width: '15%'
        },
        { 
            title: 'Descripción del Permiso', 
            data: 'permiso_desc',
            width: '18%'
        },
        { 
            title: 'Fecha Asignación', 
            data: 'asignacion_fecha',
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
            title: 'Asignado por',
            data: 'asigno_nom1',
            width: '15%',
            render: (data, type, row) => {
                return `${row.asigno_nom1} ${row.asigno_ape1}`;
            }
        },
        {
            title: 'Situación',
            data: 'asignacion_situacion',
            width: '8%',
            render: (data, type, row) => {
                return data == 1 ? 
                    '<span class="badge bg-success">ACTIVO</span>' : 
                    '<span class="badge bg-danger">INACTIVO</span>';
            }
        },
        {
            title: 'Acciones',
            data: 'asignacion_id',
            width: '15%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning btn-sm modificar mx-1' 
                         data-id="${data}" 
                         data-usuario="${row.asignacion_usuario_id || ''}"  
                         data-app="${row.asignacion_app_id || ''}"  
                         data-permiso="${row.asignacion_permiso_id || ''}"  
                         data-asigno="${row.asignacion_usuario_asigno || ''}"
                         data-motivo="${row.asignacion_motivo || ''}"
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
    cargarUsuarios();
    cargarAplicaciones();
    
    // Event listeners principales
    if (formAsignacion) {
        formAsignacion.addEventListener('submit', guardarAsignacion);
    }

    if (BtnLimpiar) {
        BtnLimpiar.addEventListener('click', limpiarTodo);
    }

    if (BtnModificar) {
        BtnModificar.addEventListener('click', ModificarAsignacion);
    }

    if (BtnBuscarAsignaciones) {
        BtnBuscarAsignaciones.addEventListener('click', MostrarTabla);
    }

    // Event listeners para la tabla
    datatable.on('click', '.eliminar', EliminarAsignacion);
    datatable.on('click', '.modificar', llenarFormulario);

    // Event listeners para filtros
    const filtroUsuario = document.getElementById('filtroUsuario');
    const filtroApp = document.getElementById('filtroApp');
    
    if (filtroUsuario) {
        filtroUsuario.addEventListener('change', FiltrarPorUsuario);
    }
    
    if (filtroApp) {
        filtroApp.addEventListener('change', FiltrarPorApp);
    }
});

// Hacer función global para limpiar filtros
window.limpiarFiltros = limpiarFiltros;