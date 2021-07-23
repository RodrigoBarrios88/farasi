<?php
	include_once('html_fns_revision.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

	$categoriasIn = $_SESSION["categorias_in"];
	//$_POST
	$ClsLis = new ClsLista();
	$ClsRev = new ClsRevision();
	$hashkey1 = $_REQUEST["hashkey1"];
	$hashkey2 = $_REQUEST["hashkey2"];
	$hashkey3 = $_REQUEST["hashkey3"];
	$codigo_lista = $ClsLis->decrypt($hashkey1, $usuario);
	$codigo_progra = $ClsLis->decrypt($hashkey2, $usuario);
	$revision = $ClsLis->decrypt($hashkey3, $usuario);
	$revision = ($revision == "")?0:$revision;
	$fecha = date("d/m/Y");
	//--
	$result = $ClsRev->get_revision($revision,'','','','','','',$fecha,$fecha,1);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$revision = trim($row["rev_codigo"]);
			$codigo_lista = trim($row["list_codigo"]);
			$sede = utf8_decode($row["sed_nombre"]);
			$sector = utf8_decode($row["sec_nombre"]);
			$area = utf8_decode($row["are_nombre"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$nombre = utf8_decode($row["list_nombre"]);
			//--
			$requiere_firma = trim($row["list_firma"]);
			$requiere_fotos = trim($row["list_fotos"]);
			//--
			$strFirma = trim($row["rev_firma"]);
		}
		/////////// PROGRAMACION /////
		// probar con programacion semanal
		$result = $ClsLis->get_programacion($codigo_progra,'','','','','',date("N"),date("H:i"));
		// probar con programacion mensual
		if(!is_array($result)) $result = $ClsLis->get_programacion($codigo_progra,'','','','','',date("J"),date("H:i"));
		if(is_array($result)){
			$i = 0;	
			foreach ($result as $row){
				$hini = trim($row["pro_hini"]);
				$hfin = trim($row["pro_hfin"]);
				$horario = "$hini - $hfin";
				$observacion = utf8_decode($row["pro_observaciones"]);
				$observacion = nl2br($observacion);
			}
		}else{
			$alerta_completa = 'swal("Alto", "Este formulario de revisi\u00F3n esta fuera de horario...", "warning").then((value)=>{ window.history.back(); });';
		}
	}else{
		// probar con programacion semanal
		$result = $ClsLis->get_programacion($codigo_progra,'','','','','',date("N"),date("H:i"));
		// probar con programacion mensual
		if(!is_array($result)) $result = $ClsLis->get_programacion($codigo_progra,'','','','','',date("J"),date("H:i"));
		if(is_array($result)){
			foreach ($result as $row){
				$codigo_lista = $row["list_codigo"];
				$sede = utf8_decode($row["sed_nombre"]);
				$sector = utf8_decode($row["sec_nombre"]);
				$area = utf8_decode($row["are_nombre"]);
				$categoria = utf8_decode($row["cat_nombre"]);
				$nombre = utf8_decode($row["list_nombre"]);
				//--
				$requiere_firma = trim($row["list_firma"]);
				$requiere_fotos = trim($row["list_fotos"]);
				//--
				$hini = trim($row["pro_hini"]);
				$hfin = trim($row["pro_hfin"]);
				$horario = "$hini - $hfin";
				$observacion = utf8_decode($row["pro_observaciones"]);
				$observacion = nl2br($observacion);
			}
			$revision = $ClsRev->max_revision();
			$revision++; /// Maximo codigo de Lista
			$usuario = $_SESSION["codigo"];
			$sql = $ClsRev->insert_revision($revision,$codigo_lista,$codigo_progra,$usuario,'');
			$rs = $ClsRev->exec_sql($sql);
			if($rs == 1){
				$usuario = $_SESSION["codigo"];
				$hashkey3 = $ClsRev->encrypt($revision, $usuario);
				$alerta_completa = 'swal("Apertura de Checklist", "Se ha aperturado una nueva revisi\u00F3n en esta lista...", "success").then((value)=>{ Submit(); });';
			}else{
				$alerta_completa = 'swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ window.history.back(); });';
			}
		}else{
			$alerta_completa = 'swal("Alto", "Este formulario de revisi\u00F3n esta fuera de horario...", "warning").then((value)=>{ window.history.back(); });';
		}
	}if(file_exists('../../CONFIG/Fotos/FIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
		$strFirma = 'Fotos/FIRMAS/'.$strFirma.'.jpg';
	}else{
		$strFirma = "img/imageSign.jpg";
	}$result = $ClsRev->get_fotos('',$revision);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$strFoto = trim($row["fot_foto"]);
		}
		if(file_exists('../../CONFIG/Fotos/REVISION/'.$strFoto.'.jpg') && $strFoto != ""){
		   $strFoto = 'Fotos/REVISION/'.$strFoto.'.jpg';
		}else{
		   $strFoto = "img/imagePhoto.jpg";
		}
	}else{
		$strFoto = "img/imagePhoto.jpg";
	}
	
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo head("../"); ?>

