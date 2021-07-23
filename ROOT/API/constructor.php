<?php

function mail_constructor($mensaje){
	return $salida = '
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Happy Mondays</title>
		<style>
			 body{
				 font-family: Arial, sans-serif;
				 font-size: 14px;
				 color: #585858;
			 }
		</style>
  </head>
<body style="margin: 0; padding: 0;">
<br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
				<tr>		
					<td align="center" bgcolor="#F9F9F9" style="padding: 20px 0 10px 0; border: 1px solid transparent;border-radius: 4px;border-color: #EDEDED;">
						<img src="https://chronbilling.farasi.com.gt/CONFIG/img/logo.png" width="20%">
					</td>
				</tr>
				<tr>		
					<td bgcolor="#ffffff" style="padding: 15px 0px 15px 0px;">
						
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td align="center">
									<p align="left">'.$mensaje.'</p>
								</td>
							</tr>	
						</table>
						
					</td>
				</tr>
				<tr>		
					<td bgcolor="#c4c4c4" style="padding: 15px 30px 15px 30px;">
						
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td align="center">
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td align = "center">
												<a href="http://www.facebook.com/"><img src="https://chronbilling.farasi.com.gt/CONFIG/img/facebook.png" alt="Twitter" width="38" height="38" style="display: block; margin:3px;" border="0" /></a>
											</td>
											<td align = "center">
												<a href="http://www.twitter.com/"><img src="https://chronbilling.farasi.com.gt/CONFIG/img/twiter.png" alt="Twitter" width="38" height="38" style="display: block; margin:3px;" border="0" /></a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align = "center">
									<p style="font-size: 13px; font-weight: bold; color: #fff;">Copyright © Chron Billing '.date("Y").'. </p>
									<p style="font-size: 11px; color: #777777;">Power by Farasi Software</p>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
		
		</td> 
	</tr>
</table>
 <br>
</body>
</html>
	';
}


$nom = "Manuel Sosa";
$mail = "soporte@farasi.com.gt";
$usu = "abcdfa";
$pass = "123546";
$cuerpo = "Has recibido un nuevo mensaje de Chrone Billing. Aqui estan los detalles de tu usuario: <br> Nombre: <b>$nom</b> <br> E-mail: <b>$mail</b> <br> Usuario: <b>$usu</b> <br> Password: <b>$pass</b> <br><br>Que pases un feliz dia!!!";
//echo mail_constructor($cuerpo);

?>