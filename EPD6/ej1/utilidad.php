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

function añadirUsuarioSingup($con, $email, $password, $nombre, $apellidos){
    $sql="INSERT INTO usuario " . "(email, password, nombre, apellidos, id_rol)" . "VALUES ('$email', '$password', '$nombre', '$apellidos', 0)";
    if(!mysqli_query($con, $sql)){
        echo "No se ha podido registrar el usuario" . mysqli_error($con);
    }
}

function añadirUsuario($con, $email, $password, $nombre, $apellidos, $id_rol){
    $sql="INSERT INTO usuario " . "(email, password, nombre, apellidos, id_rol)" . "VALUES ('$email', '$password', '$nombre', '$apellidos', '$id_rol')";
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

function comprobarRol($con, $rol){
    $check_query="SELECT * FROM rol WHERE id_rol='$rol'";
    $check_result=mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_result)===1){
        $row=mysqli_fetch_assoc($check_result);
        return $row;
    }else{
        return -1;
    }
}

function comprobarUsuarioID($con, $id){
    $check_query="SELECT * FROM usuario WHERE id_usuario='$id'";
    $check_result=mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_result)===1){
        $row=mysqli_fetch_assoc($check_result);
        return $row;
    }else{
        return -1;
    }
}

function comprobarID($con, $id){
    $check_query="SELECT * FROM usuario WHERE id_usuario='$id'";
    $check_result=mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_result)===1){
        return true;
    }else{
        return false;
    }
}

function comprobarSku($con, $sku){
    $check_query="SELECT * FROM producto WHERE sku='$sku'";
    $check_result=mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_result)===1){
        return true;
    }else{
        return false;
    }
}

function buscarProducto($con, $sku){
    $check_query="SELECT * FROM producto WHERE sku='$sku'";
    $check_result=mysqli_query($con, $check_query);
    $row=mysqli_fetch_assoc($check_result);
    return $row;
}

function buscarUsuario($con, $id){
    $check_query="SELECT * FROM usuario WHERE id_usuario='$id'";
    $check_result=mysqli_query($con, $check_query);
    $row=mysqli_fetch_assoc($check_result);
    return $row;
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

function eliminarProducto($con,$sku){
    $check_query="DELETE FROM producto WHERE sku='$sku'";
    if(!mysqli_query($con, $check_query)){
        echo "No se ha podido eliminar el producto" . mysqli_error($con);
    }
}

function eliminarUsuario($con,$id){
    $check_query="DELETE FROM usuario WHERE id_usuario='$id'";
    if(!mysqli_query($con, $check_query)){
        echo "No se ha podido eliminar el producto" . mysqli_error($con);
    }
}

function añadirLog($con,$descripcion){
    $sql="INSERT INTO logs " . "(descripcion)" . " VALUES ('$descripcion')";
    if(!mysqli_query($con, $sql)){
        echo "No se ha podido registrar el log" . mysqli_error($con);
    }
}

function modificarProducto($con, $sku, $descripcion, $num_pasillo, $num_estanteria, $cantidad){
    $check_query="UPDATE producto SET descripcion='$descripcion', num_pasillo='$num_pasillo', num_estanteria='$num_estanteria', cantidad='$cantidad' WHERE sku='$sku'";
    if(!mysqli_query($con, $check_query)){
        echo "No se ha podido eliminar el producto" . mysqli_error($con);
    }
}

function modificarUsuario($con, $id, $password, $nombre, $apellidos, $id_rol){
    $check_query="UPDATE usuario SET password='$password', nombre='$nombre', apellidos='$apellidos', id_rol='$id_rol' WHERE id_usuario='$id'";
    if(!mysqli_query($con, $check_query)){
        echo "No se ha podido eliminar el producto" . mysqli_error($con);
    }
}

function crearProducto($con, $sku, $descripcion, $num_pasillo, $num_estanteria, $cantidad){
    $sql="INSERT INTO producto " . "(sku, descripcion, num_pasillo, num_estanteria, cantidad)" . "VALUES ('$sku', '$descripcion', '$num_pasillo', '$num_estanteria', '$cantidad')";
    if(!mysqli_query($con, $sql)){
        echo "No se ha podido eliminar el producto" . mysqli_error($con);
    }
}
?>