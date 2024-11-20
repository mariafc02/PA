<?php /*
$con = mysqli_connect("localhost","root","");
if(!$con){
    die('No ha sido posible la conexión a la base de datos: '.mysqli_error());
}

$db_selected = mysqli_select_db($con, "epd6_ej1");
if(!$db_selected){
    mysqli_close($con);
    die("No se pudo usar la basse de datos: ".mysqli_error($con));
}


//mysqli_close($con);*/

function conexionDB(){
    $servername="localhost";
    $username="root";
    $password="";
    $dbname="epd6";

    $con=mysqli_connect($servername, $username, $password, $dbname);
    if(!$con){
        die("Conexion fallida: " . mysqli_connect_error());
    }
    return $con;
}

function cierreConexion($con){
    mysqli_close($con);
}
?>