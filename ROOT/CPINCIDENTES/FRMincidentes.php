<?php
	include_once('html_fns_incidente.php');
	validate_login("../");
$id = $_SESSION["codigo"];

	//$_POST
	$codigo = $_REQUEST["codigo"];
	$nombre = $_REQUEST["nom"];
	$categoria = $_REQUEST["categoria"];
	$prioridad = $_REQUEST["prioridad"];if($codigo != ""){
		$ClsInc = new ClsIncidente();
		$result = $ClsInc->get_incidente($codigo);
		if(is_array($result)){
			foreach($result as $row){
				$codigo = Agrega_Ceros($row["inc_codigo"]);
				$categoria_db = utf8_decode($row["inc_categoria"]);
				$prioridad_db = utf8_decode($row["inc_prioridad"]);
				$nom_db = utf8_decode($row["inc_nombre"]);
			}	
		}	
	}
	////---
	$categoria = ($categoria == "")?$categoria_db:$categoria;
	$prioridad = ($prioridad == "")?$prioridad_db:$prioridad;
	$nombre = ($nombre == "")?$nom_db:$nombre;?>
<!DOCTYPE html>
<html>
<head>
<?php echo head("../"); ?>
</head>
<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../","helpdesk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-bell"></i> Gestor de Incidentes</h5>
							</div>
							<div class="card-body all-icons">
								<form id="f1" action="FRMincidentes.php" method="get">
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
										<label>Categor&iacute;a:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(categorias_hd_html("categoria","Submit()","select2")); ?>
										<script>
											document.getElementById("categoria").value="<?php echo $categoria; ?>"
										</script>
									</div>
									<div class="col-md-6">
										<label>Prioridad:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(prioridades_html("prioridad","Submit()","select2")); ?>
										<script>
											document.getElementById("prioridad").value="<?php echo $prioridad; ?>"
										</script>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Nombre del Incidente:</label> <span class="text-danger">*</span>
										<input type = "text" class="form-control" name = "nombre" id = "nombre" onkeyup = "texto(this);" value="<?php echo $nombre; ?>" />
										<input type = "hidden" name = "codigo" id = "codigo" value="<?php echo $codigo; ?>" />
									</div>
								</div>
								</form>
								<br>
								<form id="form" action="#" class="wizard-big">
								<div class="row">
									<div class="col-lg-12">
									<?php
									////////---------- Obtiene las incidente asignadas a cada sede
									if($codigo != ""){
										$ClsInc = new ClsIncidente();
										$result = $ClsInc->get_usuario_incidente('',$codigo,'','','',1);
										if(is_array($result)){
											$arrusuarios = array();
											$usuarios_asignadas=0;
											foreach($result as $row){
												$arrusuarios[$usuarios_asignadas] = $row["ius_usuario"];
												$usuarios_asignadas++;
											}
											//echo $usuarios_asignadas;
										}
									}	
									////////----------
									if($categoria != "" && $prioridad != ""){
										$ClsUsu = new ClsUsuario();
										$result = $ClsUsu->get_usuario('','','','','',1);
									}	
									?>
										<select class="form-control dual_select"  name="duallistbox1[]" multiple >
									<?php
										if(is_array($result)){
											foreach($result as $row){
												$codigo = $row["usu_id"];
												$nombre = utf8_decode($row["usu_nombre"]);
												$chk = "";
												for($i = 0; $i< $usuarios_asignadas; $i++){
													//echo "$codigo == ".$arrusuarios[$i]."<br>";
													if($codigo == $arrusuarios[$i]){
														$chk = "selected";
														break;
													}
												}
												echo '<option value="'.$codigo.'" '.$chk.'>'.$nombre.'</option>';
											}
										}else{
											echo '<option value="">No hay usuarios registradas...</option>';
										}
									?>
										</select>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-6 text-center">
										<a type="button" href="FRMincidentes.php" class="btn btn-block btn-lg btn-white"><i class="fa fa-eraser"></i> Limpiar</a>
									</div>
									<div class="col-md-6 text-center">
										<button type="submit" class="btn btn-block btn-lg btn-primary" id="btn-grabar"><i class="fa fa-save"></i> Grabar</button>
									</div>
								</div>
								</form>
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
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/helpdesk/incidente.js"></script>
    </body>
</html>
