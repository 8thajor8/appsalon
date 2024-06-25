<h1 class="nombre-pagina">Recuperar Password</h1>

<p class="descripcion-pagina">Coloca tu nuevo password</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if(!$error){ ?>
<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Ingresa tu nueva clave"
        />
    </div>
    <input type="submit" class="boton" value="Guardar Nueva Password">

</form>
<?php } ?>

<div class="acciones">
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crear una</a>
    <a href="/">Ya tienes cuenta? Inicia Sesion</a>
</div>
