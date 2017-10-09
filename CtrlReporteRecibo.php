<?php

  session_start();

  require ("MysqlDB.php");
  require ("Doctor.php");
  require ("Paciente.php");
  require ("TipoOperacion.php");
  require ("Responsable.php");
  require ("Intervencion.php");
  require ("PacienteIntervencion.php");
  require ("DetallePacienteInterven.php");
  require ("Fecha.php");
  require ("Estatus.php");
  require("SmartyIni.php");
  require('ParamConf.php');

  $miParamConf = new ParamConf;

  $smarty  = new SmartyIni;

  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION['apellido_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);

  $smarty->assign('titulo','Consulta de Recibos');

  if(!$_SESSION['usuario_log']){
    $direcc = "location: ".$miParamConf->getLocalhost()."/iniciosesion.php";
    header($direcc);
  }

  $ConsultaId;


  $miconexion = new DB_mysql ;

  $miDoctor   = new Doctor;

  $miPaciente = new Paciente;

  $miTipoOperacion = new TipoOperacion;

  $miResponsable = new Responsable;

  $miIntervencion = new Intervencion;

  $miPacienteIntervencion = new PacienteIntervencion;

  $miDetallePacienteInterven = new DetallePacienteInterven;

  $miEstatus = new Estatus;

  $miconexion->conectar("", "", "", "");

  $Paciente = $miPaciente->listarPaciente( $miconexion->Conexion_ID );
  $smarty->assign('pacien_options', $Paciente);

  $TipoOperacion = $miTipoOperacion->listarTipoOperacion( $miconexion->Conexion_ID );
  $smarty->assign('tpopera_options', $TipoOperacion);

  $DoctorCiru = $miDoctor->listarDoctores( $miconexion->Conexion_ID, " id_especialidad = 1 " );
  $smarty->assign('doctorCiru_options', $DoctorCiru);

  $DoctorAnes = $miDoctor->listarDoctores( $miconexion->Conexion_ID, " id_especialidad = 2 " );
  $smarty->assign('doctorAnes_options', $DoctorAnes);

  $Responsable = $miResponsable->listarResponsable( $miconexion->Conexion_ID );
  $smarty->assign('respon_options', $Responsable);

  $Estatus = $miEstatus->listarEstatus( $miconexion->Conexion_ID );
  $smarty->assign('estatus_options', $Estatus);

  if (!empty($_POST['accion']) && $_POST['accion'] != "buscarPacientes" ) {
    if ($_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miPacienteIntervencion );
    }
    elseif ( $_POST["accion"] == "generar" ){
      salida_pdf( $smarty, $miconexion->Conexion_ID, $miPacienteIntervencion );
    }
  }
  else{

    if (!empty($_POST["accion"]) && ($_POST["accion"] == "buscarPacientes")  && !empty($_POST["criterio"]) ){
      if ($_POST["accion"] == "buscarPacientes"){
        buscarPacientes( $miconexion->Conexion_ID, $miPaciente );
      }
      elseif ($_POST["accion"] == "buscarIntervenciones"){
        buscarIntervenciones( $miconexion->Conexion_ID, $miIntervencion );
      }
      exit;
    }

    $smarty->assign('numreciboini',        '');
    $smarty->assign('numrecibofin',        '');
    $smarty->assign('id_paciente',         '');
    $smarty->assign('fechainicial',        '');
    $smarty->assign('fechafinal',          '');
    $smarty->assign('id_tpoperacion',      '');
    $smarty->assign('id_doctor_cirujano',  '');
    $smarty->assign('id_doctor_anestesia', '');
    $smarty->assign('id_responsable',      '');
    $smarty->assign('id_estatus',          '');
  }
      
  if ( !isset($_POST["bgenera"]) || $_POST["bgenera"] != 1 ){
    $smarty->display('ReporteRecibo.tpl');
  }

