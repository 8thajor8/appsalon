let paso = 1;
let pasoInicial = 1;
let pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion();
    tabs(); //cambia las secciones cuando se hace click en los tabs
    botonesPaginador();
    paginaSiguiente();
    paginaAnterior();
    consultarAPI();
    idCliente();
    nombreCliente();
    seleccionarFecha();
    seleccionarHora();
    

}

function mostrarSeccion(){
    //Ocultar la seccion q tenga la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }
    //Seleccionar la seccion con el paso correspondiente
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar'); 

    //Resaltar tab actual
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton => {
        boton.addEventListener('click', function(e){
            paso = parseInt(e.target.dataset.paso);
            
            mostrarSeccion();
            botonesPaginador();
            
        })
    })
    
}

function botonesPaginador(){

    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if(paso === 3 ){
        mostrarResumen();
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
    } else {
        paginaSiguiente.classList.remove('ocultar');
        paginaAnterior.classList.remove('ocultar');
    }

    mostrarSeccion();

}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
        
    })
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
        if(paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
        
    })
}

async function consultarAPI(){

    try {
        
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();

       
        mostrarServicios(servicios);

    } catch (error) {
        
    }

}

function mostrarServicios(servicios){
   
    servicios.forEach( servicio => {
       
        const {id, nombre, precio} = servicio;
        
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        
        servicioDiv.onclick = function (){
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);


        
    })

}

function seleccionarServicio(servicio){
    const {id} = servicio;
    const {servicios} = cita;

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    //Comprobar si un servicio ya fue agregado
    if( servicios.some ( agregado => agregado.id === id)){
        
        cita.servicios = servicios.filter( agregado => agregado.id !== id)
        
        divServicio.classList.remove('seleccionado');
    } else{

        cita.servicios = [...servicios, servicio];
    
        
        divServicio.classList.add('seleccionado');

    }
   
   

}

function nombreCliente(){

    cita.nombre = document.querySelector('#nombre').value;

}

function idCliente(){

    cita.id = document.querySelector('#id').value;
    

}

function seleccionarFecha(){

    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e){

        const dia = new Date(e.target.value).getUTCDay();

        if([6,0].includes(dia)){
            e.target.value = '';
            mostrarAlerta('Fines de Semana no permitidos', 'error', '.formulario');

        } else{
            cita.fecha = e.target.value;
        }
    })

}

function seleccionarHora(){

    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){
        
        const horaCita= e.target.value
        const hora = horaCita.split(':')[0]

        if(hora < 10 || hora > 18){
            e.target.value = '';
            mostrarAlerta('El horario del salon es de 10 a 18hs', 'error', '.formulario')
        }  else{
            cita.hora = e.target.value;
        }
    })
}


function mostrarAlerta(mensaje, tipo, elem, desaparece = true){

    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const elemento = document.querySelector(elem);
    elemento.appendChild(alerta);

    if(desaparece){
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

}

function mostrarResumen(){
    
    const resumen = document.querySelector('.contenido-resumen')

    //Limpiar tab resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }
    
    if(Object.values(cita).includes('') || cita.servicios.length === 0){

        mostrarAlerta('Debes asegurarte de seleccionar al menos un servicio y completar los campos de fecha y hora', 'error', '.contenido-resumen', false)

        return;
    }
    
    const { nombre, fecha, hora, servicios} = cita

    //Heading Servicios
    
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);
    
    servicios.forEach( servicio => {
        const {id, precio, nombre} = servicio
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio')

        const textoServicio = document.createElement('P');
        textoServicio.innerHTML = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span> Precio: </span> $${precio}`;
        
        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);
        resumen.appendChild(contenedorServicio);
    })


    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span> Nombre: </span> ${nombre}`;
    resumen.appendChild(nombreCliente);
    

    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2; //pq cada vez q instancio una fecha se desfaza un dia
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia));
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaFormateada = fechaUTC.toLocaleString('es-MX', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span> Fecha: </span> ${fechaFormateada}`;
    resumen.appendChild(fechaCita);
    
    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span> Hora: </span> ${hora} hs.`;
    resumen.appendChild(horaCita);
    
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Confirmar Reserva';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(botonReservar);

}

async function reservarCita(){
    const {nombre, fecha, hora, servicios, id} = cita;
    
    const idServicios = servicios.map(servicio => servicio.id);
    
    const datos = new FormData();
    datos.append('usuarioId', id);
    datos.append('nombre', nombre);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);
   
    
    try {
        //Peticion hacia la API
        const url = '/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        })

        const resultado = await respuesta.json();
        
        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita Reservada",
                text: "Su reserva ha sido registrada con exito!",
                
                }).then(() => {
                window.location.reload();
                });
            
        } 
    } catch (error) {

        Swal.fire({
            icon: "error",
            title: "Cita No Agendada",
            text: "Su cita no ha sido guardada",
            
            }).then(() => {
            window.location.reload();
            });

        
    }
    
}
