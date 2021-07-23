<?php
include_once('../../html_fns.php');


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