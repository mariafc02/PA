<?php
session_start();

include "utilidad.php";
$errores=[];

if(!isset($_SESSION["id_rol"]) || ($_SESSION["id_rol"]!=2 && $_SESSION["id_rol"]!=3)){
    
    header("Location: index.php");
    exit();
}
$rol=$_SESSION["id_rol"];

if(!empty($_POST["eliminar"])){
    $sku=$_POST["eliminar"];
    eliminar($con,$sku);
}elseif(!empty($_POST["crear"])){

}elseif(!empty($_POST["modificar"])){

}elseif(!empty($_POST["listar"])){

}

function eliminar($con,$sku){

}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrito</title>
    </head>
    <body>
        <header>
            <form action="index.php">
                <input type="submit" value="INICIO">
            </form>
            <h1>Carrito</h1>
        </header>
    </body>
</html>