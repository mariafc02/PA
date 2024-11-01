<?php

function inventario($csv) {
    $inventarioAux = [];
    $archivo = fopen($csv, "r");
    if ($archivo !== FALSE) {
        flock($archivo, LOCK_SH);
        while (($datos = fgetcsv($archivo, 500, ",")) !== FALSE) {
            $inventarioAux[] = $datos;
        }
        flock($archivo, LOCK_UN);
        fclose($archivo);
    }
    return $inventarioAux;
}

$inventario = inventario("inventario.csv");

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inventario</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="estilo.css">
    </head>
    <body>
        <header>
            <h1>Inventario de Almacén</h1>
        </header>
        <main>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventario as $producto){ ?>
                        <tr>
                            <?php 
                            foreach ($producto as $dato){ 
                                echo "<td> $dato </td>";
                            }
                            ?>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo "{$producto[0]}" ?>">
                                    <button type="submit">Actualizar</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo "{$producto[0]}" ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <form action="agregar.php">
                <button type="submit">Agregar Nuevo Producto</button>
            </form>
        </main>
    </body>
</html>
