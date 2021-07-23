<?php
include_once('html_fns_activo.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$ClsAct = new ClsActivo();validate_login("../");
$usuario = $_SESSION["codigo"];
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsAct->decrypt($hashkey, $usuario);
$result = $ClsAct->get_activo($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$sede = utf8_decode($row["sed_nombre"]);
		$sector = utf8_decode($row["sec_nombre"]);
		$area = utf8_decode($row["are_nombre"]);
		$nivel = utf8_decode($row["are_nivel"]);
		$nombre = utf8_decode($row["act_nombre"]);
		$marca = utf8_decode($row["act_marca"]);
		$serie = utf8_decode($row["act_serie"]);
		$modelo = utf8_decode($row["act_modelo"]);
		$parte = utf8_decode($row["act_parte"]);
		$proveedor = utf8_decode($row["act_proveedor"]);
		$periodicidad = utf8_decode($row["act_periodicidad"]);
		$capacidad = utf8_decode($row["act_capacidad"]);
		$cantidad = trim($row["act_cantidad"]);
		$precio1 = trim($row["act_precio_nuevo"]);
		$precio2 = trim($row["act_precio_compra"]);
		$precio3 = trim($row["act_precio_actual"]);
		$observaciones = utf8_decode($row["act_observaciones"]);
		//--
		$sit = trim($row["act_situacion"]);
		$situacion = ($sit == 1) ? 'Activo' : 'Inactivo';
		$situacion_color = ($sit == 1) ? 'text-info' : 'text-danger';
	}

	switch ($periodicidad) {
		case "D":
			$periodicidad = "Diario";
			break;
		case "W":
			$periodicidad = "Semanal";
			break;
		case "M":
			$periodicidad = "Mensual";
			break;
		case "Y":
			$periodicidad = "Anual";
			break;
		case "V":
			$periodicidad = "Variado";
			break;
	}
}
$result = $ClsAct->get_fotos('', $codigo, 1);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$strFoto1 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $strFoto1 . '.jpg') || $strFoto1 != "") {
			$strFoto1 = '<img  src="../../CONFIG/Fotos/ACTIVOS/' . $strFoto1 . '.jpg" alt="...">';
		} else {
			$strFoto1 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$strFoto1 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}
$result = $ClsAct->get_fotos('', $codigo, 2);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$strFoto2 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $strFoto2 . '.jpg') || $strFoto2 != "") {
			$strFoto2 = '<img  src="../../CONFIG/Fotos/ACTIVOS/' . $strFoto2 . '.jpg" alt="...">';
		} else {
			$strFoto2 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$strFoto2 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}
