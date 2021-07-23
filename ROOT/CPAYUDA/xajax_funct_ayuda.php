<?php 
//inclu�mos las clases
require ("../xajax_core/xajax.inc.php");
include_once("html_fns_ayuda.php");

//instanciamos el objeto de la clase xajax
$xajax = new xajax();
$xajax->setCharEncoding('ISO-8859-1');
date_default_timezone_set('America/Guatemala');
 

////////////----- PERDIDA DE PASS -------///////////////////////////////////
function Buscar_Pregunta($mail){
   //$respuesta = new xajaxResponse('ISO-8859-1');
   $respuesta = new xajaxResponse();
   $ClsUsu = new ClsUsuario();
    if($mail != ""){
		$mc = comprobar_email($mail);
		if($mc > 0){
			$result = $ClsUsu->get_usuario('','',$mail,'','',1);
			if(is_array($result)){
					foreach($result as $row){
						$cod = $row["usu_id"];
						$respuesta->assign("cod","value",$cod);
						$usu = $row["usu_usuario"];
						$respuesta->assign("usu","value",$usu);
						$preg = $row["usu_pregunta"];
						$respuesta->assign("preg","value",$preg);
						$respuesta->script("document.getElementById('preg').className = 'row';");
						$respuesta->script("document.getElementById('resp').className = 'row';");
						$respuesta->script("document.getElementById('bot1').className = 'hidden';");
						$respuesta->script("document.getElementById('bot2').className = 'btn btn-primary block full-width m-b';");
						//desabilita los campo e-mail y empresa
						$respuesta->script("document.getElementById('mail').setAttribute('disabled','disabled');");
					}			$respuesta->script("cerrar()");
			}else{
				$msj = '<h5>No se registran Usuarios con estos criterios de busqueda!!!</h5><br><br>';
				$msj.= '<button type="button" class="btn btn-primary" onclick = "cerrar()" ><span class="fa fa-check"></span> Aceptar</button> ';
				$respuesta->assign("lblparrafo","innerHTML",$msj);
			}
		}else{
			$msj = '<h5>Formato de e-mail incorrecto...</h5><br><br>';
			$msj.= '<button type="button" class="btn btn-primary" onclick = "cerrar()" ><span class="fa fa-check""></span> Aceptar</button> ';
			$respuesta->assign("lblparrafo","innerHTML",$msj);
			$respuesta->script("document.getElementById('email').className = 'form-control text-libre alert-danger';");
		}
	}
   		
   return $respuesta;
}


//////////////////////------------------------------------------------------------------------------
//////////////////////------------------------------------------------------------------------------
//////////////////////------------------------------------------------------------------------------

//asociamos las funciones creada anteriormente al objeto xajax
//////////////////---- PERDIDA DE PASS -----/////////////////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "Buscar_Pregunta");

//El objeto xajax tiene que procesar cualquier petici�n
$xajax->processRequest();?>  