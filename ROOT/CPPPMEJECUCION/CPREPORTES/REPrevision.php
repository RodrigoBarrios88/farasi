<?php
   //Incluir las librerias de FPDF 
   include_once('html_fns_reportes.php');
   $usuario = $_SESSION["codigo"];
   
   //$_POST
	$ClsCue = new ClsCuestionarioPPM();
	$ClsPro = new ClsProgramacionPPM();
	$hashkey = $_REQUEST["hashkey"];
	$programacion = $ClsPro->decrypt($hashkey, $usuario);
	//--
	$result = $ClsPro->get_programacion($programacion);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
         $codigo = trim($row["pro_codigo"]);
         $sede = utf8_decode($row["sed_nombre"]);
         $sector = utf8_decode($row["sec_nombre"]);
         $area = utf8_decode($row["are_nombre"]);
         $nivel = utf8_decode($row["are_nivel"]);
         $activo_codigo = utf8_decode($row["act_codigo"]);
         $activo = utf8_decode($row["act_nombre"]);
         $marca = utf8_decode($row["act_marca"]); 		
         $proveedor = utf8_decode($row["act_proveedor"]); 		
         $periodicidad = utf8_decode($row["act_periodicidad"]); 		
         $capacidad = utf8_decode($row["act_capacidad"]); 		
         $cantidad = trim($row["act_cantidad"]); 		
         $observaciones_activo = utf8_decode($row["act_observaciones"]);
         //--
			$nombre_usuario = utf8_decode($row["usu_nombre"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$presupuesto = trim($row["pro_presupuesto_programado"]);
         $moneda = utf8_decode($row["mon_simbolo"]);
			$cuestionario = trim($row["pro_cuestionario"]);
			//--
			$fecha = trim($row["pro_fecha"]);
			$fecha = cambia_fecha($fecha);
			$strFoto1 = trim($row["pro_foto1"]);
			$strFoto2 = trim($row["pro_foto2"]);
			$strFirma = trim($row["pro_firma"]);
			
			$fecha_update = trim($row["pro_fecha_update"]);
			$fecha_update = cambia_fechaHora($fecha_update);
			$fecha_update = substr($fecha_update,0,16);
			$obs_programacion = utf8_decode($row["pro_observaciones_programacion"]);
         $obs_ejecutacion = utf8_decode($row["pro_observaciones_ejecucion"]);
			//
			$situacion = trim($row["pro_situacion"]);
		}
      if(file_exists('../../../CONFIG/Fotos/PPMFIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
		   $strFirma = '../../../CONFIG/Fotos/PPMFIRMAS/'.$strFirma.'.jpg';
		}else{
		   $strFirma = "../../../CONFIG/img/imageSign.jpg";
		}
		
		if(file_exists('../../../CONFIG/Fotos/PPM/'.$strFoto1.'.jpg') && $strFoto1 != ""){
		   $strFoto1 = '../../../CONFIG/Fotos/PPM/'.$strFoto1.'.jpg';
		}else{
		   $strFoto1 = "../../../CONFIG/img/imagePhoto.jpg";
		}
		
		if(file_exists('../../../CONFIG/Fotos/PPM/'.$strFoto2.'.jpg') && $strFoto2 != ""){
		   $strFoto2 = '../../../CONFIG/Fotos/PPM/'.$strFoto2.'.jpg';
		}else{
		   $strFoto2 = "../../../CONFIG/img/imagePhoto.jpg";
		}
	}$ClsAct = new ClsActivo();
	$result = $ClsAct->get_fotos('',$activo_codigo,1);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$fotCodigo = trim($row["fot_codigo"]);
			$posicion = trim($row["fot_posicion"]);
			$strActivo1 = trim($row["fot_foto"]);
			if(file_exists('../../../CONFIG/Fotos/ACTIVOS/'.$strActivo1.'.jpg') || $strActivo1 != ""){
				$strActivo1 = '../../../CONFIG/Fotos/ACTIVOS/'.$strActivo1.'.jpg';
			}else{
				$strActivo1 = '../../../CONFIG/img/imagePhoto.jpg';
			}
		}
	}else{
		$strActivo1 = '../../../CONFIG/img/imagePhoto.jpg';
	}$result = $ClsAct->get_fotos('',$activo_codigo,2);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$fotCodigo = trim($row["fot_codigo"]);
			$posicion = trim($row["fot_posicion"]);
			$strActivo2 = trim($row["fot_foto"]);
			if(file_exists('../../../CONFIG/Fotos/ACTIVOS/'.$strActivo2.'.jpg') || $strActivo2 != ""){
				$strActivo2 = '../../../CONFIG/Fotos/ACTIVOS/'.$strActivo2.'.jpg';
			}else{
				$strActivo2 = '../../../CONFIG/img/imagePhoto.jpg';
			}
		}
	}else{
		$strActivo2 = '../../../CONFIG/img/imagePhoto.jpg';
	}$result = $ClsAct->get_fotos('',$activo_codigo,3);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$fotCodigo = trim($row["fot_codigo"]);
			$posicion = trim($row["fot_posicion"]);
			$strActivo3 = trim($row["fot_foto"]);
			if(file_exists('../../../CONFIG/Fotos/ACTIVOS/'.$strActivo3.'.jpg') || $strActivo3 != ""){
				$strActivo3 = '../../../CONFIG/Fotos/ACTIVOS/'.$strActivo3.'.jpg';
			}else{
				$strActivo3 = '../../../CONFIG/img/imagePhoto.jpg';
			}
		}
	}else{
		$strActivo3 = '../../../CONFIG/img/imagePhoto.jpg';
	}
	
    // INICIA ESCRITURA DE PDF 
    $pdf = new PDF('P','mm','Letter');
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false,2);    $pdf->SetMargins(5,5,5);
    $pdf->Ln(2);    $pdf->SetFont('Arial','',10);
    $pdf->SetX(10);
    $pdf->MultiCell(195, 5, utf8_decode('Fecha/Hora de impresión: ').date("d/m/Y H:i"), 0 , 'L' , 0);
    $pdf->SetX(10);
    $pdf->MultiCell(195, 5, 'Impreso por: '.utf8_decode($_SESSION["nombre"]), 0 , 'L' , 0);
    $pdf->Image('../../../CONFIG/img/logo.jpg', 185, 10, 15 , 15,'JPG', '');
    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(10,30);
    $pdf->Cell(92.5, 10, utf8_decode('Revisión (Orden de Trabajo)'), 0, 0, 'C',1);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(112.5,30);
    $pdf->Cell(92.5, 10, utf8_decode('Ejecución # ').Agrega_Ceros($programacion), 0, 0, 'C',1);    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(10,45);
    $pdf->MultiCell(92.5,6,utf8_decode('Ubicación'),'B','C');    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(112.5,45);
    $pdf->MultiCell(92.5,6,utf8_decode('Información'),'B','C');    $pdf->SetTextColor(0,0,0); // LETRA COLOR NEGRO
    //----------- IZQUIERDA ------//
    $pdf->SetFont('Arial','',10);
    //---
    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(10,56);
    $pdf->Cell(20, 6, utf8_decode('Sede:'), 0, 0, 'L');
    $pdf->SetXY(30,56);
    $pdf->Cell(72.5, 6, $sede, 0, 0, 'L', 1);    $pdf->SetFillColor(245, 247, 249);
    $pdf->SetXY(10,62);
    $pdf->Cell(20, 6, utf8_decode('Sector:'), 0, 0, 'L');
    $pdf->SetXY(30,62);
    $pdf->Cell(72.5, 6, $sector, 0, 0, 'L', 1);    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetXY(10,68);
    $pdf->Cell(20, 6, utf8_decode('Área:'), 0, 0, 'L');
    $pdf->SetXY(30,68);
    $pdf->Cell(72.5, 6, $area, 0, 0, 'L', 1);    $pdf->SetFillColor(245, 247, 249);
    $pdf->SetXY(10,74);
    $pdf->Cell(20, 6, utf8_decode('Nivel:'), 0, 0, 'L');
    $pdf->SetXY(30,74);
    $pdf->Cell(72.5, 6, $nivel, 0, 0, 'L', 1);    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(245, 247, 249);
    $pdf->SetXY(10,84);    
    //----------- DERECHA ------//
    $pdf->SetFont('Arial','',10);
    //---
    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(112.5,56);
    $pdf->Cell(20, 6, utf8_decode('Categoría:'), 0, 0, 'L');
    $pdf->SetXY(132.5,56);
    $pdf->Cell(72.5, 6, $categoria, 0, 0, 'L', 1);    $pdf->SetFillColor(245, 247, 249);
    $pdf->SetXY(112.5,62);
    $pdf->Cell(20, 6, utf8_decode('Ejecuta:'), 0, 0, 'L');
    $pdf->SetXY(132.5,62);
    $pdf->Cell(72.5, 6, $nombre_usuario, 0, 0, 'L', 1);    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetXY(112.5,68);
    $pdf->Cell(20, 6, utf8_decode('Fecha:'), 0, 0, 'L');
    $pdf->SetXY(132.5,68);
    $pdf->Cell(72.5, 6, $fecha, 0, 0, 'L', 1);    $pdf->SetFont('Arial','',9);
    $pdf->SetFillColor(245, 247, 249);
    $pdf->SetXY(112.5,74);
    $pdf->Cell(20, 6, utf8_decode('Presupuesto:'), 0, 0, 'L');
    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(132.5,74);
    $pdf->Cell(72.5, 6, "$moneda. $presupuesto", 0, 0, 'L', 1);    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(245, 247, 249);
    $pdf->SetXY(112.5,84);
    $Y+=10;    $Y = $pdf->getY();
    //---
    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetXY(10,$Y);
    $pdf->Cell(195, 6, utf8_decode('Observaciones de la Programación:'), 0, 0, 'L');
    $Y+=5;
    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(10,$Y);
    $pdf->MultiCell(195, 5, $obs_programacion, 0, 'J', false);
    $Y+=10;    
    /////------- ACTIVO ---------
    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(10,$Y);
    $pdf->MultiCell(92.5,6,utf8_decode('Información del Activo'),'B','L');
    $Y+=10;
    //----- FILA 1 ------//
    $pdf->SetFont('Arial','',10);
    //---
    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(10,$Y);
    $pdf->Cell(20, 6, utf8_decode('Activo:'), 0, 0, 'L');
    $pdf->SetXY(30,$Y);
    $pdf->Cell(72.5, 6, $activo, 0, 0, 'L', 1);    //---
    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(112.5,$Y);
    $pdf->Cell(20, 6, utf8_decode('Marca:'), 0, 0, 'L');
    $pdf->SetXY(132.5,$Y);
    $pdf->Cell(72.5, 6, $marca, 0, 0, 'L', 1);    //----- FILA 2 ------//
    $Y+=6;
    $pdf->SetFillColor(245, 247, 249);
    $pdf->SetXY(10,$Y);
    $pdf->Cell(20, 6, utf8_decode('Proveedor:'), 0, 0, 'L');
    $pdf->SetXY(30,$Y);
    $pdf->Cell(72.5, 6, $proveedor, 0, 0, 'L', 1);    //---
    $pdf->SetFillColor(245, 247, 249);
    $pdf->SetXY(112.5,$Y);
    $pdf->Cell(20, 6, utf8_decode('Cantidad:'), 0, 0, 'L');
    $pdf->SetXY(132.5,$Y);
    $pdf->Cell(72.5, 6, $cantidad, 0, 0, 'L', 1);
    //--
    $Y+=10;
    // Fotos
    $pdf->Image($strActivo1, 10, $Y, 60 , 40, 'JPG', '');
    $pdf->Image($strActivo2, 77, $Y, 60 , 40, 'JPG', '');
    $pdf->Image($strActivo3, 145, $Y, 60 , 40, 'JPG', '');
    $Y+=50;
    //-----
    $pdf->SetFillColor(236, 240, 244);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetXY(10,$Y);
    $pdf->Cell(195, 6, utf8_decode('Observaciones del Activo:'), 0, 0, 'L');
    $Y+=5;
    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(10,$Y);
    $pdf->MultiCell(195, 5, $observaciones_activo, 0, 'J', false);
    $Y+=10;
   
    /////------- CUESTIONARIO ---------
    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(10,$Y);
    $pdf->MultiCell(92.5,6,utf8_decode('Cuestionario'),'B','L');
    $Y+=10;
    $result = $ClsCue->get_pregunta('',$cuestionario,'',1) ;
	if(is_array($result)){
        $i = 1;	
        foreach ($result as $row){
            $pregunta_codigo = $row["pre_codigo"];
            $pregunta = utf8_decode($row["pre_pregunta"]);
            $pregunta = nl2br($pregunta);
            //--
            $respuesta = "";
            $result_respuesta = $ClsPro->get_respuesta($codigo,$cuestionario,$pregunta_codigo);
            if(is_array($result_respuesta)){
                foreach ($result_respuesta as $row_respuesta){
                    $respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
                }	
            }	
            if($respuesta == 1){
                $respSI = "X";
                $respNO = "";
                $respNA = "";
            }else if($respuesta == 2){
                $respSI = "";
                $respNO = "X";
                $respNA = "";
            }else if($respuesta == 3){
                $respSI = "";
                $respNO = "";
                $respNA = "X";
            }else{
                $respSI = "";
                $respNO = "";
                $respNA = "";
            }
            
            $Y = $pdf->getY();
            
            $pdf->SetFont('Arial','',10);
            $pdf->SetXY(10,$Y);
            $pdf->MultiCell(10, 6, $i.'.', 0, 'L');
            $pdf->SetXY(20,$Y);
            $pdf->MultiCell(185, 6, $pregunta, 0, 'J');
            $Y = $pdf->getY();
            
            $pdf->SetFont('Arial','',10);
            $pdf->SetXY(10,$Y);
            $pdf->Cell(20, 6, 'SI', 0, 0, 'C');
            $pdf->SetFillColor(236, 240, 244);
            $pdf->SetXY(30,$Y);
            $pdf->Cell(20, 6, $respSI, 0, 0, 'C', 1);
            
            $pdf->SetXY(50,$Y);
            $pdf->Cell(20, 6, 'N/A', 0, 0, 'C');
            $pdf->SetFillColor(236, 240, 244);
            $pdf->SetXY(70,$Y);
            $pdf->Cell(20, 6, $respNA, 0, 0, 'C', 1);
            
            $pdf->SetXY(90,$Y);
            $pdf->Cell(19, 6, 'NO', 0, 0, 'C');
            $pdf->SetFillColor(236, 240, 244);
            $pdf->SetXY(109,$Y);
            $pdf->Cell(18, 6, $respNO, 0, 0, 'C', 1);
            
            ///////////////// VALIDA EL CAMBIO DE PAGINA
            $yyy = $pdf->getY();
            if($yyy <= 246){
                $pdf->Ln(10);
            }else{
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(false,2);
                
                $mleft = 0;
                $mtop = 0;
                $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
                
                $pdf->setY(20);
            }
            /// AUMENTA VUELTA
            $i++;
        }
        $i--;
    }
   
   
   /////------- EJECUCIÓN ---------
   $Y = $pdf->getY();
   $Y+=10;
   if($Y > 250){
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $Y = 20;
   }
   
   //-----
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','B',11);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(195, 6, utf8_decode('Observaciones al ejecutar:'), 0, 0, 'L');
   $Y+=5;
   $pdf->SetFont('Arial','',10);
   $pdf->SetXY(10,$Y);
   $pdf->MultiCell(195, 5, $obs_ejecutacion, 0, 'J', false);
   $Y+=10;
   
   $Y = $pdf->getY();
   $Y+=10;
   if($Y > 250){
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $Y = 20;
   }
   // Fotos
   $pdf->Image($strFirma, 10, $Y, 60 , 40, 'JPG', '');
   $pdf->Image($strFoto1, 77, $Y, 60 , 40, 'JPG', '');
   $pdf->Image($strFoto2, 145, $Y, 60 , 40, 'JPG', '');
   $Y = $pdf->getY();
   $Y+=55;
   $pdf->SetFont('Arial','',10);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(60, 6, 'Firma', 0, 0, 'C');
   $pdf->SetXY(77,$Y);
   $pdf->Cell(60, 6, 'Foto Inicio', 0, 0, 'C');
   $pdf->SetXY(145,$Y);
   $pdf->Cell(60, 6, 'Foto Fin', 0, 0, 'C');
   
   if($Y > 250){
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $Y = 20;
   }   
   ////// ------ FOOTER ----------------
   
   $pdf->SetFont('Arial','I',8);
   $pdf->SetXY(175,265);
   $pdf->Cell(30, 6, date("d/m/Y H:i:s"), 0, 0, 'R');
   
   
   //Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
   $revision = Agrega_Ceros($revision);
   $pdf->Output(utf8_decode("Revisión $revision.pdf"),"I");
  
  
?>