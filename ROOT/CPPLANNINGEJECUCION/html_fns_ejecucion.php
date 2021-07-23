<?php
include_once('../html_fns.php');

function tabla_ejecucion($codigo, $usuario, $desde, $hasta, $inicio, $sistema)
{
	$ClsAcc = new ClsAccion();
	$ClsEje = new ClsEjecucion();
	$result = $ClsAcc->get_programacion_aprobada($codigo, $usuario, $desde, $hasta, $inicio, $sistema);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example">';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "50px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "150px">Objetivo</th>';
		$salida .= '<th class = "text-center" width = "150px">Acci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "50px">Fecha Planificada</th>';
		$salida .= '<th class = "text-center" width = "50px">&Uacute;ltima Fecha</th>';
		$salida .= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($result as $row) {
			$codigo = $row["pro_codigo"];
			$fini = strtotime($row["pro_fecha_inicio"]);
			$ffin = strtotime($row["pro_fecha_fin"]);
			$hoy = strtotime(date("Y-m-d"));
			$rs = $ClsEje->get_ejecucion_accion("", $codigo);
			// Si no tiene ejecucion y esta en fecha entonces esta pendiente
			if (!is_array($rs) && $ffin >= $hoy && $fini <= $hoy) {
				$salida .= '<tr>';
				// Codigo
				$codigo_ejecucion = trim($row['eje_codigo']);
				$codigo = agrega_ceros($row["pro_codigo"]);
				$salida .= '<td class = "text-center">' . $codigo . '.</td>';
				// Proceso
				$proceso = utf8_decode($row["proceso_nombre"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				// Sistema
				$sistema = utf8_decode($row["sistema_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				// Objetivo
				$objetivo = utf8_decode($row["obj_descripcion"]);
				if (strlen($objetivo) > 100) $objetivo = substr($objetivo, 0, 100) . "...";
				$objetivo = nl2br($objetivo);
				$salida .= '<td class = "text-left">' . $objetivo . '</td>';
				// Accion
				$accion = utf8_decode($row["acc_nombre"]);
				$salida .= '<td class = "text-left">' . $accion . '</td>';
				// Fecha Inicial
				$fini = trim($row["pro_fecha_inicio"]);
				$salida .= '<td class = "text-left">' . $fini . '</td>';
				// Fecha Final
				$ffin = trim($row["pro_fecha_fin"]);
				$salida .= '<td class = "text-left">' . $ffin . '</td>';
				// --
				$codigo = $row["pro_codigo"];
				$hashkey = $ClsAcc->encrypt($codigo, $usuario);
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Confirm_Cerrar_Accion(' . $codigo . ');" title = "Cancelar Acci&oacute;n" ><i class="fa fa-trash"></i></button>';
				$salida .= '<a class="btn btn-success btn-xs" href = "FRMejecutar.php?hashkey=' . $hashkey . '" title = "Ejecutar Acci&oacute;n" ><i class="fas fa-clipboard-check"></i> Disponible</a> ';
				$salida .= '</div>';
				$salida .= '</td>';
				//--
				$salida .= '</tr>';
			}
			// Si tiene ejecucion y esta en fecha esta en proceso y no esta cancelada ni finalizada
			else if (is_array($rs) && $ffin >= $hoy && $fini <=  $hoy && $rs[0]["eje_situacion"] == 1) {
				$salida .= '<tr>';
				// Codigo
				$codigo_ejecucion = utf8_decode($row['eje_codigo']);
				$codigo = agrega_ceros($row["pro_codigo"]);
				$salida .= '<td class = "text-center">' . $codigo . '.</td>';
				// Proceso
				$proceso = utf8_decode($row["proceso_nombre"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				// Sistema
				$sistema = utf8_decode($row["sistema_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				// Objetivo
				$objetivo = utf8_decode($row["obj_descripcion"]);
				if (strlen($objetivo) > 100) $objetivo = substr($objetivo, 0, 100) . "...";
				$objetivo = nl2br($objetivo);
				$salida .= '<td class = "text-left">' . $objetivo . '</td>';
				// Accion
				$accion = utf8_decode($row["acc_nombre"]);
				if (strlen($accion) > 100) $accion = substr($accion, 0, 100) . "...";
				$accion = nl2br($accion);
				$salida .= '<td class = "text-left">' . $accion . '</td>';
				// Fecha Inicial
				$fini = trim($row["pro_fecha_inicio"]);
				$salida .= '<td class = "text-left">' . $fini . '</td>';
				// Fecha Final
				$ffin = trim($row["pro_fecha_fin"]);
				$salida .= '<td class = "text-left">' . $ffin . '</td>';
				// --
				$codigo = $row["pro_codigo"];
				$hashkey = $ClsAcc->encrypt($codigo, $usuario);
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Confirm_Cerrar_Accion(' . $codigo . ');" title = "Cancelar Acci&oacute;n" ><i class="fa fa-trash"></i></button>';
				$salida .= '<a class="btn btn-success btn-xs" href = "FRMejecutar.php?hashkey=' . $hashkey . '" title = "Ejecutar Acci&oacute;n" ><i class="fas fa-clipboard-check"></i> En Proceso</a> ';
				$salida .= '</div>';
				$salida .= '</td>';
				//--
				$salida .= '</tr>';
			}
		}
	}
	$salida .= '</tbody>';
	$salida .= '</table>';
	return $salida;
}

function tabla_evaluacion($proceso, $objetivo, $sistema, $usuario)
{
	// Ejecucion
	$ClsEje = new ClsEjecucion();
	$ClsAcc = new ClsAccion();
	$result = $ClsAcc->get_programacion_aprobada("", $usuario, "", "", $objetivo, $sistema, "", $proceso);
	if (is_array($result)) {
		$sisNombre = $result[0]["sistema_nombre"];
		$salida = '<hr><h6 class="card-title"><i class="fa fa-check"></i> ' . $sisNombre . '</h6>';
		$salida .= '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "50px">Proceso.</th>';
		$salida .= '<th class = "text-center" width = "250px">Acci&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "50px">Fecha Inicio</th>';
		$salida .= '<th class = "text-left" width = "50px">Fecha Final</th>';
		$salida .= '<th class = "text-left" width = "75px">Usuario</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			// Ejecucion
			$codigo = $row["pro_codigo"];
			$rs = $ClsEje->get_ejecucion_accion("", $codigo, "", "", 2);
			if (is_array($rs)) {
				// Si no esta Evaluada 
				$evaluacion = $rs[0]["evaluacion_ejecutada"];
				if ($evaluacion == "") {
					$salida .= '<tr>';
					// No.
					$salida .= '<td class = "text-center">' . $codigo . '.</td>';
					// Proceso
					$proceso = trim($row["proceso_nombre"]);
					$salida .= '<td class = "text-left">' . $proceso . '</td>';
					// Descripcion
					$descripcion = trim($row["acc_nombre"]);
					$salida .= '<td class = "text-left">' . $descripcion . '</td>';
					// Fecha Inicial
					$fini = trim($row["pro_fecha_inicio"]);
					$salida .= '<td class = "text-left">' . $fini . '</td>';
					// Fecha Final
					$ffin = trim($row["pro_fecha_fin"]);
					$salida .= '<td class = "text-left">' . $ffin . '</td>';
					// Usuario
					$usuario = trim($row["usuario_nombre"]);
					$salida .= '<td class = "text-left">' . $usuario . '</td>';
					// Evaluar
					$salida .= '<td class = "text-center" >';
					$codigo = $row["pro_codigo"];
					$hashkey = $ClsAcc->encrypt($codigo, $_SESSION["codigo"]);
					$salida .= '<a class="btn btn-success btn-lg" href = "FRMevaluar.php?hashkey=' . $hashkey . '" title = "Evaluar" ><i class="fa fa-clipboard-check"></i></a>';
					$salida .= '</td>';
					//--
					$salida .= '</tr>';
					$i++;
				}
			}
		}
	}
	$salida .= '</tbody>';
	$salida .= '</table>';

	if ($i != 1) return $salida;
}

function tipo_objetivo($tipo)
{
	switch ($tipo) {
		case 1:
			return "Objetivo";
		case 2:
			return "Indicador";
		case 3:
			return "Control";
	}
}

function day_name($day, $tipo)
{
	if ($tipo == "W") {
		switch ($day) {
			case 1:
				return "Lunes";
			case 2:
				return "Martes";
			case 3:
				return "Miercoles";
			case 4:
				return "Jueves";
			case 5:
				return "Viernes";
			case 6:
				return "Sabado";
			case 7:
				return "Domingo";
			default:
				return "Domingo";
		}
	} else {
		return "D&iacute;a $day del Mes";
	}
}
