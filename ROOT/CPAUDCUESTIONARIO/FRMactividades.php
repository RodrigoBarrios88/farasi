<?php
	include_once('html_fns_cuestionario.php');
	validate_login("../");
$id = $_SESSION["codigo"];

	//$_POST
	$programacion = $_REQUEST["codigo"];
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion($programacion,'');
	if(is_array($result)){
		foreach($result as $row){
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			//nombre
			$nombre = utf8_decode($row["audit_nombre"]);
			//sede
			$sede = trim($row["sed_nombre"]);
			//fecha
			$fecha = cambia_fecha($row["pro_fecha"]);
			$hora = substr($row["pro_hora"],0,5);
		}	
	}?>
<!DOCTYPE html>
<html>
<head>
<?php echo head("../"); ?>
</head>
<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../","auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-clipboard-list"></i> Gestor de Actividades de Auditor&iacute;a</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
									<div class="col-xs-6 col-md-6 text-right"><label class = " text-danger">* Campos Obligatorios</label> </div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Auditor&iacute;a: </label> <span class="text-danger">*</span>
										<input type = "text" class = "form-control" value="<?php echo $nombre; ?>" readonly />
									</div>
									<div class="col-md-6">
										<label>Categor&iacute;a: </label> <span class="text-danger">*</span>
										<input type = "text" class = "form-control" value="<?php echo $categoria; ?>" readonly />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Sede: </label> <span class="text-danger">*</span>
										<input type = "text" class = "form-control" value="<?php echo $sede; ?>" readonly />
									</div>
									<div class="col-md-6">
										<label>Fecha / Hora: </label> <span class="text-danger">*</span>
										<input type = "text" class = "form-control" value="<?php echo $fecha; ?> <?php echo $hora; ?>" readonly />
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-6">
										<label>Fecha de la actividad:</label> <span class="text-danger">*</span>
										<div class="form-group" id="simple">
											<div class="input-group date">
												<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo $fecha; ?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										</div>
										<input type = "hidden" name = "codigo" id = "codigo" />
										<input type = "hidden" name = "programacion" id = "programacion" value="<?php echo $programacion; ?>" />
									</div>
									<div class="col-md-6">
										<label>Hora a desarrollar:</label> <span class="text-danger">*</span>
										<div class="form-group">
											<input type="text" class="form-control timepicker" name = "hora" id = "hora" value="08:00">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Actividad:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name = "descripcion" id = "descripcion" onkeyup = "texto(this);" >
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones:</label>
										<textarea class="form-control" name = "obs" id = "obs" rows = "3" onkeyup = "textoLargo(this);" ></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id = "btn-limpiar" onclick = "Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id = "btn-grabar" onclick = "GrabarActividad();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id = "btn-modificar" onclick = "ModificarActividad();"><i class="fas fa-save"></i> Grabar</button>
								   </div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id = "result">
										
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
    
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/actividad.js"></script>
</body>
</html>
