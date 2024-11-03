<?php 
function generarTextoAleatorio($longitud) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyz';
    $caracteres .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $caracteres .= '0123456789';
    $textoAleatorio = '';
    $maxIndice = strlen($caracteres) - 1;
    for ($i = 0; $i < $longitud; $i++) {
        $textoAleatorio .= $caracteres[random_int(0, $maxIndice)];
    }
    return $textoAleatorio;
}

$camposErrores=[];
$aux=false;

function validar(){
    $errores = [];
    $camposErroresAux=[];
    $aux=false;
    if(!empty($_GET)){
        $numero = filter_input(INPUT_GET, "numero", FILTER_SANITIZE_NUMBER_INT);
        
        if(!$numero || $numero===null || $numero < 1){
            $errores[]="El numero introducido no es valido. Debe ser un numero entero y mayor que 0.";
            $camposErroresAux["numero"]=true;
        }

        if(!empty($errores)){
            foreach($errores as $error){
                echo $error ."<br>";
            }
        }else{
            $aux=true;
        }
    }

    return [$aux, $camposErroresAux];
}
    
function generador($numero){
    $directorio="generated";
    if(!is_dir($directorio)){
        mkdir($directorio, 0777, true);
    }else{
        $archivos=glob($directorio . "/*");
        foreach($archivos as $archivo){
            unlink($archivo);
        }
    }
    for($f=0;$f<$numero;$f++){
        $archivoNuevo = $directorio . "/archivo" . $f+1 .".txt";
        $archivo = fopen($archivoNuevo, "w");
        if ($archivo !== false) {
            flock($archivo, LOCK_EX);
            $rand=random_int(1, 100);
            $fecha = date("d/m/Y");
            for($i=0;$i<$rand;$i++){
                $texto=$fecha;
                $randTXT=random_int(2,10);
                for($j=0;$j<$randTXT;$j++){
                    $texto .= "#";
                    $randAux=random_int(10,15);
                    $texto .= generarTextoAleatorio($randAux);
                }
                $texto .= "\n";
                fwrite($archivo, $texto);
            }
            flock($archivo, LOCK_UN);
            fclose($archivo);
        } else {
            $errores[]="No se pudo abrir el fichero " . $f+1 . ".";
        }
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Generar ficheros</title>
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
        <?php list($aux, $camposErrores)=validar() ?>
        <main>
            <?php
            if($aux){
                generador($_GET["numero"]);
                echo "<h2>Proceso realizado con exito.</h2>";
            }else{?>
                <form method="GET">
                    <label for="numero">Numero de formularios:</label>
                    <input type="number" name="numero" id="numero" 
                            value="<?php echo $_GET["numero"] ?? ""?>" 
                            class="<?php echo !empty($camposErrores["numero"]) ? "error" : "" ?>" required/><br>

                    <button type="submit">Generar</button>
                </form>
            <?php } ?>
        </main>
    </body>
</html>