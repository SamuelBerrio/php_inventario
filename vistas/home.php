<div class="container is-fluid">
    <h1 class="title">Home</h1>
    <h2 class="subtitle">¡Bienvenido usuario <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?>!</h2>
    <?php
    phpinfo();
    ?>
</div>