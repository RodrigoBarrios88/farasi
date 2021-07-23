<?php
//-- SISTEMA --
include_once('user_auth_fns.php');
require_once("recursos/fpdf/pdf.php");
include_once('recursos/phpqrcode/qrlib.php');
require_once("Clases/ImageResize.php");
require_once("Clases/ImageResizeException.php");
//--CLASES DEL SISTEMA
require_once("Clases/ClsConfig.php");
require_once("Clases/ClsUsuario.php");
require_once("Clases/ClsPermiso.php");
require_once("Clases/ClsAjustes.php");
require_once("Clases/ClsRol.php");
require_once("Clases/ClsMundep.php");
require_once("Clases/ClsMoneda.php");
require_once("Clases/ClsUmedida.php");
require_once("Clases/ClsVersion.php");
require_once("Clases/ClsNorma.php");
//-- GESTORES TECNICOS
require_once("Clases/ClsSede.php");
require_once("Clases/ClsSector.php");
require_once("Clases/ClsArea.php");
require_once("Clases/ClsDepartamento.php");
require_once("Clases/ClsCategoria.php");
require_once("Clases/ClsClasificacion.php");
require_once("Clases/ClsCentroCosto.php");
//--- INDICADOR --
require_once("Clases/ClsIndicador.php");
//--- PLANNING TARGETS --
require_once("Clases/ClsAccion.php");
require_once("Clases/ClsObjetivo.php");
require_once("Clases/ClsEvaluacion.php");
//-- CHECK LIST
require_once("Clases/ClsLista.php");
require_once("Clases/ClsRevision.php");
//-- HELP DESK
require_once("Clases/ClsPrioridad.php");
require_once("Clases/ClsEscalon.php");
require_once("Clases/ClsStatus.php");
require_once("Clases/ClsIncidente.php");
require_once("Clases/ClsTicket.php");
//-- PPM
require_once("Clases/ClsActivo.php");
require_once("Clases/ClsFalla.php");
require_once("Clases/ClsProgramacionPPM.php");
require_once("Clases/ClsCuestionarioPPM.php");
//-- AUDITORIA
require_once("Clases/ClsAuditoria.php");
require_once("Clases/ClsEjecucion.php");
require_once("Clases/ClsPlan.php");
//-- RIESGOS Y OPORTUNIDADES
require_once("Clases/ClsActividad.php");
require_once("Clases/ClsRiesgo.php");
require_once("Clases/ClsOportunidad.php");
//-- MEJORA CONTINUA
require_once("Clases/ClsHallazgo.php");
require_once("Clases/ClsCausa.php");
require_once("Clases/ClsQuejas.php");
//-- PROCESS MANAGER
require_once("Clases/ClsRecursos.php");
require_once("Clases/ClsSistema.php");
require_once("Clases/ClsNecesidadProcesos.php");
require_once("Clases/ClsFicha.php");
require_once("Clases/ClsProceso.php");
require_once("Clases/ClsControl.php");
require_once("Clases/ClsExpectativa.php");
//--- BIBLIOTECA --//
require_once("Clases/ClsBiblioteca.php");
//--- REQUISITOS --//
require_once("Clases/ClsRequisito.php");
require_once("Clases/clsDocumento.php");
require_once("Clases/ClsDocumento2.php");
require_once("Clases/ClsTipoEvaluacion.php");
//--- ENCUESTAS AL CLIENTE --//
require_once("Clases/ClsEncuesta.php");
require_once("Clases/ClsEncuestaResolucion.php");

//////////////////////////////////////////
include_once('html_menus.php');

///combos y funciones auxiliares

////////////////// USUARIOS Y SEGURIDAD
function validate_login($nivel = "")
{
	if (!isset($_SESSION["codigo"]) || !isset($_SESSION["rol"])) {
		echo "<form id='f1' name='f1' action='" . $nivel . "logout.php' method='post'>";
		echo "<script>document.f1.submit();</script>";
		echo "</form>";
	}
}

function grupos_html($id = '', $acc = '', $class = '')
{
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_grupo("");

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", "gperm_id", "gperm_desc", $acc, $class);
	} else {
		return combos_vacios("$id");
	}
}

function rol_html($id = '', $acc = '', $class = '')
{
	$ClsRol = new ClsRol();
	$result = $ClsRol->get_rol_libre('');

	if (is_array($result)) {
		$result = $ClsRol->get_rol_libre('');
		return combos_html_onclick($result, "$id", 'rol_id', 'rol_nombre', $acc, $class);
	} else {
		return combos_vacios("$id");
	}
}

function usuarios_html($id, $acc = '', $class = '', $proceso = "")
{
	if ($proceso == "") {
		$ClsUsu = new ClsUsuario();
		$result = $ClsUsu->get_usuario('', '', '', '', '', 1);
	} else {
		$ClsFic = new ClsFicha();
		$result = $ClsFic->get_ficha_usuario('', $proceso, '');
	}

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'usu_id', 'usu_nombre', $acc, $class);
	} else {
		return combos_vacios("$id");
	}
}

function usuarios_sedes_html($id, $sedes, $acc = '', $class = '')
{
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario_sede_combo($sedes);
	if (is_array($result)) {
		$salida = '<select name="' . $id . '" id="' . $id . '" class = "form-control ' . $class . '" onchange = "' . $acc . '">';
		$salida .= '<option value="">Seleccione</option>';
		if (is_array($result)) {
			$UsuX = '';
			foreach ($result as $row) {
				$usuario = utf8_decode($row["usu_id"]);
				if ($usuario != $UsuX) {
					$UsuX = $usuario;
					$salida .= '<option value=' . trim($row["usu_id"]) . '>' . trim($row["usu_nombre"]) . '</option>';
				}
			}
		}
		$salida .= '</select>';
		return $salida;
	} else {
		return combos_vacios("$id");
	}
}

