<?php

$camposErrores=[];
$aux=true;

function validar(){
    $errores=[];
    $camposErroresAux=[];
    $aux=true;
    if(!empty($_POST)){
        $_POST["nombreCliente"]=trim($_POST["nombreCliente"]);
        if(strlen($_POST["nombreCliente"])>200 || empty($_POST["nombreCliente"])){
            $errores[]="El nombre del cliente no puede contener mas de 200 caracteres ni puede estar vacio.";
            $camposErroresAux["nombreCliente"]=true;
        }
        list($anyo, $mes, $dia)=explode("-",$_POST["fecha"]);
        if($_POST["fecha"]<date('Y-m-d') || empty($_POST["fecha"]) || !checkdate($mes, $dia, $anyo)){
            $errores[] = "La fecha no puede ser anterior a la fecha actual, ni puede estar vacia y debe ser valida.";
            $camposErroresAux["fecha"]=true;
        }
        if($_POST["hora"]<"09:00" || $_POST["hora"]>"18:00" || empty($_POST["hora"])){
            $errores[] = "La hora no puede ser antes de las 9:00 ni despues de las 18:00 ni puede estar vacia.";
            $camposErroresAux["hora"]=true;
        }
        if(!preg_match('/^\d+(\.\d{1,2})?$/', $_POST["numeroDias"]) || empty($_POST["numeroDias"]) || $_POST["numeroDias"]<=0){
            $errores[] = "El numero de dias no puede tener mas de 2 decimales ni puede estar vacio.";
            $camposErroresAux["numeroDias"]=true;
        }
        
        $_POST["peticion"]=trim($_POST["peticion"]);
        if(strlen($_POST["peticion"])>500 || empty($_POST["peticion"])){
            $errores[]= "El numero de caracteres de la peticion no puede ser mayor a 500 ni puede estar vacio.";
            $camposErroresAux["peticion"]=true;
        }
        if(!empty($errores)){
            foreach($errores as $error){
                echo $error ."<br>";
            }
        }else{
            $aux=false;
        }
    }
    return [$aux,$camposErroresAux];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Inicio</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
        .error { 
            color: red; 
            border: 2px solid red;
        }
        </style>
    </head>
    <body>
        <?php list($aux,$camposErrores)=validar();print_r($camposErrores);?>
        <form method="POST">
            <label for="nombreCliente">Nombre del cliente:</label>
            <input type="text" id="nombreCliente" name="nombreCliente" 
                    value="<?php echo $_POST["nombreCliente"] ?? ""?>" 
                    class="<?php echo !empty($camposErrores["nombreCliente"]) ? "error" : "" ?>" required><br>

            <label for="fecha">Fecha de recepcion de la llamada:</label>
            <input type="date" name="fecha" id="fecha" 
                    value="<?php echo $_POST["fecha"] ?? ""?>" 
                    class="<?php echo !empty($camposErrores["fecha"]) ? "error" : "" ?>" required><br>

            <label for="hora">Hora de recepcion de la llamada:</label>
            <input type="time" name="hora" id="hora" min="9:00" max="18:00" 
                    value="<?php echo $_POST["hora"] ?? ""?>" 
                    class="<?php echo !empty($camposErrores["hora"]) ? "error" : "" ?>" required><br>

            <label for="numeroDias">Numero de dias para dar una respuesta:</label>
            <input type="number" step="0.01" name="numeroDias" id="numeroDias"
                    value="<?php echo $_POST["numeroDias"] ?? ""?>" 
                    class="<?php echo !empty($camposErrores["numeroDias"]) ? "error" : ""; ?>" required><br>

            <label for="peticion">Peticion:</label>
            <textarea name="peticion" id="peticion" maxlength="500" class="<?php echo !empty($camposErrores["peticion"]) ? "error" : "" ?>" 
                    required><?php echo $_POST["peticion"] ?? ""?></textarea><br>
            
            <input type="submit" value="Enviar">
        </form>
        <?php
            if($aux===false){
                $diasAux=ceil($_POST["numeroDias"]);
                $fecha = new DateTime($_POST["fecha"]);
                $fecha->modify("$diasAux day");
                $nuevaFecha=$fecha->format('Y-m-d');
                echo "<p>Cliente: {$_POST["nombreCliente"]} </p>
                        <p>Fecha maxima para dar una respuesta: $nuevaFecha - {$_POST["hora"]}</p>
                        <p>Estado: {$_POST["peticion"]} </p>";
            }
        ?>

    </body>
</html>