<?php
// Cálculo del valor de inicio para la consulta SQL utilizando paginación.
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

// Variable para construir la tabla que mostrará los datos.
$tabla = "";

// Comprobar si se ha especificado una búsqueda en el formulario.
if (isset($busqueda) && $busqueda != "") {
    // Consulta SQL para obtener los datos filtrados por la búsqueda.
    $consulta_datos = "SELECT * FROM categoria WHERE categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%' ORDER BY categoria_nombre ASC LIMIT $inicio,$registros";

    // Consulta SQL para obtener el total de registros filtrados por la búsqueda.
    $consulta_total = "SELECT COUNT(categoria_id) FROM categoria WHERE categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%'";
} else {
    // Consulta SQL para obtener todos los datos sin filtro de búsqueda.
    $consulta_datos = "SELECT * FROM categoria ORDER BY categoria_nombre ASC LIMIT $inicio,$registros";

    // Consulta SQL para obtener el total de registros sin filtro de búsqueda.
    $consulta_total = "SELECT COUNT(categoria_id) FROM categoria";
}

// Conexión a la base de datos (se asume que hay una función llamada "conexion()" que devuelve la conexión).
$conexion = conexion();

// Ejecutar las consultas SQL y almacenar los resultados en variables.
$datos = $conexion->query($consulta_datos);
$datos = $datos->fetchAll();

$total = $conexion->query($consulta_total);
$total = (int)$total->fetchColumn();

// Cálculo del número total de páginas necesarias para la paginación.
$Npaginas = ceil($total / $registros);

// Construir la estructura de la tabla HTML.
$tabla .= '
<div class="table-container">
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
            <tr class="has-text-centered">
                <th>#</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Productos</th>
                <th colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>
';

// Comprobar si existen datos y si la página actual es válida.
if ($total >= 1 && $pagina <= $Npaginas) {
    $contador = $inicio + 1;
    $pag_inicio = $inicio + 1;

    // Recorrer los datos obtenidos y construir las filas de la tabla con la información de cada categoría.
    foreach ($datos as $rows) {
        $tabla .= '
            <tr class="has-text-centered">
                <td>' . $contador . '</td>
                <td>' . $rows['categoria_nombre'] . '</td>
                <td>' . $rows['categoria_ubicacion'] . '</td>
                <td>
                    <a href="index.php?vista=product_category&category_id='.$rows['categoria_id'].'" class="button is-link is-rounded is-small">Ver productos</a>
                </td>
                <td>
                    <a href="index.php?vista=category_update&categoria_id_up=' . $rows['categoria_id'] . '" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="' . $url . $pagina . '&categoria_id_del=' . $rows['categoria_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
                </td>
            </tr>
        ';
        $contador++;
    }
    $pag_final = $contador - 1;
} else {
    // Mostrar mensaje en caso de que no haya registros o la página sea inválida.
    if ($total >= 1) {
        $tabla .= '
            <tr class="has-text-centered">
                <td colspan="5">
                    <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    } else {
        $tabla .= '
            <tr class="has-text-centered">
                <td colspan="5">
                    No hay registros en el sistema
                </td>
            </tr>
        ';
    }
}

// Cerrar la estructura de la tabla HTML.
$tabla .= '</tbody></table></div>';

// Mostrar información adicional si hay registros y la página actual es válida.
if ($total > 0 && $pagina <= $Npaginas) {
    $tabla .= '<p class="has-text-right">Mostrando categorías <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

// Cerrar la conexión a la base de datos.
$conexion = null;

// Imprimir la tabla generada con los datos de las categorías.
echo $tabla;

// Mostrar el paginador para navegar entre las diferentes páginas.
if ($total >= 1 && $pagina <= $Npaginas) {
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
?>