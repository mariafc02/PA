<?php

include 'config.php';
const con = conexionDB();

function comprobarEmail($email){
    $check_query="SELECT email FROM usuario WHERE email='$email'";
    $check_result=mysqli_query(con, $check_query);
    if(mysqli_num_rows($check_result)>0){
        return true;
    }else{
        return false;
    }
}

function comprobarRol($in_rol){
    $check_query="SELECT email FROM usuario WHERE in_rol='$in_rol'";
    $check_result=mysqli_query(con, $check_query);
    if(mysqli_num_rows($check_result)>0){
        return true;
    }else{
        return false;
    }
}

function añadirUsuario($email, $password, $nombre, $apellidos){
    $sql="INSERT INTO usuario " . "(email, password, nombre, apellidos, in_rol)" . "VALUES ('$email', '$password', '$nombre', '$apellidos', 0)";
    if(mysqli_query(con, $sql)){
        // AQUI LA REDIRECCION A LA OTRA PAGINA
    }else{
        echo "No se ha podido registrar el usuario" . mysqli_error(con);
    }
}

?>