$result = $ClsAct->get_fotos('', $codigo, 3);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$strFoto3 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $strFoto3 . '.jpg') || $strFoto3 != "") {
			$strFoto3 = '<img  src="../../CONFIG/Fotos/ACTIVOS/' . $strFoto3 . '.jpg" alt="...">';
		} else {
			$strFoto3 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$strFoto3 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "ppm"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-app"></i> &nbsp; Ficha de Activo</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-3 col-md-3 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</button>
									</div>
									<div class="col-xs-3 col-md-3 text-left">
										<label>C&oacute;digo de Activo:</label> <br>
										<strong># <?php echo Agrega_Ceros($codigo); ?></strong>
									</div>
									<div class="col-xs-6 col-md-6 text-left">
										<label>Situaci&oacute;n del Activo:</label> <br>
										<div class="form-group">
											<div class="input-group">
												<input type="text" class="form-control <?php echo $situacion_color; ?>" value="<?php echo $situacion; ?>" readonly />
												<span class="input-group-addon"></span>
												<span class="input-group-addon" onclick="listFallas(<?php echo $codigo; ?>);" title="Historial de Fallas"><i class="fa fa-list"></i></span>
												<span class="input-group-addon" onclick="newFalla(<?php echo $codigo; ?>);" title="Reportar Falla"><i class="fa fa-exclamation-circle"></i></span>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Sede:</label>
										<input type="text" class="form-control" value="<?php echo $sede; ?>" readonly />
									</div>
									<div class="col-md-6">
										<label>Area:</label>
										<input type="text" class="form-control" value="<?php echo $area; ?>" readonly />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Sector:</label>
										<input type="text" class="form-control" value="<?php echo $sector; ?>" readonly />
									</div>
									<div class="col-md-6">
										<label>Nivel:</label>
										<input type="text" class="form-control" value="<?php echo $nivel; ?>" readonly />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Nombre del Activo:</label>
										<input type="text" class="form-control" value="<?php echo $nombre; ?>" />
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" readonly />
									</div>
									<div class="col-md-6">
										<label>Marca:</label>
										<input type="text" class="form-control" readonly value="<?php echo $marca; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>No. de Serie:</label>
										<input type="text" class="form-control" readonly value="<?php echo $serie; ?>" />
									</div>
									<div class="col-md-6">
										<label>Modelo:</label>
										<input type="text" class="form-control" readonly onkeyup="texto(this)" value="<?php echo $modelo; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>No. de Parte:</label>
										<input type="text" class="form-control" readonly value="<?php echo $parte; ?>" />
									</div>
									<div class="col-md-6">
										<label>Proveedor:</label>
										<input type="text" class="form-control" readonly value="<?php echo $proveedor; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Capacidad:</label>
										<input type="text" class="form-control" readonly value="<?php echo $capacidad; ?>" />
									</div>
									<div class="col-md-6">
										<label>Cantidad:</label>
										<input type="text" class="form-control" readonly value="<?php echo $cantidad; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Periodicidad de Mantenimiento:</label>
										<input type="text" class="form-control" readonly value="<?php echo $periodicidad; ?>" />
									</div>
									<div class="col-md-6">
										<label>Precio Original (Nuevo):</label>
										<input type="text" class="form-control" readonly value="<?php echo $precio1; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Precio de Adquicisi&oacute;n:</label>
										<input type="text" class="form-control" readonly value="<?php echo $precio2; ?>" />
									</div>
									<div class="col-md-6">
										<label>Precio Actual:</label>
										<input type="text" class="form-control" readonly value="<?php echo $precio3; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones Especiales:</label>
										<textarea class="form-control" rows="3" readonly><?php echo $observaciones; ?></textarea>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="foto1">
													<?php echo $strFoto1; ?>
												</div>
												<label>Agregar Foto 1</label>
											</div>
										</div>
									</div>
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="foto2">
													<?php echo $strFoto2; ?>
												</div>
												<label>Agregar Foto 2</label>
											</div>
										</div>
									</div>
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="foto3">
													<?php echo $strFoto3; ?>
												</div>
												<label>Agregar Foto 3</label>
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<h6 class="card-title"><i class="fa fa-list"></i> Historial de Fallas del Activo</h6>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo tabla_fallas($codigo); ?>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" onclick="window.history.back();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</button>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/activo.js"></script>

	<script>
		$(document).ready(function() {
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>
</html>
<?php
function tabla_fallas($activo)
{
	$ClsFal = new ClsFalla();
	$result = $ClsFal->get_falla('', $activo);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		//$salida.= '<th class = "text-center" width = "120px">Activo</th>';
		$salida .= '<th class = "text-center" width = "150px">Falla Reportada</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha de la Falla</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha de Registro</th>';
		$salida .= '<th class = "text-center" width = "70px">Situaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha de Soluci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "100px">Comentario</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$salida .= '<td class = "text-center" >' . $i . '</td>';
			//nombre
			$nombre = utf8_decode($row["act_nombre"]);
			//$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//falla
			$falla = utf8_decode($row["fall_falla"]);
			$salida .= '<td class = "text-left">' . $falla . '</td>';
			//fecha
			$fecha = cambia_fechaHora($row["fall_fecha_falla"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//fecha registro
			$fecha = cambia_fechaHora($row["fall_fecha_registro"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//usuario
			$usuario = utf8_decode($row["usu_nombre"]);
			//$salida.= '<td class = "text-left">'.$usuario.'</td>';
			//situacion
			$sit = trim($row["fall_situacion"]);
			$situacion = ($sit == 1) ? '<span class="text-muted">Reportado</span>' : '<strong class="text-info">Solucionado</strong>';
			$salida .= '<td class = "text-center">' . $situacion . '</td>';
			//fecha solucion
			$fecha = cambia_fechaHora($row["fall_fecha_registro"]);
			$fecha = ($sit == 2) ? $fecha : '-';
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//comentario
			$comentario = utf8_decode($row["fall_comentario_solucion"]);
			$salida .= '<td class = "text-justify">' . $comentario . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	} else {
		$salida = '<h5 class="alert alert-info text-center">';
		$salida .= '<i class="fa fa-ban"></i> No hay fallas reportadas en este activo....';
		$salida .= '</h5>';
	}
	return $salida;
}
?>