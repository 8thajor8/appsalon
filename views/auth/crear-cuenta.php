<h1 class="nombre-pagina">Crear Cuenta</h1>

<p class="descripcion-pagina">Ingresa tus datos para crear una cuenta</p> 

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/crear-cuenta">

    <div class="campo">
        <label for="nombre">Nombre:</label>
        <input
            type="nombre"
            id="nombre"
            placeholder="Tu nombre"
            name="nombre"
            value="<?php echo s($usuario->nombre); ?>"
        />
    </div>

    <div class="campo">
        <label for="apellido">Apellido:</label>
        <input
            type="apellido"
            id="apellido"
            placeholder="Tu apellido"
            name="apellido"
            value="<?php echo s($usuario->apellido); ?>"
        />
    </div>

    <div class="campo">
        <label for="telefono">Telefono:</label>
        <input
            type="tel"
            id="telefono"
            placeholder="Tu telefono"
            name="telefono"
            value="<?php echo s($usuario->telefono); ?>"
        />
    </div>

    <div class="campo">
        <label for="email">Email:</label>
        <input
            type="email"
            id="email"
            placeholder="Tu email"
            name="email"
            value="<?php echo s($usuario->email); ?>"
        />
    </div>

    <div class="campo">
        <label for="password">Password:</label>
        <input
            type="password"
            id="password"
            placeholder="Tu password"
            name="password"
            value="<?php echo s($usuario->password); ?>"
        />
    </div>

    <input type="submit" class="boton" value="Crear Cuenta">

</form>

<div class="acciones">
    <a href="/">Ya tienes una cuenta? Iniciar Sesion</a>
    <a href="/olvide">Olvidaste tu password?</a>
</div>