function buscar( $smarty, $Conexion_ID, $PacienteIntervencion ) {

  $miFecha = new Fecha;

  $Where =  array();

  if ($_POST["numreciboini"]){
    $Where[] = " num_recibo >= ". $_POST["numreciboini"];
  }
  if ($_POST["numrecibofin"]){
    $Where[] = " num_recibo <= ". $_POST["numrecibofin"];
  }
  if ($_POST["id_paciente"]){
    $Where[] = " id_paciente = ". $_POST["id_paciente"];
  }
  if ($_POST["fechainicial"]){
    $Where[] = " fecha_intervencion >= '". $miFecha->formatoDbFecha($_POST["fechainicial"])."'";
  }
  if ($_POST["fechafinal"]){
    $Where[] = " fecha_intervencion <= '". $miFecha->formatoDbFecha($_POST["fechafinal"])."'";
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

  $ConsultaID = $PacienteIntervencion->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  
  $TotalReg = 0;
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
    $Datos['monto_total']      = $row->monto_total;
    $Datos['nombre_respon']    = strtoupper($row->descrespon);
    $Datos['fecha_pago']       = ($row->id_estatus == 1) ? "&nbsp;" : $miFecha->formatoFecha($row->fecha_pago);
    $Datos['descestatus']      = strtoupper($row->descestatus);
    $Datos['clase']            = $clase;

    $Recibos[$row->id] = $Datos;
    $TotalReg++;
  }

  if ($Recibos){

    if ( !$_POST["bgenera"] ){
      $smarty->assign('ArrRecibos', $Recibos);

      $smarty->assign('numreciboini',        $_POST['numreciboini']);
      $smarty->assign('numrecibofin',        $_POST['numrecibofin']);
      $smarty->assign('id_paciente',         $_POST['id_paciente']);
      $smarty->assign('fechainicial',        $_POST['fechainicial']);
      $smarty->assign('fechafinal',          $_POST['fechafinal']);
      $smarty->assign('id_tpoperacion',      $_POST['id_tpoperacion']);
      $smarty->assign('id_doctor_cirujano',  $_POST['id_doctor_cirujano']);
      $smarty->assign('id_doctor_anestesia', $_POST['id_doctor_anestesia']);
      $smarty->assign('id_responsable',      $_POST['id_responsable']);
      $smarty->assign('id_estatus',          $_POST['id_estatus']);
    }
    else{
      $smarty->assign("ArrRecibos", $Recibos);
      $smarty->assign('TotalReg', $TotalReg);
      $content = $smarty->fetch('ReportePdf.tpl');//print $content;
	
// conversion HTML => PDF
      require_once('c:\wamp\www\html2pdf\html2pdf.class.php');
      try {
        $html2pdf = new HTML2PDF('P','A4','fr', false, 'ISO-8859-15');
//      $html2pdf->setModeDebug();
	$html2pdf->setDefaultFont('Arial');
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	$html2pdf->Output('ReporteRecibo.pdf');
      }
      catch(HTML2PDF_exception $e) { echo $e; };

      exit;
    }
  }
  else{
    $smarty->assign('error_msg', 'No hay información para el criterio de búsqueda indicado');
  }

}

function buscarPacientes( $Conexion_ID, $Paciente ) {

  $Where =  array();
  if ($_POST["criterio"]){
    $Where[] = " LOWER(shistoria) like '%". strtolower($_POST["criterio"])."%'";
    $Where[] = " LOWER(snombre) like '%". strtolower($_POST["criterio"])."%'";
    $Where[] = " LOWER(sapellido) like '%". strtolower($_POST["criterio"])."%'";
  }

  $ConsultaID = $Paciente->consulta($Conexion_ID, join(" OR ", $Where));
// Retornamos los registros

  $Retorno = "";
  while ($row = mysql_fetch_row($ConsultaID)) {
    $Retorno .= $row[0].":".$row[1]." - ".$row[3].", ".$row[2]."|";
  }

  echo $Retorno;

}

function buscarIntervenciones( $Conexion_ID, $Intervencion ) {

  $Where =  array();
  if ($_POST["criterio"]){
    $Where[] = " LOWER(sdescripcion) like '%". strtolower($_POST["criterio"])."%'";
	$Where[] = " bactivo = 1 ";
  }

  $ConsultaID = $Intervencion->consulta($Conexion_ID, join("AND", $Where));
// Retornamos los registros

  $Retorno = "";
  while ($row = mysql_fetch_row($ConsultaID)) {
    $Retorno .= $row[0].":".$row[1].":".$row[2]."|";
  }

  echo $Retorno;

}

function salida_pdf( $smarty, $Conexion_ID, $PacienteIntervencion ) {

  $miFecha = new Fecha;

  $Where =  array();

  if ($_POST["numreciboini"]){
    $Where[] = " num_recibo >= ". $_POST["numreciboini"];
  }
  if ($_POST["numrecibofin"]){
    $Where[] = " num_recibo <= ". $_POST["numrecibofin"];
  }
  if ($_POST["id_paciente"]){
    $Where[] = " id_paciente = ". $_POST["id_paciente"];
  }
  if ($_POST["fechainicial"]){
    $Where[] = " fecha_intervencion >= '". $miFecha->formatoDbFecha($_POST["fechainicial"])."'";
  }
  if ($_POST["fechafinal"]){
    $Where[] = " fecha_intervencion <= '". $miFecha->formatoDbFecha($_POST["fechafinal"])."'";
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

  $ConsultaID = $PacienteIntervencion->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  
  $TotalReg = 0;
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
    $Datos['monto_total']      = $row->monto_total;
    $Datos['nombre_respon']    = strtoupper($row->descrespon);
    $Datos['fecha_pago']       = ($row->id_estatus == 1) ? "&nbsp;" : $miFecha->formatoFecha($row->fecha_pago);
    $Datos['descestatus']      = strtoupper($row->descestatus);
    $Datos['clase']            = $clase;

    $Recibos[$row->id] = $Datos;
    $TotalReg++;
  }

  if ($Recibos){

    $smarty->assign('ArrRecibos', $Recibos);
    $smarty->assign('TotalReg', $TotalReg);

    $content = $smarty->fetch('RecibosPdf.tpl');
	
// conversion HTML => PDF
    require_once('c:\wamp\www\html2pdf\html2pdf.class.php');
    try {
          $html2pdf = new HTML2PDF('P','A4','fr', false, 'ISO-8859-15');
//        $html2pdf->setModeDebug();
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
          $html2pdf->Output('ReporteRecibo.pdf');
        }
    catch(HTML2PDF_exception $e) { echo $e; };

  }
}

?>
