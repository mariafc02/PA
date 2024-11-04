<?php

$camposErrores=[];
$aux=false;
$campos=[];

function validar(){
    $errores=[];
    $campos=[];
    $aux=false;
    $camposErroresAux=[];
    $direccion="";
    if(isset($_POST["archivo2"])){
        $direccion=$_POST["archivo2"];
        $archivo = fopen($direccion, "r");
        if($archivo!==false){
            flock($archivo, LOCK_SH);
            while(($datos = fgetcsv($archivo, 500, ";")) !== false){
                $campos[]=[$datos[0], $datos[1], $datos[2]];
            }
            flock($archivo, LOCK_UN);
            fclose($archivo);
        }else{
            $errores[]="El archivo no se ha podido abrir correctamente.";
        }
    }
    if(isset($_POST["campo"])){
        foreach($_POST["campo"] as $campo1=>$campo2){
            foreach($campo2 as $campo3=>$campo4){
                if($campo1==="email"){
                    $email=filter_var(trim($campo4), FILTER_SANITIZE_EMAIL);
                    if(!$email || $email===null || empty($email)){
                        $errores[]="El email no puede estar vacio y tiene que estar bien escrito.";
                        $camposErroresAux[$campo3]=true;
                    }
                }elseif($campo1==="text"){
                    $text=htmlspecialchars(trim($campo4));
                    if(!$text || $text===null || empty($text)){
                        $errores[]="El campo de texto esta vacio o es dañino.";
                        $camposErroresAux[$campo3]=true;
                    }
                }else{
                    list($anyo, $mes, $dia)=explode("-",$campo4);
                    if(empty($campo4) || !checkdate($mes, $dia, $anyo)){
                        $errores[] = "La fecha no puede puede estar vacia y debe ser valida.";
                        $camposErroresAux[$campo3]=true;
                    }
                }
            }
        }
    }
    if(!empty($errores)){
        foreach($errores as $error){
            echo $error ."<br>";
        }
    }else{
        if(isset($_POST["campo"])){
            $aux=true;
        }
    }
    return [$aux,$camposErroresAux, $direccion, $campos];
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
        <?php list($aux,$camposErrores, $direccion,$campos)=validar() ?>
        <main>
            <?php
            if($aux){
                echo "<h2>Formulario completado exitosamente.</p>";
            }else{?>
                <form method="POST">
                    <input type="hidden" name="archivo2" id="archivo2" value="<?php echo $direccion ?>">
                    <?php
                    foreach($campos as $campo){?>
                        <label for="<?php echo $campo[2] ?>"><?php echo $campo[0] ?></label>
                        <input type="<?php echo $campo[1] ?>" name="campo[<?php echo $campo[1] ?>][<?php echo $campo[2] ?>]" id="campo[<?php echo $campo[1] ?>][<?php echo $campo[2] ?>]"
                                value="<?php echo $_POST[$campo[2]] ?? ""?>" 
                                class="<?php echo !empty($camposErrores[$campo[2]]) ? "error" : "" ?>" required><br>
                    <?php } ?>
                    <button type="submit">Enviar</button>
                </form>
            <?php } ?>
        </main>
    </body>
</html>