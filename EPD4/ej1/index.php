<?php

$BooleanErrores=true;
$camposErrores=[];

function validar(){
    $errores=[];
    $aux=true;
    $camposErroresAux=[];
    if(isset($_POST["encargo"])){
        foreach ($_POST["encargo"] as $numero=>$encargo){
            if($encargo["date"]<'2024-01-01'){
                $errores[]="La fecha del encargo " . ($numero + 1) . " no puede ser anterior a 2024.";
                $camposErroresAux[$numero]["date"]=true;
            }
            if(empty($encargo["tipo"])){
                $errores[] = "En el encargo " . ($numero + 1) . " se ha seleccionado un campo sin valor.";
                $camposErroresAux[$numero]["tipo"]=true;
            }
            if(!preg_match("/^\d{1,3}(\.\d{1,2})?$/", $encargo["precio"]) || $encargo["precio"]<1.00 || $encargo["precio"]>999.99){
                $errores[] = "El precio del encargo " . ($numero + 1) . " no puede ser menor a 1.00 ni mayor a 999.99 ni puede tener mas de 2 decimales.";
                $camposErroresAux[$numero]["precio"]=true;
            }
            if(strlen($encargo["descripcion"])>500){
                $errores[] = "La descripción del encargo " . ($numero + 1) . " no puede superar los 500 caracteres.";
                $camposErroresAux[$numero]["descripcion"]=true;
            }
        }
        if(empty($errores)){
            $aux=false;
        }else{
            foreach($errores as $error){
                echo $error ."<br>";
            }
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
        <?php list($BooleanErrores,$camposErrores)=validar() ?>
        <form method="GET">
            <label for="numeroEncargos">Numero de encargos:</label>
            <input type="number" id="numeroEncargos" name="numeroEncargos" min="1" required>
            <input type="submit" value="Enviar">
        </form>
        <form method="POST">
        <?php if(isset($_GET["numeroEncargos"]) && $_GET["numeroEncargos"]>0){
            for($i=0;$i<$_GET["numeroEncargos"];$i++){?>
                <h4>Encargo numero <?php echo $i+1;?></h4>
                
                    <label for="date<?php echo $i?>">Fecha:</label>
                    <input type="date" name="encargo[<?php echo $i?>][date]" id="date<?php echo $i?>" min="2024-01-01" 
                            value="<?php echo $_POST["encargo"][$i]["date"] ?? ""?>" 
                            class="<?php echo !empty($camposErrores[$i]["date"]) ? "error" : ""?>" required><br>

                    <label for="tipo<?php echo $i?>">Tipo de almacen:</label>
                    <select name="encargo[<?php echo $i?>][tipo]" id="tipo<?php echo $i?>" 
                            value="<?php echo $_POST["encargo"][$i]["tipo"] ?? ""?>" 
                            class="<?php echo !empty($camposErrores[$i]["tipo"]) ? "error" : ""?>" required>
                        <option value="" disabled></option>
                        <option value="Aire libre">Aire libre</option>
                        <option value="Interior">Interior</option>
                        <option value="En frio">En frio</option>
                    </select><br>

                    <label for="precio<?php echo $i?>">Precio estimado:</label>
                    <input type="number" step="0.01" min="1.00" max="999.99" name="encargo[<?php echo $i; ?>][precio]" id="precio<?php echo $i?>" 
                            value="<?php echo $_POST["encargo"][$i]["precio"] ?? ""?>" 
                            class="<?php echo !empty($camposErrores[$i]["precio"]) ? "error" : ""; ?>" required><br>

                    <label for="descripcion<?php echo $i?>">Descripcion:</label>
                    <textarea name="encargo[<?php echo $i?>][descripcion]" id="descripcion<?php echo $i?>" maxlength="500" 
                            class="<?php echo !empty($camposErrores[$i]["descripcion"]) ? "error" : ""?>" required><?php echo $_POST["encargo"][$i]["descripcion"] ?? ""?></textarea><br>
            <?php }?>
        <input type="submit" value="Generar informe">
        <?php }?>

        <?php if(!$BooleanErrores){?>
            <h2>Tabla de encargos.</h2>
            <table border="2px">
                <thead>
                    <tr>
                        <th>Fecha del informe</th>
                        <th colspan="4"><?php echo date("Y-m-d") ?></th>
                    </tr>
                    <tr>
                        <th>Tipo de almacen</th>
                        <th>Precio total</th>
                        <th>Precio estimado</th>
                        <th>Fecha de la incidencia</th>
                        <th>Descripcion</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $arrayAux = [];
                    foreach ($_POST["encargo"] as $encargo) {
                        if (!isset($arrayAux[$encargo["tipo"]])) {
                            $arrayAux[$encargo["tipo"]] = [
                                "total" => 0,
                                "cantidad"=>0,
                                "aux" => []
                            ];
                        }
                        $arrayAux[$encargo["tipo"]]["total"] += $encargo["precio"];
                        $arrayAux[$encargo["tipo"]]["cantidad"] +=1;
                        $arrayAux[$encargo["tipo"]]["aux"][] = [
                            "estimado"=>$encargo["precio"],
                            "date"=>$encargo["date"],
                            "descripcion"=>trim($encargo["descripcion"])
                        ];
                    }

                    uasort($arrayAux, function($a, $b) {return $b["total"] <=> $a["total"];});
                    
                    foreach ($arrayAux as $tipo=>$datosAux) {
                        $aux=$datosAux["aux"];
                        usort($aux, function($a, $b) {return $b["estimado"] <=> $a["estimado"];});
                        $arrayAux[$tipo]["aux"]=$aux;
                    }

                    foreach ($arrayAux as $tipo => $datos) {
                        echo "<tr>
                                <td rowspan=\"{$datos["cantidad"]}\">$tipo</td>
                                <td rowspan=\"{$datos["cantidad"]}\">{$datos["total"]}</td>
                                <td>{$datos["aux"][0]["estimado"]}</td>
                                <td>{$datos["aux"][0]["date"]}</td>
                                <td>". strtoupper($datos["aux"][0]["descripcion"])."</td>
                            </tr>";
                        if($datos["cantidad"]>1){
                            for($i=1;$i<$datos["cantidad"];$i++){
                                echo "<tr>
                                        <td>{$datos["aux"][$i]["estimado"]}</td>
                                        <td>{$datos["aux"][$i]["date"]}</td>
                                        <td>". strtoupper($datos["aux"][$i]["descripcion"])."</td>
                                    </tr>";
                            }
                        }
                    }
                ?>
                </tbody>
            </table>
        <?php } ?>
    </body>
</html>
