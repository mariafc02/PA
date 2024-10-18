<!DOCTYPE html>
<?php
function ej1(){
    $vector=array(
        "Empresa" => "empresa.html",
        "Productos" => "productos.html",
        "Consultoria" => "consultoria.html",
        "Clientes" => "clientes.html",
        "Proyectos" => "proyectos.html",
        "Blog" => "blog.html",
        "Noticias" => "noticias.html",
        "Contacto" => "contacto.html"
    );

    $numeroOpciones=rand(2, count($vector));
    $opciones= array_rand($vector, $numeroOpciones);

    foreach($opciones as $opcion){
        echo "<li><a href=\"$vector[$opcion]\">$opcion</a></li>\n";
    }
}
?>
<html>
    <head>
        <title>Pagina de inicio</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <header>
            <h1>Inicio</h1>
        </header>
        <nav>
            <ul>
                <li><a href="./index.html">Inicio</a></li>
                <?php ej1()?>
            </ul>
        </nav>
        <section>
            <article>
                <h3>Este es la pagina de inicio de nuestro almacen.</h3>
                <h4>Aqui podras encontrar todo tipo de informacion sobre nosotros, por ejemplo:</h4>
                <ol>
                    <li>Sobre nuestra empresa.</li>
                    <li>Sobre nuestros productos.</li>
                    <li>Consultoria.</li>
                    <li>Clientes.</li>
                    <li>Proyectos.</li>
                    <li>Blog.</li>
                    <li>Noticiasl.</li>
                    <li>Contactos.</li>
                </ol>
            </article>
        </section>
        <footer>
            <nav>
                <ul>
                    <li><a href="./contacto.html">Contacta con nosotros</a></li>
                    <li>Nuestro telefono: +34 000000000</li>
                    <li>Y nuestro correo electronico: info@almacen.es</li>
                </ul>
            </nav>
        </footer>
    </body>
</html>
