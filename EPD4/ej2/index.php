<?php
$camposErrores=false;
function validar(){
    $camposErroresAux=false;
    $errores=[];
    $datos=[];
    if(isset($_POST["texto"])){
        $lineas=explode("\n", $_POST["texto"]);

        foreach($lineas as $numero=>$linea){
            $linea=trim($linea);

            if(strlen($linea)>150 || !preg_match("/^[^#]+#\d+#\d+#\d+$/", $linea)){
                $errores[]="<p>Linea ". $numero+1 . ": " . $linea . "</p>";
                $camposErroresAux=true;
                continue;
            }

            list($producto,$pasillo,$estanteria,$cantidad)=explode("#", $linea);
            if(!isset($datos[$producto])){
                $datos[$producto]["total"]=0;
            }
            $datos[$producto]["localizacion"][]=[$pasillo,$estanteria,$cantidad];
            $datos[$producto]["total"]+=$cantidad;
        }
    }
    if($camposErroresAux===true){
        $datos="<p>La informacion propuesta no esta bien formateada.</p>";
        foreach($errores as $error){
            $datos =$datos . $error;
        }
        $datos = $datos . "<p>Revise la informacion.</p>";
    }

    return [$datos,$camposErroresAux];
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
        <?php list($datos,$camposErrores)=validar()?>
        <form method="POST">
            <label for="texto">Introduce informacion:</label>
            <textarea id="texto" name="texto" 
                    class="<?php echo $camposErrores===true ? "error" : ""?>" required><?php echo $_POST["texto"] ?? ""?></textarea>

            <input type="submit" value="Enviar">
        </form>
        <?php 
            if($camposErrores===true){
                echo $datos;
            }else{
                foreach($datos as $producto=>$dato1){
                    echo "<h2>Producto $producto :</h2>
                            <p>Total {$dato1["total"]} unidad/es";
                    foreach($dato1["localizacion"] as $localizacion){
                        echo "<p>" . $localizacion[2] . " unidad/es en el pasillo " . $localizacion[0] . ", estanteria " . $localizacion[1];
                    }
                }
            }
        ?>

    </body>
</html>
