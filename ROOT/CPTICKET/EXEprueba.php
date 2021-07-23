<?php
	include_once('xajax_funct_ticket.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../dieimg/apple-icon.png">
	<link rel="icon" type="image/png" href="../dieimg/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo $_SESSION["cliente_nombre"]; ?></title>
	<link rel="shortcut icon" href="../../CONFIG/img/icono.png">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
   
	<!--     Fonts and icons     -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<!-- CSS Files -->
	<link href="../assets.1.2.8/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../assets.1.2.8/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
	<!-- Swal -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<!-- Estilos Utilitarios -->
	<link href="../assets.1.2.8/css/propios/formulario.css" rel="stylesheet">
	<script src="https://kit.fontawesome.com/907a027ade.js" crossorigin="anonymous"></script>
	<link href="../assets.1.2.8/css/propios/custom.fonts.css" rel="stylesheet">
</head>
<body class="">
	<div class="wrapper ">
		
<?php
$ticket = $_REQUEST["codigo"];
mail_usuario(10,1)
?>    
	</div>
	
    
	<?php echo scripts("../") ?>
	
	
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/perfil.js"></script>
    </body>
</html>
