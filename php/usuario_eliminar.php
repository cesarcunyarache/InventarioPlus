<?php
$user_id_del = LimpiarCadena($_GET['user_id_del']);

$checkUsuario = Conexion();
$checkUsuario = $checkUsuario->query("SELECT IdUsuario FROM Usuario WHERE IdUsuario = '$user_id_del'");


if ($checkUsuario->rowCount() == 1) {

    $checkProductos = Conexion();
    $checkProductos = $checkProductos->query("SELECT IdUsuario FROM Producto WHERE IdUsuario = '$user_id_del' LIMIT 1");

    if ($checkProductos->rowCount() <= 0) {

        $eliminarUsuario = Conexion();
        $eliminarUsuario = $eliminarUsuario->prepare("DELETE FROM Usuario WHERE IdUsuario=:id");
        
        $eliminarUsuario->execute([":id" => $user_id_del]);

        if($eliminarUsuario ->rowCount() == 1) {
            echo '<div class="notification is-success is-light">
                    <button class="delete"></button>
                    <strong>!Usuario eliminado!</strong> <br>
                    Los datos del usuario fueron elimados
                </div>';

           

        } else {
            echo '<div class="notification is-danger is-light">
                    <button class="delete"></button>
                    <strong>!Ocurrio un error inesperado!</strong> <br>
                    No se pudo eliminar el usuario, por favor intente nuevamente
                </div>';
        }

        $eliminarUsuario = null;


    } else {
        echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                No podemos eliminar, el usuario cuenta con productos registrados
            </div>';
    }
    $checkProductos = null;
} else {
    echo '<div class="notification is-danger is-light">
                <button class="delete"></button>
                <strong>!Ocurrio un error inesperado!</strong> <br>
                El usuario que intenta eliminar no existe
            </div>';
}
$checkUsuario = null;
