<?php

$camposErrores=[];
$aux=false;

function validar(){
    $errores = [];
    $camposErroresAux=[];
    $aux=false;
    
    if(!empty($_POST)){
        $email=filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $date=date("d-m-Y H:i:s");

        if(!$email || $email===null){
            $errores[]="El email no puede estar vacio y tiene que estar bien escrito.";
            $camposErroresAux["email"]=true;
        }
        if(!empty($_FILES["archivo"] && $_FILES["archivo"]["error"]===0)){
            if($_FILES["archivo"]["type"]!=="text/plain" || !preg_match('/\.txt$/', $_FILES["archivo"]["name"])){
                $errores[]="El formato del fichero no corresponde con el formato esperado.";
                $camposErroresAux["archivo"]=true;
            }else{
                $contadorLineas = 0;
                $archivoAux = fopen($_FILES["archivo"]["tmp_name"], "r");
    
                if ($archivoAux !== false) {
                    while (($datos = fgets($archivoAux)) !== false) {
                        $datos = htmlspecialchars(strip_tags(trim($datos)));
                        $contadorLineas++;
                        if($datos === ""){
                            $errores[] = "La linea $contadorLineas esta en blanco.";
                            $camposErroresAux["archivo"]=true;
                            break;
                        }
                        if (strlen($datos) > 500) {
                            $errores[] = "La línea $contadorLineas excede los 500 caracteres.";
                            $camposErroresAux["archivo"]=true;
                            break;
                        }
                        if ($contadorLineas > 100) {
                            $errores[] = "El archivo excede el límite de 100 líneas.";
                            $camposErroresAux["archivo"]=true;
                            break;
                        }
                    }
                    fclose($archivoAux);
                } else {
                    $errores[] = "El archivo introducido no se pudo abrir.";
                }
            }
        }
        
        
        if(empty($errores)){
            $csv = "logs.csv";
            $archivo = fopen($csv, "a");
            if ($archivo !== false) {
                flock($archivo, LOCK_EX);
                $nombre = pathinfo($_FILES["archivo"]["name"], PATHINFO_FILENAME);
                $extension = pathinfo($_FILES["archivo"]["name"], PATHINFO_EXTENSION);
                $directorio = "ok";
                $uniqid="";
                $destino="";
                do{
                    $uniqid = uniqid();
                    $destino = $directorio . "/" . $nombre . "_" . $uniqid . "." . $extension;
                }while(file_exists($destino));
                if(!file_exists($directorio)){
                    mkdir($directorio, 0777, true);
                }
                if(!move_uploaded_file($_FILES["archivo"]["tmp_name"], $destino)){
                    $errores[] = "Error al mover el archivo al directorio $directorio.";
                }
                fputcsv($archivo, [$date, $email, $nombre . "_" . $uniqid . "." . $extension]);
                flock($archivo, LOCK_UN);
                fclose($archivo);
            } else {
                $errores[]="No se pudo abrir el archivo para guardar el log.";
            }
        }

        if(!empty($errores)){
            foreach($errores as $error){
                echo $error ."<br>";
            }
        }else{
            $aux=true;
        }
    }

    return [$aux, $camposErroresAux];
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Verificacion</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .error { 
                color: red; 
                border: 2px solid red;
            }
        </style>
    </head>
    <body>
        <?php list($aux, $camposErrores)=validar() ?>
        <main>
            <form method="POST" enctype="multipart/form-data">
                <label for="archivo">Archivo:</label>
                <input type="file" name="archivo" id="archivo" 
                        class="<?php echo !empty($camposErrores["archivo"]) ? "error" : "" ?>" required/><br>

                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" 
                        value="<?php echo $_POST["email"] ?? ""?>" 
                        class="<?php echo !empty($camposErrores["email"]) ? "error" : "" ?>" required><br>

                <button type="submit">Verificar</button>
            </form>
            <?php 
            if($aux){
                echo "<h2>El fichero es apto.</h2>";
            }
            ?>
        </main>
    </body>
</html>