///////////////// SISTEMA

function moneda_html($id, $acc = '', $class = '')
{
	$ClsMon = new ClsMoneda();
	$result = $ClsMon->get_moneda("");
	if (is_array($result)) {
		return combos_html_onclick($result, "$id", "mon_codigo", "mon_descripcion", $acc, $class);
	} else {
		return combos_vacios("$id");
	}
}

function moneda_simbolo_html($id, $acc = '', $class = '')
{
	$ClsMon = new ClsMoneda();
	$result = $ClsMon->get_moneda("");
	if (is_array($result)) {
		if ($result) {
			$salida = '<select name="' . $id . '" id="' . $id . '" class = "form-control ' . $class . '" onchange = "' . $acc . '">';
			if (is_array($result)) {
				foreach ($result as $row) {
					$desc = trim($row["mon_descripcion"]) . " | " . trim($row["mon_simbolo"]);
					$salida .= '<option value=' . $row["mon_codigo"] . '>' . $desc . '</option>';
				}
			}
			$salida .= '</select>';
		} else {
			return combos_vacios("$id");
		}
	}
	return $salida;
}

function moneda_transacciones_html($id, $acc = '', $class = '')
{
	$ClsMon = new ClsMoneda();
	$result = $ClsMon->get_moneda("");
	if (is_array($result)) {
		if ($result) {
			$salida = '<select name="' . $id . '" id="' . $id . '" class = "form-control ' . $class . '" onchange = "' . $acc . '">';
			if (is_array($result)) {
				foreach ($result as $row) {
					$desc = trim($row["mon_descripcion"]) . " / " . trim($row["mon_simbolo"]) . ". / (" . trim($row["mon_cambio"]) . " x 1) ";
					$salida .= '<option value=' . $row["mon_codigo"] . '>' . $desc . '</option>';
				}
			}
			$salida .= '</select>';
		} else {
			return combos_vacios("$id");
		}
	}
	return $salida;
}


function umedida_html($id, $acc = '', $class = '')
{
	$ClsUmed = new ClsUmedida();
	$result = $ClsUmed->get_unidad();

	if (is_array($result)) {
		return combos_html_onclick($result, $id, "umed_codigo", "umed_desc_lg", $acc, $class);
	} else {
		return combos_vacios("$id");
	}
}

function norma_html($id, $acc = '', $class = '')
{
	$ClsNor = new ClsNorma();
	$result = $ClsNor->get_norma("");
	return combos_html_onclick($result, $id, 'nor_codigo', 'nor_nombre', $acc, $class);
}

function norma_multiple_html($id, $acc = '', $class = '')
{
	$ClsNor = new ClsNorma();
	$result = $ClsNor->get_norma("");
	return combos_html_multiselect($result, $id, 'nor_codigo', 'nor_nombre', $acc, $class);
}

//combos_html_multiselect($result_id,$name,$c1,$c2,$instruc,$class='')


////////////////// ORGANIZACION

function sedes_html($id, $acc = '', $class = '', $all = '')
{
	$ClsSed = new ClsSede();
	$result = $ClsSed->get_sede('', '', '', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'sed_codigo', 'sed_nombre', $acc, $class, $all);
	} else {
		return combos_vacios("$id", $class);
	}
}

function sector_html($id, $sede, $acc = '', $class = '')
{
	$ClsSec = new ClsSector();
	$result = $ClsSec->get_sector('', $sede, '', 1);
	return combos_html_onclick($result, $id, 'sec_codigo', 'sec_nombre', $acc, $class);
}

function area_html($id, $sector, $acc = '', $class = '')
{
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area('', '', $sector, '', '', 1);
	return combos_html_onclick($result, $id, 'are_codigo', 'are_nombre', $acc, $class);
}

function areas_sede_html2($id, $sede, $acc = '', $class = '')
{
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area('', $sede, '', '', '', 1);
	return combos_html_onclick($result, $id, 'are_codigo', 'are_nombre', $acc, $class);
}

function departamento_org_html($id, $acc = '', $class = '')
{
	$ClsDep = new ClsDepartamento();
	$result = $ClsDep->get_departamento("");
	return combos_html_onclick($result, $id, 'dep_codigo', 'dep_nombre', $acc, $class);
}


function areas_sede_html($id, $acc = '', $class = '')
{
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area('', '', '', '', '', 1);
	if (is_array($result)) {
		if ($result) {
			$salida = '<select name="' . $id . '" id="' . $id . '" class = "form-control ' . $class . '" onchange = "' . $acc . '">';
			$salida .= '<option value="">Seleccione</option>';
			if (is_array($result)) {
				foreach ($result as $row) {
					$descripcion = trim($row["are_nombre"]) . " en " . trim($row["sed_nombre"]);
					$salida .= '<option value="' . $row["are_codigo"] . '" >' . $descripcion . '</option>';
				}
			}
			$salida .= '</select>';
		} else {
			return combos_vacios("$id");
		}
	} else {
		return combos_vacios("$id", $class);
	}
	return $salida;
}


////////////////// MULTISELECT

