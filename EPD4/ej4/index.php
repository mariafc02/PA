<?php

$camposErrores=[];
$aux=true;

function validar(){
    $errores=[];
    $camposErroresAux=[];
    $aux=true;
    if(!empty($_GET)){
        if($_GET["importe"]<=0 || !preg_match('/^\d+(\.\d{1,2})?$/', $_GET["importe"]) || empty($_GET["importe"])){
            $errores[]="El precio no puede ser menor o igual a 0 ni puede tener mas de 2 decimales ni puede estar vacio.";
            $camposErroresAux["importe"]=true;
        }
        $_GET["codigo"]=trim($_GET["codigo"]);
        if(strlen($_GET["codigo"])>9 || empty($_GET["codigo"]) || !preg_match('/[0-9]/', $_GET["codigo"]) || !preg_match('/^[A-Z0-9]+$/', $_GET["codigo"]) || strtoupper($_GET["codigo"])!=$_GET["codigo"]){
            $errores[] = "Codigo descuento no valido. Letras mayusculas, maximo 9 caracteres y algun numero obligatorio.";
            $camposErroresAux["codigo"]=true;
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
        <?php list($aux,$camposErrores)=validar()?>
        <form method="GET">
            <label for="importe">Importe de compra:</label>
            <input type="number" id="importe" name="importe" step="0.01"
                    value="<?php echo $_GET["importe"] ?? ""?>" 
                    class="<?php echo !empty($camposErrores["importe"]) ? "error" : "" ?>" required><br>

            <label for="codigo">Codigo promocional:</label>
            <input type="text" name="codigo" id="codigo" 
                    value="<?php echo $_GET["codigo"] ?? ""?>" 
                    class="<?php echo !empty($camposErrores["codigo"]) ? "error" : "" ?>" required><br>

            <input type="submit" value="Enviar">
        </form>
        <?php
            if($aux===false){
                preg_match_all('/[0-9]/', $_GET["codigo"], $a);
                $suma = array_sum($a[0]);
                $descuento=0;
                if($suma % 2 ==0){
                    $descuento=0.90;
                }else{
                    $descuento=0.95;
                }

                echo "<p>El importe es de: {$_GET["importe"]}€</p>
                        <p>El descuento a aplicar es de un:" . (1-$descuento)*100 . "% </p>
                        <p>El importe con el codigo promocional aplicado es de: " . round($_GET["importe"]*$descuento, 2) . "€ </p>" ;
            }
        ?>

    </body>
</html>