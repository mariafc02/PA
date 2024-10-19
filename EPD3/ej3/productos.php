<!DOCTYPE html>
<?php

function tabla() {

    $textoEstatico = array("CCIKE123@@Mesa de escritorio@@1@@01/01/2025",
        "CCIKE123@@Mesa de escritorio@@3@@02/01/2025",
        "CCIKE123@@Mesa de escritorio@@3@@03/01/2025",
        "AWERF545@@Mesa de oficina@@1@@01/01/2025",
        "RETG@@Silla de oficina@@87@@02/01/2025");

    foreach ($textoEstatico as $texto) {
        list($sku, $descripcion, $unidades, $fecha) = explode("@@", $texto);
        $fecha = date_create_from_format('d/m/Y', $fecha);

        if (!isset($arrayDef[$sku])) {
            $arrayDef[$sku] = [
                "descripcion" => $descripcion,
                "primeraFecha" => $fecha,
                "ultimaFecha" => $fecha,
                "unidadesP" => $unidades,
                "unidadesF" => $unidades,
                "totalUnidades" => $unidades
            ];
        } else {
            if ($fecha > $arrayDef[$sku]["ultimaFecha"]) {
                $arrayDef[$sku]["ultimaFecha"] = $fecha;
                $arrayDef[$sku]["unidadesF"] = $unidades;
            }
            if ($fecha < $arrayDef[$sku]["primeraFecha"]) {
                $arrayDef[$sku]["primeraFecha"] = $fecha;
                $arrayDef[$sku]["unidadesP"] = $unidades;
            }
            $arrayDef[$sku]["totalUnidades"] += $unidades;
        }
    }

    crearTabla($arrayDef);
}

function crearTabla($array) {

    echo "<table border=\"2px\">"
    . "<thead>"
        . "<th>SKU</th>"
        . "<th>Descripcion</th>"
        . "<th>Primera fecha de recepcion</th>"
        . "<th>Ultima fecha de recepcion</th>"
        . "<th>Total de unidades</th>"
    . "</thead>"
    . "<tbody>";
    foreach ($array as $key=>$value) {
        $fechaPrimera= date_format($value["primeraFecha"], 'd/m/Y');
        $fechaUltima= date_format($value["ultimaFecha"], 'd/m/Y');
        $unidadesPrimeras= unidades($value["unidadesP"]);
        $unidadesFinales= unidades($value["unidadesF"]);
        echo "<tr>"
                . "<td>$key</td>"
                . "<td>{$value["descripcion"]}</td>"
                . "<td>$fechaPrimera<br>(".$unidadesPrimeras.")</td>"
                . "<td>$fechaUltima<br>(".$unidadesFinales.")</td>"
                . "<td>{$value["totalUnidades"]}</td>"
            . "</tr>";
    }
    echo "</tbody>";
}
function unidades($unidades){
    if($unidades>1){
        return "$unidades unidades";
    }else{
        return "$unidades unidad";
    }
}

?>
<html>
    <head>
        <title>Productos</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <header>
            <h1>Productos</h1>
        </header>
        <main>
            <section>
                <h3>Lista de productos:</h3>
                <?php tabla() ?>
            </section>
        </main>
    </body>
</html>
