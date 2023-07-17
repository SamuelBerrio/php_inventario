<?php
    require_once "./php/main.php";

    // Obtener el ID de la categoría a actualizar desde los parámetros GET o establecerlo a 0 si no se proporciona
    $id = (isset($_GET['categoria_id_up'])) ? $_GET['categoria_id_up'] : 0;
    $id = limpiar_cadena($id);
?>

<!-- Sección de título -->
<div class="container is-fluid mb-6">
    <?php if ($id == $_SESSION['id']) { ?>
        <h1 class="title">Mi cuenta</h1>
        <h2 class="subtitle">Actualizar datos de cuenta</h2>
    <?php } else { ?>
        <h1 class="title">Categorías</h1>
        <h2 class="subtitle">Actualizar categoría</h2>
    <?php } ?>
</div>

<!-- Sección de formulario de actualización -->
<div class="container pb-6 pt-6">
    <?php
        // Incluir el botón de regreso
        include "./inc/btn_back.php";

        /*== Verificando categoría ==*/
        // Realizar una consulta para verificar si la categoría con el ID proporcionado existe en la base de datos
        $check_categoria = conexion();
        $check_categoria = $check_categoria->query("SELECT * FROM categoria WHERE categoria_id='$id'");

        // Si se encontró una categoría con el ID proporcionado, mostrar el formulario de actualización
        if ($check_categoria->rowCount() > 0) {
            $datos = $check_categoria->fetch();
    ?>

    <!-- Formulario de actualización -->
    <div class="form-rest mb-6 mt-6"></div>
    <form action="./php/categoria_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">

        <!-- Campo oculto para el ID de la categoría a actualizar -->
        <input type="hidden" name="categoria_id" value="<?php echo $datos['categoria_id']; ?>" required>

        <!-- Campos para los datos de la categoría -->
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre</label>
                    <input class="input" type="text" name="categoria_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['categoria_nombre']; ?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Ubicación</label>
                    <input class="input" type="text" name="categoria_ubicacion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,150}" maxlength="150" required value="<?php echo $datos['categoria_ubicacion']; ?>">
                </div>
            </div>
        </div>
        <!-- Campos para la verificación del usuario y clave actual -->
        <br><br><br>
        <p class="has-text-centered">
            Para poder actualizar los datos de esta categoría por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión
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
            // Si no se encontró una categoría con el ID proporcionado, mostrar un mensaje de error
            include "./inc/error_alert.php";
        }
        $check_categoria = null;
    ?>
</div>