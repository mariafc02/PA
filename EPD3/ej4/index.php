<!DOCTYPE html>

<?php
function maxStock(){
    static $inventario = [
        1 => [
            'a' => [
                ['CCFWT-000500', 7],
                ['CCFWT-005000', 5],
                ['CCT-025000', 8],
            ],
            'b' => [
                ['COS-025000', 5],
            ],
            'c' => [
                ['COS-025000', 9],
                ['CCT-025000', 1],
                ['CCT-025000', 8],
            ]
        ],
        2 => [
            'a' => [
                ['CCT-025000', 8],
                ['CCT-025000', 9],
            ]
        ],
    ];
    
    $totalUnidades=[];
    $skuMax="";
    $maxCantidad=0;
    $productosTotal=[];
    
    foreach ($inventario as $pasillo => $estantes) {
        foreach ($estantes as $estante => $productos) {
            $productosAux=[];
            foreach ($productos as $producto) {
                if(isset($productosAux[$producto[0]])){
                    $productosAux[$producto[0]]+=$producto[1];
                }else{
                    $productosAux[$producto[0]]=$producto[1];
                }
            }
            foreach($productosAux as $sku=>$cantidad){
                $productosTotal[$pasillo][$estante][$sku]=$cantidad;
                if(isset($totalUnidades[$sku])){
                    $totalUnidades[$sku]+=$cantidad;
                }else{
                    $totalUnidades[$sku]=$cantidad;
                }
                if($totalUnidades[$sku]>$maxCantidad){
                    $skuMax=$sku;
                    $maxCantidad=$totalUnidades[$sku];
                }
            }
        }
    }
    
    $encontrar=encontrar($skuMax,$productosTotal);
    
    return [$skuMax, $maxCantidad, $encontrar];
}

function encontrar($skuBuscado,$almacen) {

    $localizacionesProducto = [];
    foreach ($almacen as $pasillo => $estantes) {
        foreach ($estantes as $estante => $productos) {
            foreach ($productos as $key=>$producto) {
                if ($key === $skuBuscado) {
                    $localizacionesProducto[] = "Pasillo $pasillo - Estante $estante - $producto unidad/es";
                }
            }
        }
    }

    return $localizacionesProducto;
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>EPD3-EJ4</title>
    </head>
    <body>
        <?php
        $maximo=maxStock();
        echo "<p>El producto con mayor cantidad de stock es el {$maximo[0]} con {$maximo[1]} unidades.<br><br>"
                . "Se encuentra en:</p>";
        foreach ($maximo[2] as $values) {
            echo "<p>$values</p>";
        }
        
        ?>
    </body>
</html>
