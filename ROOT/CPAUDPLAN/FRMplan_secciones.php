<?php
	include_once('html_fns_ejecucion.php');
	validate_login("../");
$usuario = $_SESSION["codigo"];

	$categoriasIn = $_SESSION["categorias_in"];
	$sedes_IN = $_SESSION["sedes_in"];
	//$_POST
	$ClsAud = new ClsAuditoria();
	$ClsEje = new ClsEjecucion();
	$ClsPla = new ClsPlan();
	$seccion = $_REQUEST["seccion"];
	$hashkey = $_REQUEST["hashkey"];
	$ejecucion = $ClsAud->decrypt($hashkey, $usuario);
	//--
	$result = $ClsEje->get_ejecucion($ejecucion,'','');
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$ejecucion = trim($row["eje_codigo"]);
			$codigo_audit = trim($row["audit_codigo"]);
			$codigo_progra = trim($row["pro_codigo"]);
			$codigo_sede = utf8_decode($row["sed_codigo"]);
			$sede = utf8_decode($row["sed_nombre"]);
			$direccion = utf8_decode($row["sed_direccion"]).", ".utf8_decode($row["sede_municipio"]);
			$departamento = utf8_decode($row["dep_nombre"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$nombre = utf8_decode($row["audit_nombre"]);
			$usuario_nombre = utf8_decode($row["usuario_nombre"]);
			$strFirma = trim($row["eje_firma"]);
			//--
			$fecha_inicio = trim($row["eje_fecha_inicio"]);
			$fecha_inicio = cambia_fechaHora($fecha_inicio);
			$fecha_inicio = substr($fecha_inicio,0,16);
			//--
			$fecha_finaliza = trim($row["eje_fecha_final"]);
			$fecha_finaliza = cambia_fechaHora($fecha_finaliza);
			$fecha_finaliza = substr($fecha_finaliza,0,16);
			//--
			$fecha_progra = trim($row["pro_fecha"]);
			$fecha_progra = cambia_fecha($fecha_progra);
			$hora_progra = substr($row["pro_hora"],0,5);
			$fecha_progra = "$fecha_progra $hora_progra";
			$obs = utf8_decode($row["pro_observaciones"]);
			//
			$ejeobs = utf8_decode($row["eje_observaciones"]);
			$responsable = utf8_decode($row["eje_responsable"]);
		}
	}$result = $ClsPla->get_plan($ejecucion,'','');
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$observaciones = utf8_decode($row["pla_observaciones"]);
			$situacion = trim($row["pla_situacion"]);
			$ultima_actualizacion = cambia_fechaHora($row["pla_fecha_update"]);
		}
		$situacion = ($situacion == 1)?"En edici&oacute;n":"Finalizado";
		$usuario = $_SESSION["codigo"];
	}$result = $ClsAud->get_secciones($seccion,$codigo_audit,1);
	if(is_array($result)){
		$i = 1;	
		foreach ($result as $row){
			$seccion_codigo = $row["sec_codigo"];
			$titulo = trim($row["sec_numero"]).". ".utf8_decode($row["sec_titulo"]);
			$proposito = utf8_decode($row["sec_proposito"]);
			$proposito = nl2br($proposito);
		}
	}

	