function sedes_multiselect($id, $acc = '', $class = '')
{
	$ClsSed = new ClsSede();
	$result = $ClsSed->get_sede('', '', '', '', 1);

	if (is_array($result)) {
		return combos_html_multiselect($result, "$id", 'sed_codigo', 'sed_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

////////////////// CHECKLIST

function categorias_chk_html($id, $acc = '', $class = '')
{
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}
function categorias_chk_html_usuarios_categorias($id, $acc = '', $class = '')
{
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist_usuario('', '', 1);
	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


function categorias_chk_usuarios_html($id, $acc = '', $class = '')
{
	$categoriasIn = $_SESSION["categorias_in"];
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist($categoriasIn, '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


////////////////// HELPDESK

function categorias_hd_html($id, $acc = '', $class = '')
{
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_helpdesk('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function prioridades_html($id, $acc = '', $class = '')
{
	$ClsPri = new ClsPrioridad();
	$result = $ClsPri->get_prioridad('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'pri_codigo', 'pri_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function status_html_helpdesk($id, $acc = '', $class = '')
{
	$ClsSta = new ClsStatus();
	$result = $ClsSta->get_status_hd('', '', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'sta_codigo', 'sta_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function status_html_auditoria($id, $acc = '', $class = '')
{
	$ClsSta = new ClsStatus();
	$result = $ClsSta->get_status_aud('', '', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'sta_codigo', 'sta_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function incidentes_html($id, $categoria, $prioridad, $acc = '', $class = '')
{
	$ClsInc = new ClsIncidente();
	$result = $ClsInc->get_incidente('', $categoria, $prioridad, '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'inc_codigo', 'inc_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

////////////////////////// PPM /////////////////////////////

function categorias_ppm_html($id, $acc = '', $class = '')
{
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_ppm('', '', 1);
	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function activos_html($id, $sede = '', $area = '', $acc = '', $class = '')
{
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo('', $sede, '', $area);
	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'act_codigo', 'act_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


function cuestionario_html($id, $acc = '', $class = '')
{
	$ClsCue = new ClsCuestionarioPPM();
	$result = $ClsCue->get_cuestionario('', '', 1);
	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cue_codigo', 'cue_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


function activo_sedes_html($id, $acc = '', $class = '')
{
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo('', '', '', '');
	if (is_array($result)) {
		if ($result) {
			$salida = '<select name="' . $id . '" id="' . $id . '" class = "form-control ' . $class . '" onchange = "' . $acc . '">';
			$salida .= '<option value="">Seleccione</option>';
			if (is_array($result)) {
				foreach ($result as $row) {
					$descripcion = trim($row["act_nombre"]) . " de " . trim($row["are_nombre"]) . ". en " . trim($row["sed_nombre"]);
					$salida .= '<option value="' . $row["act_codigo"] . '" >' . $descripcion . '</option>';
				}
			}
			$salida .= '</select>';
		} else {
			return combos_vacios("$id");
		}
	}
	return $salida;
}

////////////////// AUDITORIA

function auditoria_html($id, $acc = '', $class = '')
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_cuestionario('', '', '', 1);
	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'audit_codigo', 'audit_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function categorias_auditoria_html($id, $acc = '', $class = '')
{
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_auditoria('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function secciones_auditoria_html($id, $audioria, $acc = '', $class = '')
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_secciones('', $audioria, 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'sec_codigo', 'sec_numero_titulo', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

/////////////////// PROCESOS
function sistema_html($id, $acc = '', $class = '')
{
	$ClsSis = new ClsSistema();
	$result = $ClsSis->get_sistema('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'sis_codigo', 'sis_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function ficha_html($id, $acc = '', $class = '', $usuario = "")
{
	$ClsFic = new ClsFicha();
	if (strlen($usuario) > 0) {
		$result = $ClsFic->get_ficha_usuario('', '', $usuario);
	} else $result = $ClsFic->get_ficha();

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'fic_codigo', 'fic_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function clase_recursos_html($id, $acc = '', $class = '')
{
	$ClsRec = new ClsRecursos();
	$result = $ClsRec->get_tipo_recursos('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'tip_codigo', 'tip_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


function subtitulos_html($id, $acc = '', $class = '', $posicion = '')
{
	$ClsPro = new ClsProceso();
	$result = $ClsPro->get_subtitulo('', '', '', 1, $posicion);

	if (is_array($result)) {
		$salida = '<select name="' . $id . '" id="' . $id . '" class = "form-control ' . $class . '" onchange = "' . $acc . '">';
		$salida .= '<option value="">Seleccione</option>';
		foreach ($result as $row) {
			$salida .= '<option value=' . trim($row['sub_codigo']) . '>' . trim($row['tit_nombre']) . ' ' . trim($row['sub_nombre']) . '</option>';
		}
		$salida .= '</select>';
		return $salida;
	} else {
		return combos_vacios("$id", $class);
	}
}

////////////////// Biblioteca ///////////////

function categorias_biblioteca_html($id, $acc = '', $class = '')
{
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_categoria('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


////////////////// Indicadores ///////////////
function indicador_html($id, $acc = '', $class = '')
{
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_indicador('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'ind_codigo', 'ind_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function categorias_indicador_html($id, $acc = '', $class = '')
{
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_indicador('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}

function clasificacion_indicador_html($id, $acc = '', $class = '')
{
	$ClsClas = new ClsClasificacion();
	$result = $ClsClas->get_clasificacion_indicador('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cla_codigo', 'cla_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


////////////////// ENCUESTAS ///////////////

function categorias_encuesta_html($id, $acc = '', $class = '')
{
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_categoria('', '', 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cat_codigo', 'cat_nombre', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


function cuestionario_encuesta_html($id, $categoria, $acc = '', $class = '')
{
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_cuestionario('', $categoria, 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'cue_codigo', 'cue_titulo', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}


function secciones_encuesta_html($id, $cuestionario, $acc = '', $class = '')
{
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_secciones('', $cuestionario, 1);

	if (is_array($result)) {
		return combos_html_onclick($result, "$id", 'sec_codigo', 'sec_numero_titulo', $acc, $class);
	} else {
		return combos_vacios("$id", $class);
	}
}



////////////////// UBICACION

function departamento_html($id, $acc = '', $class = '')
{
	$ClsMdep = new ClsMundep();
	$result = $ClsMdep->get_departamentos();
	return combos_html_onclick($result, $id, 'dm_codigo', 'dm_desc', $acc, $class);
}


function municipio_html($dep, $id, $acc = '', $class = '')
{
	$ClsMdep = new ClsMundep();
	$result = $ClsMdep->get_municipios($dep);

	return combos_html_onclick($result, $id, 'dm_codigo', 'dm_desc', $acc, $class);
}


//////////////////

function combos_html_onclick($result_id, $name, $c1, $c2, $instruc, $class = '', $all = '')
{

	if ($result_id) {
		$salida = '<select name="' . $name . '" id="' . $name . '" class = "form-control ' . $class . '" onchange = "' . $instruc . '">';
		$salida .= '<option value="">Seleccione</option>';
		if ($all != '') {
			$salida .= '<option value="' . $all . '">' . $all . '</option>';
		}
		if (is_array($result_id)) {
			foreach ($result_id as $row) {
				$salida .= '<option value=' . trim($row[$c1]) . '>' . trim($row[$c2]) . '</option>';
			}
		}
		$salida .= '</select>';
	} else {
		$salida = '<select name="' . $name . '" id="' . $name . '" class = "select2 form-control">';
		$salida .= '<option value="">Seleccione</option>';
		$salida .= '</select>';
	}
	return $salida;
}


function combos_vacios($name, $class = '')
{

	$salida = '<select name="' . $name . '" id="' . $name . '" class = "' . $class . ' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	$salida .= '</select>';

	return $salida;
}

function combos_html_multiselect($result_id, $name, $c1, $c2, $instruc, $class = '')
{

	if ($result_id) {
		$salida = '<select name="' . $name . '[]" id="' . $name . '" class = "form-control ' . $class . '"  multiple="multiple" onchange = "' . $instruc . '">';
		if (is_array($result_id)) {
			foreach ($result_id as $row) {
				$salida .= '<option value=' . trim($row[$c1]) . '>' . trim($row[$c2]) . '</option>';
			}
		}
		$salida .= '</select>';
	} else {
		$salida = '<select name="' . $name . '" id="' . $name . '" class = "select2 form-control">';
		$salida .= '<option value="">Seleccione</option>';
		$salida .= '</select>';
	}
	return $salida;
}


function lista_multiple_html($result_id, $name, $c1, $c2, $titulo)
{

	if ($result_id) {
		$i = 1;
		$salida = '<div class="list-group">';
		$salida .= '<span class="list-group-item active">';
		$salida .= '<input type = "checkbox" name="' . $name . 'base" id="' . $name . 'base" onclick = "check_lista_multiple(\'' . $name . '\');" />';
		$salida .= ' ' . $titulo . '</span>';
		if (is_array($result_id)) {
			foreach ($result_id as $row) {
				$salida .= '<a href="javascript:void(0)" class="list-group-item text-left">';
				$salida .= '<input type = "checkbox" name="' . $name . '' . $i . '" id="' . $name . '' . $i . '" value="' . trim($row[$c1]) . '" />';
				$salida .= ' <small>' . trim($row[$c2]) . '</small></a>';
				$i++;
			}
			$i--;
			$salida .= '<span class="list-group-item disabled text-right">';
			$salida .= '<input type = "hidden" name="' . $name . 'rows" id="' . $name . 'rows" value=' . $i . ' />';
			$salida .= $i . ' Registro(s)</span>';
		} else {
			$salida .= '<a href="javascript:void(0)" class="list-group-item text-left">';
			$salida .= '<input type = "checkbox" name="' . $name . '0" id="' . $name . '0" value = "" disabled />';
			$salida .= ' <small>No se reportan registros...</small></a>';
			$salida .= '<span class="list-group-item disabled text-right">';
			$salida .= '<input type = "hidden" name="' . $name . 'rows" id="' . $name . 'rows" value="0" />';
			$salida .= '0 Registro(s)</span>';
		}
		$salida .= '</div>';
	} else {
		$salida = '<div class="list-group">';
		$salida .= '<span class="list-group-item active">';
		$salida .= '<input type = "checkbox" name="' . $name . 'base" id="' . $name . 'base" onclick = "check_lista_multiple(\'' . $name . '\');" />';
		$salida .= ' ' . $titulo . '</span>';
		$salida .= '<a href="javascript:void(0)" class="list-group-item text-left">';
		$salida .= '<input type = "checkbox" name="' . $name . '0" id="' . $name . '0" value = "" disabled />';
		$salida .= ' <small>No se reportan registros...</small></a>';
		$salida .= '<span class="list-group-item disabled text-right">';
		$salida .= '<input type = "hidden" name="' . $name . 'rows" id="' . $name . 'rows" value="0" />';
		$salida .= '0 Registro(s)</span>';
		$salida .= '</div>';
	}
	return $salida;
}


function lista_multiple_vacia($name, $titulo)
{

	$salida = '<div class="list-group">';
	$salida .= '<span class="list-group-item active">';
	$salida .= '<input type = "checkbox" name="' . $name . 'base" id="' . $name . 'base" onclick = "check_lista_multiple(\'' . $name . '\');" />';
	$salida .= ' ' . $titulo . '</span>';
	$salida .= '<a href="javascript:void(0)" class="list-group-item text-left">';
	$salida .= '<input type = "checkbox" name="' . $name . '0" id="' . $name . '0" value = "" disabled />';
	$salida .= ' <small>No se reportan registros...</small></a>';
	$salida .= '<span class="list-group-item disabled text-right">';
	$salida .= '<input type = "hidden" name="' . $name . 'rows" id="' . $name . 'rows" value="0" />';
	$salida .= '0 Registro(s)</span>';
	$salida .= '</div>';

	return $salida;
}


//////////////////////////////////////////////////// 
//Convierte fecha de normal a Informix
//////////////////////////////////////////////////// 
function escape_format($fecha, $escape)
{
	/// recibe y devuelve ---
	return $fecha;
}

//////////////////////////////////////////////////// 
//quita caracteres de espa�ol
//////////////////////////////////////////////////// 
function depurador_texto($texto)
{
	$texto = trim($texto);
	$texto = str_replace("�", "a", $texto);
	$texto = str_replace("�", "e", $texto);
	$texto = str_replace("�", "i", $texto);
	$texto = str_replace("�", "o", $texto);
	$texto = str_replace("�", "u", $texto);
	$texto = str_replace("�", "A", $texto);
	$texto = str_replace("�", "E", $texto);
	$texto = str_replace("�", "I", $texto);
	$texto = str_replace("�", "U", $texto);
	$texto = str_replace("�", "n", $texto);
	$texto = str_replace("�", "N", $texto);
	//--
	$texto = str_replace("�", "A", $texto);
	$texto = str_replace("�", "E", $texto);
	$texto = str_replace("�", "I", $texto);
	$texto = str_replace("�", "O", $texto);
	$texto = str_replace("�", "U", $texto);
	$texto = str_replace("�", "a", $texto);
	$texto = str_replace("�", "e", $texto);
	$texto = str_replace("�", "i", $texto);
	$texto = str_replace("�", "o", $texto);
	$texto = str_replace("�", "u", $texto);

	return $texto;
}


//////////////////////////////////////////////////// 
//Convierte fecha de Informix a normal 
//////////////////////////////////////////////////// 
function cambia_fecha($Fecha)
{
	if ($Fecha <> "") {
		$trozos = explode("-", $Fecha, 3);
		return $trozos[2] . "/" . $trozos[1] . "/" . $trozos[0];
	} else {
		return $Fecha;
	}
}

//////////////////////////////////////////////////// 
//Convierte fecha de Informix a normal 
//////////////////////////////////////////////////// 
function si_no($cumple)
{
	if ($cumple == 0) {
		$cumple = 'No';
	} else if($cumple == 1){
		$cumple = 'Si';
	}
	return $cumple;
}


//////////////////////////////////////////////////// 
//Frecuencia
//////////////////////////////////////////////////// 
function Frecuencias($frecuencia)
{
	if ($frecuencia == 1) {
		$frecuencia = 'Semestral';
	} else if($frecuencia == 2){
		$frecuencia = 'Trimestral';
	}else if($frecuencia == 3){
		$frecuencia = 'Anual';
	}
	return $frecuencia;
}


//////////////////////////////////////////////////// 
//Convierte fecha de normal a Informix
//////////////////////////////////////////////////// 
function regresa_fecha($Fecha)
{
	if ($Fecha <> "") {
		$trozos = explode("/", $Fecha, 3);
		return $trozos[2] . "-" . $trozos[1] . "-" . $trozos[0];
	} else {
		return $Fecha;
	}
}

//////////////////////////////////////////////////// 
//Convierte fecha y hora de Informix a normal 
//////////////////////////////////////////////////// 
function cambia_fechaHora($Fecha)
{
	if ($Fecha <> "") {
		$trozos = explode("-", $Fecha);
		$trozos2 = explode(" ", $trozos[2]);
		$fecha = $trozos2[0] . "/" . $trozos[1] . "/" . $trozos[0];
		$hora = $trozos2[1];
		return "$fecha $hora";
	} else {
		return $Fecha;
	}
}


//////////////////////////////////////////////////// 
//Convierte fecha y hora de Informix a normal 
//////////////////////////////////////////////////// 
function regresa_fechaHora($Fecha)
{
	if ($Fecha <> "") {
		$trozos = explode("/", $Fecha);
		$trozos2 = explode(" ", $trozos[2]);
		$fecha = $trozos2[0] . "-" . $trozos[1] . "-" . $trozos[0];
		$hora = $trozos2[1];
		return "$fecha $hora";
	} else {
		return $Fecha;
	}
}

////////////////////////////////////////////////////
// Fecha en formato dd/mm/yyyy o dd-mm-yyyy retorna la diferencia en dias
////////////////////////////////////////////////////

function restaFechas($dFecIni, $dFecFin)
{

	$date1 = strtotime($dFecIni);
	$date2 = strtotime($dFecFin);

	$date1 = new DateTime($dFecIni);
	$date2 = new DateTime($dFecFin);
	$interval = $date1->diff($date2);
	//print_r($interval)."<br>";

	return $interval->days;
}


function comparaFechas($fecha1, $fecha2)
{

	$date1 = strtotime($fecha1);
	$date2 = strtotime($fecha2);

	if ($date1 > $date2) {
		return 1; //la fecha1 es mayor
	} else if ($date2 > $date1) {
		return 2; //la fecha2 es mayor
	} else {
		return 0; //las fechas son iguales
	}
}


/////----

function horasYdecimales($tiempo)
{
	$trozos = explode("/", $tiempo);
	$horas = $trozos[0];
	$minutos = $trozos[1];
	$minutos = $minutos / 60;

	return $horas + $minutos;
}

////////////////////////////////////////////////////
// Fecha en formato dd/mm/yyyy retorna fecha con los dias sumados
////////////////////////////////////////////////////

function Fecha_suma_dias($Fecha, $dias)
{
	$fec = explode("/", $Fecha);
	$day = $fec[0];
	$mon = $fec[1];
	$year = $fec[2];

	$fecha_cambiada = mktime(0, 0, 0, $mon, $day + $dias, $year);
	$fecha = date("d/m/Y", $fecha_cambiada);
	return $fecha; //devuelve dd/mm/yyyy  
}


////////////////////////////////////////////////////
// Fecha en formato dd/mm/yyyy retorna fecha con los dias sumados
////////////////////////////////////////////////////

function Fecha_resta_dias($Fecha, $dias)
{
	$fec = explode("/", $Fecha);
	$day = $fec[0];
	$mon = $fec[1];
	$year = $fec[2];

	$fecha_cambiada = mktime(0, 0, 0, $mon, $day - $dias, $year);
	$fecha = date("d/m/Y", $fecha_cambiada);
	return $fecha; //devuelve dd/mm/yyyy  
}


////////////////////////////////////////////////////
// compara horarios
////////////////////////////////////////////////////

function compara_horas($hora1, $hora2)
{
	$h1 = substr($hora1, 0, 2);
	$m1 = substr($hora1, 3, 2);
	$h2 = substr($hora2, 0, 2);
	$m2 = substr($hora2, 3, 2);
	if ($h1 > $h2) {
		$mayor = true;
	} else if ($h1 == $h2) {
		if ($m1 > $m2) {
			$mayor = true;
		} else {
			$mayor = false;
		}
	} else {
		$mayor = false;
	}

	return $mayor; //devuelve si la hora 1 es mayor a la hora 2
}


//////////////////////////////////////////////////// 
//Calcular Horas, Minutos y Segundos
//////////////////////////////////////////////////// 
function calculaHoras($segundos)
{
	$horas = ($segundos / 3600);
	$horas = number_format($horas, 2, ".", "");
	$separa = explode(".", $horas);
	$horas = $separa[0];
	$decimales = $separa[1];
	//--
	$minutos = ($decimales * 60) / 100;
	$minutos = number_format($minutos, 2, ".", "");
	$separa = explode(".", $minutos);
	$minutos = $separa[0];
	$decimales = $separa[1];
	//--
	$segundos = ($decimales * 60) / 100;
	$segundos = round($segundos, 0);

	return "$horas hora(s), $minutos minuto(s), $segundos segundo(s)";
}


////////////////////////////////////////////////////
// Fecha en formato dd/mm/yyyy retorna fecha con los dias sumados
////////////////////////////////////////////////////

function cambioMoneda($de, $para, $cuanto)
{
	$dato = $de * $cuanto;
	$dato = $dato / $para;
	$dato = round($dato, 2);
	return $dato;
}
////////////////////////////////////////////////////
// frecuencia
////////////////////////////////////////////////////



//////////////////////////////////////////////////// 
//devuelve los Nombres de los meses en letras
//////////////////////////////////////////////////// 
function Meses_Letra($num)
{
	switch ($num) {
		case 1:
			$letra = "Enero";
			break;
		case 2:
			$letra = "Febrero";
			break;
		case 3:
			$letra = "Marzo";
			break;
		case 4:
			$letra = "Abril";
			break;
		case 5:
			$letra = "Mayo";
			break;
		case 6:
			$letra = "Junio";
			break;
		case 7:
			$letra = "Julio";
			break;
		case 8:
			$letra = "Agosto";
			break;
		case 9:
			$letra = "Septiembre";
			break;
		case 10:
			$letra = "Octubre";
			break;
		case 11:
			$letra = "Noviembre";
			break;
		case 12:
			$letra = "Diciembre";
			break;
	}
	return $letra;
}

//////////////////////////////////////////////////// 
//devuelve los Nombres de los d�as en letras
//////////////////////////////////////////////////// 
function Dias_Letra($num)
{
	switch ($num) {
		case 1:
			$letra = "Lunes";
			break;
		case 2:
			$letra = "Martes";
			break;
		case 3:
			$letra = "Miercoles";
			break;
		case 4:
			$letra = "Jueves";
			break;
		case 5:
			$letra = "Viernes";
			break;
		case 6:
			$letra = "Sabado";
			break;
		case 7:
			$letra = "Domingo";
			break;
	}
	return $letra;
}

//////////-----
function Calcula_Edad($fecnac)
{
	if ($fecnac !== '') {
		//calculo la fecha de hoy
		$hoy = date("d/m/Y");
		$array_fecha = explode("/", $fecnac);
		$ano = intval($array_fecha[2], 10);
		$mes = intval($array_fecha[1], 10);
		$dia = intval($array_fecha[0], 10);
		$edad = date("Y") - $ano; ////// NOTA //////////			
		if ((date("m") - $mes) < 0) {
			$edad--;
			return $edad;
		}
		if ((date("m") - $mes) >= 0) {
			if ((date("m") - $mes) == 0) {
				if ((date("d")) >= $dia) {
					return $edad;
				} else {
					$edad--;
					return $edad;
				}
			} else {
				return $edad;
			}
		}
	}
}

////////////////////////////////////////////////////
//--------------------
////////////////////////////////////////////////////

function Agrega_Ceros($dato)
{
	$len = strlen($dato);
	switch ($len) {
		case 1:
			$dato = "000$dato";
			break;
		case 2:
			$dato = "00$dato";
			break;
		case 3:
			$dato = "0$dato";
			break;
	}
	return $dato;
}

function comprobar_email($email)
{
	$mail_correcto = 0;
	//compruebo unas cosas primeras
	if ((strlen($email) >= 6) && (substr_count($email, "@") == 1) && (substr($email, 0, 1) != "@") && (substr($email, strlen($email) - 1, 1) != "@")) {
		if ((!strstr($email, "'")) && (!strstr($email, "\"")) && (!strstr($email, "\\")) && (!strstr($email, "\$")) && (!strstr($email, " "))) {
			//miro si tiene caracter .
			if (substr_count($email, ".") >= 1) {
				//obtengo la terminacion del dominio
				$term_dom = substr(strrchr($email, '.'), 1);
				//compruebo que la terminacion del dominio sea correcta (@)
				if (strlen($term_dom) > 1 && strlen($term_dom) < 5 && (!strstr($term_dom, "@"))) {
					//compruebo que lo de antes del dominio sea correcto
					$antes_dom = substr($email, 0, strlen($email) - strlen($term_dom) - 1);
					$caracter_ult = substr($antes_dom, strlen($antes_dom) - 1, 1);
					if ($caracter_ult != "@" && $caracter_ult != ".") {
						$mail_correcto = 1;
					}
				}
			}
		}
	}
	if ($mail_correcto)
		return 1; // si el correo es valido regresa 1 o true
	else
		return 0; // si el correo no es valido regresa 0 o false
}

//////////////////////////////////////////////////// 
// URL DEL SERVIDOR
//////////////////////////////////////////////////// 
function url_origin($s, $use_forwarded_host = false)
{
	$ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
	$sp       = strtolower($s['SERVER_PROTOCOL']);
	$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	$port     = $s['SERVER_PORT'];
	$port     = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
	$host     = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
	$host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;
	return $protocol . '://' . $host;
}

function full_url($s, $use_forwarded_host = false)
{
	return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}

//////////////////////////////////////////////////// 
//Exportaci�n a Excel
////////////////////////////////////////////////////
function LetrasBase($numero)
{
	if ($numero > 0 && $numero <= 26) {
		$letras = Trae_letra($numero);
	} else if ($numero > 26 && $numero <= 52) {
		$resta = ($numero - 26);
		$letras = "A" . Trae_letra($resta);
	} else if ($numero > 52 && $numero <= 78) {
		$resta = ($numero - 52);
		$letras = "B" . Trae_letra($resta);
	} else if ($numero > 78 && $numero <= 104) {
		$resta = ($numero - 78);
		$letras = "C" . Trae_letra($resta);
	} else if ($numero > 104 && $numero <= 130) {
		$resta = ($numero - 104);
		$letras = "D" . Trae_letra($resta);
	} else if ($numero > 130 && $numero <= 156) {
		$resta = ($numero - 130);
		$letras = "E" . Trae_letra($resta);
	}

	return $letras;
}

////////////////////////////////////////////////////
//devuelve las letras de las columnas segun el numero de columna
////////////////////////////////////////////////////
function Trae_letra($num)
{
	switch ($num) {
		case 1:
			$letra = "A";
			break;
		case 2:
			$letra = "B";
			break;
		case 3:
			$letra = "C";
			break;
		case 4:
			$letra = "D";
			break;
		case 5:
			$letra = "E";
			break;
		case 6:
			$letra = "F";
			break;
		case 7:
			$letra = "G";
			break;
		case 8:
			$letra = "H";
			break;
		case 9:
			$letra = "I";
			break;
		case 10:
			$letra = "J";
			break;
		case 11:
			$letra = "K";
			break;
		case 12:
			$letra = "L";
			break;
		case 13:
			$letra = "M";
			break;
		case 14:
			$letra = "N";
			break;
		case 15:
			$letra = "O";
			break;
		case 16:
			$letra = "P";
			break;
		case 17:
			$letra = "Q";
			break;
		case 18:
			$letra = "R";
			break;
		case 19:
			$letra = "S";
			break;
		case 20:
			$letra = "T";
			break;
		case 21:
			$letra = "U";
			break;
		case 22:
			$letra = "V";
			break;
		case 23:
			$letra = "W";
			break;
		case 24:
			$letra = "X";
			break;
		case 25:
			$letra = "Y";
			break;
		case 26:
			$letra = "Z";
			break;
	}
	return $letra;
}

function quita_tildes($cadena)
{
	$cadena = str_replace("�", "A", $cadena);
	$cadena = str_replace("�", "E", $cadena);
	$cadena = str_replace("�", "I", $cadena);
	$cadena = str_replace("�", "O", $cadena);
	$cadena = str_replace("�", "U", $cadena);
	$cadena = str_replace("�", "n", $cadena);
	$cadena = str_replace("�", "a", $cadena);
	$cadena = str_replace("�", "e", $cadena);
	$cadena = str_replace("�", "i", $cadena);
	$cadena = str_replace("�", "o", $cadena);
	$cadena = str_replace("�", "u", $cadena);
	$cadena = str_replace("�", "n", $cadena);
	//--
	$cadena = str_replace("�", "A", $cadena);
	$cadena = str_replace("�", "E", $cadena);
	$cadena = str_replace("�", "I", $cadena);
	$cadena = str_replace("�", "O", $cadena);
	$cadena = str_replace("�", "U", $cadena);
	$cadena = str_replace("�", "a", $cadena);
	$cadena = str_replace("�", "e", $cadena);
	$cadena = str_replace("�", "i", $cadena);
	$cadena = str_replace("�", "o", $cadena);
	$cadena = str_replace("�", "u", $cadena);

	return $cadena;
}

//////////////////////////////////////////////////// 
//Creador de Imagen QR
//////////////////////////////////////////////////// 
function crea_QR($codigo)
{
	//set it to writable location, a place for temp generated image files
	$directorio = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;

	//ofcourse we need rights to create temp dir
	if (!file_exists($directorio)) {
		mkdir($directorio);
	}

	///directorio para utilizar con la libreria pahpQRcode
	$filename = $directorio . $codigo . '.jpg';

	//processing form input
	$errorCorrectionLevel = "H";
	$matrixPointSize = 5;


	//it's very important!
	if (trim($codigo) == '') {
		die('Error en el Generador, Repita el Proceso!');
		return;
	}
	// user data
	QRcode::png($codigo, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

	///// CONVIERTE A JPG
	$imagen = imagecreatefrompng($filename);
	$destino = str_replace(".png", ".jpg", 'QRs/' . $codigo . '.jpg');
	imagejpeg($imagen, $destino, 100);
	//--
	$path = '../temp/' . $codigo . '.jpg';
	if (file_exists($path)) {
		unlink($path);
	}
	$path = '../../temp/' . $codigo . '.jpg';
	if (file_exists($path)) {
		unlink($path);
	}
	return $destino;
}

//////////////////////////////////////////////////// 
//Convierte un array de javascript a uno de php
//////////////////////////////////////////////////// 
function php_array($array)
{
	$index = 0;
	$pointer = 0;
	$salida = array();
	while ($array[$index] != null) {
		if ($array[$index] != ',') {
			$salida[$pointer] = $array[$index];
			$pointer++;
		}
		$index++;
	}
	return $salida;
}


//////////////////////////////////////////////////// 
//RETORNA UN SISTEMA SEGUN SU CODIGO
//////////////////////////////////////////////////// 
function get__Sistema($codigo){
	$clsSis = new ClsSistema();
	$sistema = $clsSis->get_sistema($codigo);
	if(is_array($sistema)){
		foreach($sistema as $row){
			$sis = trim($row["sis_nombre"]);
		}
	}
	return $sis;
}

//////////////////////////////////////////////////////////////////// 
//RETORNA UN NUMERO CON EL TOTAL DE FICHAS DE PROCESO SIN APROBAR
///////////////////////////////////////////////////////////////////
function count_fichas_sin_aprobar(){
	$ClsFic = new ClsFicha();
	if(isset($_SESSION['APROFICHA'])){
	$total = $ClsFic->fichas_sin_aprobar();
		if(is_array($total)){
			foreach($total as $row){
				$total = $row['Fichas_Sin_Aprobar'];
			} 
		}
	}else{
		$total = "";
	}
	return $total;
}

//////////////////////////////////////////////////////////////////// 
//RETORNA UN NUMERO CON EL TOTAL DE FICHAS DE PROCESO SIN ACTUALIZAR
///////////////////////////////////////////////////////////////////
function count_fichas_actualizacion_usuario($usuario){
	$ClsFic = new ClsFicha();
	if(isset($_SESSION['MISFIC'])){
	$total = $ClsFic->fichas_actualizacion($usuario);
		if(is_array($total)){
			foreach($total as $row){
				$total = $row['Fichas_Actualizacion'];
			} 
		}
	}else{
		$total = "";
	}
	return $total;
}


//////////////////////////////////////////////////////////////////// 
//RETORNA EL TOTAL DE CHECKLIST A EJECUTAR EL DIA DE HOY///////////
///////////////////////////////////////////////////////////////////
function count_checklist(){
	$usuario = $_SESSION['codigo'];
	$ClsChk = new ClsLista();
	$total_checklist = 0;
	if(isset($_SESSION['REGREVWEB'])){	
		for ($i = 1; $i <= 3; $i++) {
			switch ($i) {
				case 1:
					$dia = date("d");
					//mensual
					$result = $ClsChk->count_checklist(1, $usuario, $dia, date("H:i"), date("d/m/Y"), date("d/m/Y"));
					break;
				case 2:
					$diaSemana = date("D");
					//semanal
					$result = $ClsChk->count_checklist(2, $usuario, $diaSemana, date("H:i"), date("d/m/Y"), date("d/m/Y"));
					break;
				case 3:
					$fechaHoy = date('Y-m-d');
					//unico
					$result = $ClsChk->count_checklist(3, $usuario, $fechaHoy, date("H:i"), date("d/m/Y"), date("d/m/Y"));
					break;

			}
			if (is_array($result)) {
				foreach ($result as $row) {
					$revision_ejecutada = $row['revision_ejecutada'];
					$revision_activa = $row['revision_activa'];
					if($revision_ejecutada == NULL){ //&& $revision_activa == NULL){
						$total_checklist++;
					}
				}	
			}	
		}
	}
	return $total_checklist;
}

