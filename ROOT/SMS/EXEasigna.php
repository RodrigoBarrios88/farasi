<?php
date_default_timezone_set("America/Guatemala");
include_once("../../sms-api/src/SmsApi.php");
include_once('../Clases/ClsTicket.php');

define('API_KEY', 'fKeIVdVFmYj7Rh4OvtLg8aiMGauMaf3r');
define('API_SECRET', 'poXYyS5fd3vCAYVg9yFx6eIXS80gk96E');
define('API_URL', 'https://comunicador.tigo.com.gt/api/rest/');

$ticket = $_REQUEST["ticket"];
$url = url_origin( $_SERVER );
   
   $api = new SmsApi(API_KEY, API_SECRET, API_URL, false);
   //asignados
   $ClsTic = new ClsTicket();
   $result = $ClsTic->get_asignacion($ticket,'');
   $i = 0;
   $status = 0;
   if(is_array($result)){
      foreach($result as $row){
         $sms = trim($row["pri_sms"]);
         if($sms == 1){
            $numero = trim($row["usu_telefono"]);
            if(strlen($numero) == 8){
               ///generacion del mensaje
               $asignacion = str_shuffle($i.$ticket.uniqid());
               $mensaje = 'Estimado Usuario ('.$numero.'), hay una nueva solicitud reportada con el numero # '.Agrega_Ceros($ticket).', Asignacion #'.$asignacion.'; puede consultarla al ingresar a este enlace: '.$url.'/HDAPP/';
               //echo "$mensaje <br><br>";
               //----
               $response = $api->messages()->sendToContact("502$numero", $mensaje);
               //print_r($response);
               //echo "<br><br>";
               if ($response->status == "OK"){
                  $status = 1;
               }else{
                  $status = 0;
                  break;
               }
               $i++;
            }
         }
      }
   }
  
  if ($status == 1){
       $msj = "Ticket asignado satisfactoriamente!, $i mensaje(s) SMS fue(ron) enviado(s) como notificaci\u00F3n ...";
   }else{
       $msj = "Ticket asignado satisfactoriamente!, pero uno o varios de los n\u00FAmero de tel\u00E9fono no son validos";
       //echo "Failed to send message with status code $response->code\n";
   }
//////////////////////////////////////////////////// 
//Agrega Ceros para adornar el codigo
////////////////////////////////////////////////////    
function Agrega_Ceros($dato){
   $len = strlen($dato);
	switch($len){
		case 1: $dato = "000$dato"; break;
		case 2: $dato = "00$dato"; break;
		case 3: $dato = "0$dato"; break;
	}
	return $dato;
}

//////////////////////////////////////////////////// 
//quita caracteres de español
//////////////////////////////////////////////////// 
function depurador_texto($texto) {
	$texto = trim($texto);
	$texto = str_replace("á","a",$texto);
	$texto = str_replace("é","e",$texto);
	$texto = str_replace("í","i",$texto);
	$texto = str_replace("ó","o",$texto);
	$texto = str_replace("ú","u",$texto);
	$texto = str_replace("Á","A",$texto);
	$texto = str_replace("É","E",$texto);
	$texto = str_replace("Í","I",$texto);
	$texto = str_replace("Ú","U",$texto);
	$texto = str_replace("ñ","n",$texto);
	$texto = str_replace("Ñ","N",$texto);
	
   return $texto;
}

//////////////////////////////////////////////////// 
// URL DEL SERVIDOR
//////////////////////////////////////////////////// 
function url_origin( $s, $use_forwarded_host = false ){
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo head("../../"); ?>
</head>
<body class="">
	<div class="wrapper ">
	<!-- //////////////////////////////////////////////////////// -->
	
		<script type='text/javascript' >
			function mensaje(status){
				var msj = '<?php echo $msj; ?>';
				//-----
				if(status === 1){
					swal("Excelete!", "<?php echo $msj; ?>", "success").then((value)=>{ window.location.href="../CPTICKET/FRMtickets.php"; });
				}else{
					swal("", "<?php echo $msj; ?>", "warning").then((value)=>{ window.location.href="../CPTICKET/FRMtickets.php"; });
				}
			}
			window.setTimeout('mensaje(<?php echo $status; ?>);',500);
		</script></div>
	
    
	<?php echo scripts("../") ?>
	
	
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/perfil.js"></script>
    </body>
</html>