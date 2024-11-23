<?php
session_start();

include "utilidad.php";
$errores=[];
$accion=false;
if(!isset($_SESSION["id_rol"]) || ($_SESSION["id_rol"]!=2 && $_SESSION["id_rol"]!=3)){
    
    header("Location: index.php");
    exit();
}
$rol=(int)$_SESSION["id_rol"];

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
        <title>Productos</title>
    </head>
    <body>
        <header>
            <form action="index.php">
                <input type="submit" value="INICIO">
            </form>
            <h1>Productos</h1>
        </header>
        <main>
            <?php
                if($accion===false){?>
                    <form method="POST">
                        <input type="submit" name="crear" value="Crear producto">
                    </form><?php
                    $productos=cargarProductos($con);
                    if($productos!==-1){?>
                        <table>
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                        while($producto = mysqli_fetch_assoc($productos)){?>
                                <tr>
                                    <td><?php echo $producto["sku"] ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="modificar" value="<?php echo $producto["sku"] ?>">
                                            <input type="submit" value="Modificar">
                                        </form><?php
                                    if($rol===3){?>
                                        <form method="POST">
                                            <input type="hidden" name="eliminar" value="<?php echo $producto["sku"] ?>">
                                            <input type="submit" value="Eliminar">
                                        </form>
                                    <?php } ?>
                                    </td>
                                </tr>
                        <?php }
                    }
                    ?>
                            </tbody>
                        </table><?php

                }else{
                    if(!empty($_POST["eliminar"])){
                        $sku=$_POST["eliminar"];
                        eliminar($con,$sku);
                    }elseif(!empty($_POST["crear"])){
                    
                    }elseif(!empty($_POST["modificar"])){
                    
                    }
                }
            ?>
        </main>
    </body>
</html>