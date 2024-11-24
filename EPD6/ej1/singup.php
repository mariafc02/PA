<?php
include 'utilidad.php';

function validar($con){
    $camposErroresAux=[];
    $errores=[];
    
    if(isset($_POST["registro"])){
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $booleanEmail = comprobarEmail($con,$email);
        $password =htmlspecialchars(trim($_POST["password"]));
        $passwordHash=password_hash($password,PASSWORD_DEFAULT);
        $nombre = htmlspecialchars(trim($_POST["nombre"]));
        $apellidos = htmlspecialchars(trim($_POST["apellidos"]));

        if(!$email || $email===null || $booleanEmail===true || !preg_match('/\@almacen.com$/', $email)){
            $errores[]="El email no puede estar vacio, ni contener caracteres dañinos, tiene que estar bien escrito, no puede estar en uso y tiene que terminar en @almacen.com.";
            $camposErroresAux["email"]=true;
        }
        if(!$password || $password===null || empty($password)){
            $errores[]="La contraseña no puede estar vacia ni puede contener caracteres dañinos.";
            $camposErroresAux["password"]=true;
        }
        if(!$nombre || $nombre===null || empty($nombre)){
            $errores[]="El nombre no puede estar vacio, ni puede contener caracteres dañinos.";
            $camposErroresAux["nombre"]=true;
        }
        if(!$apellidos || $apellidos===null || empty($apellidos)){
            $errores[]="El apellido no puede estar vacio, ni puede contener caracteres dañinos.";
            $camposErroresAux["apellidos"]=true;
        }

        if(empty($errores)){
            añadirUsuarioSingup($con, $email, $passwordHash, $nombre, $apellidos);
            cierreConexion($con);
            header("Location: login.php");
            exit();
        }else{
            cierreConexion($con);
            return [$errores,$camposErroresAux];
        }
    }
    if(isset($_POST["inicioSesion"])){
        cierreConexion($con);
        header("Location: login.php");
        exit();
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
        <title>Registro de Usuario</title>
    </head>
    <body>
        <?php list($errores,$camposErroresAux)=validar($con) ?>
        <header>
            <h1>Registro de Usuario</h1>
        </header>
        <main>
            <form method="POST">
                <label for="nombre">Nombre:</label><br>
                <input type="text" id="nombre" name="nombre" 
                    class="<?php echo !empty($camposErroresAux["nombre"]) ? "error" : "" ?>"
                    value="<?php echo $_POST["nombre"] ?? ""?>"><br><br>

                <label for="apellidos">Apellidos:</label><br>
                <input type="text" id="apellidos" name="apellidos" 
                    class="<?php echo !empty($camposErroresAux["apellidos"]) ? "error" : "" ?>"
                    value="<?php echo $_POST["apellidos"] ?? ""?>"><br><br>
    
                <label for="email">Correo Electrónico:</label><br>
                <input type="email" id="email" name="email" 
                    class="<?php echo !empty($camposErroresAux["email"]) ? "error" : "" ?>"
                    value="<?php echo $_POST["email"] ?? ""?>"><br><br>
    
                <label for="password">Contraseña:</label><br>
                <input type="password" id="password" name="password" 
                    class="<?php echo !empty($camposErroresAux["password"]) ? "error" : "" ?>"
                    value="<?php echo $_POST["password"] ?? ""?>"><br><br>
    
                <input type="submit" name="registro" id="registro" value="Registrarse"/><br><br>
                <input type="submit" name="inicioSesion" id="inicioSesion" value="Ir a iniciar sesion"/>
            </form>
            <?php 
                if(!empty($errores)){?>
                    <section class="campoErrores"><?php
                        foreach($errores as $error){
                            echo $error ."<br>";
                        }?>

                    </section><?php
                }
            ?>
        </main>
    </body>
</html>