<?php

function conexionDB(){
    $servername="localhost";
    $username="root";
    $password="";
    $db="notas";
    $con= mysqli_connect($servername, $username, $password, $db);
    if(!$con){
        die("Conexion no establecida:". mysqli_connect_error());
    }
    return $con;
}

function cierreDB($con){
    mysqli_close($con);
}

function comprobarEmailContrasenia($con, $email){
    $query="SELECT contrasenia_hash FROM usuario WHERE email='$email'";
    $combinacion= mysqli_query($con, $query);
    if(mysqli_num_rows($combinacion)>0){
        $row= mysqli_fetch_assoc($combinacion);
        return $row;
    }else{
        return false;
    }
}

function obtenerNotasPorUsuario($con, $email, $estado){
    $query="SELECT ID, texto, fecha FROM notas WHERE email='$email' AND estado='$estado'";
    $combinacion= mysqli_query($con, $query);
    if(mysqli_num_rows($combinacion)>0){
        return $combinacion;
    }else{
        return false;
    }
}

function borradoLogico($con, $id){
    $sql = "UPDATE notas SET estado='1' WHERE ID='$id'";
    if(!mysqli_query($con, $sql)){
        echo "No se ha podido eliminar el producto" . mysqli_error($con);
        return false;
    }
}

function altaNota($con, $email, $id, $texto, $fecha){
    $sql="INSERT INTO notas (ID, texto, fecha, email, estado)VALUES ('$id', '$texto', '$fecha', '$email', 0)";
    if(!mysqli_query($con, $sql)){
        echo "No se ha podido registrar la nota ". mysqli_error($con);
    }
}

function escribirLog($email, $id){
    $NOMBRE_FICHERO_LOG="registro_borrado.log";
    
    $archivo = fopen($NOMBRE_FICHERO_LOG, "a");
    if($archivo !== false){
        flock($archivo, LOCK_EX);
        fputcsv($archivo, [$email, $id], ";");
        flock($archivo, LOCK_UN);
        fclose($archivo);
    }
}

?>