<?php

require_once "main.php";
require_once "../inc/session_start.php";

#Almacenando datos
$codigo = limpiar_cadena($_POST['producto_codigo']);
$nombre = limpiar_cadena($_POST['producto_nombre']);

$precio = limpiar_cadena($_POST['producto_precio']);
$stock = limpiar_cadena($_POST['producto_stock']);
$categoria = limpiar_cadena($_POST['producto_categoria']);


#Verificando campos obligatorios

if($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == ""){
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    No has llenado todos los campos de el formulario
    </div>';
    exit();
}


# Verificando integridad de los datos

if (!preg_match("/^[a-zA-Z0-9- ]{1,70}$/", $codigo)) {
    echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El código no coincide con el formato solicitado
    </div>';
    exit();
}

if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}$/", $nombre)) {
    echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El nombre no coincide con el formato solicitado
    </div>';
    exit();
}

if (!preg_match("/^[0-9.]{1,25}$/", $precio)) {
    echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El precio no coincide con el formato solicitado
    </div>';
    exit();
}

if (!preg_match("/^[0-9]{1,25}$/", $stock)) {
    echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El stock no coincide con el formato solicitado
    </div>';
    exit();
}

# Verificando la existencia del código en la base de datos

$conexion = conexion();
$check_codigo = $conexion->prepare("SELECT producto_id FROM producto WHERE producto_codigo = :codigo");
$check_codigo->execute(array(':codigo' => $codigo));
if ($check_codigo->rowCount() > 0) {
    echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El código ingresado ya se encuentra registrado, por favor elija otro
    </div>';
    exit();
}
$check_codigo = null;

# Verificando la existencia de nombre en la base de datos

$check_nombre = $conexion->prepare("SELECT producto_id FROM producto WHERE producto_nombre = :nombre");
$check_nombre->execute(array(':nombre' => $nombre));
if ($check_nombre->rowCount() > 0) {
    echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El nombre ingresado ya se encuentra registrado, por favor elija otro
    </div>';
    exit();
}
$check_nombre = null;

# Verificando que la categoría exista en la base de datos

$check_categoria = $conexion->prepare("SELECT categoria_id FROM categoria WHERE categoria_id = :categoria");
$check_categoria->execute(array(':categoria' => $categoria));
if ($check_categoria->rowCount() == 0) {
    echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La categoría seleccionada no existe, por favor elija una categoría válida
    </div>';
    exit();
}
$check_categoria = null;

#Directorio de imagenes
$img_dir = "../img/productos/";

#Comprobar si se selecciono una imagen
if($_FILES['producto_foto']['name'] != "" && $_FILES['producto_foto']['size'] > 0){

    #Creando directorio
    if(!file_exists($img_dir)){

        if(!mkdir($img_dir,0777)){
            echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Error al crear el directorio
        </div>';
        exit();
        }

    }

    #Verificar el formato de las imagenes
    if(mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" 
    && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png"){
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La imagen que ha seleccionado es de un formato que no es permitido
        </div>';
        exit();
    }

    #Verificar peso de imagen
    if(($_FILES['producto_foto']['size'])/1024 > 3072){
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La imagen que ha seleccionado supera el peso permitido
        </div>';
        exit();
    }

    #Extension de la imagen
    switch(mime_content_type($_FILES['producto_foto']['tmp_name'])){
        case 'image/jpeg':
            $img_ext = ".jpg";
            break;
        case 'image/png':
            $img_ext = ".png";
            break;
        case 'image/jpg':
            $img_ext = ".jpg";
            break;
    }

    chmod($img_dir,0777);

    $img_nombre = renombrar_fotos($nombre);
    $foto = $img_nombre.$img_ext;

    #Moviendo imagen al directorio
    if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)){
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No fue posible cargar la imagen al sistema en este momento
        </div>';
        exit();
    }

}else{
    $foto = "";
}

#Guardar datos

$guardar_producto = conexion();
$guardar_producto = $guardar_producto->prepare("INSERT INTO producto(producto_codigo,producto_nombre,producto_precio,producto_stock,producto_foto,categoria_id,usuario_id) 
VALUES(:codigo,:nombre,:precio,:stock,:foto,:categoria,:usuario)");

$marcadores=[
    ":codigo"=>$codigo,
    ":nombre"=>$nombre,
    ":precio"=>$precio,
    ":stock"=>$stock,
    ":foto"=>$foto,
    ":categoria"=>$categoria,
    ":usuario"=>$_SESSION['id']
];

$guardar_producto->execute($marcadores);

if($guardar_producto->rowCount()==1){
    echo '
        <div class="notification is-info is-light">
            <strong>¡PRODUCTO REGISTRADO!</strong><br>
            El producto se registro con exito
        </div>
    ';
}else{

    if(is_file($img_dir.$foto)){
        chmod($img_dir.$foto,0777);
        unlink($img_dir.$foto);
    }

    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo registrar el producto, por favor intente nuevamente
        </div>
    ';
}
$guardar_producto=null;
?>