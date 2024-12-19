<!DOCTYPE html>
<?php
session_start();

include 'utilidad.php';
include 'seguridad.php';
$con= conexionDB();

$campoErrores=[];

if(isset($_POST["texto"])){
    $texto= htmlspecialchars(trim($_POST["texto"]));
    $fecha = explode("-", $_POST["fecha"]);
    if(sizeof($fecha)!==3 || empty($fecha)){
        $campoErrores["fecha"]=true;
    }else{
        list($anyo, $mes, $dia)=[(int)$fecha[0], (int)$fecha[1], (int)$fecha[2]];
        echo $dia;
        $anyo=filter_var($anyo, FILTER_SANITIZE_NUMBER_INT);
        $mes=filter_var($mes, FILTER_SANITIZE_NUMBER_INT);
        $dia=filter_var($dia, FILTER_SANITIZE_NUMBER_INT);
        if($anyo===false || $anyo===null || empty($anyo)){
            $campoErrores["fecha"]=true;
        }elseif($mes===false || $mes===null || empty($mes)){
            $campoErrores["fecha"]=true;
        }elseif($dia===false || $dia===null || empty($dia)){
            $campoErrores["fecha"]=true;
        }elseif(!checkdate($mes, $dia, $anyo)){
            $campoErrores["fecha"]=true;
        }
    }
    if($texto===false || $texto===null || empty($texto)){
        $campoErrores["texto"]=true;
    }
    if(empty($campoErrores)){
        $id = uniqid();
        $email = $_SESSION["email"];
        altaNota($con, $email, $id, $texto, $_POST["fecha"]);
        cierreDB($con);
        header("Location: index.php");
        exit();
    }
    
}

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Alta de nota</title>
    </head>
    <body>
        <h1>Alta de nota</h1>

        <form method="post">
            <label for="texto">Texto:</label>
            <br>
            <textarea id="texto" name="texto" rows="5" cols="40" required="false"><?php echo $_POST["texto"] ?? ""?></textarea>
            <br>
            <label for="fecha">Fecha:</label>
            <br>
            <input type="date" id="fecha" name="fecha" value="<?php echo $_POST["fecha"] ?? ""?>" required="">
            <br><br>
            <input type="submit">
        </form>
        <?php
            if(!empty($campoErrores)){
                if(isset($campoErrores["fecha"])){
                    ?><p style="color: red; font-weight: bold" > La fecha introducida es incorrecta.</p><?php
                }
                if(isset($campoErrores["texto"])){
                    ?><p style="color: red; font-weight: bold" > El texto introducido no es valido.</p><?php
                }
                ?><p style="color: red; font-weight: bold" > No se ha podido crear la nota.</p><?php
            }
        ?>

    </body>
</html>

<?php cierreDB($con) ?>