<?php
	include_once('html_fns_ejecucion.php');
	//$_POST
	$ClsEnc = new ClsEncuesta();
	$ClsRes = new ClsEncuestaResolucion();
	$hashkey = $_REQUEST["hashkey"];
	$codigo_invitacion = $ClsEnc->decryptt($hashkey);
	$result = $ClsEnc->get_invitacion($codigo_invitacion,'');
	if(is_array($result)){
		foreach ($result as $row){
			$codigo_cuestionario = trim($row["cue_codigo"]);
			$ejecucion = trim($row["ejecucion_activa"]);
			$cliente = utf8_decode($row["inv_cliente"]);
			$correo = utf8_decode($row["inv_correo"]);
			$url = utf8_decode($row["inv_url"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$titulo = utf8_decode($row["cue_titulo"]);
			$descripcion = utf8_decode($row["cue_descripcion"]);
			$descripcion = nl2br($descripcion);
			//
			$usuario_nombre = utf8_decode($row["usuario_nombre"]);
			$fecha_invitacion = cambia_fechaHora($row["inv_fecha_registro"]);
		}	
	}else{
		echo '<html>';
		echo '<head>';
		echo '<link href="../assets.1.2.8/css/bootstrap.min.css" rel="stylesheet" />';
		echo '<link href="../assets.1.2.8/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />';
		echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
		echo '</head>';
		echo '<body>';
		echo '<script>swal("Ohho..", "Ya no existe esta encuesta o el enlace est\u00E1 roto..., \npor favor cont\u00E1cte a la persoa que se lo envi\u00F3...", "error").then((value)=>{ window.history.back(); });</script>';
		echo '</body>';
		echo '</html>';
		die;
	}if($ejecucion != ""){
		$result = $ClsRes->get_ejecucion($ejecucion);
		if(is_array($result)){
			$i = 0;	
			foreach ($result as $row){
				$ejecucion = trim($row["eje_codigo"]);
				$codigo_cuestionario = trim($row["eje_encuesta"]);
				$codigo_invitacion = trim($row["eje_invitacion"]);
				$cliente = utf8_decode($row["inv_cliente"]);
				$correo = utf8_decode($row["inv_correo"]);
				$categoria = utf8_decode($row["cat_nombre"]);
				$titulo = utf8_decode($row["cue_titulo"]);
				//
				$respondio = utf8_decode($row["eje_respondio"]);
				$EjeCorreo = trim($row["eje_correo"]);
				$EjeTelefono = trim($row["eje_telefono"]);
				$EjeObservacion = utf8_decode($row["eje_observaciones"]);
				//--
				$situacion = trim($row["eje_situacion"]);
			}
		}
	} else {
		$ejecucion = $ClsRes->max_ejecucion();
		$ejecucion++;
	}
	//////////// OBTIENE GEOPOSICIONAMIENTO //////////////
	$arr_ubicacion = write_visita();
	//print_r($arr_ubicacion);
	$ip = $arr_ubicacion["ip"];
	$ciudad = $arr_ubicacion["ciudad"];
	$region = $arr_ubicacion["region"];$sql = $ClsRes->insert_ejecucion($ejecucion,$codigo_cuestionario,$codigo_invitacion,$ip,$region,$ciudad);
	$rs = $ClsRes->exec_sql($sql);
	
if($codigo_invitacion != ""){
?>
<!DOCTYPE html>
<html>
<head>
   <?php echo head("../"); ?>
	
</head>
<body class="sidebar-mini">
	<div class="wrapper ">
		<div class="sidebar" data-color="brown" data-active-color="danger">
			<!-- Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow" -->
			<div class="logo">
				<a href="./?hashkey=<?php echo $hashkey; ?>" class="simple-text logo-mini">
					<div class="logo-image-small">
						<img src="../../CONFIG/img/logo2.png" />
					</div>
				</a>
				<a href="../menu.php" class="simple-text logo-normal">
					BPManagement
				</a>
			</div>
			<div class="sidebar-wrapper">
				
			</div>
		</div>
		<div class="main-panel">
			
			<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<div class="navbar-minimize">
							<button id="minimizeSidebar" class="btn btn-icon btn-round">
								<i class="fas fa-bars text-center visible-on-sidebar-mini"></i>
								<i class="fas fa-bars text-center visible-on-sidebar-regular"></i>
							</button>
						</div>
						<div class="navbar-toggle">
							<button type="button" class="navbar-toggler">
								<span class="navbar-toggler-bar bar1"></span>
								<span class="navbar-toggler-bar bar2"></span>
								<span class="navbar-toggler-bar bar3"></span>
							</button>
						</div>
						<a class="navbar-brand" href="javascript:void(0);"><?php //echo menu_aplicaciones('../'); ?></a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end" id="navigation">
						<ul class="navbar-nav">
							<?php //echo menu_navigation_top('../'); ?>
						</ul>
					</div>
				</div>
			</nav>
			
			
			<div class="content">
				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-clipboard-list"></i> <?php echo $titulo; ?></h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id = "result">
										<div class="row">
											<div class="col-md-12">
												<label>Cliente:</label> 
												<input type = "text" class="form-control"  value="<?php echo $cliente; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Correo de Invitaci&oacute;n:</label> 
												<input type = "text" class="form-control"  value="<?php echo $correo; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Invitaci&oacute;n #:</label>
												<input type = "text" class="form-control"  value="<?php echo Agrega_Ceros($codigo_invitacion); ?>" disabled />
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
								<h5 class="card-title"><i class="fas fa-tags"></i> <?php echo $categoria; ?></h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id = "result">
										<div class="row">
											<div class="col-md-12">
												<label>Ubicaci&oacute;n:</label> 
												<input type = "text" class="form-control"  value="<?php echo "$region, $ciudad"; ?>" disabled />
												<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
												<input type="hidden" id="encuesta" name="encuesta" value="<?php echo $codigo_cuestionario; ?>" />
												<input type="hidden" id="invitacion" name="invitacion" value="<?php echo $codigo_invitacion; ?>" />
												<input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de generaci&oacute;n:</label><br>
												<input type = "text" class="form-control"  value="<?php echo $fecha_invitacion; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Generada por:</label>
												<input type = "text" class="form-control"  value="<?php echo $usuario_nombre; ?>" disabled />
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
										<label>Descrip&oacute;n del Cuestionario:</label><br>
										<p class="text-justify"><?php echo $descripcion; ?></p>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
				
				<?php
				$result_seccion = $ClsEnc->get_secciones('',$codigo_cuestionario,1);
				if(is_array($result_seccion)){
					$i = 1;	
					foreach ($result_seccion as $row_seccion){
						$seccion_codigo = $row_seccion["sec_codigo"];
						$titulo = trim($row_seccion["sec_numero"]).". ".utf8_decode($row_seccion["sec_titulo"]);
						$proposito = utf8_decode($row_seccion["sec_proposito"]);
						$proposito = nl2br($proposito);
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><?php echo $titulo; ?></h5>
							</div>
							<div class="card-body all-icons">		
								<?php
									$result = $ClsEnc->get_pregunta('',$codigo_cuestionario,$seccion_codigo,1) ;
									if(is_array($result)){
										$i = 1;	
										foreach ($result as $row){
											$pregunta_codigo = $row["pre_codigo"];
											$pregunta_tipo = $row["pre_tipo"];
											$peso = $row["pre_peso"];
											$pregunta = utf8_decode($row["pre_pregunta"]);
											$pregunta = nl2br($pregunta);
											//--
											$respuesta = '0';
											$observacion = '';
											$result_respuesta = $ClsRes->get_respuesta($ejecucion,$codigo_cuestionario,$pregunta_codigo);
											if(is_array($result_respuesta)){
												foreach ($result_respuesta as $row_respuesta){
													$respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
													$observacion = utf8_decode($row_respuesta["resp_observacion"]);
												}
											}	
											$salida="";
											if($pregunta_tipo == 1){
												$salida.='<div class="form-group">';
													$salida.='<select class ="form-control" name ="combo'.$pregunta_codigo.'" id ="combo'.$pregunta_codigo.'" onchange = "responderPonderacion(\''.$codigo_cuestionario.'\',\''.$pregunta_codigo.'\',\''.$ejecucion.'\',\''.$seccion_codigo.'\',1,this.value);" >';
														$salida.='<option value="0">Seleccione</option>';
														$salida.='<option value="1">1</option>';
														$salida.='<option value="2">2</option>';
														$salida.='<option value="3">3</option>';
														$salida.='<option value="4">4</option>';
														$salida.='<option value="5">5</option>';
														$salida.='<option value="6">6</option>';
														$salida.='<option value="7">7</option>';
														$salida.='<option value="8">8</option>';
														$salida.='<option value="9">9</option>';
														$salida.='<option value="10">10</option>';
													$salida.='</select>';
												$salida.='</div>';
												$salida.='<script>';
													$salida.='document.getElementById("combo'.$pregunta_codigo.'").value = "'.$respuesta.'";';
													if($aplica == 2){ // si no aplica deshabilita
														$salida.='document.getElementById("combo'.$pregunta_codigo.'").setAttribute("disabled", "disabled");';
													}
												$salida.='</script>';
											}else if($pregunta_tipo == 2){
												if($respuesta == 1){
													$respSI = "active";
													$respNO = "";
												}else if($respuesta == 2){
													$respSI = "";
													$respNO = "active";
												}else{
													$respSI = "";
													$respNO = "";
												}
												
												$salida="";///limpia la cadena por cada vuelta
												$salida.='<div class="btn-group btn-group-toggle" data-toggle="buttons" >';
													$salida.='<label class="btn btn-white '.$respSI.'" id="labelSI'.$pregunta_codigo.'" '.$disabled.' onclick="responderPonderacion(\''.$codigo_cuestionario.'\',\''.$pregunta_codigo.'\',\''.$ejecucion.'\',\''.$seccion_codigo.'\',2,1);">';
													$salida.='<input type="radio" name="options" id="optSI'.$pregunta_codigo.'" autocomplete="off"> <i class="fa fa-check"></i> Si';
													$salida.='</label>';
												//--
													$salida.='<label class="btn btn-white '.$respNO.'" id="labelNO'.$pregunta_codigo.'" '.$disabled.' onclick="responderPonderacion(\''.$codigo_cuestionario.'\',\''.$pregunta_codigo.'\',\''.$ejecucion.'\',\''.$seccion_codigo.'\',2,2);">';
													$salida.='<input type="radio" name="options" id="optNO'.$pregunta_codigo.'" autocomplete="off"> No <i class="fa fa-times"></i>';
													$salida.='</label>';
												$salida.='</div>';
											
											}
								?>
									<div class="row">
										<div class="col-xs-2 col-md-1 text-center"><strong><?php echo $i; ?>.</strong></div>
										<div class="col-xs-10 col-md-10">
											<p class="text-justify"><?php echo $pregunta.""; ?></p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-1 col-xs-1"></div>
										<div class="col-md-11 col-xs-11 text-left">
											<div class="row">
												<div class="col-md-12 col-xs-12">
													<?php echo $salida; ?>
													<input type="hidden" name = "respuesta<?php echo $pregunta_codigo; ?>" id = "respuesta<?php echo $pregunta_codigo; ?>" value="<?php echo $respuesta; ?>" />
													<input type="hidden" name = "peso<?php echo $pregunta_codigo; ?>" id = "peso<?php echo $pregunta_codigo; ?>" value="<?php echo $peso; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 col-xs-12">
													<label>Amplienos su respuesta si desea:</label>
													<textarea class = "form-control" name = "observacion<?php echo $pregunta_codigo; ?>" id = "observacion<?php echo $pregunta_codigo; ?>"  onkeyup = "texto(this)"  onblur = "responderTexto('<?php echo $codigo_cuestionario; ?>','<?php echo $pregunta_codigo; ?>','<?php echo $ejecucion; ?>','<?php echo $seccion_codigo; ?>',this.value);" <?php echo $disabled; ?> ><?php echo $observacion; ?></textarea>                  
													<br>
												</div>
											</div>
										</div>
									</div>
									<br>
								<?php
											$i++;
										}
									}else{
										
									}
								?>
								
							</div>
						</div>
					</div>
				</div>
				<?php
					}
				}
				?>
			
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-briefcase-24"></i> Ya terminamos! gracias por tus respuestas...</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Nombre de qui&eacute;n contesta: <span class="text-danger">*</span></label>
										<input type="text" class="form-control" name = "responsable" id = "responsable" value="<?php echo $respondio; ?>" onkeyup = "texto(this);" onblur="ejecucionCampos(1,this.value);" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-1"></div>
									<div class="col-md-5">
										<label>Correo electr&oacute;nico: <span class="text-danger">*</span> </label>
										<input type="text" class="form-control" name = "correo" id = "correo" value="<?php echo $EjeCorreo; ?>" onkeyup = "texto(this);" onblur="ejecucionCampos(2,this.value);" />
									</div>
									<div class="col-md-5">
										<label>T&eacute;lefono: </label>
										<input type="text" class="form-control" name = "telefono" id = "telefono" value="<?php echo $EjeTelefono; ?>" onkeyup = "enteros(this);" onblur="ejecucionCampos(3,this.value);" />
									</div>
									<div class="col-md-1"></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<h5>&iquest;Tienes alg&uacute;n comentario o sugerencia general?:</h5>
										<textarea class="form-control" name = "observaciones" id = "observaciones" onkeyup = "textoLargo(this);" onblur="ejecucionCampos(4,this.value);" rows="5" ><?php echo $EjeObservacion; ?></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<button type="button" class="btn btn-success btn-lg" id = "btn-cerrar" onclick = "cerrarEjecucion();"><i class="fa fa-folder"></i> Cerrar</button>
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
	
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/encuestas/ejecucion.js"></script>    
	<script>
		$(document).ready(function(){
			$('.select2').select2({ width: '100%' });
        });
		
    </script>

</body>
</html>
<?php
}else{
	echo '<html>';
	echo '<head>';
	echo '<link href="../assets.1.2.8/css/bootstrap.min.css" rel="stylesheet" />';
	echo '<link href="../assets.1.2.8/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />';
	echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
	echo '</head>';
	echo '<body>';
	echo '<script>swal("Ohho..", "Este enlace est\u00E1 roto..., por favor cont\u00E1cte a la persoa que se lo envi\u00F3...", "error").then((value)=>{ window.history.back(); });</script>';
	echo '</body>';
	echo '</html>';
	die;
}?>