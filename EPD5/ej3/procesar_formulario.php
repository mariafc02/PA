<?php

function validarYguardar($csv){
    $archivo = fopen($csv, "r");
    $aux = [];

    if ($archivo !== FALSE) {
        flock($archivo, LOCK_SH);
        while (($datos = fgetcsv($archivo, 1000, ";")) !== FALSE) {
            if(count($datos) === 3) {
                $aux[] = [
                    'label' => $datos[0],
                    'type' => $datos[1],
                    'id' => $datos[2]
                ];
            }
        }
        flock($archivo, LOCK_UN);
        fclose($archivo);
    }
    return $aux;
}


$datos = validarYguardar("fichero.csv");
?>

<!DOCTYPE html>
<html>  
    <head>
        <title>Nuevo Usuario</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="estilo.css">
    </head>
    <body>
        <header>
            <h1>Nuevo Usuario</h1>
        </header>
        <main>
            <form action="" method="POST">
                <label for="csv_file">Seleccione el archivo CSV:</label>
                <input type="file" name="cvs_file" id="csv_file" accept=".csv" required>
                <button type="submit">Generar Formulario</button>
            </form>

            <?php if (!empty($formFields)) : ?>
                <h2>Formulario generado</h2>
                <form action="procesar_formulario.php" method="POST">
                    <?php foreach ($formFields as $field): ?>
                        <label for="<?= htmlspecialchars($field['id']) ?>"><?= htmlspecialchars($field['label']) ?></label>
                    
                        <?php if (in_array($field['type'], ['text', 'email', 'date'])): ?>
                            <input type="<?= htmlspecialchars($field['type']) ?>" id="<?= htmlspecialchars($field['id']) ?>" name="<?= htmlspecialchars($field['id']) ?>" required>
                        <?php else: ?>
                            <p>Error: Tipo de campo inválido (<?= htmlspecialchars($field['type']) ?>).</p>
                        <?php endif; ?>
                        <br>
                    <?php endforeach; ?>
                    <button type="submit">Enviar</button> 
                </form>   
            <?php endif; ?>
        </main>
    </body>
</html>

