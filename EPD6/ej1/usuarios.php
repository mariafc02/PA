<?php
session_start();

include "utilidad.php";
$errores=[];
$accion=false;
if(!isset($_SESSION["id_rol"]) || ($_SESSION["id_rol"]!=1 && $_SESSION["id_rol"]!=2 && $_SESSION["id_rol"]!=3)){
    
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
    
    $password =htmlspecialchars(trim($_POST["password"]));
    $passwordHash=password_hash($password,PASSWORD_DEFAULT);
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $apellidos = htmlspecialchars(trim($_POST["apellidos"]));
    $id_rol=filter_input(INPUT_POST, "idRol", FILTER_SANITIZE_NUMBER_INT);
    
    if(empty($_POST["enviarModificar"])){
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $booleanEmail = comprobarEmail($con,$email);
        if($email===false || $email===null || $booleanEmail===true || !preg_match('/\@almacen.com$/', $email)){
            $errores[]="El email no puede estar vacio, ni contener caracteres dañinos, tiene que estar bien escrito, no puede estar en uso y tiene que terminar en @almacen.com.";
            $camposErroresAux["email"]=true;
        }
    }
    
    if($password===false || $password===null || empty($password)){
        $errores[]="La contraseña no puede estar vacia ni puede contener caracteres dañinos.";
        $camposErroresAux["password"]=true;
    }
    if($nombre===false || $nombre===null || empty($nombre)){
        $errores[]="El nombre no puede estar vacio, ni puede contener caracteres dañinos.";
        $camposErroresAux["nombre"]=true;
    }
    if($apellidos===false || $apellidos===null || empty($apellidos)){
        $errores[]="El apellido no puede estar vacio, ni puede contener caracteres dañinos.";
        $camposErroresAux["apellidos"]=true;
    }

    if($id_rol===false || $id_rol===null || empty($id_rol)){
        $errores[]="El id_rol no puede estar vacio, ni puede contener caracteres dañinos.";
        $camposErroresAux["id_rol"]=true;
    }
    if((int)$_SESSION["id_rol"]===1 && (int)$id_rol!==2){
        $errores[]="No puedes crear datos de personas que no son operarios.";
        $camposErroresAux["id_rol"]=true;
    }
    if(empty($errores)){
        if(!empty($_POST["idAntiguo"])){
            $idAntiguo = filter_input(INPUT_POST, "idAntiguo", FILTER_SANITIZE_NUMBER_INT);
            $booleanID = comprobarID($con, $idAntiguo);
            if($idAntiguo===false || $idAntiguo===null || $booleanID===false){
                $errores[]="Porfavor no modifique los datos ocultos.";
            }else{
                modificarUsuario($con, $idAntiguo, $passwordHash, $nombre, $apellidos, $id_rol);
                $rowAux=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
                $rolAux=comprobarRol($con, $_SESSION["id_rol"]);
                $descripcion="{$rowAux["nombre"]} {$rowAux["apellidos"]} <Rol: {$rolAux["nombre"]}> ha realizado una modificacion del usuario con identificador: $idAntiguo, de la tabla \"usuario\".";
                añadirLog($con,$descripcion);
                cierreConexion($con);
                header("Location: usuarios.php");
                exit();
            }
        }else{
            añadirUsuario($con, $email, $passwordHash, $nombre, $apellidos, $id_rol);
            $rowAux=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
            $rolAux=comprobarRol($con, $_SESSION["id_rol"]);
            $descripcion="{$rowAux["nombre"]} {$rowAux["apellidos"]} <Rol: {$rolAux["nombre"]}> ha realizado una creacion del usuario con identificacdor: $id_usuario, en la tabla \"usuario\".";
            añadirLog($con,$descripcion);
            cierreConexion($con);
            header("Location: usuarios.php");
            exit();
        }
    }else{
        $accion=true;
    }
}elseif(!empty($_POST["cancelar"])){
    cierreConexion($con);
    header("Location: usuarios.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/estilo.css">
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
            if($accion===false){
                if($rol===1){?>
                    <form method="POST">
                        <input type="submit" name="crear" id="crear" value="Crear usuario">
                    </form><?php
                    $infoPropia=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
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
                            <tr>
                            <td><?php echo $infoPropia["id_usuario"] ?></td>
                                <td><?php echo $infoPropia["email"] ?></td>
                                <td><?php echo $infoPropia["id_rol"] ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="modificar" value="<?php echo $infoPropia["id_usuario"] ?>">
                                        <input type="submit" value="Modificar">
                                    </form>
                                    <form method="POST">
                                        <input type="hidden" name="ver" value="<?php echo $infoPropia["id_usuario"] ?>">
                                        <input type="submit" value="Ver">
                                    </form>
                                </td>
                            </tr>
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
                                    <form method="POST">
                                        <input type="hidden" name="ver" value="<?php echo $usuario["id_usuario"] ?>">
                                        <input type="submit" value="Ver">
                                    </form>
                                </td>
                            </tr><?php
                        } ?>     
                            
                        </tbody>
                    </table><?php
                }elseif($rol===2){
                    $usuario=comprobarUsuarioID($con, $_SESSION["id_usuario"]);?>
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
                                        <input type="hidden" name="modificar" value="<?php echo $usuario["id_usuario"] ?>">
                                        <input type="submit" value="Modificar">
                                    </form>
                                    <form method="POST">
                                        <input type="hidden" name="ver" value="<?php echo $usuario["id_usuario"] ?>">
                                        <input type="submit" value="Ver">
                                    </form>
                                </td>
                            </tr>    
                        </tbody>
                    </table><?php
                }elseif($rol===3){?>
                    <form method="POST">
                        <input type="submit" name="crear" id="crear" value="Crear usuario">
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
                                    <form method="POST">
                                        <input type="hidden" name="ver" value="<?php echo $usuario["id_usuario"] ?>">
                                        <input type="submit" value="Ver">
                                    </form>
                                    <form method="POST">
                                        <input type="hidden" name="eliminarBoton" value="<?php echo $usuario["id_usuario"] ?>">
                                        <input type="submit" value="Eliminar">
                                    </form>
                                </td>
                            </tr><?php
                        } ?>     
                            
                        </tbody>
                    </table><?php
                }
            }else{
                if(!empty($_POST["eliminarBoton"]) || !empty($_POST["eliminarAux"])){
                    if(empty($_POST["eliminarAux"])){?>
                        <h2>¿Desea eliminar el usuario con id:<?php echo $_POST["eliminarBoton"]?> ?</h2>
                        <form method="POST">
                            <input type="hidden" name="eliminar" id="eliminar" value="<?php echo $_POST["eliminarBoton"] ?>">
                            <input type="submit" name="eliminarAux" id="eliminarAux" value="Si">
                            <input type="submit" name="cancelar" id="cancelar" value="No">
                        </form><?php
                    }else{
                        $id_usuario=$_POST["eliminar"];
                        $id_usuario = filter_var($id_usuario, FILTER_SANITIZE_NUMBER_INT);
                        $booleanID = comprobarID($con, $id_usuario);
                        if($id_usuario===false || $id_usuario===null || $booleanID===false || $id_usuario<0){
                            echo "Por favor, no modifique los datos ocultos.";
                        }else{
                            eliminarUsuario($con,$id_usuario);
                            $rowAux=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
                            $rolAux=comprobarRol($con, $_SESSION["id_rol"]);
                            $descripcion="{$rowAux["nombre"]} {$rowAux["apellidos"]} <Rol: {$rolAux["nombre"]}> ha realizado una eliminacion de la tabla \"usuario\". El registro eliminado tiene identificacion $id_usuario.";
                            añadirLog($con,$descripcion);   
                            cierreConexion($con); 
                            if($id_usuario===$_SESSION["id_usuario"]){
                                header("Location: logout.php");
                                exit();
                            }else{
                                header("Location: usuarios.php");
                                exit();
                            }
                        }
                    }
                }elseif(!empty($_POST["crear"]) || !empty($_POST["enviarCrear"])){?>
                    <form method="POST">
                        <label for="email">Email:</label><br>
                        <input type="email" id="email" name="email" 
                            class="<?php echo !empty($camposErroresAux["email"]) ? "error" : "" ?>"
                            value="<?php echo $_POST["email"] ?? ""?>"><br><br>

                        <label for="password">Contraseña:</label><br>
                        <input type="password" id="password" name="password" 
                            class="<?php echo !empty($camposErroresAux["password"]) ? "error" : "" ?>"
                            value="<?php echo $_POST["password"] ?? ""?>"><br><br>

                        <label for="nombre">Nombre:</label><br>
                        <input type="text" id="nombre" name="nombre" 
                            class="<?php echo !empty($camposErroresAux["nombre"]) ? "error" : "" ?>"
                            value="<?php echo $_POST["nombre"] ?? ""?>"><br><br>

                        <label for="apellidos">Apellidos:</label><br>
                        <input type="text" id="apellidos" name="apellidos" 
                            class="<?php echo !empty($camposErroresAux["apellidos"]) ? "error" : "" ?>"
                            value="<?php echo $_POST["apellidos"] ?? ""?>"><br><br>

                        <label for="idRol">ID rol:</label><br>
                        <input type="number" id="idRol" name="idRol" 
                            class="<?php echo !empty($camposErroresAux["id_rol"]) ? "error" : "" ?>"
                            value="<?php echo $_POST["idRol"] ?? ""?>"><br><br>

                        <input type="submit" name="enviarCrear" id="enviarCrear" value="Enviar"/>
                        <input type="submit" name="cancelar" id="cancelar" value="cancelar"/>
                    </form><?php
                }elseif(!empty($_POST["modificar"]) || !empty($_POST["enviarModificar"])){
                    if(!empty($_POST["modificar"])){
                        $id_usuario=$_POST["modificar"];
                    }else{
                        $id_usuario=$_POST["idAntiguo"];
                    }
                    $id_usuario = filter_var($id_usuario, FILTER_SANITIZE_NUMBER_INT);
                    $booleanID = comprobarID($con, $id_usuario);
                    if($id_usuario===false || $id_usuario===null || $booleanID===false || $id_usuario<0){
                        echo "Por favor, no modifique los datos ocultos.";
                    }else{
                        $usuario=buscarUsuario($con, $id_usuario);
                    ?>
                        <form method="POST">
                            <label for="idUsuario">ID usuario:</label><br>
                            <input type="number" name="idUsuario" id="idUsuario" value="<?php echo $usuario["id_usuario"] ?>" readonly><br><br>

                            <label for="email">Email:</label><br>
                            <input type="email" id="email" name="email" 
                                class="<?php echo !empty($camposErroresAux["email"]) ? "error" : "" ?>"
                                value="<?php echo $_POST["email"] ?? "{$usuario["email"]}"?>" readonly><br><br>

                            <label for="password">Contraseña:</label><br>
                            <input type="password" id="password" name="password" 
                                class="<?php echo !empty($camposErroresAux["password"]) ? "error" : "" ?>"><br><br>

                            <label for="nombre">Nombre:</label><br>
                            <input type="text" id="nombre" name="nombre" 
                                class="<?php echo !empty($camposErroresAux["nombre"]) ? "error" : "" ?>"
                                value="<?php echo $_POST["nombre"] ?? "{$usuario["nombre"]}"?>"><br><br>

                            <label for="apellidos">Apellidos:</label><br>
                            <input type="text" id="apellidos" name="apellidos" 
                                class="<?php echo !empty($camposErroresAux["apellidos"]) ? "error" : "" ?>"
                                value="<?php echo $_POST["apellidos"] ?? "{$usuario["apellidos"]}"?>"><br><br>
                            
                            <label for="idRol">ID rol:</label><br>
                            <input type="number" id="idRol" name="idRol" 
                                class="<?php echo !empty($camposErroresAux["id_rol"]) ? "error" : "" ?>"
                                value="<?php echo $_POST["idRol"] ?? "{$usuario["id_rol"]}"?>"><br><br>

                            <input type="hidden" name="idAntiguo" id="idAntiguo" value="<?php echo $usuario["id_usuario"] ?>">
                            <input type="submit" name="enviarModificar" id="enviarModificar" value="Enviar"/>
                            <input type="submit" name="cancelar" id="cancelar" value="cancelar"/>
                        </form><?php
                    }
                }elseif(!empty($_POST["ver"])){
                    $id_usuario=$_POST["ver"];
                    $id_usuario = filter_var($id_usuario, FILTER_SANITIZE_NUMBER_INT);
                    $booleanID = comprobarID($con, $id_usuario);
                    if($id_usuario===false || $id_usuario===null || $booleanID===false || $id_usuario<0){
                        echo "Por favor, no modifique los datos ocultos.";
                    }else{
                        $row=buscarUsuario($con, $id_usuario);
                        $rowAux=comprobarUsuarioID($con,$_SESSION["id_usuario"]);
                        $rol=comprobarRol($con, $_SESSION["id_rol"]);
                        $descripcion="{$rowAux["nombre"]} {$rowAux["apellidos"]} <Rol: {$rol["nombre"]}> ha realizado una vista del usuario con identificador: $id_usuario, de la tabla \"usuairo\".";
                        añadirLog($con,$descripcion);
                        ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID usuario</th>
                                    <th>Email</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>ID rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $row["id_usuario"] ?></td>
                                    <td><?php echo $row["email"] ?></td>
                                    <td><?php echo $row["nombre"] ?></td>
                                    <td><?php echo $row["apellidos"] ?></td>
                                    <td><?php echo $row["id_rol"] ?></td>
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