<?php
include_once('../html_fns.php');

function tabla_quejas($codigo, $queja = "")
{
	$ClsQue = new ClsQuejas();
	$result = $ClsQue->get_quejas($codigo, $queja);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "5%">No.</th>';
		$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-left" width = "20%">Descripci&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "10%">Usuario que Registra</th>';
		$salida .= '<th class = "text-center" width = "10%">Fecha Registro</th>';
		$salida .= '<th class = "text-left" width = "10%">Cliente</th>';
		$salida .= '<th class = "text-left" width = "10%">Tipo </th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["que_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Proceso
			$proceso = trim($row["fic_nombre"]);
			$proceso = nl2br($proceso);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			// sistema 
			$sistema = trim($row["sis_nombre"]);
			$sistema = nl2br($sistema);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			// Descripcion
			$descripcion = trim($row["que_descripcion"]);
			$descripcion = nl2br($descripcion);
			if(strlen($descripcion) > 40){
				$salida .= '<td class = "text-left">' .   substr ( $descripcion, 0, 30) .'...</td>';
			}else{
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';

			}
			// usuario
			$usuario  = trim($row["usu_nombre"]);
			$usuario = nl2br($usuario);
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			// Fecha Inicio
			$fecha = cambia_fecha($row["que_fecha_registro"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//cliente
			$cliente = trim($row["que_cliente"]);
			$cliente = nl2br($cliente);
			$salida .= '<td class = "text-left">' . $cliente . '</td>';
			//tipo
			$tipo = trim($row["que_tipo"]);
			$tipo = nl2br($tipo);
			$salida .= '<td class = "text-left">' . $tipo . '</td>';


			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_externas($codigo = '')
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_externa($codigo);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "10%">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "15%">Tipo</th>';
		$salida .= '<th class = "text-left" width = "20%">Entidad</th>';
		$salida .= '<th class = "text-left" width = "20%">Objetivo</th>';
		$salida .= '<th class = "text-left" width = "20%">Resumen</th>';
		$salida .= '<th class = "text-left" width = "15%">Fecha Realizada</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["ext_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsAud->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '<a class="btn btn-info btn-xd" href = "FRMobservaciones.php?hashkey=' . $hashkey . '"  title = "Observaciones de la Auditor&iacute;a" ><i class="fas fa-list-alt"></i></a> ';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . Agrega_Ceros($codigo) . '</td>';
			// Tipo
			$tipo = get_tipo_auditoria($row["ext_tipo"]);
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			// Entidad
			$entidad = trim($row["ext_entidad"]);
			$salida .= '<td class = "text-left">' . $entidad . '</td>';
			// Objetivo
			$objetivo = trim($row["ext_objetivo"]);
			$salida .= '<td class = "text-left">' . $objetivo . '</td>';
			// Resumen
			$resumen = trim($row["ext_resumen"]);
			$salida .= '<td class = "text-left">' . $resumen . '</td>';
			// Fecha
			$fecha  = cambia_fechaHora($row["ext_fecha_auditoria"]);
			$salida .= '<td class = "text-left">' . $fecha . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_externa_detalle($codigo = '', $auditoria = '')
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_externa_detalle($codigo, $auditoria);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example">';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "5px">No.</th>';
		$salida .= '<th class = "text-left" width = "450px">Observaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["dext_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Causa
			$causa = trim($row["dext_descripcion"]);
			$salida .= '<td class = "text-left">' . $causa . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_identificar_externas()
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_externa_detalle();
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example">';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%">No.</th>';
		$salida .= '<th class = "text-left" width = "20%">Entidad</th>';
		$salida .= '<th class = "text-left" width = "20%">Objetivo</th>';
		$salida .= '<th class = "text-left" width = "15%">Fecha Realizada</th>';
		$salida .= '<th class = "text-left" width = "450px">Observaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Entidad
			$entidad = trim($row["ext_entidad"]);
			$salida .= '<td class = "text-left">' . $entidad . '</td>';
			// Objetivo
			$objetivo = trim($row["ext_objetivo"]);
			$salida .= '<td class = "text-left">' . $objetivo . '</td>';
			// fecha
			$fecha = cambia_fecha($row["ext_fecha_auditoria"]);
			$salida .= '<td class = "text-left">' . $fecha . '</td>';
			// observacion
			$observacion = trim($row["dext_descripcion"]);
			$salida .= '<td class = "text-left">' . $observacion . '</td>';
			//codigo
			$codigo = $row["dext_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsAud->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-info btn-sm" href = "FRMauditoriaExterna.php?hashkey=' . $hashkey . '" target="_blank" title = "Seleccionar Auditoria Externa" ><i class="fa fa-search"></i></a> ';
			$salida .= '<button class="btn btn-success btn-sm" onclick="identificar_auditoria_externa(' . $codigo . ');" title = "Identificar como Hallazgo" ><i class="fas fa-clipboard-check"></i></button> ';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
function tabla_identificar_quejas()
{
	$ClsQue = new ClsQuejas();
	$result = $ClsQue->get_quejas();
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%">No.</th>';
		$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-left" width = "20%">Descripci&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "10%">Cliente</th>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Proceso
			$proceso = trim($row["fic_nombre"]);
			$proceso = nl2br($proceso);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			// sistema 
			$sistema = trim($row["sis_nombre"]);
			$sistema = nl2br($sistema);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			// Descripcion
			$descripcion = trim($row["que_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			//cliente
			$cliente = trim($row["que_cliente"]);
			$cliente = nl2br($cliente);
			$salida .= '<td class = "text-left">' . $cliente . '</td>';
			//codigo
			$codigo = $row["que_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsQue->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-info btn-sm" href = "FRMqueja.php?hashkey=' . $hashkey . '" target="_blank" title = "Seleccionar Queja" ><i class="fa fa-search"></i></a> ';
			$salida .= '<button class="btn btn-success btn-sm" onclick="identificar_queja(' . $codigo . ');" title = "Identificar como Hallazgo" ><i class="fas fa-clipboard-check"></i></button> ';
			$salida .= '</div>';
			$salida .= '</td>';

			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_riesgos()
{
	$ClsRie = new ClsRiesgo();
	$result = $ClsRie->get_riesgo("", "", "", "", "", 2);


	if (is_array($result)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5px">No.</th>';
		$salida .= '<th class = "text-left" width = "25px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "25px">Sistema</th>';
		$salida .= '<th class = "text-left" width = "40px">Riesgo</th>';
		$salida .= '<th class = "text-left" width = "20px">Fecha de Materializaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//nombre
			$nombre = utf8_decode($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			//nombre
			$nombre = utf8_decode($row["sis_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			//riesgo
			$riesgo = utf8_decode($row["fod_descripcion"]);
			$salida .= '<td class = "text-left">' . $riesgo . '</td>';
			// Fecha
			$riesgo = cambia_fecha($row["rie_fecha_materializacion"]);
			$salida .= '<td class = "text-left">' . $riesgo . '</td>';
			//codigo
			$codigo = $row["rie_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsRie->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-info btn-sm" href = "FRMriesgo.php?hashkey=' . $hashkey . '" target="_blank" title = "Seleccionar Riesgo" ><i class="fa fa-search"></i></a> ';
			$salida .= '<button class="btn btn-success btn-sm" onclick="identificar_riesgo(' . $codigo . ');" title = "Identificar como Hallazgo" ><i class="fas fa-clipboard-check"></i></button> ';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
function tabla_identificar_requisitos(){
	$ClsTipEval = new ClsTipoEvaluacion();
	$result = $ClsTipEval->get_tipo_evaluacion("", "", "", 0);
	//var_dump($result);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%">No.</th>';
		$salida .= '<th class = "text-left" width = "20%">Documento</th>';
		$salida .= '<th class = "text-left" width = "10%">Requisito</th>';
		$salida .= '<th class = "text-left" width = "20%">Tipo de Evaluaci&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "10%">Fecha Reevaluac&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Proceso
			$documento = utf8_decode($row["doc_titulo"]);
			;nl2br($documento);
			$documento = substr ( $documento, 0, 20) . "..." ;
			$salida .= '<td class = "text-left">' . $documento . '</td>';
			// sistema 
			$requisito = utf8_decode($row["req_nomenclatura"]);
			$requisito = nl2br($requisito);
			$salida .= '<td class = "text-left">' . $requisito . '</td>';
			// Descripcion
			$tipo_evaluacion = utf8_decode($row["eva_nombre"]);
			$tipo_evaluacion = nl2br($tipo_evaluacion);
			$salida .= '<td class = "text-left">' . $tipo_evaluacion . '</td>';
			//cliente
			$fecha_reevaluacion = utf8_decode($row["eva_fecha_reevaluacion"]);
			$fecha_reevaluacion = nl2br($fecha_reevaluacion);
			$salida .= '<td class = "text-left">' . $fecha_reevaluacion . '</td>';
			//codigo
			$codigo = $row["eva_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsTipEval->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-info btn-sm" href = "FRMrequisito.php?hashkey=' . $hashkey . '" target="_blank" title = "Seleccionar Requisito" ><i class="fa fa-search"></i></a> ';
			$salida .= '<button class="btn btn-success btn-sm" onclick="identificar_requisito(' . $codigo . ');" title = "Identificar como Hallazgo" ><i class="fas fa-clipboard-check"></i></button> ';
			$salida .= '</div>';
			$salida .= '</td>';

			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
function tabla_indicadores()
{
	$ClsRev = new ClsRevision();
	$ClsInd = new ClsIndicador();
	$indicadores = $ClsInd->get_indicador();
	if (is_array($indicadores)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5px">No.</th>';
		$salida .= '<th class = "text-left" width = "25px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "25px">Sistema</th>';
		$salida .= '<th class = "text-left" width = "40px">Indicador</th>';
		$salida .= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($indicadores as $row) {
			$indicador = trim($row["ind_codigo"]);
			//--
			$bajo = 0;
			$conveniente = 0;
			$arriba = 0;
			$j = 0;
			$result_lectura = $ClsRev->get_revision_indicador("", $indicador);
			if (is_array($result_lectura)) {
				foreach ($result_lectura as $row_lectura) {
					$lectura = $row_lectura['rev_lectura'];
					$minima = $row_lectura['ind_lectura_minima'];
					$maxima = $row_lectura['ind_lectura_maxima'];
					if ($lectura < $minima) $bajo++;
					else if ($lectura > $maxima) $arriba++;
					else $conveniente++;
					$j++;
				}
			}
			if ($bajo != 0 || $arriba != 0) {
				$salida .= '<tr>';
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//nombre
				$nombre = utf8_decode($row["obj_proceso"]);
				$salida .= '<td class = "text-left">' . $nombre . '</td>';
				//nombre
				$nombre = utf8_decode($row["obj_sistema"]);
				$salida .= '<td class = "text-left">' . $nombre . '</td>';
				//indicador
				$indicador = utf8_decode($row["ind_nombre"]);
				$salida .= '<td class = "text-left">' . $indicador . '</td>';
				//codigo
				$codigo = $row["ind_codigo"];
				$usu = $_SESSION["codigo"];
				$hashkey = $ClsInd->encrypt($codigo, $usu);
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "detalle_indicador(' . $codigo . ');" title = "Seleccionar Indicador" ><span class="fa fa-search"></span></button>';
				$salida .= '<button class="btn btn-success btn-sm" onclick="identificar_indicador(' . $codigo . ');" title = "Identificar como Hallazgo" ><i class="fas fa-clipboard-check"></i></button> ';
				$salida .= '</div>';
				$salida .= '</td>';
				//--
				$salida .= '</tr>';
				$i++;
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_auditoria_interna()
{
	$ClsEje = new ClsEjecucion();
	$result = $ClsEje->get_ejecucion("", "", "", "", "", "", "", "", "4");
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "150px">Sede</th>';
		$salida .= '<th class = "text-center" width = "150px">Departamento</th>';
		$salida .= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "100px">Cuestionario</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha/Hora</th>';
		$salida .= '<th class = "text-center" width = "100px">Status</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida .= '<td class = "text-left">' . $sede . '</td>';
			//departamento
			$departamento = utf8_decode($row["dep_nombre"]);
			$salida .= '<td class = "text-left">' . $departamento . '</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//nombre
			$nom = utf8_decode($row["audit_nombre"]);
			$salida .= '<td class = "text-left">' . $nom . '</td>';
			//fecha/hora
			$fechor = trim($row["eje_fecha_final"]);
			$fechor = cambia_fechaHora($fechor);
			$salida .= '<td class = "text-left">' . $fechor . '</td>';
			//stauts
			$plan = trim($row["eje_plan"]);
			if ($plan == "") {
				$status = '<em class="text-success">Aprobada</em>';
			} else {
				$status = '<strong class="text-info">Plan # ' . Agrega_Ceros($plan) . '</strong>';
			}
			$salida .= '<td class = "text-center">' . $status . '</td>';
			//codigo
			$codigo = $row["eje_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsEje->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-info btn-sm" href = "../CPAUDEJECUCION/FRMrevision.php?hashkey=' . $hashkey . '" target="_blank" title = "Seleccionar Auditor&iacute;a" ><i class="fa fa-search"></i></a> ';
			$salida .= '<button class="btn btn-success btn-sm" onclick="identificar_auditoria_interna(' . $codigo . ');" title = "Identificar como Hallazgo" ><i class="fas fa-clipboard-check"></i></button> ';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}


function tabla_planes()
{
	//////////
	$ClsHal = new ClsHallazgo();
	$ClsPla = new ClsPlan();
	$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "5px">C&oacute;digo</th>';
	$salida .= '<th class = "text-left" width = "25px">Proceso</th>';
	$salida .= '<th class = "text-left" width = "25px">Sistema</th>';
	$salida .= '<th class = "text-left" width = "40px">Hallazgo</th>';
	$salida .= '<th class = "text-left" width = "20px">Tipo</th>';
	$salida .= '<th class = "text-left" width = "20px">Origen</th>';
	$salida .= '<th class = "text-left" width = "20px">Fecha</th>';
	$salida .= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	$i = 1;
	for ($origenTipo = 1; $origenTipo <= 6; $origenTipo++) {
		$result = null;
		switch ($origenTipo) {
			case 1:
				$result = $ClsHal->get_hallazgo_auditoria_interna();
				break;
			case 2:
				$result = $ClsHal->get_hallazgo_auditoria_externa();
				break;
			case 3:
				$result = $ClsHal->get_hallazgo_queja();
				break;
			case 4:
				$result = $ClsHal->get_hallazgo_indicador();	
				break;
			case 5:
				$result = $ClsHal->get_hallazgo_riesgo();
				break;
			case 6:
				$result = $ClsHal->get_hallazgo_requisito();
				break;
		}
		if (is_array($result)) {
			foreach ($result as $row) {
				// Plan en edicion
				$edicion = true;
				$codigo = $row["hal_codigo"];
				$plan = $ClsPla->get_plan_mejora("", $codigo);
				if (is_array($plan)) {
					foreach ($plan as $rowPlan) {
						if ($rowPlan["pla_situacion"] != 1) $edicion = false;
					}
				}
				if ($edicion && $row["hal_tipo"] != 0) {
					$salida .= '<tr>';
					//codigo
					$codigo = Agrega_Ceros($codigo);
					$salida .= '<td class = "text-center">' . $codigo . '</td>';
					//proceso
					$proceso = utf8_decode($row["fic_nombre"]);
					$salida .= '<td class = "text-left">' . $proceso . '</td>';
					//sistema
					$sistema = utf8_decode($row["sis_nombre"]);
					$salida .= '<td class = "text-left">' . $sistema . '</td>';
					//descripcion
					$hallazgo = utf8_decode($row["hal_descripcion"]);
					$salida .= '<td class = "text-left">' . $hallazgo . '</td>';
					//Tipo
					$tipo_nombre = get_tipo($row["hal_tipo"]);
					$salida .= '<td class = "text-left">' . $tipo_nombre . '</td>';
					//Origen
					$origen = get_origen($origenTipo);
					$salida .= '<td class = "text-left">' . $origen . '</td>';
					//Fecha
					$fecha = cambia_fechaHora($row["hal_fecha"]);
					$salida .= '<td class = "text-left">' . $fecha . '</td>';
					//codigo
					$codigo = $row["hal_codigo"];
					$origen = trim($row["hal_origen"]);
					$usu = $_SESSION["codigo"];
					$hashkey = $ClsHal->encrypt($codigo, $usu);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<a class="btn btn-info btn-sm" href = "FRMplan.php?hashkey=' . $hashkey . '&origen=' . $origen . '" target="_blank" title = "Seleccionar Hallazgo" ><i class="fas fa-clipboard"></i></a> ';
					$salida .= '</div>';
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
	return ($i != 1) ? $salida : "";
}
function tabla_incumplimientolegal($codigo, $queja = ""){
	$ClsQue = new ClsQuejas();
	$result = $ClsQue->get_quejas($codigo, $queja);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "5%">No.</th>';
		$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-left" width = "20%">Descripci&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "10%">Usuario que Registra</th>';
		$salida .= '<th class = "text-center" width = "10%">Fecha Registro</th>';
		$salida .= '<th class = "text-left" width = "10%">Cliente</th>';
		$salida .= '<th class = "text-left" width = "10%">Tipo </th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["que_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Proceso
			$proceso = trim($row["fic_nombre"]);
			$proceso = nl2br($proceso);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			// sistema 
			$sistema = trim($row["sis_nombre"]);
			$sistema = nl2br($sistema);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			// Descripcion
			$descripcion = trim($row["que_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			// usuario
			$usuario  = trim($row["usu_nombre"]);
			$usuario = nl2br($usuario);
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			// Fecha Inicio
			$fecha = cambia_fecha($row["que_fecha_registro"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//cliente
			$cliente = trim($row["que_cliente"]);
			$cliente = nl2br($cliente);
			$salida .= '<td class = "text-left">' . $cliente . '</td>';
			//tipo
			$tipo = trim($row["que_tipo"]);
			$tipo = nl2br($tipo);
			$salida .= '<td class = "text-left">' . $tipo . '</td>';


			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
function tabla_actividades($codigo, $plan = "", $responsable = "")
{
	$ClsAct = new ClsActividad();
	$result = $ClsAct->get_actividad_mejora($codigo, $plan, $responsable);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "40%">Actividad</th>';
		$salida .= '<th class = "text-center" width = "10%">Fecha de Inicio</th>';
		$salida .= '<th class = "text-center" width = "10%">Fecha Final</th>';
		$salida .= '<th class = "text-center" width = "10%">Programaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "30%">Comentario de Gerencia</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["act_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Descripcion
			$descripcion = trim($row["act_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			// Fecha Inicio
			$fini = cambia_fecha($row["act_fecha_inicio"]);
			$salida .= '<td class = "text-center">' . $fini . '</td>';
			// Fecha Final
			$ffin = cambia_fecha($row["act_fecha_fin"]);
			$salida .= '<td class = "text-center">' . $ffin . '</td>';
			// Programacion
			$codigo = $row["act_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verProgramacion(' . $codigo . ');" title = "Programaci&oacute;n de Actividad" ><i class="fa fa-calendar"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// comentario
			$comentario = trim($row["act_comentario"]);
			$comentario = nl2br($comentario);
			$salida .= '<td class = "text-left">' . $comentario . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_aprobacion($usuario)
{
	$ClsSis = new ClsSistema();
	$ClsHal = new ClsHallazgo();
	$ClsPla = new ClsPlan();
	$asignadas = $ClsSis->get_sistema("", "", "", $usuario);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "20%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "20%">Sistema</th>';
		$salida .= '<th class = "text-left" width = "20%">Usuario</th>';
		$salida .= '<th class = "text-left" width = "25%">Hallazgo</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowSistema) {
			$sistema_codigo = trim($rowSistema["sis_codigo"]);
			for ($origenTipo = 1; $origenTipo <= 6; $origenTipo++) {
				$hallazgos = null;
				switch ($origenTipo) {
					case 1:
						$hallazgos = $ClsHal->get_hallazgo_auditoria_interna("", "", "", $sistema_codigo);
						break;
					case 2:
						$hallazgos = $ClsHal->get_hallazgo_auditoria_externa("", "", "", $sistema_codigo);
						break;
					case 3:
						$hallazgos = $ClsHal->get_hallazgo_queja("", "", "", $sistema_codigo);
						break;
					case 4:
						$hallazgos = $ClsHal->get_hallazgo_indicador("", "", "", $sistema_codigo);
						break;
					case 5:
						$hallazgos = $ClsHal->get_hallazgo_riesgo("", "", "", $sistema_codigo);
						break;
					case 6:
						$hallazgos = $ClsHal->get_hallazgo_requisito("","","", $sistema_codigo);
						break;
				}
				if (is_array($hallazgos)) {
					foreach ($hallazgos as $rowHallazgo) {
						$hallazgo = trim($rowHallazgo["hal_codigo"]);
						$proceso = utf8_decode($rowHallazgo["fic_nombre"]);
						$sistema = utf8_decode($rowHallazgo["sis_nombre"]);
						$usu = utf8_decode($rowHallazgo["usu_nombre"]);
						$descripcion = utf8_decode($rowHallazgo["hal_descripcion"]);
						$planes = $ClsPla->get_plan_mejora("", $hallazgo, "", "", "", "2");
						if (is_array($planes)) {
							foreach ($planes as $row) {
								$salida .= '<tr>';
								// No. 
								$salida .= '<td class = "text-center">' . $i . '</td>';
								// Proceso
								$salida .= '<td class = "text-left">' . $proceso . '</td>';
								// Sistema
								$salida .= '<td class = "text-left">' . $sistema . '</td>';
								// Usuario
								$salida .= '<td class = "text-left">' . $usu . '</td>';
								// Descripcion
								$descripcion = nl2br($descripcion);
								$salida .= '<td class = "text-left">' . $descripcion . '</td>';
								//codigo
								$codigo = $row["pla_codigo"];
								$usuario = $_SESSION["codigo"];
								$hashkey = $ClsPla->encrypt($codigo, $usuario);
								//--
								$salida .= '<td class = "text-center" >';
								$salida .= '<div class="btn-group">';
								$origen = trim($row["hal_origen"]);
								$salida .= '<a class="btn btn-info btn-xs" href = "FRMaprobar.php?hashkey=' . $hashkey . '&origen=' . $origen . '" title = "Verificar Eficacia" ><i class="fa fa-search"></i></a>';
								$salida .= '</div>';
								$salida .= '</td>';
								//--
								$salida .= '</tr>';
								$i++;
							}
						}
					}
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_causa($codigo  = '', $plan = '', $pertenece = '')
{
	$ClsCau = new ClsCausa();
	$result = $ClsCau->get_causa($codigo, $plan, $pertenece);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example">';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5px"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "5px">No.</th>';
		$salida .= '<th class = "text-left" width = "450px">Causa</th>';
		if ($pertenece == "" || $pertenece == 0) $salida .= '<th class = "text-center" width = "10px"><i class="fas fa-network-wired"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["cau_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "SeleccionarCausas(' . $codigo . ');" title = "Editar Causa" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "DeshabilitarCausas(' . $codigo . ');" title = "Eliminar Causa" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Causa
			$causa = trim($row["cau_descripcion"]);
			$salida .= '<td class = "text-left">' . $causa . '</td>';
			//codigo
			$codigo = $row["cau_codigo"];
			//--
			if ($pertenece == "" || $pertenece == 0) {
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<a class="btn btn-info btn-xs" onclick="subcausa(' . $codigo . ',\'' . $causa . '\')" title = "Agregar Subcausa" ><i class="fa fa-arrow-right"></i></a>';
				$salida .= '</div>';
				$salida .= '</td>';
			}
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
///////////////// Extras /////////////////
function get_archivos($numero, $codigo)
{
	// Si no existe ningun archivo me devuelve false en la posicion 0
	$ClsRie = new ClsRiesgo();
	$evidencia = false;
	for ($i = 1; $i <= $numero; $i++) {
		$result = $ClsRie->get_archivo('', $codigo, $i);
		if (is_array($result)) {
			foreach ($result as $row) {
				$strArchivo = trim($row["arc_archivo"]);
				if (file_exists('../../CONFIG/Fotos/RYO/' . $strArchivo . '.jpg')) {
					$strArchivo = '<a href="../../CONFIG/Fotos/RYO/' . $strArchivo . '.jpg" target="_blank"><img class="img-upload" src="../../CONFIG/Fotos/RYO/' . $strArchivo . '.jpg" alt="..."></a>';
					$evidencia = true;
				} else if (file_exists('../../CONFIG/Archivos/RYO/' . $strArchivo . '.pdf')) {
					$strArchivo = '<a href="../../CONFIG/Archivos/RYO/' . $strArchivo . '.pdf" target="_blank"><img class="img-upload" src="../../CONFIG/img/document.png" alt="..."></a>';
					$evidencia = true;
				} else {
					$strArchivo = '<i class="fa fa-file-o fa-8x"></i>';
				}
			}
		} else {
			$strArchivo = '<i class="fa fa-file-o fa-8x"></i>';
		}
		$arrArchivos[$i] = $strArchivo;
	}
	$arrArchivos[0] = $evidencia;
	return $arrArchivos;
}
function get_accion_riesgo($accion)
{
	switch ($accion) {
		case 0:
			return "Sin Acci&oacute;n";
		case 1:
			return "Eliminar";
		case 2:
			return "Mitigar";
		case 3:
			return "Compartir";
		case 4:
			return "Transferir";
		case 5:
			return "Aceptar";
	}
}
function get_condicion($severidad)
{
	if ($severidad <= 5) return "Riesgo M&iacute;nimo";
	if ($severidad > 5 && $severidad <= 10) return "Riesgo Bajo";
	if ($severidad > 10 && $severidad <= 15) return "Riesgo Medio";
	if ($severidad > 15) return "Riesgo Alto";
}
function get_probabilidad($probabilidad)
{
	switch ($probabilidad) {
		case 0:
			return "Sin Evaluar";
		case 1:
			return "No ocurre en 5 a&ntilde;os";
		case 2:
			return "1 vez en 5 a&ntilde;os";
		case 3:
			return "1 vez en 2 a&ntilde;os";
		case 4:
			return "1 vez en 1 a&ntilde;o";
		case 5:
			return "M&aacute;s de una vez al a&ntilde;o";
	}
}
function get_impacto($impacto)
{
	switch ($impacto) {
		case 0:
			return "Sin Evaluar";
		case 1:
			return "Peque&ntilde;o";
		case 2:
			return "Moderado";
		case 3:
			return "Grande";
		case 4:
			return "Catastrofico";
	}
}
function get_tipo($tipo)
{
	switch ($tipo) {
		case 0:
			return "No Identificado";
		case 1:
			return "No conformidad";
		case 2:
			return "Observacion";
		case 3:
			return "Oportunidad de Mejora";
	}
}

function get_origen($tipo)
{
	switch ($tipo) {
		case 0:
			return "No Identificado";
		case 1:
			return "Auditor&iacute;a Interna";
		case 2:
			return "Auditor&iacute;a Externa";
		case 3:
			return "Salidas no Conformes";
		case 4:
			return "Incumplimiento de Indicadores";
		case 5:
			return "Riesgos Materializados";
		case 6:
			return "Incumplimiento Legal";
	}
}

function get_tipo_auditoria($tipo)
{
	switch ($tipo) {
		case 1:
			return "Calidad";
		case 2:
			return "Ambiente";
		case 3:
			return "Salud";
		case 4:
			return "Huella de Carbono";
		case 5:
			return "Ministerio de Salud";
		case 6:
			return "Ministerio de Ambiente";
		case 7:
			return "Ministerio de Trabajo";
		case 8:
			return "Clientes";
	}
}


function combo_tipo_auditoria($name, $instruc = '', $class = '')
{
	$salida  = '<select name="' . $name . '" id="' . $name . '" onchange="' . $instruc . '" class = "' . $class . ' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	$salida .= '<option value="1">Calidad</option>';
	$salida .= '<option value="2">Ambiente</option>';
	$salida .= '<option value="3">Salud</option>';
	$salida .= '<option value="4">Huella de Carbono</option>';
	$salida .= '<option value="5">Ministerio de Salud</option>';
	$salida .= '<option value="6">Ministerio de Ambiente</option>';
	$salida .= '<option value="7">Ministerio de Trabajo</option>';
	$salida .= '<option value="8">Clientes</option>';
	$salida .= '</select>';
	return $salida;
}
