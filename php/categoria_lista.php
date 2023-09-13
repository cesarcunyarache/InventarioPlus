<?php

    $inicio = ($pagina > 0) ? (($pagina * $registro) - $registro) : 0;
    $tablas = '';

	
    if (isset($busqueda) && $busqueda !== "") {
        $consulta_datos = "SELECT * FROM Categoria WHERE nombre LIKE '%$busqueda%' OR ubicacion LIKE '%$busqueda%' ORDER BY nombre ASC LIMIT $inicio, $registro";
        
		$consulta_total = "SELECT COUNT(idCategoria) FROM Categoria WHERE nombre LIKE '%$busqueda%' OR ubicacion LIKE '%$busqueda%'";
    } else {
        $consulta_datos = "SELECT * FROM Categoria ORDER BY nombre ASC LIMIT $inicio, $registro";

		$consulta_total = "SELECT COUNT(idCategoria) FROM Categoria";
    }


   
    $conexion= Conexion();
	

	$datos = $conexion->query($consulta_datos);
  
	$datos = $datos->fetchAll();
	
	$total = $conexion->query($consulta_total);
	
	$total = (int) $total->fetchColumn();
    
	$Npaginas = ceil($total/$registro);

	

	$tabla.='
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

	if($total>=1 && $pagina<=$Npaginas){
		$contador=$inicio+1;
		$pag_inicio=$inicio+1;
		foreach($datos as $rows){
			$tabla.='
				<tr class="has-text-centered">
					<td>'.$contador.'</td>
                    <td>'.$rows['nombre'].'</td>
                    <td>'.substr($rows['ubicacion'],0, 25).'</td>
                    <td>
                        <a href="index.php?vista=product_category&category_id='.$rows['idCategoria'].'" class="button is-link is-rounded is-small">Ver productos</a>
                    </td>
                    <td>
                        <a href="index.php?vista=user_update&category_id_up='.$rows['idCategoria'].'" class="button is-success is-rounded is-small">Actualizar</a>
                    </td>
                    <td>
                        <a href="'.$url.$pagina.'&category_id_del='.$rows['idCategoria'].'" class="button is-danger is-rounded is-small">Eliminar</a>
                    </td>
                </tr>
            ';
            $contador++;
		}
		$pag_final=$contador-1;
	}else{
		if($total>=1){
			$tabla.='
				<tr class="has-text-centered" >
					<td colspan="6">
						<a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
							Haga clic acá para recargar el listado
						</a>
					</td>
				</tr>
			';
		}else{
			$tabla.='
				<tr class="has-text-centered" >
					<td colspan="6">
						No hay registros en el sistema
					</td>
				</tr>
			';
		}
	}

    
	$tabla.='</tbody></table></div>';
    
	if($total>0 && $pagina<=$Npaginas){
		$tabla.='<p class="has-text-right">Mostrando categorias <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

    $conexion = null;
	echo $tabla;

    if($total>=1 && $pagina<=$Npaginas) {
        echo PaginadorTablas($pagina, $Npaginas, $url, 7);
    }