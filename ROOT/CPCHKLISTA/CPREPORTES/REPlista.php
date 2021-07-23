<?php
   //Incluir las librerias de FPDF 
   include_once('html_fns_reportes.php');
   $usuario = $_SESSION["codigo"];
   
   //$_POST
	$ClsLis = new ClsLista();
	$hashkey = $_REQUEST["hashkey"];
	$programacion = $ClsLis->decrypt($hashkey, $usuario);
	//--
	$result = $ClsLis->get_programacion($programacion,'');
	$i = 0;	
	if(is_array($result)){
		foreach ($result as $row){
			$codigo_lista = trim($row["list_codigo"]);
			$codigo_progra = trim($row["pro_codigo"]);
			$sede = utf8_decode($row["sed_nombre"]);
			$sector = utf8_decode($row["sec_nombre"]);
			$area = utf8_decode($row["are_nombre"]);
			$nivel = utf8_decode($row["are_nivel"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$nombre = utf8_decode($row["list_nombre"]);
			$usuario_nombre = utf8_decode($_SESSION["nombre"]);
			//--
			$requiere_firma = trim($row["list_firma"]);
			$requiere_fotos = trim($row["list_fotos"]);
			$strFirma = trim($row["rev_firma"]);
			//
         $observaciones = utf8_decode($row["pro_observaciones"]);
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
   $pdf->Cell(92.5, 10, utf8_decode('Lista de Chequeo Programada'), 0, 0, 'C',1);
   $pdf->SetFont('Arial','B',12);
   $pdf->SetXY(112.5,30);
   $pdf->Cell(92.5, 10, utf8_decode('Programación Código # ').Agrega_Ceros($programacion), 0, 0, 'C',1);
   
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
   //$pdf->Cell(92.5, 6, utf8_decode('Inicio de la Revisión: '.$fecha_inicio), 0, 0, 'C',1);
   
   
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
   $pdf->Cell(72.5, 6, $usuario_nombre, 0, 0, 'L', 1);
   
   $pdf->SetFont('Arial','B',10);
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(112.5,84);
   //$pdf->Cell(92.5, 6, utf8_decode('Finalización de la Revisión: '.$fecha_finaliza), 0, 0, 'C', 1);
   $Y+=10;
   
   $Y = $pdf->getY();
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','',11);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(195, 6, utf8_decode('Observaciones de la Programación:'), 0, 0, 'L');
   $Y+=5;
   $pdf->SetXY(10,$Y);
   $pdf->MultiCell(195, 6, $observaciones, 0, 'J', true);
   $Y+=10;
   /////-------
   
   $pdf->setY($Y);
   $result = $ClsLis->get_pregunta('',$codigo_lista,'',1) ;
	if(is_array($result)){
      $i = 1;	
      foreach ($result as $row){
         $pregunta_codigo = $row["pre_codigo"];
         $pregunta = utf8_decode($row["pre_pregunta"]);
         $pregunta = nl2br($pregunta);
         
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
         $pdf->Cell(20, 6, '', 0, 0, 'C', 1);
         
         $pdf->SetXY(50,$Y);
         $pdf->Cell(20, 6, 'N/A', 0, 0, 'C');
         $pdf->SetFillColor(236, 240, 244);
         $pdf->SetXY(70,$Y);
         $pdf->Cell(20, 6, '', 0, 0, 'C', 1);
         
         $pdf->SetXY(90,$Y);
         $pdf->Cell(19, 6, 'NO', 0, 0, 'C');
         $pdf->SetFillColor(236, 240, 244);
         $pdf->SetXY(109,$Y);
         $pdf->Cell(18, 6, '', 0, 0, 'C', 1);
         
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
   $Y+=30;
   if($Y > 250){
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $Y = 0;
   }
   
   $pdf->SetFont('Arial','B',10);
   $pdf->SetXY(67.5,$Y);
   $pdf->Cell(70, 6, $usuario_nombre, 'T', 0, 'C');
   $Y+=5;
   $pdf->SetFont('Arial','',10);
   $pdf->SetXY(67.5,$Y);
   $pdf->Cell(70, 6, utf8_decode('Firma'), 0, 0, 'C');
   
   
   ////// ------ FOOTER ----------------
   $pdf->SetFont('Arial','B',6);
   $pdf->SetXY(10,264);
   $pdf->Cell(10, 6, 'NOTA:', 0, 0, 'L');
   
   $pdf->SetFont('Arial','',6);
   $pdf->SetXY(20,265);
   $nota = 'Este documento es únicamente una herramienta para utlizar en lugares donde no se puede ingresar con dispositivos elctrónicos. La información debe ser registrada en el sistema en durante el horario habilitado de la lista al finalizar para ser valido, de lo contrario el sistema lo tomara como un incumplimiento. ';
   $pdf->MultiCell(150, 3, utf8_decode($nota), 0, 'J', false);
   
   $pdf->SetFont('Arial','I',8);
   $pdf->SetXY(175,265);
   $pdf->Cell(30, 6, date("d/m/Y H:i:s"), 0, 0, 'R');
   
   
   //Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
   $programacion = Agrega_Ceros($programacion);
   $pdf->Output("Checklist $programacion.pdf","I");
  
  
?>