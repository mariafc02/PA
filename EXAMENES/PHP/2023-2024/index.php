<!DOCTYPE html>
<?php
session_start();
include 'utilidad.php';
include 'seguridad.php';
$con= conexionDB();

$email=$_SESSION["email"];

if(isset($_SESSION["email"])){
    $notas= obtenerNotasPorUsuario($con, $email, 0);
    if($notas!==false){
        $notasEstructura=[];
        while($nota = mysqli_fetch_assoc($notas)){
            $notasEstructura[]=$nota;
        }
        usort($notasEstructura, function ($a, $b) {
            return $b['fecha'] <=> $a['fecha'];
        });
    }
}else{
    cierreDB($con);
    header("Location: login.php");
    exit();
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Index</title>
    </head>
    <body>
        <h1>Notas</h1>
        <p>Usuario conectado: <?php echo "$email" ?></p>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Texto</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($notasEstructura as $notaFor){
                        ?><tr>
                            <td><?php echo $notaFor["ID"] ?></td>
                            <td><?php echo $notaFor["texto"] ?></td>
                            <td><?php echo $notaFor["fecha"] ?></td>
                            <td>
                                <form action="borrar.php" method="get">
                                    <input type="hidden" name="ID" value="<?php echo $notaFor["ID"] ?>"/>
                                    <input type="submit" name="borrar" value="Borrar"/>
                                </form>
                            </td>
                        </tr><?php
                    }
                ?>
            </tbody>
        </table>

        <a href="alta.php">Crear nueva nota</a>
        <br><br>
        <a href="logout.php">Cerrar Sesi&oacute;n</a>
    </body>
</html>

<?php cierreDB($con); ?>