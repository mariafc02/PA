<?php
session_start();

include "utilidad.php";
$errores=[];
$accion=false;
if(!isset($_SESSION["id_rol"]) || ($_SESSION["id_rol"]!=2 && $_SESSION["id_rol"]!=3)){

    cierreConexion($con);
    header("Location: index.php");
    exit();
}
$rol=(int)$_SESSION["id_rol"];

if(!empty($_POST["eliminarBoton"]) || !empty($_POST["eliminarAux"])){
    $accion=true;
}elseif(!empty($_POST["crear"])){
    $accion=true;
}elseif(!empty($_POST["ver"])){
    $accion=true;
}elseif(!empty($_POST["modificar"])){
    $accion=true;
}elseif(!empty($_POST["enviarCrear"]) || !empty($_POST["enviarModificar"])){
    
    $descripcion = htmlspecialchars(trim($_POST["descripcion"]));
    $num_pasillo = filter_input(INPUT_POST, "numPasillo", FILTER_SANITIZE_NUMBER_INT);
    $num_estanteria = filter_input(INPUT_POST, "numEstanteria", FILTER_SANITIZE_NUMBER_INT);
    $cantidad = filter_input(INPUT_POST, "cantidad", FILTER_SANITIZE_NUMBER_INT);
    
    if(empty($_POST["skuAntiguo"])){
        $sku = filter_input(INPUT_POST, "sku", FILTER_SANITIZE_NUMBER_INT);
        $booleanSku = comprobarSku($con, $sku);
        if($sku===false || $sku===null || $booleanSku===true || $sku<0){
            $errores[]="El sku esta en uso, esta vacio, no puede ser menor que 1 o tiene caracteres dañinos.";
            $camposErroresAux["sku"]=true;
        }
    }
    if(!$descripcion || $descripcion===null || empty($descripcion)){
        $errores[]="La descripcion no puede estar vacia o contiene caracteres dañinos.";
        $camposErroresAux["descripcion"]=true;
    }
    if($num_pasillo===false || $num_pasillo===null || $num_pasillo<1){
        $errores[]="El numero de pasillo no puede estar vacio, no puede ser menor que 1 o contiene caracteres dañinos.";
        $camposErroresAux["numPasillo"]=true;
    }
    if($num_estanteria===false || $num_estanteria===null || $num_estanteria<1){
        $errores[]="El numero de estante no puede estar vacio,no puede ser menor que 1 o contiene caracteres dañinos.";
        $camposErroresAux["numEstanteria"]=true;
    }
    if($cantidad===false || $cantidad===null || $cantidad<0){
        $errores[]="La cantidad del producto no puede estar vacia, no puede ser menor que 0 o contiene caracteres dañinos.";
        $camposErroresAux["cantidad"]=true;
    }
    if(empty($errores)){
        if(!empty($_POST["skuAntiguo"])){
            $skuAntiguo = htmlspecialchars(trim($_POST["skuAntiguo"]));
            $booleanSkuAntiguo = comprobarSku($con, $skuAntiguo);
            if($skuAntiguo===false || $skuAntiguo===null || $booleanSkuAntiguo===false){
                $errores[]="Porfavor no modifique los datos ocultos.";
            }else{
                modificarProducto($con, $skuAntiguo, $descripcion, $num_pasillo, $num_estanteria, $cantidad);
                $rowAux=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
                $rolAux=comprobarRol($con, $_SESSION["id_rol"]);
                $descripcion="{$rowAux["nombre"]} {$rowAux["apellidos"]} <Rol: {$rolAux["id_rol"]}> ha realizado una modificacion del producto con identificador: $sku, de la tabla \"producto\".";
                añadirLog($con,$descripcion);
                cierreConexion($con);
                header("Location: productos.php");
                exit();
            }
            
        }else{
            crearProducto($con, $sku, $descripcion, $num_pasillo, $num_estanteria, $cantidad);
            $rowAux=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
            $rolAux=comprobarRol($con, $_SESSION["id_rol"]);
            $descripcion="{$rowAux["nombre"]} {$rowAux["apellidos"]} <Rol: {$rolAux["id_rol"]}> ha realizado una creacion del producto con identificacdor: $sku, en la tabla \"producto\".";
            añadirLog($con,$descripcion);
            cierreConexion($con);
            header("Location: productos.php");
            exit();
        }
    }else{
        $accion=true;
    }
}elseif(!empty($_POST["cancelar"])){
    cierreConexion($con);
    header("Location: productos.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet"  href="css/estilo.css">
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
                        <input type="submit" name="crear" id="crear" value="Crear producto">
                    </form><?php
                    $productos=cargarProductos($con);
                    if($productos!==-1){?>
                        <table>
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Descripcion</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                        while($producto = mysqli_fetch_assoc($productos)){?>
                                <tr>
                                    <td><?php echo $producto["sku"] ?></td>
                                    <td><?php echo $producto["descripcion"] ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="modificar" value="<?php echo $producto["sku"] ?>">
                                            <input type="submit" value="Modificar">
                                        </form>
                                        <form method="POST">
                                            <input type="hidden" name="ver" value="<?php echo $producto["sku"] ?>">
                                            <input type="submit" value="Ver">
                                        </form><?php
                                    if($rol===3){?>
                                        <form method="POST">
                                            <input type="hidden" name="eliminarBoton" value="<?php echo $producto["sku"] ?>">
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
                    if(!empty($_POST["eliminarBoton"]) || !empty($_POST["eliminarAux"])){
                        if(empty($_POST["eliminarAux"])){?>
                            <h2>¿Desea eliminar el producto con sku:<?php echo $_POST["eliminarBoton"]?> ?</h2>
                            <form method="POST">
                                <input type="hidden" name="eliminar" id="eliminar" value="<?php echo $_POST["eliminarBoton"] ?>">
                                <input type="submit" name="eliminarAux" id="eliminarAux" value="Si">
                                <input type="submit" name="cancelar" id="cancelar" value="No">
                            </form><?php
                        }else{
                            $sku=$_POST["eliminar"];
                            $sku = filter_var($sku, FILTER_SANITIZE_NUMBER_INT);
                            $booleanSku = comprobarSku($con, $sku);
                            if($sku===false || $sku===null || $booleanSku===false || $sku<0){
                                echo "Por favor, no modifique los datos ocultos.";
                            }else{
                                eliminarProducto($con,$sku);
                                $rowAux=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
                                $rolAux=comprobarRol($con, $_SESSION["id_rol"]);
                                $descripcion="{$rowAux["nombre"]} {$rowAux["apellidos"]} <Rol: {$rolAux["id_rol"]}> ha realizado una eliminacion de la tabla \"producto\". El registro eliminado tiene identificacion $sku.";
                                añadirLog($con,$descripcion);   
                                cierreConexion($con); 
                                header("Location: productos.php");
                                exit();
                            }
                        }
                    }elseif(!empty($_POST["crear"]) || !empty($_POST["enviarCrear"])){?>
                        <form method="POST">
                            <label for="sku">SKU:</label><br>
                            <input type="number" id="sku" name="sku" 
                                class="<?php echo !empty($camposErroresAux["sku"]) ? "error" : "" ?>"
                                value="<?php echo $_POST["sku"] ?? ""?>"><br><br>

                            <label for="descripcion">Descripcion:</label><br>
                            <textarea name="descripcion" id="descripcion" class="<?php echo !empty($camposErroresAux["descripcion"]) ? "error" : "" ?>"><?php echo $_POST["descripcion"] ?? ""?></textarea><br><br>

                            <label for="numPasillo">Numero de pasillo:</label><br>
                            <input type="number" id="numPasillo" name="numPasillo" 
                                class="<?php echo !empty($camposErroresAux["numPasillo"]) ? "error" : "" ?>"
                                value="<?php echo $_POST["numPasillo"] ?? ""?>"><br><br>

                            <label for="numEstanteria">Numero de estanteria:</label><br>
                            <input type="number" id="numEstanteria" name="numEstanteria" 
                                class="<?php echo !empty($camposErroresAux["numEstanteria"]) ? "error" : "" ?>"
                                value="<?php echo $_POST["numEstanteria"] ?? ""?>"><br><br>

                            <label for="cantidad">Cantidad:</label><br>
                            <input type="number" id="cantidad" name="cantidad" 
                                class="<?php echo !empty($camposErroresAux["cantidad"]) ? "error" : "" ?>"
                                value="<?php echo $_POST["cantidad"] ?? ""?>"><br><br>

                            <input type="submit" name="enviarCrear" id="enviarCrear" value="Enviar"/>
                            <input type="submit" name="cancelar" id="cancelar" value="cancelar"/>
                        </form><?php
                    }elseif(!empty($_POST["modificar"]) || !empty($_POST["enviarModificar"])){
                        if(!empty($_POST["modificar"])){
                            $sku=$_POST["modificar"];
                        }else{
                            $sku=$_POST["skuAntiguo"];
                        }
                        $sku = filter_var($sku, FILTER_SANITIZE_NUMBER_INT);
                        $booleanSku = comprobarSku($con, $sku);
                        if($sku===false || $sku===null || $booleanSku===false || $sku<0){
                            echo "Por favor, no modifique los datos ocultos.";
                        }else{
                            $producto=buscarProducto($con, $sku);
                        ?>
                            <form method="POST">
                                <label for="sku">SKU:</label><br>
                                <input type="number" name="sku" id="sku" value="<?php echo $producto["sku"] ?>" readonly><br><br>

                                <label for="descripcion">Descripcion:</label><br>
                                <textarea name="descripcion" id="descripcion" class="<?php echo !empty($camposErroresAux["descripcion"]) ? "error" : "" ?>"><?php echo $_POST["descripcion"] ?? "{$producto["descripcion"]}"?></textarea><br><br>

                                <label for="numPasillo">Numero de pasillo:</label><br>
                                <input type="number" id="numPasillo" name="numPasillo" 
                                    class="<?php echo !empty($camposErroresAux["numPasillo"]) ? "error" : "" ?>"
                                    value="<?php echo $_POST["numPasillo"] ?? "{$producto["num_pasillo"]}"?>"><br><br>

                                <label for="numEstanteria">Numero de estanteria:</label><br>
                                <input type="number" id="numEstanteria" name="numEstanteria" 
                                    class="<?php echo !empty($camposErroresAux["numEstanteria"]) ? "error" : "" ?>"
                                    value="<?php echo $_POST["numEstanteria"] ?? "{$producto["num_estanteria"]}"?>"><br><br>

                                <label for="cantidad">Cantidad:</label><br>
                                <input type="number" id="cantidad" name="cantidad" 
                                    class="<?php echo !empty($camposErroresAux["cantidad"]) ? "error" : "" ?>"
                                    value="<?php echo $_POST["cantidad"] ?? "{$producto["cantidad"]}"?>"><br><br>

                                <input type="hidden" name="skuAntiguo" id="skuAntiguo" value="<?php echo $producto["sku"] ?>">
                                <input type="submit" name="enviarModificar" id="enviarModificar" value="Enviar"/>
                                <input type="submit" name="cancelar" id="cancelar" value="cancelar"/>
                            </form><?php
                        }
                    }elseif(!empty($_POST["ver"])){
                        $sku=$_POST["ver"];
                        $sku = filter_var($sku, FILTER_SANITIZE_NUMBER_INT);
                        $booleanSku = comprobarSku($con, $sku);
                        if($sku===false || $sku===null || $booleanSku===false || $sku<0){
                            echo "Por favor, no modifique los datos ocultos.";
                        }else{
                            $row=buscarProducto($con, $sku);
                            $rowAux=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
                            $rol=comprobarRol($con, $_SESSION["id_rol"]);
                            $descripcion="{$rowAux["nombre"]} {$rowAux["apellidos"]} <Rol: {$rol["id_rol"]}> ha realizado una vista del producto con identificador: $sku, de la tabla \"producto\".";
                            añadirLog($con,$descripcion);
                            ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Descripcion</th>
                                        <th>Numero de pasillo</th>
                                        <th>Numero de estanteria</th>
                                        <th>Cantidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $row["sku"] ?></td>
                                        <td><?php echo $row["descripcion"] ?></td>
                                        <td><?php echo $row["num_pasillo"] ?></td>
                                        <td><?php echo $row["num_estanteria"] ?></td>
                                        <td><?php echo $row["cantidad"] ?></td>
                                        <td>
                                            <form method="POST">
                                                <input type="submit" name="cancelar" id="cancelar" value="Volver"/>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                     <?php 
                     }
                    }
                }?>
            <?php 
            if(!empty($errores)){?>
                <section class="campoErrores"><?php
                foreach($errores as $error){
                    echo $error ."<br>";
                }
                ?></section><?php
            }?>
        </main>
    </body>
</html>
<?php cierreConexion($con); ?>