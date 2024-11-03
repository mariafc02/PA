<?php

$camposErrores=[];
$aux=true;

function validar(){
    $errores = [];
    $camposErroresAux=[];
    $aux=true;

    if(!empty($_POST)){
        $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
        $idUsado = false;

        $csv = "inventario.csv";
        $archivoControlar=fopen($csv, "r");
        if($archivoControlar !== false){
            flock($archivoControlar, LOCK_SH);
            while(($datos = fgetcsv($archivoControlar, 500, ",")) !== false){
                if($datos[0] == $id){
                    $idUsado = true;
                    break;
                }
            }
            flock($archivoControlar, LOCK_UN);
            fclose($archivoControlar);
        }

        $nombre = htmlspecialchars(trim($_POST["nombre"]));
        $descripcion = htmlspecialchars(trim($_POST["descripcion"]));
        $cantidad = filter_input(INPUT_POST, "cantidad", FILTER_SANITIZE_NUMBER_INT);
        $precio = filter_input(INPUT_POST, "precio", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if(!$id || $id <= 0 || $id===null || $idUsado===true){
            $errores[] = "El ID debe ser mayor que 0, entero, no puede estar vacio y no puede estar ya usado.";
            $camposErroresAux["id"]=true;
        }
        if(!$nombre || $nombre===null){
            $errores[] = "El nombre no puede estar vacio ni contener caracteres dañinos.";
            $camposErroresAux["nombre"]=true;
        }
        if(!$descripcion || $descripcion===null){
            $errores[] = "La descripcion no puede estar vacia ni contener caracteres dañinos.";
            $camposErroresAux["descripcion"]=true;
        }
        if(!$cantidad || $cantidad < 0 || $cantidad===null){
            $errores[] = "La cantidad no puede ser menor que 0, tiene que ser un numero entero y no puede estar vacia.";
            $camposErroresAux["cantidad"]=true;
        }
        if(!$precio || $precio===null || $precio <= 0 || !preg_match('/^\d+(\.\d{1,2})?$/',$precio)){
            $errores[] = "El precio tiene que ser mayor que 0, no puede estar vacio y puede tener maximo 2 decimales.";
            $camposErroresAux["precio"]=true;
        }

        if(empty($errores)){
            $aux=false;
            
            $archivo = fopen($csv, "a");
            if ($archivo !== false) {
                flock($archivo, LOCK_EX);
                fputcsv($archivo, [$id, $nombre, $descripcion, $cantidad, $precio]);
                flock($archivo, LOCK_UN);
                fclose($archivo);
            } else {
                $errores[]="No se pudo abrir el archivo para guardar el producto.";
            }
        }else{
            foreach($errores as $error){
                echo $error ."<br>";
            }
        }
    }

    return [$aux,$camposErroresAux];
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Agregar Producto</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="estilo.css">
    </head>
    <body>
        <?php list($aux,$camposErrores)=validar()?>
        <header>
            <h1>Agregar Nuevo Producto</h1>
        </header>
        <main>
            <form action="agregar.php" method="POST">
                <label for="id">ID del Producto:</label>
                <input type="number" name="id" id="id" 
                        value="<?php echo $_POST["id"] ?? ""?>" 
                        class="<?php echo !empty($camposErrores["id"]) ? "error" : "" ?>" required>
                        
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" 
                        value="<?php echo $_POST["nombre"] ?? ""?>" 
                        class="<?php echo !empty($camposErrores["nombre"]) ? "error" : "" ?>" required>
                        
                <label for="descripcion">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" 
                        value="<?php echo $_POST["descripcion"] ?? ""?>" 
                        class="<?php echo !empty($camposErrores["descripcion"]) ? "error" : "" ?>" required>
                        
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" 
                        value="<?php echo $_POST["cantidad"] ?? ""?>" 
                        class="<?php echo !empty($camposErrores["cantidad"]) ? "error" : "" ?>" required>
                        
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" name="precio" id="precio" 
                        value="<?php echo $_POST["precio"] ?? ""?>" 
                        class="<?php echo !empty($camposErrores["precio"]) ? "error" : "" ?>"required>
                        
                <button type="submit">Agregar Producto</button>
            </form>
            <form action="index.php">
                <button type="submit">Volver a inventario</button>
            </form>
        </main>
    </body>
</html>