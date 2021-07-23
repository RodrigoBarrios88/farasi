<?php
include_once('html_fns.php');
validate_login("../");
$id = $_SESSION["codigo"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head(); ?>

	<style>
		.card-stats .card-body .numbers {
			font-size: 14px;
		}
	</style>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("", "herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<div class="card">
					<div class="card-body">
						<h5><i class="fa fa-question-circle"></i> Preguntas Frecuentes</h5>
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center" width="5px">No.</th>
									<th class="text-center" width="200px">Pregunta</th>
									<th class="text-center" width="30px"><i class="fa fa-cogs"></i></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-center">1.</td>
									<td class="text-left">&iquest;C&oacute;mo ingresar PPManagement App?</td>
									<td class="text-center">
										<a class="btn btn-primary btn-xs btn-block" href="#" target="_blank" title="Ir a Tutorial">Ir &nbsp; <i class="fa fa-chevron-right"></i></a>
									</td>
								</tr>
								<tr>
									<td class="text-center">2.</td>
									<td class="text-left">&iquest;C&oacute;mo actualizar mi perfil en el sistema?</td>
									<td class="text-center">
										<a class="btn btn-primary btn-xs btn-block" href="#" target="_blank" title="Ir a Tutorial">Ir &nbsp; <i class="fa fa-chevron-right"></i></a>
									</td>
								</tr>
								<tr>
									<td class="text-center">3.</td>
									<td class="text-left">&iquest;C&oacute;mo cambiar mi contrase&ntilde;a?</td>
									<td class="text-center">
										<a class="btn btn-primary btn-xs btn-block" href="#" target="_blank" title="Ir a Tutorial">Ir &nbsp; <i class="fa fa-chevron-right"></i></a>
									</td>
								</tr>
								<tr>
									<td class="text-center">4.</td>
									<td class="text-left">&iquest;C&oacute;mo registrar una Lista de Chequeo?</td>
									<td class="text-center">
										<a class="btn btn-primary btn-xs btn-block" href="#" target="_blank" title="Ir a Tutorial">Ir &nbsp; <i class="fa fa-chevron-right"></i></a>
									</td>
								</tr>
								<tr>
									<td class="text-center">5.</td>
									<td class="text-left">&iquest;C&oacute;mo reportar un incidente en el sistema?</td>
									<td class="text-center">
										<a class="btn btn-primary btn-xs btn-block" href="#" target="_blank" title="Ir a Tutorial">Ir &nbsp; <i class="fa fa-chevron-right"></i></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo scripts() ?>
	<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu.js"></script>

	<script>
		$(document).ready(function() {

			$calendar = $('#fullCalendar');

			today = new Date();
			y = today.getFullYear();
			m = today.getMonth();
			d = today.getDate();

			$calendar.fullCalendar({
				viewRender: function(view, element) {
					// We make sure that we activate the perfect scrollbar when the view isn't on Month
					if (view.name != 'month') {
						$(element).find('.fc-scroller').perfectScrollbar();
					}
				},
				header: {
					left: 'title',
					center: 'month,agendaWeek,agendaDay',
					right: 'prev,next,today'
				},
				defaultDate: today,
				selectable: false,
				selectHelper: false,

				editable: false,
				eventLimit: true, // allow "more" link when too many events

				// color classes: [ event-blue | event-azure | event-green | event-orange | event-red ]
				events: <?php echo $json; ?>
			});

		});
	</script>

</body>

</html>
<?php
function json_calendario($sedes, $departamento, $categoria, $fini, $ffin)
{
	$i = 1;
	$json = "[";
	//////////////////////////////////////// AUDITORIA ///////////////////////////////////////////
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion('', '', $sedes, $departamento, $categoria, $fini, $ffin, '', '', '1,2', '');
	if (is_array($result)) {
		foreach ($result as $row) {
			$json .= '{';
			//--
			$cod = $row["pro_codigo"];
			$json .= "id: $i,";
			//---
			$nombre = utf8_decode($row["audit_nombre"]) . " / " . utf8_decode($row["sed_nombre"]);
			$json .= "title: '$nombre',";
			//--
			$fini = trim($row["pro_fecha"]) . " " . trim($row["pro_hora"]);
			//FECHA/HORA INICIO DE ACTIVIDAD
			$json .= fecha_inicio($fini);
			$json .= "allDay: true, ";
			//codigo
			$codigo = $row["audit_codigo"];
			$progra = $row["pro_codigo"];
			$ejecucion = $row["ejecucion_activa"];
			$usu = $_SESSION["codigo"];
			$hashkey1 = $ClsAud->encrypt($codigo, $usu);
			$hashkey2 = $ClsAud->encrypt($progra, $usu);
			if ($ejecucion != "") {
				$json .= "className: 'event-green'";
			} else {
				$json .= "url: 'CPAUDEJECUCION/FRMcuestionario.php?hashkey1=$hashkey1&hashkey2=$hashkey2',";
				$json .= "className: 'event-orange'";
			}
			//--
			$json .= '},';
			$i++;
		}
		//$json = substr($json, 0, -1);
	}
	//////////////////////////////////////// PPM ///////////////////////////////////////////
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('', '', '', '', $sedes, '', '', $fini, $ffin, '', '', '');
	if (is_array($result)) {
		foreach ($result as $row) {
			$json .= '{';
			//--
			$cod = $row["pro_codigo"];
			$json .= "id: $i,";
			//---
			$nombre = "PPM " . utf8_decode($row["act_nombre"]) . " / " . utf8_decode($row["sed_nombre"]);
			$json .= "title: '$nombre',";
			//--
			$fini = trim($row["pro_fecha"]) . " 08:00:00";
			//FECHA/HORA INICIO DE ACTIVIDAD
			$json .= fecha_inicio($fini);
			$json .= "allDay: true, ";
			//codigo
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$situacion = $row["pro_situacion"];
			if ($situacion == 1) {
				$json .= "url: 'CPPPMPROGRA/FRMorden.php?hashkey=$hashkey',";
				$json .= "className: 'event-gray'";
			} else {
				$json .= "url: 'CPPPMEJECUCION/FRMrevision.php?hashkey=$hashkey',";
				$json .= "className: 'event-azure'";
			}
			//--
			$json .= '},';
			$i++;
		}
		$json = substr($json, 0, -1);
	}

	$json .= "]";

	return $json;
}





function fecha_inicio($fechor)
{
	//FECHA/HORA INICIO DE ACTIVIDAD
	$fechor = cambia_fechaHora($fechor);
	$dia = intval(substr($fechor, 0, 2));
	$mes = intval(substr($fechor, 3, 2));
	$anio = intval(substr($fechor, 6, 4));
	$hora = intval(substr($fechor, 11, 2));
	$min = intval(substr($fechor, 14, 2));
	///AÑO///
	$year = date("Y");
	if ($year > $anio) {
		$calc = $year - $anio;
		$Y = "y-$calc";
	} else if ($year < $anio) {
		$calc = $anio - $year;
		$Y = "y+$calc";
	} else {
		$Y = "y";
	}
	///MES///
	$month = date("m");
	if ($month > $mes) {
		$calc = $month - $mes;
		$M = "m-$calc";
	} else if ($month < $mes) {
		$calc = $mes - $month;
		$M = "m+$calc";
	} else {
		$M = "m";
	}
	///DIA///
	$day = date("d");
	if ($day > $dia) {
		$calc = $day - $dia;
		$D = "d-$calc";
	} else if ($day < $dia) {
		$calc = $dia - $day;
		$D = "d+$calc";
	} else {
		$D = "d";
	}
	///H ///
	//echo "start: new Date($Y, $M, $D, $hora, $min),";
	//echo "<br>";
	return "start: new Date($Y, $M, $D, $hora, $min),";
}


function fecha_final($fechor)
{
	//FECHA/HORA INICIO DE ACTIVIDAD
	$fechor = cambia_fechaHora($fechor);
	$dia = intval(substr($fechor, 0, 2));
	$mes = intval(substr($fechor, 3, 2));
	$anio = intval(substr($fechor, 6, 4));
	$hora = intval(substr($fechor, 11, 2));
	$min = intval(substr($fechor, 14, 2));
	///AÑO///
	$year = date("Y");
	if ($year > $anio) {
		$calc = $year - $anio;
		$Y = "y-$calc";
	} else if ($year < $anio) {
		$calc = $anio - $year;
		$Y = "y+$calc";
	} else {
		$Y = "y";
	}
	///MES///
	$month = date("m");
	if ($month > $mes) {
		$calc = $month - $mes;
		$M = "m-$calc";
	} else if ($month < $mes) {
		$calc = $mes - $month;
		$M = "m+$calc";
	} else {
		$M = "m";
	}
	///DIA///
	$day = date("d");
	if ($day > $dia) {
		$calc = $day - $dia;
		$D = "d-$calc";
	} else if ($day < $dia) {
		$calc = $dia - $day;
		$D = "d+$calc";
	} else {
		$D = "d";
	}
	///H ///
	return "end: new Date($Y, $M, $D, $hora, $min),";
} ?>