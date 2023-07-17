<?php
$categoria_id_del = limpiar_cadena($_GET['categoria_id_del']);

# Verificando categoría
$conexion = conexion();
$check_categoria = $conexion->query("SELECT categoria_id FROM categoria WHERE categoria_id = '$categoria_id_del'");

if ($check_categoria->rowCount() == 1) {

    $check_productos = $conexion->query("SELECT categoria_id FROM producto WHERE categoria_id = '$categoria_id_del' LIMIT 1");

    if ($check_productos->rowCount() <= 0) {
        $eliminar_categoria = $conexion->prepare("DELETE FROM categoria WHERE categoria_id=:id");

        $eliminar_categoria->execute([":id" => $categoria_id_del]);

        if ($eliminar_categoria->rowCount() == 1) {
            echo '
            <div class="notification is-info is-light">
                <strong>¡Categoría eliminada!</strong><br>
                Categoría eliminada con éxito
            </div>
        ';
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo eliminar la categoría, por favor intente nuevamente
            </div>
        ';
        }

        $eliminar_categoria = null;
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No podemos eliminar la categoría ya que tiene productos registrados
        </div>
    ';
    }

    $check_productos = null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La categoría que intenta eliminar no existe
    </div>
';
}

$check_categoria = null;
