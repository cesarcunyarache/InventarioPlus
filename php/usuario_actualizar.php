<?php

require_once "../inc/sesion_start.php";
require_once "../php/main.php";

$id = LimpiarCadena($_POST['usuario_id']);


// verificar el usuario;
echo "<script> document.write(" . $id . ")</script>";

$checkUsuario = Conexion();
$checkUsuario = $checkUsuario->query("SELECT * FROM Usuario WHERE idUsuario = '$id'");

if ($checkUsuario->rowCount() <= 0) {
    echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                El usuario no existe en el sistema
            </div>';
    exit();
} else {
    $datos = $checkUsuario->fetch();
}

$checkUsuario = null;


$admin_usuario = LimpiarCadena($_POST['administrador_usuario']);
$admin_clave  = LimpiarCadena($_POST['administrador_clave']);


if ($admin_usuario == "" || $admin_clave == "") {
    echo '<div class="notification is-danger is-light">
                   <button class="delete"></button>
                   <strong>!Ocurrio un error inesperado!</strong> <br>
                    No has llenado todos lo campos que son obligatorios
                 </div>';
    exit();
}


if (VerificarDatos("[a-zA-Z0-9]{4,20}", $admin_usuario)) {
    echo '<div class="notification is-danger is-light">
                   <button class="delete"></button>
                   <strong>!Ocurrio un error inesperado!</strong> <br>
                    El USUARIO no coincide con el formato solicitado
                 </div>';
    exit();
}


if (VerificarDatos("[a-zA-Z0-9$@.-]{7,100}",  $admin_clave)) {
    echo '<div class="notification is-danger is-light">
                   <button class="delete"></button>
                   <strong>!Ocurrio un error inesperado!</strong> <br>
                    La CLAVE no coincide con el formato solicitado
                 </div>';
    exit();
}

$checkAdmin = Conexion();
$checkAdmin = $checkAdmin->query("SELECT usuario, clave FROM Usuario WHERE usuario = '$admin_usuario' AND idUsuario ='" . $_SESSION['id'] . "'");

if ($checkAdmin->rowCount() == 1) {
    $checkAdmin = $checkAdmin->fetch();

    if ($checkAdmin['usuario'] != $admin_usuario || !password_verify($admin_clave, $checkAdmin['clave'])) {
        echo '<div class="notification is-danger is-light">
                    <button class="delete"></button>
                    <strong>!Ocurrio un error inesperado!</strong> <br>
                    USUARIO o CLAVE del administrador son incorrectos
                </div>';
    }
} else {
    echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                USUARIO o CLAVE del administrador son incorrectos
            </div>';
    exit();
}

$checkAdmin =  null;


# Almacenando datos

$nombre = LimpiarCadena($_POST['usuario_nombre']);
$apellido = LimpiarCadena($_POST['usuario_apellido']);

$usuario = LimpiarCadena($_POST['usuario_usuario']);
$email = LimpiarCadena($_POST['usuario_email']);

$clave1 = LimpiarCadena($_POST['usuario_clave_1']);
$clave2 = LimpiarCadena($_POST['usuario_clave_2']);


if (
    $nombre == "" || $apellido == "" ||
    $usuario == ""
) {
    echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                    No has llenado todos lo campos que son obligatorios
                </div>';
    exit();
}

#verificar la integridad de los datos de la
if (VerificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
    echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                    El nombre no coincide con el formato solicitado
                </div>';
    exit();
}

if (VerificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
    echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                    El apellido no coincide con el formato solicitado
                </div>';
    exit();
}


if (VerificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
    echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                    El usuario no coincide con el formato solicitado
                </div>';
    exit();
}


# verificando email
if ($email != "" && $email != $datos['email']) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $check_email = conexion($email);
        $check_email = $check_email->query("SELECT email FROM Usuario WHERE email = '$email'");

        if ($check_email->rowCount() > 0) {
            echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                    El email ya esta registrado en la base de datos
                </div>';
            exit();
        }
        $check_email = null;
    } else {
        echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                    El email ingresado no es valido
                </div>';
        exit();
    }
}


#verifando el usuario
if ($usuario != $datos['usuario']) {
    $check_usuario = conexion();
    $check_usuario = $check_usuario->query("SELECT usuario FROM Usuario WHERE usuario = '$usuario'");

    if ($check_usuario->rowCount() > 0) {
        echo '<div class="notification is-danger is-light">
                    <button class="delete"></button>
                    <strong>!Ocurrio un error inesperado!</strong> <br>
                    El usuario no es valido, ya existe en la base de datos
                </div>';
        exit();
    }

    $check_usuario = null;
}




# las contraseñas coinciden?
if ($clave1 != "" || $clave2 != "") {
    if (
        VerificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave1)
        || VerificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave2)
    ) {

        echo '<div class="notification is-danger is-light">
                       <button class="delete"></button>
                       <strong>!Ocurrio un error inesperado!</strong> <br>
                        Las claves no coincide con el formato solicitado
                     </div>';
        exit();
    } else {
        if ($clave1 != $clave2) {
            echo '<div class="notification is-danger is-light">
                        <button class="delete"></button>
                        <strong>!Ocurrio un error inesperado!</strong> <br>
                        Las contraseñas no coincicen
                    </div>';
            exit();
        } else {
            $clave = password_hash($clave1, PASSWORD_BCRYPT, ["cost" => 10]);
        }
    }
} else {
    $clave = $datos['clave'];
}


# actualizar los datos 

$actualizarDatos = Conexion();
$actualizarDatos = $actualizarDatos->prepare("UPDATE Usuario SET nombre=:nombre, apellido=:apellido,usuario=:usuario, clave=:clave, email=:email WHERE idUsuario=:id");

$marcadores = [
    ":nombre" => $nombre,
    ":apellido" => $apellido,
    ":usuario" => $usuario,
    ":clave" => $clave,
    ":email" => $email,
    ":id" => $id
];


if ($actualizarDatos->execute($marcadores)){
    echo '<div class="notification is-success is-light">
            <button class="delete"></button>
            <strong>!Enhorabuena!</strong> <br>
            El usuario se actualizó correctamente!
        </div>';

} else {

    echo '<div class="notification is-danger is-light">
            <button class="delete"></button>
            <strong>!Ocurrio un error inesperado!</strong> <br>
            No se pudo actualizar el usuario, por favor intente nuevamente
        </div>';
}
$actualizarDatos = null;
