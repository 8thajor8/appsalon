<h1 class="nombre-pagina">Servicios</h1>

<p class="descripcion-pagina">Nuevo Servicio</p> 

<?php
    
    include_once __DIR__ . '/../templates/alertas.php';

?>

<form action="/servicios/crear" method="POST" class="formulario">

    <?php include 'formulario.php'; ?>

    <input type="submit" class="boton" value="Crear Servicio">

</form>