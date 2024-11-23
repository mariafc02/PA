<?php
session_start();

include "utilidad.php";
$errores=[];
$acction=false;
if(!isset($_SESSION["id_rol"]) || ($_SESSION["id_rol"]!=1 && $_SESSION["id_rol"]!=2 && $_SESSION["id_rol"]!=3)){
    
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
        <title>Usuarios</title>
    </head>
    <body>
        <header>
            <form action="index.php">
                <input type="submit" value="INICIO">
            </form>
            <h1>Usuarios</h1>
        </header>
        <main>
            <?php
            if($acction===false){
                if($rol===1){?>
                    <form method="POST">
                        <input type="submit" name="crear" value="Crear usuario">
                    </form><?php
                    $usuarios=cargarUsuariosOperarios($con);?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID usuario</th>
                                <th>Email</th>
                                <th>ID rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while($usuario = mysqli_fetch_assoc($usuarios)){?>
                            <tr>
                                <td><?php echo $usuario["id_usuario"] ?></td>
                                <td><?php echo $usuario["email"] ?></td>
                                <td><?php echo $usuario["id_rol"] ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="modificar" value="<?php echo $usuario["id_usuario"] ?>">
                                        <input type="submit" value="Modificar">
                                    </form>
                                </td><?php
                        } ?>     
                            </tr>
                        </tbody>
                    </table><?php
                }elseif($rol===2){
                    if(isset($_COOKIE["email"])){
                        $email = htmlspecialchars($_COOKIE["email"]);
                    }
                    $usuario=comprobarUsuario($con, $email);?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID usuario</th>
                                <th>Email</th>
                                <th>ID rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $usuario["id_usuario"] ?></td>
                                <td><?php echo $usuario["email"] ?></td>
                                <td><?php echo $usuario["id_rol"] ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="modificar" value="<?php echo $producto["sku"] ?>">
                                        <input type="submit" value="Modificar">
                                    </form>
                                </td>
                            </tr>    
                        </tbody>
                    </table><?php
                }elseif($rol===3){?>
                    <form method="POST">
                        <input type="submit" name="crear" value="Crear usuario">
                    </form><?php
                    $usuarios=cargarUsuarios($con);?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID usuario</th>
                                <th>Email</th>
                                <th>ID rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while($usuario = mysqli_fetch_assoc($usuarios)){?>
                            <tr>
                                <td><?php echo $usuario["id_usuario"] ?></td>
                                <td><?php echo $usuario["email"] ?></td>
                                <td><?php echo $usuario["id_rol"] ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="modificar" value="<?php echo $usuario["id_usuario"] ?>">
                                        <input type="submit" value="Modificar">
                                    </form>
                                </td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="eliminar" value="<?php echo $usuario["id_usuario"] ?>">
                                        <input type="submit" value="Eliminar">
                                    </form>
                                </td><?php
                        } ?>     
                            </tr>
                        </tbody>
                    </table><?php
                }
            }
            ?>
        </main>
    </body>
</html>