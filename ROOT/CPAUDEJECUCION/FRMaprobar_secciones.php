<?php
	include_once('html_fns_ejecucion.php');
	validate_login("../");
$usuario = $_SESSION["codigo"];

	$categoriasIn = $_SESSION["categorias_in"];
	$sedes_IN = $_SESSION["sedes_in"];
	//$_POST
	$ClsAud = new ClsAuditoria();
	$ClsEje = new ClsEjecucion();
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
	<style>
		.img-upload{
			width: 30%;
			margin:  1px;
			cursor: pointer;
		}
		.img-demo{
			width: 50%;
		}
	</style>
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
										<a class="btn btn-white btn-lg" href = "FRMaprobar.php?hashkey=<?php echo $hashkey; ?>"><i class="fa fa-chevron-left"></i> Regresar</a>
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
												$aplica_desc = ($aplica == 1)?'<i class="fa fa-check"></i> Aplica':'<i class="fa fa-times"></i> No Aplica';
											}	
											//---
											$resultado = "";
											$observacionRespuesta = "";
											$result_revision = $ClsEje->get_ejecucion_revision($ejecucion,$codigo_audit,$pregunta_codigo);
											if(is_array($result_revision)){
												foreach ($result_revision as $row_revision){
													$resultado = trim($row_revision["rev_resultado"]);
													$observacionRespuesta = utf8_decode($row_revision["rev_observaciones"]);
												}
												$aplicaDisabled = "";
												switch($resultado){
													case 1:
														$active1 = "active";
														$active2 = "";
														$active3 = "";
														$active4 = "";
														break;
													case 2:
														$active1 = "";
														$active2 = "active";
														$active3 = "";
														$active4 = "";
														break;
													case 3:
														$active1 = "";
														$active2 = "";
														$active3 = "active";
														$active4 = "";
														break;
													case 4:
														$active1 = "";
														$active2 = "";
														$active3 = "";
														$active4 = "active";
														break;
													default:
														$active1 = "";
														$active2 = "";
														$active3 = "";
														$active4 = "";
														break;
												}
											}else{
												$aplicaDisabled = "disabled";
												$active1 = "";
												$active2 = "";
												$active3 = "";
												$active4 = "";
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
													<textarea class = "form-control" disabled ><?php echo $observacion; ?></textarea>    
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													<button type="button" id="btn_1_<?php echo $pregunta_codigo; ?>" class="btn btn-success btn-block <?php echo $active1; ?>" onclick="revisionRespuesta(<?php echo $pregunta_codigo; ?>,1);" ><i class="fa fa-check"></i> Aprobar</button>
												</div>
												<div class="col-md-6">
													<button type="button" id="btn_4_<?php echo $pregunta_codigo; ?>" class="btn btn-info btn-block <?php echo $active4; ?>" onclick="revisionRespuesta(<?php echo $pregunta_codigo; ?>,4);" ><i class="fa fa-dot-circle"></i> Otro</button>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<button type="button" id="btn_2_<?php echo $pregunta_codigo; ?>" class="btn btn-warning btn-block <?php echo $active2; ?>" onclick="revisionRespuesta(<?php echo $pregunta_codigo; ?>,2);" ><i class="fa fa-spell-check"></i> Revisar Ortografi&iacute;a</button>
												</div>
												<div class="col-md-6">
													<button type="button" id="btn_3_<?php echo $pregunta_codigo; ?>" class="btn btn-danger btn-block <?php echo $active3; ?>" onclick="revisionRespuesta(<?php echo $pregunta_codigo; ?>,3);" ><i class="fa fa-times"></i> Punto de Norma</button>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label class="mt-2">Observaciones o Comentarios:</label>
													<textarea class = "form-control" id="observacion<?php echo $pregunta_codigo; ?>" onblur="observacionRespuesta(<?php echo $pregunta_codigo; ?>, this.value);" <?php echo $aplicaDisabled; ?> ><?php echo $observacionRespuesta; ?></textarea>    
													<input type="hidden" id="resultado<?php echo $pregunta_codigo; ?>" name="resultado<?php echo $pregunta_codigo; ?>" value="<?php echo $resultado; ?>" />
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
				
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-body all-icons">
								<br>
								<div class="row">
									<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
									<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo_audit; ?>" />
									<input type="hidden" id="programacion" name="programacion" value="<?php echo $codigo_progra; ?>" />
								</div>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<a class="btn btn-white btn-lg" href = "FRMaprobar.php?hashkey=<?php echo $hashkey; ?>"><i class="fa fa-chevron-left"></i> Regresar</a>
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
    
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/aprobar.js"></script>
    	<script>
		$(document).ready(function(){
			$('.select2').select2({ width: '100%' });
        });
    </script>

</body>
</html>
