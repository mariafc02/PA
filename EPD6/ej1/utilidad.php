<?php

include 'config.php';
$con = conexionDB();

function comprobarEmail($con, $email){
    $check_query="SELECT email FROM usuario WHERE email='$email'";
    $check_result=mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_result)>0){
        return true;
    }else{
        return false;
    }
}

/*function comprobarRol($con, $id_rol){
    $check_query="SELECT email FROM usuario WHERE id_rol='$id_rol'";
    $check_result=mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_result)>0){
        return true;
    }else{
        return false;
    }
}*/

function añadirUsuario($con, $email, $password, $nombre, $apellidos){
    $sql="INSERT INTO usuario " . "(email, password, nombre, apellidos, id_rol)" . "VALUES ('$email', '$password', '$nombre', '$apellidos', 0)";
    if(!mysqli_query($con, $sql)){
        echo "No se ha podido registrar el usuario" . mysqli_error($con);
    }
}

function comprobarUsuario($con, $email){
    $check_query="SELECT * FROM usuario WHERE email='$email'";
    $check_result=mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_result)===1){
        $row=mysqli_fetch_assoc($check_result);
        return $row;
    }else{
        return -1;
    }
}

function cargarProductos($con){
    $check_query="SELECT * FROM producto";
    $check_result=mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_result)>0){
        return $check_result;
    }else{
        return -1;
    }
}

function cargarUsuarios($con){
    $check_query="SELECT * FROM usuario";
    $check_result=mysqli_query($con, $check_query);
    return $check_result;
}

function cargarUsuariosOperarios($con){
    $check_query="SELECT * FROM usuario WHERE id_rol='2'";
    $check_result=mysqli_query($con, $check_query);
    return $check_result;
}
?>