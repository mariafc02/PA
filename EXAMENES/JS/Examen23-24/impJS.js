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

    if(valid === false){
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
    if(spanError.firstChild){
        spanError.removeChild(spanError.firstChild);
    }

    return spanError;
}

function mostrarErrores(campo, error){
    let spanError = eliminarErrores(campo);
    spanError.appendChild(document.createTextNode(error));
}

function limitarCaracteres(campo, limite){

}

function comprobarFormatoFecha(campo){

}

function agregarFichero(){

}

function eliminarFichero(){

}

function comporbarAdjuntos(){

}