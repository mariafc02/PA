<!DOCTYPE html>
<?php
function crearSKU($caracteres){
    if($caracteres<12){
        $caracteres=13-$caracteres;
    } else {
        $caracteres=0;
    }
    $idProducto= substr(strtoupper(uniqid()),$caracteres);
    
    echo ": con id=$idProducto";
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
                <p>Tenemos a disposicion del cliente productos como:</p>
                <ul>
                    <li>Sillas de montar<?php crearSKU(5) ?></li>
                    <li>Herraduras<?php crearSKU(5) ?></li>
                    <li>Productos de limpieza<?php crearSKU(5) ?></li>
                    <li>Alimento<?php crearSKU(5) ?></li>
                </ul>
            </section>
        </main>
        <footer>
            <mark>Contacta con nosotros:<br>+34 000000000<br>info@almacen.es</mark>
        </footer>
    </body>
</html>
