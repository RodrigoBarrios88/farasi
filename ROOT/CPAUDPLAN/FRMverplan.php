<?php
	include_once('html_fns_ejecucion.php');
	validate_login("../");
$usuario = $_SESSION["codigo"];

	$categoriasIn = $_SESSION["categorias_in"];
	//$_POST
	$ClsAud = new ClsAuditoria();
	$ClsEje = new ClsEjecucion();
	$ClsPla = new ClsPlan();
	$ejecucion = $_REQUEST["plan"];
	//--
	$result = $ClsEje->get_ejecucion($ejecucion,'','');
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$ejecucion = trim($row["eje_codigo"]);
			$codigo_audit = trim($row["audit_codigo"]);
			$codigo_progra = trim($row["pro_codigo"]);
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
			///---
			$observaciones = utf8_decode($row["pla_observaciones"]);
			$situacion = trim($row["eje_situacion"]);
			$ultima_actualizacion = cambia_fechaHora($row["pla_fecha_update"]);
		}
		$situacion = ($situacion == 1)?"En edici&oacute;n":"Finalizado";
		$usuario = $_SESSION["codigo"];
	}else{
		$alerta_completa = 'swal("Error", "Na hay un plan de acci\u00F3n desarrollado a\u00FAn...", "error").then((value)=>{ window.history.back(); });';
	}
	$result = $ClsPla->get_plan($ejecucion,'','');
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$strFirma = trim($row["pla_firma"]);
			$tramiento_usuario = utf8_decode($row["pla_tratamiento"]);
			$nombre_usuario = utf8_decode($row["pla_nombre"]);
			$rol_usuario = utf8_decode($row["pla_rol"]);
			$observaciones = utf8_decode($row["pla_observaciones"]);
			$situacion = trim($row["pla_situacion"]);
			$ultima_actualizacion = cambia_fechaHora($row["pla_fecha_update"]);
		}
		$situacion = ($situacion == 1)?"En edici&oacute;n":"Finalizado";
		$usuario = $_SESSION["codigo"];
	}
	if(file_exists('../../CONFIG/Fotos/AUDFIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
		$strFirma = 'Fotos/AUDFIRMAS/'.$strFirma.'.jpg';
	}else{
		$strFirma = "img/imageSign.jpg";
	}$nombre_usuario = ($nombre_usuario == '')?$usuario_nombre:$nombre_usuario; //valida si coloca el nombre del usuario gestor o el nombre del responsable

	
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo head("../"); ?>
</head>
<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../","auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-pin-3"></i> Ubicaci&oacute;n
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<button type="button"  class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back();"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></button>
									</div>
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
												<label>Direcci&oacute;n:</label><br>
												<input type = "text" class="form-control"  value="<?php echo $direccion; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Departamento:</label>
												<input type = "text" class="form-control"  value="<?php echo $departamento; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Programaci&oacute;n:</label><br>
												<input type = "text" class="form-control"  value="<?php echo $fecha_progra; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Responsable o Evaluado: </label>
												<input type="text" class="form-control" value = "<?php echo $responsable; ?>" disabled />
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
								<h5 class="card-title">
									<i class="nc-icon nc-bullet-list-67"></i> Informe Final de Auditor&iacute;a
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<a class="btn btn-white btn-lg sin-margin pull-right" href="CPREPORTES/REPplan.php?ejecucion=<?php echo $ejecucion; ?>" target="_blank"><small><i class="fa fa-print"></i> Informe Final de Auditor&iacute;a</small></a>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12" id = "result">
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label> 
												<input type = "text" class="form-control"  value="<?php echo $categoria; ?>" disabled />
												<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
												<input type="hidden" id="reqfoto" name="reqfoto" value="<?php echo $requiere_fotos; ?>" />
												<input type="hidden" id="reqfirma" name="reqfirma" value="<?php echo $requiere_firma; ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Cuestionario de Auditor&iacute;a:</label>
												<input type = "text" class="form-control"  value="<?php echo $nombre; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Inicio:</label><br>
												<input type = "text" class="form-control"  value="<?php echo $fecha_inicio; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Finalizaci&oacute;n:</label><br>
												<input type = "text" class="form-control"  value="<?php echo $fecha_finaliza; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Usuario que registr&oacute;:</label><br>
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
									<div class="col-md-6">
										<label>Plan: </label>
										<input type="text" class="form-control" value = "<?php echo $situacion; ?>" disabled />
									</div>
									<div class="col-md-6">
										<label>&Uacute;ltima actualizaci&oacute;n: </label>
										<input type="text" class="form-control" value = "<?php echo $ultima_actualizacion; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones durante la programaci&oacute;n:</label><br>
										<textarea class="form-control text-justify" rows="3" disabled ><?php echo $obs; ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 ml-auto mr-auto">
										<label>Observaciones durante la auditor&iacute;a:</label>
										<textarea class="form-control" rows="3" disabled ><?php echo $ejeobs; ?></textarea>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
				
				<?php
				$result_seccion = $ClsAud->get_secciones('',$codigo_audit,1);
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
													$responsable = utf8_decode($row_plan["sol_responsable_nombre"]);
													$situacion = trim($row_plan["sol_situacion"]);
													$solucionado = cambia_fechaHora($row_plan["sol_fecha_solucion"]);
												}
												$status = ($situacion == 2)?"Solucionado":"Pendiente";
												$solucionado = ($situacion == 2)?$solucionado:"";
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
											$result = $ClsEje->get_fotos('',$ejecucion,$codigo_audit,$pregunta_codigo);
											$strImagen = "";
											$foto = "";
											if(is_array($result)){
											    foreach ($result as $row){
													$fotCodigo = trim($row["fot_codigo"]);
													$foto = trim($row["fot_foto"]);
													if(file_exists('../../CONFIG/Fotos/AUDITORIA/'.$foto.'.jpg') || $foto != ""){
														$strImagen.= '<img onclick="abrir();verFotoAuditoria('.$fotCodigo.','.$codigo_audit.','.$pregunta_codigo.','.$ejecucion.');" class="img-upload" src="../../CONFIG/Fotos/AUDITORIA/'.$foto.'.jpg" alt="...">';
													}else{
														$strImagen.= '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="...">';
													}
												}	
											}else{
												$strImagen = '<img class="img-demo" src="../../CONFIG/img/imagePhoto.jpg" alt="...">';
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
														$strFoto.= '<img onclick="abrir();verFoto('.$fotCodigo.','.$codigo_audit.','.$pregunta_codigo.','.$ejecucion.');" class="img-upload" src="../../CONFIG/Fotos/SOLUCION/'.$foto.'.jpg" alt="...">';
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
													<label>Hallazgo y/o observaciones:</label>
													<textarea rows="5" class = "form-control" disabled ><?php echo $observacion; ?></textarea>    
												</div>
											</div>
											<br>
											<div class="row">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="">
														<?php echo $strImagen; ?>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<label>Responsable:</label>
													<input type="text" class="form-control" name="responsable<?php echo $pregunta_codigo; ?>" id="responsable<?php echo $pregunta_codigo; ?>" value="<?php echo $responsable; ?>" disabled >                      
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Pr&oacute;xima Auditor&iacute;a:</label>
													<div class="form-group">
														<div class="input-group date">
															<input type="text" class="form-control" name="fecha<?php echo $pregunta_codigo; ?>" id="fecha<?php echo $pregunta_codigo; ?>" value="<?php echo $fecha; ?>" disabled >                      
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Instruciones y/o observaciones:</label>
													<textarea rows="5" class = "form-control" name = "solucion<?php echo $pregunta_codigo; ?>" id = "solucion<?php echo $pregunta_codigo; ?>" onkeyup="texto(this)" disabled ><?php echo $solucion; ?></textarea>                  
												</div>
											</div>
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
				<?php
					}
				}
				?>
				
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-briefcase-24"></i> Cierre del Informe</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones:</label>
										<textarea class="form-control" name = "obs" id = "obs" rows="5" disabled ><?php echo $observaciones; ?></textarea>
										<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
										<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo_audit; ?>" />
										<input type="hidden" id="programacion" name="programacion" value="<?php echo $codigo_progra; ?>" />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<a href="FRMfirma.php?hashkey=<?php echo $hashkey; ?>">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput-new thumbnail">
													<img src="../../CONFIG/<?php echo $strFirma; ?>" alt="...">
												</div>
											</div>
											<p><?php echo $tramiento_usuario; ?> <?php echo $nombre_usuario; ?></p>
											<p><em><?php echo $rol_usuario; ?></em></p>
										</a>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<button type="button" class="btn btn-default btn-lg" onclick = "window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
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
