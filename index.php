<?php require './inc/sesion_start.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './inc/head.php' ?>
</head>

<body>
    <?php
    if (!isset($_GET['vista']) || $_GET['vista'] == "") {
        $_GET['vista'] = "login";
    }

    if (is_file("./vistas/" . $_GET['vista'] . ".php") && $_GET['vista'] != "login" && $_GET['vista'] != "404") {
        #cierre de sesion forzado
        if ((!isset($_SESSION['id']) || $_SESSION['id'] == "") ||
            (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "")
        ) {
            include "./vistas/logout.php";
            exit();
        }
        include "./inc/nav.php";
        include "./vistas/" . $_GET['vista'] . ".php";
        include "./inc/script.php";
    } else {
        if ($_GET['vista'] == "login") {
            include "./vistas/login.php";
        } else {
            include "./vistas/404.php";
        }
    }

    ?>

</body>

</html>