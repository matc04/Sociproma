<?php

  session_start();

  require ("MysqlDB.php");
  require ("Doctor.php");
  require ("Paciente.php");
  require ("TipoOperacion.php");
  require ("Responsable.php");
  require ("Intervencion.php");
  require ("PacienteIntervencion.php");
  require ("Fecha.php");
  require('SmartyIni.php');
  require('ParamConf.php');

  $miParamConf = new ParamConf;

  $smarty  = new SmartyIni;

  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION['apellido_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);
  $smarty->assign('subtitulo', '');
  $smarty->assign('error_msg', '');

  $smarty->assign('titulo','Pagar Recibo');

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

  $miconexion->conectar("", "", "", "");

  $Paciente = $miPaciente->listarPaciente( $miconexion->Conexion_ID );
  $smarty->assign('pacien_options', $Paciente);

  if (!empty($_POST['accion']) && $_POST['accion'] != "buscarPacientes" ) {

#Se hace la inserciòn de los valores de la pantalla

    if( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miPacienteIntervencion )){
        $smarty->assign('error_msg', 'La acualización de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualización de los datos');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miPacienteIntervencion);
    }

    $smarty->display('PagarRecibo.tpl');
  }
  else{

    if (!empty($_POST["accion"]) && ($_POST["accion"] == "buscarPacientes") ){
      if ($_POST["accion"] == "buscarPacientes"){
        buscarPacientes( $miconexion->Conexion_ID, $miPaciente );
      }
      exit;
    }
    else{
      $smarty->assign('id',                  '');
      $smarty->assign('num_recibo',          '');
      $smarty->assign('id_paciente',         '');
      $smarty->assign('fecha',               '');
      $smarty->assign('id_tpoperacion',      '');
      $smarty->assign('id_doctor_cirujano',  '');
      $smarty->assign('id_doctor_anestesia', '');
      $smarty->assign('monto_total',         '');
      $smarty->assign('id_responsable',      '');
      $smarty->assign('sobservacion',        '');
      
      $smarty->display('PagarRecibo.tpl');
    }
  }

/* Realiza la actualizacón de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miPacienteIntervencion ){

  $miFecha = new Fecha;

  $Where = " id = " . $_POST["id"];

  $datos = array( "monto_pagado" => $_POST["monto_pagado"],
	          "fecha_pago"   => $miFecha->formatoDBFecha($_POST["fecha_pago"]) );
  
  $resultado = $miPacienteIntervencion->pagar( $Conexion_ID, $Where, $datos );

  return $resultado;
}

function buscar( $smarty, $Conexion_ID, $PacienteIntervencion ) {

  $miFecha  = new Fecha;

#Se muestran los datos asociados al id en tratamiento
  if ($_POST["num_recibo"]){
    $Where[] = " me.num_recibo = " . $_POST["num_recibo"];
  }
  if ($_POST["id_paciente"]){
    $Where[] = " me.id_paciente = " . $_POST["id_paciente"];
  }
  if ($_POST["fecha"]){
    $Where[] = " me.fecha_intervencion = '" . $miFecha->formatoDbFecha($_POST["fecha"])."'";
  }

  $WhereText =  "(" . join(" OR ", $Where) . ")";
  $WhereText .= " AND me.id_estatus = 1 ";

  $ConsultaID = $PacienteIntervencion->consulta($Conexion_ID, $WhereText);
// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  If ($row = mysql_fetch_object($ConsultaID)) {

    $smarty->assign('id',                 $row->id);
    $smarty->assign('num_recibo',         $row->num_recibo);
    $smarty->assign('id_paciente',        $row->id_paciente);
    $smarty->assign('nombre_paciente',    strtoupper($row->nombrepac.", ".$row->apellidopac));
    $smarty->assign('fecha',              $miFecha->formatoFecha($row->fecha_intervencion));
    $smarty->assign('nombre_tpopera',     strtoupper($row->desctpopera));
    $smarty->assign('nombre_cirujano',    strtoupper($row->apellidociru.", ".$row->nombreciru));
    $smarty->assign('nombre_anestesia',   strtoupper($row->apellidoanes.", ".$row->nombreanes));
    $smarty->assign('monto_total',        $row->monto_total);
    $smarty->assign('nombre_responsable', strtoupper($row->descrespon));
    $smarty->assign('fecha_pago', '');
  }
  else{
    $smarty->assign('error_msg', 'No hay información para el criterio de búsqueda indicado, o el recibo ya esta pagado, por favor verifique');
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

?>
