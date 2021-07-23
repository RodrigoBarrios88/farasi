<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

include_once('html_fns.php');

$request = $_REQUEST["request"];
switch ($request) {
		////////////////// COMBOS /////////////////////////
	case "sector":
		$sede = $_REQUEST["sede"];
		get_sector($sede);
		break;
	case "area":
		$sector = $_REQUEST["sector"];
		get_area($sector);
		break;
		////////////////// CALENDARIOS /////////////////////////
	case "calendario_menu":
		$sede = $_REQUEST["sede"];
		$departamento = $_REQUEST["departamento"];
		$categoria = $_REQUEST["categoria"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		calendario_menu($sede, $departamento, $categoria, $fini, $ffin);
		break;
	case "calendario_auditoria":
		$sede = $_REQUEST["sede"];
		$departamento = $_REQUEST["departamento"];
		$categoria = $_REQUEST["categoria"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		calendario_auditoria($sede, $departamento, $categoria, $fini, $ffin);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// COMBOS /////////////////////////
function get_sector($sede)
{
	if ($sede != "") {
		$sede = ($sede == "Todas") ? "" : $sede;
		$combo = sector_html("sector", $sede, "Submit();comboArea();", "select2");
	} else {
		$combo = combos_vacios("sector", "select2");
	}
	$arr_respuesta = array(
		"status" => true,
		"combo" => $combo
	);
	echo json_encode($arr_respuesta);
}

function get_area($sector)
{
	if ($sector != "") {
		$combo = area_html("area", $sector, "Submit();", "select2");
	} else {
		$combo = combos_vacios("area", "select2");
	}
	$arr_respuesta = array(
		"status" => true,
		"combo" => $combo
	);
	echo json_encode($arr_respuesta);
}

////////////////// CALENDARIOS /////////////////////////
function calendario_menu($sedes, $departamento, $categoria, $desde, $hasta)
{
	$arr_data = array();
	$desde = ($desde == "") ? "01/01/" . date("Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
	$hasta = ($hasta == "") ? "31/12/" . date("Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
	//////////////////////////////////////// AUDITORIA ///////////////////////////////////////////
	$i = 0;
	if ($_SESSION["GRP_AUDIT"] == 1) {
		$usuario = $_SESSION["codigo"];
		$ClsAud = new ClsAuditoria();
		$result = $ClsAud->get_programacion('', '', $sedes, $departamento, "", $desde, $hasta, '', '', '1,2', $usuario);
		if (is_array($result)) {
			foreach ($result as $row) {
				//--
				$codigo = $row["audit_codigo"];
				$progra = $row["pro_codigo"];
				$ejecucion = $row["ejecucion_activa"];
				$usu = $_SESSION["codigo"];
				$hashkey1 = $ClsAud->encrypt($codigo, $usuario);
				$hashkey2 = $ClsAud->encrypt($progra, $usu);
				//--
				$arr_data[$i]["id"] = $i + 1;
				$arr_data[$i]["title"] = trim($row["audit_nombre"]) . " / " . trim($row["sed_nombre"]);
				$fini = trim($row["pro_fecha"]) . " " . trim($row["pro_hora"]); //Y-m-d H:i:s
				$arr_data[$i]["start"] = $fini;
				if ($ejecucion != "") {
					$arr_data[$i]["url"] = "";
					$arr_data[$i]["className"] = 'event-green';
				} else {
					$arr_data[$i]["url"] = "CPAUDEJECUCION/FRMcuestionario.php?hashkey1=$hashkey1&hashkey2=$hashkey2";
					$arr_data[$i]["className"] = 'event-orange';
				}
				$i++;
			}
		}
	}
	//////////////////////////////////////// PPM ///////////////////////////////////////////
	if ($_SESSION["GRP_PPM"] == 1) {
		$usuario = $_SESSION["codigo"];
		$ClsPro = new ClsProgramacionPPM();
		$result = $ClsPro->get_programacion('', '', $usuario, '', $sedes, '', '', $fini, '', '', '', '');
		if (is_array($result)) {
			foreach ($result as $row) {
				//--
				$codigo = $row["pro_codigo"];
				$usu = $_SESSION["codigo"];
				$hashkey = $ClsPro->encrypt($codigo, $usu);
				$situacion = $row["pro_situacion"];
				//--
				$arr_data[$i]["id"] = $i + 1;
				$arr_data[$i]["title"] = "PPM " . trim($row["act_nombre"]) . " / " . trim($row["sed_nombre"]);
				$fini = trim($row["pro_fecha"]) . " 08:00:00";
				$arr_data[$i]["start"] = $fini;
				if ($situacion == 1) {
					$arr_data[$i]["url"] = "CPPPMPROGRA/FRMorden.php?hashkey=$hashkey";
					$arr_data[$i]["className"] = 'event-gray';
				} else {
					$arr_data[$i]["url"] = "CPPPMEJECUCION/FRMrevision.php?hashkey=$hashkey";
					$arr_data[$i]["className"] = 'event-azure';
				}
				$i++;
			}
		}
	}
	//////////////////////////////////////// Indicadores ///////////////////////////////////////////
	if ($_SESSION["GRP_INDICATOR"] == 1) {
		$usuario = $_SESSION["codigo"];
		$ClsInd = new ClsIndicador();
		$result = $ClsInd->get_programacion("", "", "", "", "", "", "", $usuario);
		if (is_array($result)) {
			foreach ($result as $row) {
				//--
				$arr_data[$i]["id"] = $i + 1;
				$arr_data[$i]["title"] = "Indicador: " . trim($row["ind_nombre"]);
				// Codigo
				$programacion = $row["pro_codigo"];
				$fini = strtotime(trim($row["pro_fecha"]) .  " " . trim($row["pro_hini"]));
				$ffin = strtotime(trim($row["pro_fecha"]) .  " " . trim($row["pro_hfin"]));
				$hoy = strtotime(date("Y-m-d H:i:s"));
				$revision = $row["revision"];
				$usu = $_SESSION["codigo"];
				$hashkey = $ClsInd->encrypt($programacion, $usu);
				if ($revision != "") {
					$ClsRev = new ClsRevision();
					$revision = $ClsRev->get_revision_indicador($revision);
					$situacion = $revision[0]["rev_situacion"];
					if ($situacion == "1" && $ffin >= $hoy && $fini <= $hoy) {
						$arr_data[$i]["url"] = "CPINDREVISION/FRManotar.php?hashkey=$hashkey";
						$arr_data[$i]["className"] = 'event-yellow';
					} else {
						$arr_data[$i]["url"] = "CPINDREVISION/FRManotacion.php";
						$arr_data[$i]["className"] = 'event-gray';
					}
				} else if ($ffin >= $hoy && $fini <= $hoy) {
					$arr_data[$i]["url"] = "CPINDREVISION/FRManotar.php?hashkey=$hashkey";
					$arr_data[$i]["className"] = 'event-yellow';
				} else {
					$arr_data[$i]["url"] = "CPINDREVISION/FRManotacion.php";
					$arr_data[$i]["className"] = 'event-gray';
				}
				$fini = trim($row["pro_fecha"]) . " " . trim($row["pro_hini"]);
				$ffin = trim($row["pro_fecha"]) . " " . trim($row["pro_hfin"]);
				$arr_data[$i]["start"] = $fini;
				$arr_data[$i]["end"] = $ffin;
				$i++;
			}
		}
	}
	//////////////////////////////////////// Planning Targets ///////////////////////////////////////////
	if ($_SESSION["GRP_PLANNING"] == 1) {
		$usuario = $_SESSION["codigo"];
		$ClsAcc = new ClsAccion();
		$ClsEje = new ClsEjecucion();
		$result = $ClsAcc->get_programacion_aprobada("", $usuario);
		if (is_array($result)) {
			foreach ($result as $row) {
				$codigo = $row["pro_codigo"];
				$fini = strtotime($row["pro_fecha_inicio"]);
				$ffin = strtotime($row["pro_fecha_fin"]);
				$hoy = strtotime(date("Y-m-d"));
				$rs = $ClsEje->get_ejecucion_accion("", $codigo);
				// Si no tiene ejecucion y esta en fecha entonces esta pendiente
				// Si tiene ejecucion y esta en fecha esta en proceso y no esta cancelada ni finalizada
				if ((!is_array($rs) && $ffin >= $hoy && $fini <= $hoy)
					|| (is_array($rs) && $ffin >= $hoy && $fini <=  $hoy && $rs[0]["eje_situacion"] == 1)
				) {
					$arr_data[$i]["url"] = "CPPLANNINGEJECUCION/FRMejecutar.php?hashkey=$hashkey";
					$arr_data[$i]["className"] = 'event-blue';
				} else {
					$arr_data[$i]["url"] = "CPPLANNINGEJECUCION/FRMejecucion.php";
					$arr_data[$i]["className"] = 'event-gray';
				}
				//--
				$arr_data[$i]["id"] = $i + 1;
				$arr_data[$i]["title"] = "Planning Targets: " . trim($row["acc_nombre"]);
				$fini = trim($row["pro_fecha_inicio"])  . " 08:00:00";
				$ffin = trim($row["pro_fecha_fin"])  . " 17:00:00";
				$arr_data[$i]["start"] = $fini;
				$arr_data[$i]["end"] = $ffin;
				$i++;
			}
		}
	}
	//////////////////////////////////////// R&O manager ///////////////////////////////////////////
	if ($_SESSION["GRP_RYO"] == 1) {
		$usuario = $_SESSION["codigo"];
		$ClsPla = new ClsPlan();
		$ClsAct = new ClsActividad();
		$planes = $ClsPla->get_plan_ryo("", "", "", $usuario, "", "", 3); // Planes aprobados para el usuario
		if (is_array($planes)) {
			foreach ($planes as $rowPlan) {
				$plan = trim($rowPlan["pla_codigo"]);
				$actividades = $ClsAct->get_actividad("", $plan,1);
				if (is_array($actividades)) {
					foreach ($actividades as $rowActividad) {
						$actividad = trim($rowActividad["act_codigo"]);
						$result = $ClsAct->get_programacion("", "", $actividad);
						if (is_array($result)) {
							foreach ($result as $row) {
								$codigo = $row["pro_codigo"];
								$usuario = $_SESSION["codigo"];
								$hashkey = $ClsPla->encrypt($codigo, $usuario);
								$fini = strtotime($row["pro_fecha_inicio"]);
								$ffin = strtotime($row["pro_fecha_fin"]);
								$hoy = strtotime(date("Y-m-d"));
								$situacion = $row["pro_situacion"];
								// Si no tiene ejecucion y esta en fecha entonces esta pendiente
								// Si tiene ejecucion y esta en fecha esta en proceso y no esta cancelada ni finalizada
								if (($situacion == "1" || $situacion == "2") && $ffin >= $hoy && $fini <= $hoy) {
									$arr_data[$i]["url"] = "CPRYOEJECUCION/FRMejecutar.php?hashkey=$hashkey";
									$arr_data[$i]["className"] = 'event-blue';
								} else {
									$arr_data[$i]["url"] = "CPRYOEJECUCION/FRMejecucion.php";
									$arr_data[$i]["className"] = 'event-gray';
								}
								//--
								$arr_data[$i]["id"] = $i + 1;
								$arr_data[$i]["title"] = "R&O Manager: " . trim($row["act_descripcion"]);
								$fini = trim($row["pro_fecha_inicio"])  . " 08:00:00";
								$ffin = trim($row["pro_fecha_fin"])  . " 17:00:00";
								$arr_data[$i]["start"] = $fini;
								$arr_data[$i]["end"] = $ffin;
								$i++;
							}
						}
					}
				}
			}
		}
	}
	//////////////////////////////////////// Mejora Continua ///////////////////////////////////////////
	if ($_SESSION["GRP_RYO"] == 1) {
		$usuario = $_SESSION["codigo"];
		$ClsPla = new ClsPlan();
		$ClsAct = new ClsActividad();
		$planes = $ClsPla->get_plan_mejora("", "", $usuario, "", "", 3); // Planes aprobados para el usuario
		if (is_array($planes)) {
			foreach ($planes as $rowPlan) {
				$plan = trim($rowPlan["pla_codigo"]);
				$actividades = $ClsAct->get_actividad_mejora("", $plan);
				if (is_array($actividades)) {
					foreach ($actividades as $rowActividad) {
						$actividad = trim($rowActividad["act_codigo"]);
						$result = $ClsAct->get_programacion_mejora("", "", $actividad);
						if (is_array($result)) {
							foreach ($result as $row) {
								$codigo = $row["pro_codigo"];
								$usuario = $_SESSION["codigo"];
								$hashkey = $ClsPla->encrypt($codigo, $usuario);
								$fini = strtotime($row["pro_fecha_inicio"]);
								$ffin = strtotime($row["pro_fecha_fin"]);
								$hoy = strtotime(date("Y-m-d"));
								$situacion = $row["pro_situacion"];
								// Si no tiene ejecucion y esta en fecha entonces esta pendiente
								// Si tiene ejecucion y esta en fecha esta en proceso y no esta cancelada ni finalizada
								if (($situacion == "1" || $situacion == "2") && $ffin >= $hoy && $fini <= $hoy) {
									$arr_data[$i]["url"] = "CPMEJORAEJECUCION/FRMejecutar.php?hashkey=$hashkey";
									$arr_data[$i]["className"] = 'event-yellow';
								} else {
									$arr_data[$i]["url"] = "CPMEJORAEJECUCION/FRMejecucion.php";
									$arr_data[$i]["className"] = 'event-gray';
								}
								//--
								$arr_data[$i]["id"] = $i + 1;
								$arr_data[$i]["title"] = "Continuous Improver: " . trim($row["act_descripcion"]);
								$fini = trim($row["pro_fecha_inicio"])  . " 08:00:00";
								$ffin = trim($row["pro_fecha_fin"])  . " 17:00:00";
								$arr_data[$i]["start"] = $fini;
								$arr_data[$i]["end"] = $ffin;
								$i++;
							}
						}
					}
				}
			}
		}
	}
	//////////////////////////////////////// Resultado ///////////////////////////////////////////
	if ($i > 0) {
		$arr_respuesta = array(
			"status" => true,
			//"parametros" => "$sedes,$departamento,$categoria,$desde,$hasta",
			"data" => $arr_data,
		);
	} else {
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "No hay registros con estos parametros de busqueda..."
		);
	}
	echo json_encode($arr_respuesta);
}

//////////////////////////////////////// AUDITORIA ///////////////////////////////////////////
function calendario_auditoria($sedes, $departamento, $categoria, $desde, $hasta)
{
	$arr_data = array();
	$desde = ($desde == "") ? "01/01/" . date("Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
	$hasta = ($hasta == "") ? "31/12/" . date("Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
	$i = 0;
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion('', '', $sedes, $departamento, $categoria, $desde, $hasta, '', '', '1,2');
	if (is_array($result)) {
		foreach ($result as $row) {
			//--
			$codigo = $row["pro_codigo"];
			$codigo = $row["audit_codigo"];
			$progra = $row["pro_codigo"];
			$ejecucion = $row["ejecucion_activa"];
			$usu = $_SESSION["codigo"];
			$hashkey1 = $ClsAud->encrypt($codigo, $usu);
			$hashkey2 = $ClsAud->encrypt($progra, $usu);
			//--
			$arr_data[$i]["id"] = $i + 1;
			$arr_data[$i]["title"] = trim($row["audit_nombre"]) . " / " . trim($row["sed_nombre"]);
			$fini = trim($row["pro_fecha"]) . " " . trim($row["pro_hora"]);
			$arr_data[$i]["start"] = $fini;
			if ($ejecucion != "") {
				$arr_data[$i]["url"] = "";
				$arr_data[$i]["className"] = 'event-green';
			} else {
				$arr_data[$i]["url"] = "CPAUDEJECUCION/FRMcuestionario.php?hashkey1=$hashkey1&hashkey2=$hashkey2";
				$arr_data[$i]["className"] = 'event-orange';
			}
			$i++;
		}
	}
	if ($i > 0) {
		$arr_respuesta = array(
			"status" => true,
			//"parametros" => "$sedes,$departamento,$categoria,$desde,$hasta",
			"data" => $arr_data,
		);
	} else {
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "No hay registros con estos parametros de busqueda..."
		);
	}
	echo json_encode($arr_respuesta);
}
