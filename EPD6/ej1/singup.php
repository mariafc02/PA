<?php
include 'utilidad.php';

$aux=true;

function validar(){
    $aux=false;
    $campoErroresAux=[];
    $errores=[];
    
    if(!empty($_POST)){
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $booleanEmail = comprobarEmail(con,$email);
        $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT);
        $nombre = htmlspecialchars(trim($_POST["nombre"]));
        $apellidos = htmlspecialchars(trim($_POST["apellidos"]));

        if(!$email || $email===null || $booleanEmail===true || !preg_match('/\@almacen.com$/', $email)){
            $errores[]="El email no puede estar vacio, ni contener caracteres dañinos, tiene que estar bien escrito y tiene que terminar en @almacen.com.";
            $campoErroresAux["email"]=true;
        }
        if(!$password || $password===null || empty($password)){
            $errores[]="La contraseña no puede estar vacia ni puede contener caracteres dañinos.";
            $campoErroresAux["password"]=true;
        }
        if(!$nombre || $nombre===null || empty($nombre)){
            $errores[]="El nombre no puede estar vacio, ni puede contener caracteres dañinos.";
            $campoErroresAux["nombre"]=true;
        }
        if(!$apellidos || $apellidos===null || empty($apellidos)){
            $errores[]="El apellido no puede estar vacio, ni puede contener caracteres dañinos.";
            $campoErroresAux["apellido"]=true;
        }
    }

    if(empty($errores)){
        $aux=true;
        añadirUsuario($email, $password, $nombre, $apellidos);        
    }else{
        foreach($errores as $error){
            echo $error ."<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de Usuario</title>
    </head>
    <body>
        <?php validar() ?>
        <header>
            <h1>Registro de Usuario</h1>
        </header>
        <main>
            <form method="POST">
                <label for="nombre">Nombre:</label><br>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="apellidos">Apellidos:</label><br>
                <input type="text" id="apellidos" name="apellidos" required><br><br>
    
                <label for="email">Correo Electrónico:</label><br>
                <input type="email" id="email" name="email" required><br><br>
    
                <label for="password">Contraseña:</label><br>
                <input type="password" id="password" name="password" required><br><br>
    
                <button type="submit">Registrarse</button> <!-- INPUT-->
                <button type="submit">Iniciar sesion</button>
            </form>
        </main>
    </body>
</html>