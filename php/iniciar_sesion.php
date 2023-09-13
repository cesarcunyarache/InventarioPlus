<?php

#almacenar datos
$usuario = LimpiarCadena($_POST['login_usuario']);
$clave = LimpiarCadena($_POST['login_clave']);

#comprabaro que los datos no esten vacios
if ( $usuario == "" || $clave == "" ) {
    echo '<div class="notification is-danger is-light">
               <button class="delete"></button>
               <strong>!Ocurrio un error inesperado!</strong> <br>
                No has llenado todos lo campos que son obligatorios
             </div>';
    exit();
}

#verificar la integridad de los datos de la
if (VerificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
    echo '<div class="notification is-danger is-light">
               <button class="delete"></button>
               <strong>!Ocurrio un error inesperado!</strong> <br>
                El usuario no coincide con el formato solicitado
             </div>';
    exit();
}

if (VerificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
    echo '<div class="notification is-danger is-light">
               <button class="delete"></button>
               <strong>!Ocurrio un error inesperado!</strong> <br>
                La contraseña no coincide con el formato solicitado
             </div>';
    exit();
}


#consulta a la base de datos;
$check_user = conexion();
$check_user = $check_user -> query("SELECT *
                                    FROM Usuario 
                                    WHERE usuario = '$usuario'");

if ($check_user->rowCount() == 1){
    $check_user = $check_user->fetch();
    
    if($check_user['usuario'] == $usuario && 
         password_verify($clave, $check_user['clave'])){

            $_SESSION['id']= $check_user['idUsuario'];
            $_SESSION['nombre'] = $check_user['nombre'];
            $_SESSION['apellido'] = $check_user['apellido'];
            $_SESSION['usuario'] = $check_user['usuario'];

            if (headers_sent()){
                echo "<script> 
                        window.location.href='index.php?vista=home'
                    </script>";
            } else {
                header("Location: index.php?vista=home");
            }
        
    } else {
        echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                Clave incorrecta
            </div>';
    }

}else {
    echo '<div class="notification is-danger is-light">
            <button class="delete"></button>
            <strong>!Ocurrio un error inesperado!</strong> <br>
            Usuario incorrecto
        </div>';
}

$check_user = null;