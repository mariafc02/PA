<?php 

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