?>
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
							<div class="card-body all-icons">
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<a class="btn btn-white btn-lg" href = "FRMplan.php?hashkey=<?php echo $hashkey; ?>"><i class="fa fa-chevron-left"></i> Regresar</a>
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
							<div class="card-header">
								<h5 class="card-title"><?php echo $titulo; ?></h5>
							</div>
							<div class="card-body all-icons">		
								<?php
									$result = $ClsAud->get_pregunta('',$codigo_audit,$seccion_codigo,1) ;
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
											$aplicaDisabled = '';
											$aplica_desc = 'Aplica';
											$aplica = '';
											$result_respuesta = $ClsEje->get_respuesta($ejecucion,$codigo_audit,$pregunta_codigo);
											if(is_array($result_respuesta)){
												foreach ($result_respuesta as $row_respuesta){
													$aplica = utf8_decode($row_respuesta["resp_aplica"]);
													$respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
													$observacion = utf8_decode($row_respuesta["resp_observacion"]);
												}
												$aplicaDisabled = ($aplica == 1)?"":"disabled";
												$aplica_desc = ($aplica == 1)?'<i class="fa fa-check"></i> Aplica':'<i class="fa fa-times"></i> No Aplica';
											}	
											//---
											$fecha = "";
											$solucion = "";
											$responsable = "";
											$status = "";
											$result_plan = $ClsPla->get_solucion($ejecucion,$codigo_audit,$pregunta_codigo);
											if(is_array($result_plan)){
												foreach ($result_plan as $row_plan){
													$fecha = cambia_fecha($row_plan["sol_fecha"]);
													$solucion = utf8_decode($row_plan["sol_solucion"]);
													$responsable = utf8_decode($row_plan["sol_responsable"]);
													$status = utf8_decode($row_plan["sta_nombre"]);
													$solucionado = cambia_fechaHora($row_plan["sol_fecha_solucion"]);
												}
											}else{
												$status = "Pendiente";
												$responsable = "";
											}
											//--
											$salida="";
											if($pregunta_tipo == 1){
												$salida.='<div class="form-group">';
													$salida.='<input type="text" class = "form-control text-center" value="'.$respuesta.'" disabled />';
												$salida.='</div>';
											}else if($pregunta_tipo == 2){
												switch($respuesta){
													case 1: $elemento = 'SI - '.$peso.' pts.'; break;
													case 2: $elemento = 'NO'; break;
													default: $elemento = '-'; break;
												}
												$salida.='<div class="form-group">';
													$salida.='<input type="text" class = "form-control text-center" value="'.$elemento.'" disabled />';
												$salida.='</div>';
											}else if($pregunta_tipo == 3){
												switch($respuesta){
													case 1: $elemento = 'SATISFACTORIO'; break;
													case 2: $elemento = 'NO SATISFACTORIO'; break;
													default: $elemento = '-'; break;
												}
												$salida.='<div class="form-group">';
													$salida.='<input type="text" class = "form-control text-center" value="'.$elemento.'" disabled />';
												$salida.='</div>';
											}
											//////// IMAGENES ///////
											$result = $ClsPla->get_fotos('',$ejecucion,$codigo_audit,$pregunta_codigo);
											$strFoto = "";
											$foto = "";
											if(is_array($result)){
											    foreach ($result as $row){
													$fotCodigo = trim($row["fot_codigo"]);
													$foto = trim($row["fot_foto"]);
													if(file_exists('../../CONFIG/Fotos/SOLUCION/'.$foto.'.jpg') || $foto != ""){
														$strFoto.= '<img onclick="menuFoto('.$fotCodigo.','.$codigo_audit.','.$pregunta_codigo.','.$ejecucion.');" class="img-upload" src="../../CONFIG/Fotos/SOLUCION/'.$foto.'.jpg" alt="...">';
													}else{
														$strFoto.= '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="...">';
													}
												}	
											}else{
												$strFoto = '<img class="img-demo" src="../../CONFIG/img/imagePhoto.jpg" alt="...">';
											}
											
											$fecha = ($fecha == "")?date("d/m/Y"):$fecha;
											
								?>
									<div class="row">
										<div class="col-sm-1 col-xs-2 text-center"><strong><?php echo $i; ?>.</strong></div>
										<div class="col-sm-11 col-xs-10">
											<p class="text-justify"><?php echo $pregunta.""; ?></p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-8 col-xs-7">
													<label>Nota / hallazgo:</label>
													<?php echo $salida; ?>
												</div>
												<div class="col-md-4 col-xs-5 text-right">
													<label>&nbsp;</label><br>
													<strong><?php echo $aplica_desc; ?></strong>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<br>
													<button type="button" class="btn btn-white btn-block" onclick="imagenRespuesta(<?php echo $codigo_audit; ?>,<?php echo $pregunta_codigo; ?>,<?php echo $ejecucion; ?>);" <?php echo $aplicaDisabled; ?> ><i class="fa fa-search"></i> <i class="fa fa-camera"></i> Ver Fotos</button>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Hallazgo y/o observaciones:</label>
													<textarea class = "form-control" rows="5" disabled ><?php echo $observacion; ?></textarea>    
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<label>Responsable:</label> <span class="text-danger">*</span>
													<?php echo utf8_decode(usuarios_sedes_html("responsable$pregunta_codigo",$sedes_IN,"responderResponsable('$codigo_audit','$pregunta_codigo','$ejecucion',this.value);","select2")); ?>
													<script>
														document.getElementById("responsable<?php echo $pregunta_codigo; ?>").value = '<?php echo $responsable; ?>';
													</script>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Pr&oacute;xima Auditor&iacute;a:</label> <span class="text-danger">*</span>
													<div class="form-group">
														<div class="input-group date">
															<input type="text" class="form-control" name="fecha<?php echo $pregunta_codigo; ?>" id="fecha<?php echo $pregunta_codigo; ?>" value="<?php echo $fecha; ?>" onblur="window.setTimeout('responderFecha(<?php echo $codigo_audit; ?>,<?php echo $pregunta_codigo; ?>,<?php echo $ejecucion; ?>,<?php echo $pregunta_codigo; ?>)',500);" >                      
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Instruciones y/o observaciones:</label>
													<textarea class = "form-control" rows="5" name = "solucion<?php echo $pregunta_codigo; ?>" id = "solucion<?php echo $pregunta_codigo; ?>" onkeyup="texto(this)" onblur="responderTexto('<?php echo $codigo_audit; ?>','<?php echo $pregunta_codigo; ?>','<?php echo $ejecucion; ?>',this.value);" ><?php echo $solucion; ?></textarea>                  
												</div>
											</div>
											<!--div class="row">
												<div class="col-md-7 col-xs-7">
													<label>Status:</label> <span class="text-danger">*</span>
													<div class="form-group">
														<div class="input-group">
															<input type="text" class="form-control text-center" value="<?php echo $status; ?>" readonly onclick="responderSituacion('<?php echo $codigo_audit; ?>','<?php echo $pregunta_codigo; ?>','<?php echo $ejecucion; ?>');" />                      
															<a href="javascript:void(0);" onclick="responderSituacion('<?php echo $codigo_audit; ?>','<?php echo $pregunta_codigo; ?>','<?php echo $ejecucion; ?>');" class="input-group-addon" title="cambiar status">| <i class="fa fa-check-circle-o text-success"></i></a>
															<a href="../CPTICKET/FRMnewticket.php?sede=<?php echo $codigo_sede; ?>&desc=<?php echo $observacion; ?>" target = "_blank" class="input-group-addon" title="crear ticket"><i class="fa fa-exclamation-circle text-warning"></i></a>
														</div>
													</div>
												</div>
												<div class="col-md-5 col-xs-5">
													<label>Status Fecha:</label> <span class="text-danger">*</span>
													<input type="text" class="form-control" name="solucionado<?php echo $pregunta_codigo; ?>" id="solucionado<?php echo $pregunta_codigo; ?>" value="<?php echo $solucionado; ?>" readonly >                      
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-center">
													<div class="fileinput fileinput-new text-center" data-provides="fileinput">
														<div class="fileinput fileinput-new text-center" data-provides="fileinput">
															<div class="text-center" id="foto<?php echo $pregunta_codigo; ?>">
																<?php echo $strFoto; ?>
															</div>
															<span class="btn btn-rose btn-round btn-file">
																<span class="fileinput-new" onclick="FotoJs(<?php echo $pregunta_codigo; ?>);" ><i class="fa fa-camera"></i> Agregar Imagen</span>
															</span>
														</div>
													</div>
												</div>
											</div-->
										</div>
									</div>
									<hr>
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
				
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-body all-icons">
								<br>
								<div class="row">
									<form action="EXEcarga_foto_solucion.php" name = "f1" name = "f1" method="post" enctype="multipart/form-data">
										<input id="imagen" name="imagen" type="file" multiple="false" class = "hidden" onchange="uploadImage();" >
										<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
										<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo_audit; ?>" />
										<input type="hidden" id="programacion" name="programacion" value="<?php echo $codigo_progra; ?>" />
										<input type="hidden" id="pregunta" name="pregunta" />
									</form>
								</div>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<a class="btn btn-white btn-lg" href = "FRMplan.php?hashkey=<?php echo $hashkey; ?>"><i class="fa fa-chevron-left"></i> Regresar</a>
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
    
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/plan.js"></script>
    	<script>
		$(document).ready(function(){
			$('.dataTables-example').DataTable({
                pageLength: 100,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ]
            });
			
            $('.input-group.date').datepicker({
                format: 'dd/mm/yyyy',
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
			
            $('.select2').select2({ width: '100%' });
        });
    </script>

</body>
</html>
