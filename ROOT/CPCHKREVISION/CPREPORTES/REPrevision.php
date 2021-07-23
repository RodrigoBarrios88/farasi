<?php
   //Incluir las librerias de FPDF 
   include_once('html_fns_reportes.php');
   $usuario = $_SESSION["codigo"];
   
   //$_POST
	$ClsLis = new ClsLista();
	$ClsRev = new ClsRevision();
	$hashkey = $_REQUEST["hashkey"];
	$revision = $ClsLis->decrypt($hashkey, $usuario);
	//--
	$result = $ClsRev->get_revision($revision);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$revision = trim($row["rev_codigo"]);
			$codigo_lista = trim($row["list_codigo"]);
			$codigo_progra = trim($row["pro_codigo"]);
			$sede = utf8_decode($row["sed_nombre"]);
			$sector = utf8_decode($row["sec_nombre"]);
			$area = utf8_decode($row["are_nombre"]);
			$nivel = utf8_decode($row["are_nivel"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$nombre = utf8_decode($row["list_nombre"]);
			$usuario = utf8_decode($row["usuario_nombre"]);
			//--
			$requiere_firma = trim($row["list_firma"]);
			$requiere_fotos = trim($row["list_fotos"]);
			$strFirma = trim($row["rev_firma"]);
			//--
			$fecha_inicio = trim($row["rev_fecha_inicio"]);
			$fecha_inicio = cambia_fechaHora($fecha_inicio);
			$fecha_inicio = substr($fecha_inicio,0,16);
			
			$fecha_finaliza = trim($row["rev_fecha_final"]);
			$fecha_finaliza = cambia_fechaHora($fecha_finaliza);
			$fecha_finaliza = substr($fecha_finaliza,0,16);
			$obs = utf8_decode($row["rev_observaciones"]);
			//
			$situacion = trim($row["rev_situacion"]);
		}
		/////////// PROGRAMACION /////
		$dia = date("N");
		$result = $ClsLis->get_programacion($codigo_progra,$codigo_lista);
		if(is_array($result)){
			$i = 0;	
			foreach ($result as $row){
				$hini = trim($row["pro_hini"]);
				$hfin = trim($row["pro_hfin"]);
				$horario = "$hini - $hfin";
			}
		}
	}// INICIA ESCRITURA DE PDF 
	$pdf = new PDF('P','mm','Letter');
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false,2);
	
   $pdf->SetMargins(5,5,5);
	$pdf->Ln(2);
   
   $pdf->SetFont('Arial','',10);
   $pdf->SetX(10);
	$pdf->MultiCell(195, 5, utf8_decode('Fecha/Hora de impresión: ').date("d/m/Y H:i"), 0 , 'L' , 0);
   $pdf->SetX(10);
	$pdf->MultiCell(195, 5, 'Impreso por: '.utf8_decode($_SESSION["nombre"]), 0 , 'L' , 0);
	$pdf->Image('../../../CONFIG/img/logo.jpg', 185, 10, 15 , 15,'JPG', '');
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','B',12);
   $pdf->SetXY(10,30);
   $pdf->Cell(92.5, 10, utf8_decode('Reporte de Revisión (Lista de Chequeo)'), 0, 0, 'C',1);
   $pdf->SetFont('Arial','B',14);
   $pdf->SetXY(112.5,30);
   $pdf->Cell(92.5, 10, utf8_decode('Código # ').Agrega_Ceros($revision), 0, 0, 'C',1);
   
   $pdf->SetFont('Arial','B',12);
   $pdf->SetXY(10,45);
   $pdf->MultiCell(92.5,6,utf8_decode('Ubicación'),'B','C');
   
   $pdf->SetFont('Arial','B',12);
   $pdf->SetXY(112.5,45);
   $pdf->MultiCell(92.5,6,utf8_decode('Información'),'B','C');
   
   $pdf->SetTextColor(0,0,0); // LETRA COLOR NEGRO
   //----------- IZQUIERDA ------//
   $pdf->SetFont('Arial','',10);
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','',10);
   $pdf->SetXY(10,56);
   $pdf->Cell(20, 6, utf8_decode('Sede:'), 0, 0, 'L');
   $pdf->SetXY(30,56);
   $pdf->Cell(72.5, 6, $sede, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(10,62);
   $pdf->Cell(20, 6, utf8_decode('Sector:'), 0, 0, 'L');
   $pdf->SetXY(30,62);
   $pdf->Cell(72.5, 6, $sector, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(10,68);
   $pdf->Cell(20, 6, utf8_decode('Área:'), 0, 0, 'L');
   $pdf->SetXY(30,68);
   $pdf->Cell(72.5, 6, $area, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(10,74);
   $pdf->Cell(20, 6, utf8_decode('Nivel:'), 0, 0, 'L');
   $pdf->SetXY(30,74);
   $pdf->Cell(72.5, 6, $nivel, 0, 0, 'L', 1);
   
   $pdf->SetFont('Arial','B',10);
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(10,84);
   $pdf->Cell(92.5, 6, utf8_decode('Inicio de la Revisión: '.$fecha_inicio), 0, 0, 'C',1);
   
   
   //----------- DERECHA ------//
   $pdf->SetFont('Arial','',10);
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','',10);
   $pdf->SetXY(112.5,56);
   $pdf->Cell(20, 6, utf8_decode('Categoría:'), 0, 0, 'L');
   $pdf->SetXY(132.5,56);
   $pdf->Cell(72.5, 6, $categoria, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(112.5,62);
   $pdf->Cell(20, 6, utf8_decode('Lista:'), 0, 0, 'L');
   $pdf->SetXY(132.5,62);
   $pdf->Cell(72.5, 6, $nombre, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(112.5,68);
   $pdf->Cell(20, 6, utf8_decode('Horario:'), 0, 0, 'L');
   $pdf->SetXY(132.5,68);
   $pdf->Cell(72.5, 6, $horario, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(112.5,74);
   $pdf->Cell(20, 6, utf8_decode('Usuario:'), 0, 0, 'L');
   $pdf->SetXY(132.5,74);
   $pdf->Cell(72.5, 6, $usuario, 0, 0, 'L', 1);
   
   $pdf->SetFont('Arial','B',10);
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(112.5,84);
   $pdf->Cell(92.5, 6, utf8_decode('Finalización de la Revisión: '.$fecha_finaliza), 0, 0, 'C', 1);
   
   $pdf->setY(95);
   $result = $ClsLis->get_pregunta('',$codigo_lista,'',1) ;
	if(is_array($result)){
      $i = 1;	
      foreach ($result as $row){
         $pregunta_codigo = $row["pre_codigo"];
         $pregunta = utf8_decode($row["pre_pregunta"]);
         $pregunta = nl2br($pregunta);
         $result_respuesta = $ClsRev->get_respuesta($revision,$codigo_lista,$pregunta_codigo);
         if(is_array($result_respuesta)){
            foreach ($result_respuesta as $row_respuesta){
               $respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
            }	
         }	
         
         $Y = $pdf->getY();
         $A = "";
         $B = "";
         $C = "";
             switch($respuesta){
               case 1: $A = 'X'; break;
               case 2: $B = 'X'; break;
               case 3: $C = 'X'; break;
            }
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
            $pdf->Cell(20, 6, $A, 0, 0, 'C', 1);
            
            $pdf->SetXY(50,$Y);
            $pdf->Cell(20, 6, 'N/A', 0, 0, 'C');
            $pdf->SetFillColor(236, 240, 244);
            $pdf->SetXY(70,$Y);
            $pdf->Cell(20, 6, $C, 0, 0, 'C', 1);
            
            $pdf->SetXY(90,$Y);
            $pdf->Cell(19, 6, 'NO', 0, 0, 'C');
            $pdf->SetFillColor(236, 240, 244);
            $pdf->SetXY(109,$Y);
            $pdf->Cell(18, 6, $B, 0, 0, 'C', 1);
         
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
            
            $pdf->setY(30);
         }
         /// AUMENTA VUELTA
         $i++;
      }
      $i--;
   }
					
   $Y = $pdf->getY();
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','',11);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(195, 6, 'Observaciones:', 0, 0, 'L');
   $Y+=8;
   $pdf->SetXY(10,$Y);
   $pdf->MultiCell(195, 6, $obs, 0, 'J', true);
   $Y+=10;
   
   $Y = $pdf->getY();
   if($Y > 200){
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $Y = 0;
   }
   $Y+=10;
   if(file_exists('../../../CONFIG/Fotos/FIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
      $pdf->Image('../../../CONFIG/Fotos/FIRMAS/'.$strFirma.'.jpg', 20, $Y, 80 , 60,'JPG', '');
   }else{
      $pdf->Image('../../../CONFIG/img/imageSign.jpg', 20, $Y, 80 , 60,'JPG', '');
   }
   /////////// PROGRAMACION /////
   $dia = date("N");
   $result = $ClsLis->get_programacion($codigo_progra,$codigo_lista);
   if(is_array($result)){
      $i = 0;	
      foreach ($result as $row){
         $hini = trim($row["pro_hini"]);
         $hfin = trim($row["pro_hfin"]);
         $horario = "$hini - $hfin";
      }
	}$result = $ClsRev->get_fotos('',$revision);
   if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$strFoto = trim($row["fot_foto"]);
		}
		if(file_exists('../../../CONFIG/Fotos/REVISION/'.$strFoto.'.jpg') && $strFoto != ""){
		   $pdf->Image('../../../CONFIG/Fotos/REVISION/'.$strFoto.'.jpg', 112.5, $Y, 80 , 60,'JPG', '');
		}else{
		   $pdf->Image('../../../CONFIG/img/imagePhoto.jpg', 112.5, $Y, 80 , 60,'JPG', '');
		}
	}else{
      $pdf->Image('../../../CONFIG/img/imagePhoto.jpg', 112.5, $Y, 80 , 60,'JPG', '');
   }
   
   //$pdf->SetXY(3,210);
   //$pdf->MultiCell(195, 5, "Prueba", 0, 'J', true);
   //$pdf->AddPage();
	//$pdf->SetAutoPageBreak(false,2);
   
   
   //Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
   $revision = Agrega_Ceros($revision);
   $pdf->Output(utf8_decode("Revisión $revision.pdf"),"I");
  
  
?>