function inicializacion(){
    let ubicaciones = generarUnidades(DEPTOS, SEDES, NUMUBICACIONES);
    let localizaciones = document.getElementById("loc");

    ubicaciones.forEach(ubicacion =>{
        let option = document.createElement("option");
        option.value = ubicacion[0];
        option.appendChild(document.createTextNode(DEPTOS[ubicacion[1][0]]+" "+SEDES[ubicacion[1][1]]));
        localizaciones.appendChild(option);
    });
}

function generarUnidades(DEPTOS, SEDES, NUMUBICACIONES){
    let ubicacionesAux = [];

    for(let i = 1; i <= NUMUBICACIONES ; i++){
        let indiceDEPTOS = Math.floor(Math.random() * (DEPTOS.length));
        let indiceSEDES = Math.floor(Math.random() * (SEDES.length));
        let aux=false;
        
        for(let j=0; j < ubicacionesAux.length && aux===false ; j++){
            if(ubicacionesAux[j][1][0]===indiceDEPTOS && ubicacionesAux[j][1][1]===indiceSEDES){
                aux=true;
            }
        }
        if(aux===true){
            i--;
            continue;
        }

        ubicacionesAux.push([i,[indiceDEPTOS, indiceSEDES]]);
    }

    return ubicacionesAux;
}

function validarFormulario(){
    let valido=true;
    let prioridadValido = validarLista(document.getElementById("priotity"));
    let ubicacionValido = validarLista(document.getElementById("loc"));
    let tipoIncidenciaValido = validarTipoIncidencia();
    let caracteresValido = limitarCaracteres(document.getElementById("descripcion"), LIMITE_DE_CARACTERES);
    let fechaValido = comprobarFormatoFecha(document.getElementById("date"));

    if(prioridadValido===false || ubicacionValido===false || tipoIncidenciaValido===false || caracteresValido===false || fechaValido===false){
        valido=false;
        alert(ERRORES_EN_FORMULARIO);
    }else{
        let confirmar = confirm(AVISO_USUARIO);
        if(confirmar===false){
            valido=false;
        }
    }

    return valido;
}

function validarTipoIncidencia(){
    let valido=false;
    let incidencias = document.getElementsByName("incidentType");
    eliminarErrores("incidentType");

    incidencias.forEach(incidencia=>{
        if(incidencia.checked === true){
            valido=true;
        }
    });

    if(valido === false){
        mostrarErrores("incidentType", ERROR_TIPO_DE_INCIDENCIA_NO_SELECCIONADA);
    }

    return valido;
}

function validarLista(campo){
    eliminarErrores(campo.name);
    if(campo.value === "invalid"){
        mostrarErrores(campo.name, ERROR_ELEMENTO_NO_SELECCIONADO);
        return false;
    }else{
        return true;
    }
}

function eliminarErrores(campo){
    let spanError = document.getElementById(campo+"Error");
    if(spanError.firstChild!==null){
        spanError.removeChild(spanError.firstChild);
    }

    return spanError;
}

function mostrarErrores(campo, error){
    let spanError = eliminarErrores(campo);
    spanError.appendChild(document.createTextNode(error));
}

function limitarCaracteres(campo, limite){
    eliminarErrores(campo);
    if(campo.value.length<=0 || campo.value.length>limite){
        mostrarErrores(campo.name, ERROR_SUPERADO_LIMITE_CARACTERES.replace("X", limite)+", has escrito "+campo.value.length+" caracteres.");
        return false;
    }else{
        return true;
    }
}

function comprobarFormatoFecha(campo){
    let spanError = eliminarErrores(campo);
    if(/^\d{2}\/\d{2}\/\d{4}$/.test(campo.value)===false){
        mostrarErrores(campo.name, ERROR_FORMATO_FECHA);
        return false;
    }else{
        return true;
    }
}

function agregarFichero(){
    let archivosContainer = document.getElementById("archivosContainer");
    let files = document.querySelectorAll("input[type='file']");

    files.forEach(file=>{
        if(file.value===""){
            return;
        }
    });

    let nuevoCampo=document.createElement("input");
    nuevoCampo.type="file";
    nuevoCampo.id="file"+(files.length+1);
    nuevoCampo.name="file"+(files.length+1);
    nuevoCampo.onchange=comprobarAdjuntos;

    archivosContainer.append(nuevoCampo);

    btnAgregar.disabled=true;
    document.getElementById("btnEliminar").disabled=false;
}

function eliminarFichero(){
    let btnEliminar = document.getElementById("btnEliminar");
    let files = document.querySelectorAll("input[type='file']");

    if(files.length===1){
        btnEliminar.disabled = true;
    }else{
        files[files.length-1].remove();
        document.getElementById("btnAgregar").disabled=false;
    }
}

function comprobarAdjuntos(){
    let files = document.querySelectorAll("input[type='file']");

    files.forEach(file=>{
        if(file.value===""){
            document.getElementById("btnAgregar").disabled=true;
            document.getElementById("btnEliminar").disabled=false;
            return;
        }
    });

    document.getElementById("btnAgregar").disabled=false;
    document.getElementById("btnEliminar").disabled=false;
}