</head>
<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../","checklist"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-pin-3"></i> Ubicaci&oacute;n</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left"><a class="btn btn-white" href="FRMejecutar.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a> </div>
								</div>
								<div class="row">
									<div class="col-lg-12" id = "result">
										<div class="row">
											<div class="col-md-12">
												<label>Sede:</label> 
												<input type = "text" class="form-control"  value="<?php echo $sede; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Sector:</label>
												<input type = "text" class="form-control"  value="<?php echo $sector; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>&Aacute;rea:</label><br>
												<input type = "text" class="form-control"  value="<?php echo $area; ?>" disabled />
											</div>
										</div>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-12 col-md-12 text-right"><a type="button" class="btn btn-white" href="FRMejecutar.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a> </div>
								</div>
								<div class="row">
									<div class="col-lg-12" id = "result">
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label> 
												<input type = "text" class="form-control"  value="<?php echo $categoria; ?>" disabled />
												<input type="hidden" id="revision" name="revision" value="<?php echo $revision; ?>" />
												<input type="hidden" id="reqfoto" name="reqfoto" value="<?php echo $requiere_fotos; ?>" />
												<input type="hidden" id="reqfirma" name="reqfirma" value="<?php echo $requiere_firma; ?>" />
												<form name="f1" id="f1">
													<input type="hidden" id="hashkey1" name="hashkey1" value="<?php echo $hashkey1; ?>" />
													<input type="hidden" id="hashkey2" name="hashkey2" value="<?php echo $hashkey2; ?>" />
													<input type="hidden" id="hashkey3" name="hashkey3" value="<?php echo $hashkey3; ?>" />
												</form>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Lista:</label>
												<input type = "text" class="form-control"  value="<?php echo $nombre; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Horario de Ejecuci&oacute;n:</label><br>
												<input type = "text" class="form-control"  value="<?php echo $horario; ?>" disabled />
											</div>
										</div>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones de Programaci&oacute;n:</label><br>
										<textarea class="form-control text-justify" rows="4" readonly ><?php echo $observacion; ?></textarea>
									</div>
								</div>
								<br>
								<?php
								//echo "$revision,$codigo_lista";
									$result = $ClsLis->get_pregunta('',$codigo_lista,'',1) ;
									if(is_array($result)){
										$i = 1;	
										foreach ($result as $row){
											$respuesta = "";
											$pregunta_codigo = $row["pre_codigo"];
											$pregunta = utf8_decode($row["pre_pregunta"]);
											$pregunta = nl2br($pregunta)."";
											//--
											$respuesta = "";
											$result_respuesta = $ClsRev->get_respuesta($revision,$codigo_lista,$pregunta_codigo);
											if(is_array($result_respuesta)){
												foreach ($result_respuesta as $row_respuesta){
													$respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
												}	
											}	
											if($respuesta == 1){
												$respSI = "active";
												$respNO = "";
												$respNA = "";
											}else if($respuesta == 2){
												$respSI = "";
												$respNO = "active";
												$respNA = "";
											}else if($respuesta == 3){
												$respSI = "";
												$respNO = "";
												$respNA = "active";
											}else{
												$respSI = "";
												$respNO = "";
												$respNA = "";
											}
											$salida="";///limpia la cadena por cada vuelta
											$salida.='<div class="btn-group btn-group-toggle" data-toggle="buttons">';
												$salida.='<label class="btn btn-white '.$respSI.'" onclick="responder('.$revision.','.$codigo_lista.','.$pregunta_codigo.',1);">';
												$salida.='<input type="radio" name="options" id="optSI'.$i.'" autocomplete="off"> <i class="fa fa-check"></i> Si';
												$salida.='</label>';
											//--
												$salida.='<label class="btn btn-white '.$respNA.'" onclick="responder('.$revision.','.$codigo_lista.','.$pregunta_codigo.',3);">';
												$salida.='<input type="radio" name="options" id="optNA'.$i.'" autocomplete="off"> No Aplica';
												$salida.='</label>';
											//--
												$salida.='<label class="btn btn-white '.$respNO.'" onclick="responder('.$revision.','.$codigo_lista.','.$pregunta_codigo.',2);">';
												$salida.='<input type="radio" name="options" id="optNO'.$i.'" autocomplete="off"> No <i class="fa fa-times"></i>';
												$salida.='</label>';
											$salida.='</div>';
											
								?>
									<div class="row">
										<div class="col-md-1 text-right"><strong><?php echo $i; ?>.</strong></div>
										<div class="col-md-10">
											<p class="text-justify"><?php echo $pregunta; ?></p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-10 ml-auto mr-auto"><?php echo $salida; ?></div>
									</div>
									<br>
								<?php
											$i++;
										}
									}else{
										
									}
								?>
								<hr>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones:</label>
										<textarea class="form-control" name = "observacion" id = "observacion" onkeyup = "textoLargo(this);" rows="5" ></textarea>
									</div>
								</div>
								<br>
								<?php
									if($requiere_firma > 0 ||  $requiere_fotos > 0){
								?>
								<div class="row">
									<div class="col-md-3"></div>
									<?php
										if($requiere_firma > 0){
									?>
									<div class="col-md-3">
										<div class="fileinput fileinput-new text-center">
											<div class="fileinput-new thumbnail">
												<img src="../../CONFIG/<?php echo $strFirma; ?>" alt="...">
											</div>
											<div>
												<a class="btn btn-rose btn-round btn-file" href="FRMfirma.php?revision=<?php echo $revision; ?>">
													<span class="fileinput-new"><i class="fas fa-signature"></i> Agregar Firma</span>
												</a>
											</div>
										</div>
									</div>
									<?php
										}else{
											echo '<div class="col-md-3"></div>';
										}
									?>
									<?php
										if($requiere_fotos > 0){
									?>
									<div class="col-md-3">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail" id="div-imagen">
												<img src="../../CONFIG/<?php echo $strFoto; ?>" alt="...">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div>
												<input type="file" name="foto" id="foto" class = "hidden" onchange="cargarFoto();" />
												<button type="button" class="btn btn-rose btn-round btn-file" onclick="FotoJs();" id="btn-foto" >
													<i class="fa fa-camera"></i> Agregar Imagen 
												</button>
											</div>
										</div>
									</div>
									<?php
										}else{
											echo '<div class="col-md-3"></div>';
										}
									?>
									<div class="col-md-3"></div>
								</div>
								<?php
									}
								?>
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<a href="FRMejecutar.php" class="btn btn-default btn-lg" ><span class="fa fa-chevron-left"></span> Regresar</a>
										<button type="button" class="btn btn-success btn-lg" id = "btn-grabar" onclick = "cerrarRevision();"><span class="fa fa-folder"></span> Cerrar</button>
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
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/checklist/revision.js"></script>
	
    	<script>
		$(document).ready(function(){
            $('.select2').select2({ width: '100%' });
			
			window.setTimeout('mensaje(<?php echo $status; ?>);', 500);
        });
		
		function mensaje() {
			<?php echo $alerta_completa; ?>
		}
    </script>

</body>
</html>
