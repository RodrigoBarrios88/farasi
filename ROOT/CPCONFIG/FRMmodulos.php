<?php
	include_once('xajax_funct_config.php');
	validate_login("../");
$id = $_SESSION["codigo"];

		
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo head("../"); ?>
</head>
<body class="">
	<div class="wrapper ">
		<div class="sidebar" data-color="brown" data-active-color="danger">
			<div class="logo">
				<a href="../menu.php" class="simple-text logo-mini">
					<div class="logo-image-small">
						<img src="../../CONFIG/img/logo2.png" />
					</div>
			</div>
			<div class="sidebar-wrapper">
				<div class="user">
					<div class="photo">
						<img src="../../CONFIG/Fotos/<?php echo $foto; ?>" />
					</div>
					<div class="info">
						<a data-toggle="collapse" href="#collapseExample" class="collapsed">
							<span>
								<?php echo $nombre_sesion; ?>
								<b class="caret"></b>
							</span>
						</a>
						<div class="clearfix"></div>
						<div class="collapse" id="collapseExample">
							<ul class="nav">
								<li>
									<a href="../CPPERFIL/FRMperfil.php">
										<span class="sidebar-mini-icon"><i class="nc-icon nc-single-02"></i></span>
										<span class="sidebar-normal">Perfil</span>
									</a>
								</li>
								<li>
									<a href="../CPPERFIL/FRMpassword.php">
										<span class="sidebar-mini-icon"><i class="nc-icon nc-lock-circle-open"></i></span>
										<span class="sidebar-normal">Contrase&ntilde;a</span>
									</a>
								</li>
								<li>
									<a href="../CPPERFIL/FRMajustes.php">
										<span class="sidebar-mini-icon"><i class="fa fa-cog"></i></span>
										<span class="sidebar-normal">Ajustes</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<ul class="nav">
					<li>
						<a href="../menu_checklist.php">
							<i class="nc-icon nc-layout-11"></i>
							<p>Men&uacute;</p>
						</a>
					</li>
					<?php if($_SESSION["GRP_GPADMIN"] == 1){ ?>
					<li class="active">
						<a data-toggle="collapse" href="#administracio">
							<i class="fa fa-users-cog"></i>
							<p>
								Administraci&oacute;n
								<b class="caret"></b>
							</p>
						</a>
						<div class="collapse " id="administracio">
							<ul class="nav">
								<?php if($_SESSION["GUSU"] == 1){ ?>
								<li>
									<a href="../CPUSUARIOS/FRMusuarios.php">
										<span class="sidebar-mini-icon"><i class="fa fa-user"></i></span>
										<span class="sidebar-normal"> Gestor de Usuarios </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["GPERM"] == 1){ ?>
								<li>
									<a href="../CPUSUARIOS/FRMasignacion_rol.php">
										<span class="sidebar-mini-icon"><i class="fa fa-key"></i></span>
										<span class="sidebar-normal"> Administrador de Permisos </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["USUSED"] == 1){ ?>
								<li>
									<a href="../CPUSUARIOS/FRMusuario_sede.php">
										<span class="sidebar-mini-icon"><i class="fa fa-bank"></i></span>
										<span class="sidebar-normal"> Usuarios / Sedes </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["USUCAT"] == 1){ ?>
								<li>
									<a href="../CPUSUARIOS/FRMusuario_categoria.php">
										<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
										<span class="sidebar-normal"> Usuarios / Categor&iacute;as </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["GPERM"] == 1){ ?>
								<li>
									<a href="../CPMONEDA/FRMmoneda.php">
										<span class="sidebar-mini-icon"><i class="fa fa-money"></i></span>
										<span class="sidebar-normal"> Gestor de Monedas</span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["GPERM"] == 1){ ?>
								<li class="active">
									<a href="../CPVERSION/FRMversion.php">
										<span class="sidebar-mini-icon"><i class="nc-icon nc-mobile"></i></span>
										<span class="sidebar-normal"> Admin. Versiones</span>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</li>
					<?php } ?>
					<?php if($_SESSION["GRP_GESTEC"] == 1){ ?>
					<li>
						<a data-toggle="collapse" href="#gestores">
							<i class="fa fa-cogs"></i>
							<p>
								Gestores T&eacute;cnicos
								<b class="caret"></b>
							</p>
						</a>
						<div class="collapse " id="gestores">
							<ul class="nav">
								<?php if($_SESSION["GESSED"] == 1){ ?>
								<li>
									<a href="../CPSEDE/FRMsede.php">
										<span class="sidebar-mini-icon"><i class="fa fa-bank"></i></span>
										<span class="sidebar-normal"> Gestor de Sedes </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["GESTA"] == 1){ ?>
								<li>
									<a href="../CPSECTOR/FRMsector.php">
										<span class="sidebar-mini-icon"><i class="fa fa-building-o"></i></span>
										<span class="sidebar-normal"> Gestor de Sector | Torres </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["GETAR"] == 1){ ?>
								<li>
									<a href="../CPAREA/FRMarea.php">
										<span class="sidebar-mini-icon"><i class="fa fa-cube"></i></span>
										<span class="sidebar-normal"> Gestor de &Aacute;reas </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["GETAR"] == 1){ ?>
								<li>
									<a href="../CPDEPARTAMENTO/FRMdepartamento.php">
										<span class="sidebar-mini-icon"><i class="fa fa-building-o"></i></span>
										<span class="sidebar-normal"> Gestor de Departamento </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["CATCHK"] == 1){ ?>
								<li>
									<a href="../CPCATEGORIA/FRMcategoria_checklist.php">
										<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
										<span class="sidebar-normal"> Categor&iacute;as (Check List) </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["CATHD"] == 1){ ?>
								<li>
									<a href="../CPCATEGORIA/FRMcategoria_helpdesk.php">
										<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
										<span class="sidebar-normal"> Categor&iacute;as (Sweeper) </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["CATAUDIT"] == 1){ ?>
								<li>
									<a href="../CPCATEGORIA/FRMcategoria_auditoria.php">
										<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
										<span class="sidebar-normal"> Categor&iacute;as (Audit Active) </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["CATPPM"] == 1){ ?>
								<li>
									<a href="../CPCATEGORIA/FRMcategoria__ppm.php">
										<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
										<span class="sidebar-normal"> Categor&iacute;as (Mant. Planner) </span>
									</a>
								</li>
								<?php } ?>
								<?php if($_SESSION["GENQR"] == 1){ ?>
								<li>
									<a href="../CPAREA/FRMqrcode.php">
										<span class="sidebar-mini-icon"><i class="fa fa-qrcode"></i></span>
										<span class="sidebar-normal"> Impresi&oacute;n de QR </span>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</li>
					<?php } ?>
					<?php if($_SESSION["GRP_CKLIST"] == 1){ ?>
					<li>
						<a href="../menu_checklist.php">
							<i class="fa fa-check-square-o"></i>
							<p>Check List</p>
						</a>
					</li>
					<?php } ?>
					<?php if($_SESSION["GRP_HELPDESK"] == 1){ ?>
					<li>
						<a href="../menu.php">
							<i class="fas fa-toolbox"></i>
							<p>Problem Sweeper</p>
						</a>
					</li>
					<?php } ?>
					<?php if($_SESSION["GRP_PPM"] == 1){ ?>
					<li>
						<a href="../menu_ppm.php">
							<i class="fa fa-tools"></i>
							<p>Maintenance Planner</p>
						</a>
					</li>
					<?php } ?>
					<?php if($_SESSION["GRP_AUDIT"] == 1){ ?>
					<li>
						<a href="../menu_auditoria.php">
							<i class="fas fa-clipboard-list"></i>
							<p>Auditor&iacute;a</p>
						</a>
					</li>
					<?php } ?>
					<hr>
					<li>
						<a href="../logout.php">
							<i class="fa fa-power-off"></i>
							<p>Salir</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-sitemap"></i> M&oacute;dulos del Sistema</h5>
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
										<?php
											$ClsConf = new ClsConfig();
											$result = $ClsConf->get_modulos();
											echo lista_multiple_html($result,"modulo","mod_codigo","mod_nombre","Listado de M&oacute;dulos");
										?>
										<script>
											<?php
											if(is_array($result)){
												$i = 1;
												foreach($result as $row){
													$codigo = $row["mod_codigo"];
													$nombre = $row["mod_nombre"];
													$situacion = $row["mod_situacion"];
													if($situacion == 1){
														echo "document.getElementById('modulo$i').checked = true;";
													}else{
														echo "document.getElementById('modulo$i').checked = false;";
													}
													$i++;
												}
											}
											?>
										</script>
									</div>
									<div class="col-md-6 text-center">
										<h5 class="alert alert-info text-center">
											<i class="fa fa-info-circle"></i><br>
											La habilitaci&oacute;n de los m&oacute;dulos se ver&aacute; reflejada en los diferentes portales y aplicaciones.
										</h5>
										<br>
										<button type="button" class="btn btn-white" onclick = "Limpiar();"><i class="fa fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" onclick = "modificarModulos();"><i class="fa fa-save"></i> Grabar</button>
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
	
	<!-- Color picker -->
	<script src="../assets.1.2.8/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/master/config.js"></script>
	
	</body>
</html>
