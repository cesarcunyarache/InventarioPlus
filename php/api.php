<?php

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://dog-breeds2.p.rapidapi.com/dog_breeds",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: dog-breeds2.p.rapidapi.com",
		"X-RapidAPI-Key: 1b68539ff6msh4c38c82b4d44459p122127jsnec487d26e78c"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
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

if ($total >= 1 && $pagina <= $Npaginas) {
    $contador = $inicio + 1;
    $pag_inicio = $inicio + 1;
    foreach ($datos as $rows) {
        $tabla .= '
				<tr class="has-text-centered">
					<td>' . $contador . '</td>
                    <td>' . $rows['nombre'] . '</td>
                    <td>' . substr($rows['ubicacion'], 0, 25) . '</td>
                    <td>
                        <a href="index.php?vista=product_category&category_id=' . $rows['idCategoria'] . '" class="button is-link is-rounded is-small">Ver productos</a>
                    </td>
                    <td>
                        <a href="index.php?vista=user_update&category_id_up=' . $rows['idCategoria'] . '" class="button is-success is-rounded is-small">Actualizar</a>
                    </td>
                    <td>
                        <a href="' . $url . $pagina . '&category_id_del=' . $rows['idCategoria'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
                    </td>
                </tr>
            ';
        $contador++;
    }
    $pag_final = $contador - 1;
} else {
    if ($total >= 1) {
        $tabla .= '
				<tr class="has-text-centered" >
					<td colspan="6">
						<a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
							Haga clic acá para recargar el listado
						</a>
					</td>
				</tr>
			';
    } else {
        $tabla .= '
				<tr class="has-text-centered" >
					<td colspan="6">
						No hay registros en el sistema
					</td>
				</tr>
			';
    }
}


$tabla .= '</tbody></table></div>';

if ($total > 0 && $pagina <= $Npaginas) {
    $tabla .= '<p class="has-text-right">Mostrando categorias <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

$conexion = null;
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
    echo PaginadorTablas($pagina, $Npaginas, $url, 7);

}



?>