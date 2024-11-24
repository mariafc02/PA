<?php
session_start();

include "utilidad.php";

function validar($con){
    $camposErroresAux=[];
    $errores=[];
    
    if(isset($_POST["inicioSesion"])){
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST["password"]));

        setcookie("email", $email, time() + (24 * 60 * 60));

        if($email===false || $email===null || !preg_match('/\@almacen.com$/', $email)){
            $errores[]="El email no puede estar vacio, ni contener caracteres dañinos, tiene que estar bien escrito y tiene que terminar en @almacen.com.";
            $camposErroresAux["email"]=true;
        }
        if($password===false || $password===null || empty($password)){
            $errores[]="La contraseña no puede estar vacia ni puede contener caracteres dañinos.";
            $camposErroresAux["password"]=true;
        }

        if(empty($errores)){
            $row=comprobarUsuario($con, $email);
            if($row!=-1){
                if(password_verify($password, $row["password"])){
                    $_SESSION["id_usuario"]=$row["id_usuario"];
                    $_SESSION["id_rol"]=$row["id_rol"];
                    header("Location: index.php");
                }else{
                    $errores[]="La contraseña no es correcta.";
                    $camposErroresAux["password"]=true;
                }
            }else{
                $errores[]="El email introducido no esta asociado a ningun usuario.";
                $camposErroresAux["email"]=true;
            }
        }
        if(!empty($errores)){
            cierreConexion($con);
            return [$errores,$camposErroresAux];
        }
    }
    if(isset($_POST["registro"])){
        header("Location: singup.php");
    }
    cierreConexion($con);
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/estilo.css">
        <title>Inicio de sesion</title>
    </head>
    <body>
        <?php list($errores,$camposErroresAux)=validar($con) ?>
        <header>
            <h1>Inicio de sesion</h1>
        </header>
        <main>
            <form method="POST">
                <label for="email">Correo Electrónico:</label><br>
                <input type="email" id="email" name="email" 
                    class="<?php echo !empty($camposErroresAux["email"]) ? "error" : "" ?>"
                    value="<?php echo $_POST["email"] ?? ""?>"><br><br>
    
                <label for="password">Contraseña:</label><br>
                <input type="password" id="password" name="password" 
                    class="<?php echo !empty($camposErroresAux["password"]) ? "error" : "" ?>"><br><br>
    
                <input type="submit" name="inicioSesion" id="inicioSesion" value="Iniciar sesion"/><br><br>
                <input type="submit" name="registro" id="registro" value="Ir a registrarse"/>
            </form>
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