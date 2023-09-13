<?php
require_once 'main.php';

# Almacenando datos

$nombre = LimpiarCadena($_POST['usuario_nombre']);
$apellido = LimpiarCadena($_POST['usuario_apellido']);

$usuario = LimpiarCadena($_POST['usuario_usuario']);
$email = LimpiarCadena($_POST['usuario_email']);

$clave1 = LimpiarCadena($_POST['usuario_clave_1']);
$clave2 = LimpiarCadena($_POST['usuario_clave_2']);


# verificando campos obligatorio

if (
    $nombre == "" || $apellido == "" ||
    $usuario == "" || $clave1 == "" || $clave2 == ""
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
}


# verificando email
if ($email != "") {
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



# las contraseñas coinciden?

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


# guardando datos en la base de datos
$guardar_usuario = conexion();
$guardar_usuario = $guardar_usuario->prepare("INSERT INTO 
                    Usuario(nombre,apellido,usuario,clave,email ) 
                    VALUES (:nombre,:apellido,:usuario,:clave,:email)");

$marcadores = [
    ":nombre" => $nombre,
    ":apellido" => $apellido,
    ":usuario" => $usuario,
    ":clave" => $clave,
    ":email" => $email
];

$guardar_usuario->execute($marcadores);

if ($guardar_usuario->rowCount() == 1) {
    echo '<div class="notification is-success is-light">
            <button class="delete"></button>
            <strong>!Ocurrio un error inesperado!</strong> <br>
            El usuario se registro con exito
        </div>';
} else {
    echo '<div class="notification is-danger is-light">
            <button class="delete"></button>
            <strong>!Ocurrio un error inesperado!</strong> <br>
            Error al regitrar, intente nuevamente
        </div>';
}

$guardar_usuario = null;
