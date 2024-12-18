<!DOCTYPE html>
<?php
session_start();

include 'utilidad.php';

$con = conexionDB();

$campoErrores=[];

if(isset($_POST["email"])){
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($_POST["password"]));
    
    if(!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', $email) || $email===false){
        $campoErrores["emailCorrecto"]=true;
    }
    if(empty($email)){
        $campoErrores["emailRelleno"]=true;
    }
    if(empty($password)){
        $campoErrores["passwordRelleno"]=true;
    }
    if(empty($campoErrores)){
        $result = comprobarEmailContrasenia($con, $email);
        if($result===false){
            $campoErrores["combinacion"]=true;
        }else{
            if(password_verify($password, $result["contrasenia_hash"])){
                $_SESSION["email"]=$email;
                cierreDB($con);
                header("Location: index.php");
                exit();
            }else{
                $campoErrores["combinacion"]=true;
            }
        }
    }
    
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Sistema de notas</title>
    </head>
    <body>
        <h1>Iniciar sesi&oacute;n</h1>

        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" >
            <br>
            <br>
            <label for="password">Contrase&ntilde;a:</label>
            <input type="password" id="password" name="password">
            <br>
            <br>
            <input type="submit" value="Iniciar sesi&oacute;n">
            <br>
        </form>
        <?php
            if(!empty($campoErrores)){
                if(!empty($campoErrores["emailCorrecto"])){
                    ?><p style="color: red; font-weight: bold" > El usuario no es correcto.</p><?php
                }
                if(!empty($campoErrores["emailRelleno"])){
                    ?><p style="color: red; font-weight: bold" > El usuario debe rellenarses.</p><?php
                }
                if(!empty($campoErrores["passwordRelleno"])){
                    ?><p style="color: red; font-weight: bold"> La contrase&ntilde; debe rellenarse.</p><?php
                }
                if(!empty($campoErrores["combinacion"])){
                    ?><p style="color: red; font-weight: bold"> La combinación de usuario y contrase&ntilde; no es correcta.</p><?php
                }
            }
        ?>
    </body>
</html>
<?php cierreDB($con) ?>