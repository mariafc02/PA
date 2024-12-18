<!DOCTYPE html>
<?php
session_start();

include 'utilidad.php';
$con= conexionDB();

$id;
if(isset($_GET["borrar"])){
    $id= filter_input(INPUT_GET, "ID", FILTER_SANITIZE_NUMBER_INT);
    if($id===false || $id===null || empty($id)){
        cierreDB($con);
        header("Location: index.php");
        exit();
    }
}

if(isset($_POST["confirmar"])){
    $id= filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
    if($id===false || $id===null || empty($id)){
        cierreDB($con);
        header("Location: index.php");
        exit();
    }else{
        borradoLogico($con, $id);
        cierreDB($con);
        header("Location: index.php");
    }
}

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Borrar nota</title>
    </head>
    <body>
        <h1>Borrar nota</h1>
 
        <p>&iexcl;Est&aacute;s seguro de que quieres borrar la nota con identificador <?php echo $id ?>&excl;</p>

        <form method="post">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="submit" name="confirmar" value="Confirmar borrado">
        </form>
    </body>
</html>