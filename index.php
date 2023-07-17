<?php
// Se incluye el archivo "session_start.php", que probablemente contiene la lógica para iniciar o reanudar una sesión en PHP.
require "./inc/session_start.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    // Se incluye el archivo "head.php" que posiblemente contenga la sección head común a todas las páginas, como metadatos, hojas de estilo y scripts.
    include "./inc/head.php";
    ?>
</head>
<body>
    <?php
    // Comprobación para asegurar que siempre haya una vista válida para cargar. Si el parámetro "vista" no está presente o está vacío, se establece "login" como valor predeterminado.
    if (!isset($_GET["vista"]) || $_GET["vista"] == "") {
        $_GET["vista"] = "login";
    }

    // Verificar si el archivo correspondiente a la vista solicitada existe en el directorio "./vistas/" y si el parámetro "vista" no es ni "login" ni "404".
    if (is_file("./vistas/" . $_GET["vista"] . ".php") && $_GET["vista"] != "login" && $_GET["vista"] != "404") {

        // Comprobación para cerrar la sesión si el usuario no está autenticado. Si alguna de las variables de sesión "id" o "usuario" no está configurada o está vacía, se destruye la sesión.
        if ((!isset($_SESSION['id']) || $_SESSION['id'] == "") || (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "")) {
            session_destroy();
            // Si se han enviado encabezados, se utiliza JavaScript para redirigir al usuario a la página de inicio de sesión.
            if (headers_sent()) {
                echo "<script> window.location.href='index.php?vista=login'; </script>";
            } else {
                // De lo contrario, se utiliza una redirección HTTP para llevar al usuario a la página de inicio de sesión.
                header("Location: index.php?vista=login");
            }
            exit();
        }

        // Se incluye el archivo "navbar.php", que probablemente contiene la barra de navegación común a todas las páginas.
        include "./inc/navbar.php";

        // Se incluye el archivo correspondiente a la vista solicitada. Esto carga el contenido específico de la página que el usuario desea ver.
        include "./vistas/" . $_GET["vista"] . ".php";

        // Se incluye el archivo "script.php", que podría contener scripts JavaScript comunes a todas las páginas.
        include "./inc/script.php";
    } else {
        // Si la vista solicitada no existe o es "login", se carga la vista "login.php". De lo contrario, se carga la vista "404.php".
        if ($_GET["vista"] == "login") {
            include "./vistas/login.php";
        } else {
            include "./vistas/404.php";
        }
    }
    ?>
</body>
</html>
