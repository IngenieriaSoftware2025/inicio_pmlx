// ✅ USUARIOS DEBUG VERSION

console.log('🚀 === INICIANDO USUARIOS.JS ===');

import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

// Detectar base URL
const baseUrl = window.location.pathname.split('/').slice(0, 2).join('/');
console.log('🔧 Base URL detectada:', baseUrl);

// Test de conectividad para usuarios
const testConectividadUsuarios = async () => {
    console.log('🧪 === TEST DE CONECTIVIDAD USUARIOS ===');
    
    const urls = [
        `${baseUrl}/usuarios/buscarAPI`,
        `${baseUrl}/usuarios/roles`
    ];
    
    for (const url of urls) {
        console.log(`🔗 Probando: ${url}`);
        
        try {
            const respuesta = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            console.log(`📡 ${url} - Status: ${respuesta.status}`);
            
            if (respuesta.ok) {
                const contentType = respuesta.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const datos = await respuesta.json();
                    console.log(`✅ ${url} - Respuesta:`, datos);
                } else {
                    const texto = await respuesta.text();
                    console.log(`⚠️ ${url} - No es JSON:`, texto.substring(0, 300));
                }
            } else {
                const error = await respuesta.text();
                console.log(`❌ ${url} - Error:`, error.substring(0, 300));
            }
        } catch (error) {
            console.error(`❌ ${url} - Error de red:`, error);
        }
    }
};

// Elementos del DOM
console.log('🔍 Verificando elementos del DOM...');
const FormUsuarios = document.getElementById('FormUsuarios');
const TableUsuarios = document.getElementById('TableUsuarios');

console.log('FormUsuarios:', FormUsuarios ? '✅' : '❌');
console.log('TableUsuarios:', TableUsuarios ? '✅' : '❌');

// DataTable para usuarios
let datatableUsuarios;
if (TableUsuarios) {
    try {
        datatableUsuarios = new DataTable('#TableUsuarios', {
            language: lenguaje,
            data: [],
            responsive: true,
            columns: [
                {
                    title: 'No.',
                    data: 'id_usuario',
                    width: '5%',
                    render: (data, type, row, meta) => meta.row + 1
                },
                { 
                    title: 'Nombre Completo', 
                    data: 'nombre',
                    render: (data, type, row) => `${data || ''} ${row.apellido || ''}`
                },
                { title: 'Email', data: 'email' },
                { title: 'Rol', data: 'nombre_rol' },
                { 
                    title: 'Estado', 
                    data: 'activo',
                    render: (data) => data === 'S' ? 
                        '<span class="badge bg-success">Activo</span>' : 
                        '<span class="badge bg-danger">Inactivo</span>'
                },
                {
                    title: 'Acciones',
                    data: 'id_usuario',
                    searchable: false,
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                        <div class='d-flex gap-1'>
                             <button class='btn btn-warning btn-sm modificar' 
                                 data-id="${data}" 
                                 data-nombre="${row.nombre || ''}"  
                                 data-apellido="${row.apellido || ''}"  
                                 data-email="${row.email || ''}"
                                 data-id_rol="${row.id_rol || ''}"
                                 data-activo="${row.activo || ''}"
                                 title="Modificar">
                                 <i class='fas fa-edit'></i>
                             </button>
                             <button class='btn btn-danger btn-sm eliminar' 
                                 data-id="${data}"
                                 title="Eliminar">
                                <i class="fas fa-trash"></i>
                             </button>
                             <button class='btn btn-info btn-sm cambiar-estado' 
                                 data-id="${data}"
                                 title="Cambiar estado">
                                <i class="fas fa-toggle-on"></i>
                             </button>
                         </div>`;
                    }
                }
            ]
        });
        console.log('✅ DataTable usuarios inicializado');
    } catch (error) {
        console.error('❌ Error al inicializar DataTable usuarios:', error);
    }
}

// Buscar usuarios
const BuscarUsuarios = async () => {
    console.log('🔄 === BUSCANDO USUARIOS ===');
    
    const url = `${baseUrl}/usuarios/buscarAPI`;
    console.log('🔗 URL:', url);
    
    try {
        const respuesta = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        console.log('📡 Status:', respuesta.status);
        
        if (!respuesta.ok) {
            const errorText = await respuesta.text();
            console.error('❌ Error HTTP:', errorText);
            throw new Error(`HTTP ${respuesta.status}`);
        }
        
        const contentType = respuesta.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await respuesta.text();
            console.error('❌ No es JSON:', text.substring(0, 500));
            
            Swal.fire({
                icon: 'error',
                title: 'Error del servidor',
                text: 'El servidor no devolvió datos válidos',
                showConfirmButton: true
            });
            return;
        }
        
        const datos = await respuesta.json();
        console.log('✅ Datos recibidos:', datos);
        
        const { codigo, mensaje, data } = datos;
        
        if (datatableUsuarios) {
            datatableUsuarios.clear().draw();
            
            if (codigo === 1 && data && data.length > 0) {
                console.log(`✅ Cargando ${data.length} usuarios`);
                datatableUsuarios.rows.add(data).draw();
                
                Swal.fire({
                    icon: "success",
                    title: "¡Usuarios cargados!",
                    text: `Se encontraron ${data.length} usuarios`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                console.log('ℹ️ No hay usuarios');
                Swal.fire({
                    icon: "info",
                    title: "Sin usuarios",
                    text: mensaje || "No hay usuarios registrados",
                    showConfirmButton: true
                });
            }
        }
        
    } catch (error) {
        console.error('❌ Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: `Error: ${error.message}`,
            showConfirmButton: true
        });
    }
};

// Buscar roles
const BuscarRoles = async () => {
    console.log('🔄 === BUSCANDO ROLES ===');
    
    const url = `${baseUrl}/usuarios/roles`;
    console.log('🔗 URL:', url);
    
    try {
        const respuesta = await fetch(url, { method: 'GET' });
        
        if (respuesta.ok) {
            const datos = await respuesta.json();
            console.log('✅ Roles recibidos:', datos);
            
            const selectRol = document.getElementById('id_rol');
            if (selectRol && datos.codigo === 1) {
                selectRol.innerHTML = '<option value="">Seleccione un rol</option>';
                datos.data.forEach(rol => {
                    selectRol.innerHTML += `<option value="${rol.id_rol}">${rol.nombre_rol}</option>`;
                });
                console.log('✅ Roles cargados en select');
            }
        }
    } catch (error) {
        console.error('❌ Error al cargar roles:', error);
    }
};

// Función de prueba manual
window.testearUsuarios = async () => {
    console.log('🧪 === PRUEBA MANUAL USUARIOS ===');
    await testConectividadUsuarios();
    await BuscarUsuarios();
    await BuscarRoles();
};

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 === DOM CARGADO - USUARIOS ===');
    
    // Test automático
    setTimeout(() => {
        console.log('⏰ Ejecutando test automático...');
        testConectividadUsuarios();
    }, 1000);
    
    setTimeout(() => {
        console.log('⏰ Cargando usuarios y roles...');
        BuscarUsuarios();
        BuscarRoles();
    }, 2000);
    
    console.log('🎯 Para probar manualmente: testearUsuarios()');
});

console.log('🏁 === USUARIOS.JS CARGADO ===');