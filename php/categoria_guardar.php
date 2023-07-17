<?php
require_once "main.php";

$conexion = conexion();

#Almacenando datos

$nombre = limpiar_cadena($_POST['categoria_nombre']);
$ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

#Verificando campos obligatorios

if($nombre == "" || $ubicacion == ""){
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    No has llenado todos los campos de el formulario
    </div>';
    exit();
}

#Verificando integridar de los datos

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El nombre no coincide con el formato solicitado
    </div>';
    exit();
}


if($ubicacion!=""){
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El apellido no coincide con el formato solicitado
        </div>';
        exit();
    }
}else{
    echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Porfavor ingrese datos en la ubicacion
        </div>';
        exit();
}


#Verificando categoria 

$check_categoria=$conexion->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre='$nombre'");
if($check_categoria->rowCount()>0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La categoria ingresada ya se encuentra registrada, por favor elija otro
        </div>
    ';
    exit();
}

#Guardar datos

$guardar_categoria = $conexion->prepare("INSERT INTO categoria(categoria_nombre,categoria_ubicacion) VALUES(:nombre,:ubicacion)");

$marcadores=[
    ":nombre"=>$nombre,
    ":ubicacion"=>$ubicacion
];

$guardar_categoria->execute($marcadores);

if($guardar_categoria->rowCount()==1){
    echo '
        <div class="notification is-info is-light">
            <strong>CATEGORIA REGISTRADO!</strong><br>
            La categoria se registro con exito
        </div>
    ';
}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo registrar la categoria, por favor intente nuevamente
        </div>
    ';
}
$conexion = null;
?>