<?php

  set_time_limit(36000); 

  require("SmartyIni.php");
  require("PacienteIntervencion.php");
  require("Fecha.php");
  require_once('ParamConf.php');

  $miParamConf = new ParamConf;

  $PacienteIntervencion = new PacienteIntervencion;
  $miFecha = new Fecha;

  $smarty  = new SmartyIni;
  $link = mysql_connect("localhost", "root", "") or die ("ERRRRRO");
  mysql_select_db("anestesia", $link) or die("EN ANestesia");

  $Where =  array();

  $Titulo = "Reporte de Recibos";

  if ($_POST["numreciboini"]){
    $Where[] = " num_recibo >= ". $_POST["numreciboini"];
    $Titulo .= "<br/>Desde " . $_POST["numreciboini"];
  }
  if ($_POST["numrecibofin"]){
    $Where[] = " num_recibo <= ". $_POST["numrecibofin"];
    $Titulo .= " Hasta " . $_POST["numrecibofin"];
  }
  if ($_POST["id_paciente"]){
    $Where[] = " id_paciente = ". $_POST["id_paciente"];
  }
  if ($_POST["fechainicial"]){
    $Where[] = " fecha_intervencion >= '". $miFecha->formatoDbFecha($_POST["fechainicial"])."'";
    $Titulo .= "<br/>Realizadas a Partir de " . $_POST["fechainicial"];
  }
  if ($_POST["fechafinal"]){
    $Where[] = " fecha_intervencion <= '". $miFecha->formatoDbFecha($_POST["fechafinal"])."'";
    $Titulo .= " Hasta " . $_POST["fechafinal"];
  }
  if ($_POST["id_tpoperacion"]){
    $Where[] = " id_tpoperacion = ". $_POST["id_tpoperacion"];
  }
  if ($_POST["id_doctor_cirujano"]){
    $Where[] = " id_doctor_cirujano = ". $_POST["id_doctor_cirujano"];
  }
  if ($_POST["id_doctor_anestesia"]){
    $Where[] = " id_doctor_anestesia = ". $_POST["id_doctor_anestesia"];
  }
  if ($_POST["id_responsable"]){
    $Where[] = " id_responsable = ". $_POST["id_responsable"];
  }
  if ($_POST["id_estatus"]){
    $Where[] = " id_estatus = ". $_POST["id_estatus"];
  }


  $ConsultaID = $PacienteIntervencion->consulta($link, join(" AND ", $Where));

// mostrarmos los registros
  
  $TotalReg = 0;
  $TotalMonto = 0;
  $Recibos = array(); 
  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_object($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos["id"]               = $row->id;
    $Datos['num_recibo']       = $row->num_recibo;
    $Datos['nombre_paciente']  = strtoupper($row->apellidopac.", ".$row->nombrepac);
    $Datos['fecha']            = $miFecha->formatoFecha($row->fecha_intervencion);
    $Datos['desctpopera']      = $row->desctpopera;
    $Datos['nombre_cirujano']  = strtoupper($row->apellidociru.", ".$row->nombreciru);
    $Datos['nombre_anestesia'] = strtoupper($row->apellidoanes.", ".$row->nombreanes);
    $Datos['monto_total']      = number_format ( $row->monto_total, 2, ",", "." );
    $Datos['nombre_respon']    = strtoupper($row->descrespon);
    $Datos['fecha_pago']       = ($row->id_estatus == 1) ? "" : $miFecha->formatoFecha($row->fecha_pago);
    $Datos['descestatus']      = strtoupper($row->descestatus);
    $Datos['clase']            = $clase;

    $Recibos[$row->id] = $Datos;
    $TotalReg++;
    $TotalMonto += $row->monto_total;
  }


    $smarty->assign('titulo', $Titulo);
    $smarty->assign('ArrRecibos', $Recibos);
    $smarty->assign('TotalReg', $TotalReg);
    $smarty->assign('TotalMonto', number_format ($TotalMonto, 2, ",", "."));
    $smarty->assign('nombre_log', '');

 	// récupération du contenu HTML
	ob_start();
#	include('localhost://exemple00.php');
	$content = $smarty->fetch('RecibosPdf.tpl'); //print $content;
	
	// conversion HTML => PDF
	//require_once('c:\wamp\www\html2pdf\html2pdf.class.php');
	//require_once('c:\xampp\htdocs\sociproma\html2pdf\html2pdf.class.php');
  require_once($miParamConf->getClassPdf());
	try
	{
          $html2pdf = new HTML2PDF('l','letter','es', false, 'UTF-8');
	  #$html2pdf->pdf->SetDisplayMode('real');
	  #$html2pdf->setModeDebug();
	  $html2pdf->setDefaultFont('Arial','','5');
	  $html2pdf->writeHTML(utf8_decode($content), isset($_GET['vuehtml']));
 	  $html2pdf->Output('exemple00.pdf');
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
