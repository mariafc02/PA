<?php
session_start();

if(!isset($_SESSION["id_usuario"])){
    header("Location: login.php");
    exit();
}

$rol = (int)$_SESSION["id_rol"];

?>
<!DOCTYPE html>
<html>
    <head>
        <title>EPD06</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/estilo.css">
    </head>
    <body>
        <header>
            <h1>Bienvenido</h1>
        </header>
        <main>
            <section class="index">
                <h3>Aqui le mostramos las paginas a las que puede acceder:</h3>
                <ul>
                    <?php
                        
                        if($rol===1){
                            echo "<li><a href=\"usuarios.php\">Usuarios</a></li>";
                        }elseif($rol===2 || $rol===3){
                            echo "<li><a href=\"usuarios.php\">Usuarios</a></li>";
                            echo "<li><a href=\"productos.php\">Productos</a></li>";
                        }else{
                            echo "<li>Aun no se le ha asignado un rol valido, mantente a la espera.</li>";
                        }
                    ?>
                </ul>
            </section>
        </main>
    </body>
    <footer>
        <form action="logout.php">
            <input type="submit" name="logout" id="logout" value="Logout">
        </form>
    </footer>
</html>
