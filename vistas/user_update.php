<?php
    require_once "./php/main.php";

    // Obtener el ID del usuario a actualizar desde los parámetros GET o establecerlo a 0 si no se proporciona
    $id = (isset($_GET['user_id_up'])) ? $_GET['user_id_up'] : 0;
    $id = limpiar_cadena($id);
?>

<!-- Sección de título -->
<div class="container is-fluid mb-6">
    <?php if ($id == $_SESSION['id']) { ?>
        <h1 class="title">Mi cuenta</h1>
        <h2 class="subtitle">Actualizar datos de cuenta</h2>
    <?php } else { ?>
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Actualizar usuario</h2>
    <?php } ?>
</div>

<!-- Sección de formulario de actualización -->
<div class="container pb-6 pt-6">
    <?php
        // Incluir el botón de regreso
        include "./inc/btn_back.php";

        /*== Verificando usuario ==*/
        // Realizar una consulta para verificar si el usuario con el ID proporcionado existe en la base de datos
        $check_usuario = conexion();
        $check_usuario = $check_usuario->query("SELECT * FROM usuario WHERE usuario_id='$id'");

        // Si se encontró un usuario con el ID proporcionado, mostrar el formulario de actualización
        if ($check_usuario->rowCount() > 0) {
            $datos = $check_usuario->fetch();
    ?>

    <!-- Formulario de actualización -->
    <div class="form-rest mb-6 mt-6"></div>
    <form action="./php/usuario_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">

        <!-- Campo oculto para el ID del usuario a actualizar -->
        <input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>" required>

        <!-- Campos para los datos del usuario -->
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombres</label>
                    <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['usuario_nombre']; ?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Apellidos</label>
                    <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['usuario_apellido']; ?>">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Usuario</label>
                    <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required value="<?php echo $datos['usuario_usuario']; ?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Email</label>
                    <input class="input" type="email" name="usuario_email" maxlength="70" value="<?php echo $datos['usuario_email']; ?>">
                </div>
            </div>
        </div>
        <!-- Campos para la actualización de la clave del usuario -->
        <br><br>
        <p class="has-text-centered">
            SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
        </p>
        <br>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Clave</label>
                    <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Repetir clave</label>
                    <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
                </div>
            </div>
        </div>
        <!-- Campos para la verificación del usuario y clave actual -->
        <br><br><br>
        <p class="has-text-centered">
            Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión
        </p>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Usuario</label>
                    <input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Clave</label>
                    <input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                </div>
            </div>
        </div>
        <!-- Botón de actualización -->
        <p class="has-text-centered">
            <button type="submit" class="button is-success is-rounded">Actualizar</button>
        </p>
    </form>
    <?php
        } else {
            // Si no se encontró un usuario con el ID proporcionado, mostrar un mensaje de error
            include "./inc/error_alert.php";
        }
        $check_usuario = null;
    ?>
</div>
