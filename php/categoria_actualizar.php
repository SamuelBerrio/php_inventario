<?php
    require_once "../inc/session_start.php";

    require_once "main.php";

    /*== Almacenando id ==*/
    $id = limpiar_cadena($_POST['categoria_id']);

    /*== Verificando categoría ==*/
    $check_categoria = conexion();
    $check_categoria = $check_categoria->query("SELECT * FROM categoria WHERE categoria_id = '$id'");

    if ($check_categoria->rowCount() <= 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La categoría no existe en el sistema
            </div>
        ';
        exit();
    } else {
        $datos = $check_categoria->fetch();
    }
    $check_categoria = null;

    /*== Almacenando datos del administrador ==*/
    $admin_usuario = limpiar_cadena($_POST['administrador_usuario']);
    $admin_clave = limpiar_cadena($_POST['administrador_clave']);

    /*== Verificando campos obligatorios del administrador ==*/
    if ($admin_usuario == "" || $admin_clave == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No ha llenado los campos que corresponden a su USUARIO o CLAVE
            </div>
        ';
        exit();
    }

    /*== Verificando integridad de los datos (admin) ==*/
    if (verificar_datos("[a-zA-Z0-9]{4,20}", $admin_usuario)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Su USUARIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Su CLAVE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    /*== Verificando el administrador en DB ==*/
    $check_admin = conexion();
    $check_admin = $check_admin->query("SELECT usuario_usuario, usuario_clave FROM usuario WHERE usuario_usuario = '$admin_usuario' AND usuario_id = '" . $_SESSION['id'] . "'");
    if ($check_admin->rowCount() == 1) {

        $check_admin = $check_admin->fetch();

        if ($check_admin['usuario_usuario'] != $admin_usuario || !password_verify($admin_clave, $check_admin['usuario_clave'])) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    USUARIO o CLAVE de administrador incorrectos
                </div>
            ';
            exit();
        }

    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                USUARIO o CLAVE de administrador incorrectos
            </div>
        ';
        exit();
    }
    $check_admin = null;

    /*== Almacenando datos de la categoría ==*/
    $nombre = limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

    /*== Verificando campos obligatorios de la categoría ==*/
    if ($nombre == "" || $ubicacion == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    /*== Verificando integridad de los datos (categoría) ==*/
    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $ubicacion)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La UBICACIÓN no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    /*== Actualizar datos ==*/
    $actualizar_categoria = conexion();
    $actualizar_categoria = $actualizar_categoria->prepare("UPDATE categoria SET categoria_nombre = :nombre, categoria_ubicacion = :ubicacion WHERE categoria_id = :id");

    $marcadores = [
        ":nombre" => $nombre,
        ":ubicacion" => $ubicacion,
        ":id" => $id
    ];

    if ($actualizar_categoria->execute($marcadores)) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡CATEGORÍA ACTUALIZADA!</strong><br>
                La categoría se actualizó con éxito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo actualizar la categoría, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_categoria = null;
?>