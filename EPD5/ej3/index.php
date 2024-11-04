<?php

$camposErrores=[];
$aux=false;
$campos=[];
$destino="";

function validar(){
    $errores = [];
    $camposErroresAux=[];
    $aux = false;
    $destino="";
    if(!empty($_FILES)){
        if(!empty($_FILES["archivo"] && $_FILES["archivo"]["error"]===0)){
            if($_FILES["archivo"]["type"]!=="text/csv" || !preg_match('/\.csv$/', $_FILES["archivo"]["name"])){
                $errores[]="El formato del fichero no corresponde con el formato esperado.";
                $camposErroresAux["archivo"]=true;
            }else{
                $archivo = fopen($_FILES["archivo"]["tmp_name"], "r");

                if($archivo !== false){
                    flock($archivo, LOCK_SH);
                    $i=0;
                    $validarId=[];
                    while(($datos = fgetcsv($archivo, 500, ";")) !== false){
                        $i++;
                        if(sizeof($datos)!=3){
                            $errores[]="En la linea $i no hay 3 campos.";
                            $camposErroresAux["fichero"]=true;
                            break;
                        }
                        for($j=0;$j<sizeof($datos);$j++){
                            $datos[$j]=htmlspecialchars(trim($datos[$j]));
                            if(!$datos[$j] || $datos[$j] === false || $datos[$j]===""){
                                $errores[]="El campo $j de la linea $i esta vacio o es dañino.";
                                $camposErroresAux["fichero"]=true;
                                break;
                            }
                        }
                        if(isset($validarId[$datos[2]])){
                            $errores[]="El campo 3 de la linea $i coincide con otro id de otra linea. No puede haber 2 ids iguales.";
                            $camposErroresAux["fichero"]=true;
                            break;
                        }else{
                            $validarId[$datos[2]]=true;
                        }
                        if($datos[1]!=="text" && $datos[1]!=="email" && $datos[1]!=="date"){
                            $errores[]="Los campos en la posicion 2 deben ser tipo text, tipo email o tipo date.";
                            $camposErroresAux["fichero"]=true;
                            break;
                        }
                    }
                    flock($archivo, LOCK_UN);
                    fclose($archivo);
                }else{
                    $errores[]="El archivo csv no se ha abierto correctamente.";
                }
            }
        }

        if(!empty($errores)){
            foreach($errores as $error){
                echo $error ."<br>";
            }
        }else{
            $aux=true;
            $directorio="data";
            if(!file_exists($directorio)){
                mkdir($directorio, 0777, true);
            }
            $destino= $destino . $directorio . "/" . uniqid() . ".csv";
            if(!move_uploaded_file($_FILES["archivo"]["tmp_name"], $destino)){
                $errores[] = "Error al mover el archivo al directorio $directorio.";
            }
        }
    }

    return [$aux, $camposErroresAux, $destino];
}

?>

<!DOCTYPE html>
<html>  
    <head>
        <title>Creador de formularios</title>
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
        <?php list($aux, $camposErrores, $destino)=validar() ?>
        <main>
            <?php
            if($aux){?>
                <h2>El archivo se ha cargado correctamente:</h2><br>
                <form action="auxiliar.php" method="POST">
                    <input type="hidden" name="archivo2" id="archivo2" value="<?php echo $destino ?>">
                    <button type="submit">Ir al formulario generado</button>
                </form>
            <?php 
            }else{?>
                <form method="POST" enctype="multipart/form-data">
                    <label for="archivo">Seleccione el archivo CSV:</label>
                    <input type="file" name="archivo" id="archivo" 
                            class="<?php echo !empty($camposErrores["archivo"]) ? "error" : "" ?>" required><br>

                    <button type="submit">Generar Formulario</button>
                </form>
            <?php } ?>

        </main>
    </body>
</html>

