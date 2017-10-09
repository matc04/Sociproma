<?php

  session_start();

  require("MysqlDB.php");
  require("Doctor.php");
  require("Paciente.php");
  require("TipoOperacion.php");
  require("Responsable.php");
  require("Intervencion.php");
  require("PacienteIntervencion.php");
  require("DetallePacienteInterven.php");
  require("Fecha.php");
  require('SmartyIni.php');
  require_once('ParamConf.php');

  $miParamConf = new ParamConf;

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miDoctor   = new Doctor;

  $miPaciente = new Paciente;

  $miTipoOperacion = new TipoOperacion;

  $miResponsable = new Responsable;

  $miIntervencion = new Intervencion;

  $miPacienteIntervencion = new PacienteIntervencion;

  $miconexion->conectar("", "", "", "");

  if (!empty($_GET["accion"]) && ($_GET["accion"] == "buscarPacientes" || $_GET["accion"] == "buscarIntervenciones" ||
      $_GET['accion'] == "buscarResponsables" || $_GET['accion'] == "buscarCirujanos")){
    if ($_GET["accion"] == "buscarPacientes"){
      buscarPacientes( $miconexion->Conexion_ID, $miPaciente );
    }
    elseif ($_GET["accion"] == "buscarIntervenciones"){
      buscarIntervenciones( $miconexion->Conexion_ID, $miIntervencion );
    }elseif ($_GET["accion"] == "buscarResponsables"){
      buscarResponsables( $miconexion->Conexion_ID, $miResponsable );
    }elseif ($_GET["accion"] == "buscarCirujanos"){
      buscarCirujanos( $miconexion->Conexion_ID, $miDoctor );
    }
    exit;
  }

function buscarPacientes( $Conexion_ID, $Paciente ) {

  $Where =  array();
  if ($_GET["term"]){
    $Where[] = " LOWER(shistoria) like '%". strtolower($_GET["term"])."%'";
    $Where[] = " LOWER(snombre) like '%". strtolower($_GET["term"])."%'";
    $Where[] = " LOWER(sapellido) like '%". strtolower($_GET["term"])."%'";
  }

  $whereJ = "(".join(" OR ", $Where).") AND bactivo = 1 ";

  $Order = "sapellido";

  $ConsultaID = $Paciente->consulta($Conexion_ID, $whereJ, $Order);
// Retornamos los registros

  $a_json = Array();
  while ($row = mysql_fetch_row($ConsultaID)) {
    $a_json_row["id"] = $row[0];
    $a_json_row["value"] = utf8_encode($row[1]." --- ".$row[3].", ".$row[2]);
    $a_json_row["label"] = utf8_encode($row[1]." --- ".$row[3].", ".$row[2]);
    array_push($a_json, $a_json_row);
  }

  echo json_encode($a_json);
}

function buscarIntervenciones( $Conexion_ID, $Intervencion ) {

  $Where =  array();
  if ($_GET["term"]){
    $Where[] = " LOWER(sdescripcion) like '%". strtolower($_GET["term"])."%'";
    $Where[] = " bactivo = 1 ";
  }

  $ConsultaID = $Intervencion->consulta($Conexion_ID, join(" AND ", $Where));
// Retornamos los registros

  $a_json = Array();
  while ($row = mysql_fetch_row($ConsultaID)) {
    $a_json_row["id"] = $row[0];
    $a_json_row["value"] = utf8_encode($row[1]);
    $a_json_row["label"] = utf8_encode($row[1]);
    array_push($a_json, $a_json_row);
  }

  echo json_encode($a_json);
}

function buscarResponsables( $Conexion_ID, $Responsable ) {

  $Where =  array();
  if ($_GET["term"]){
    $Where[] = " LOWER(sdescripcion) like '%". strtolower($_GET["term"])."%'";
    $Where[] = " bactivo = 1 ";
  }

  $ConsultaID = $Responsable->consulta($Conexion_ID, join("AND", $Where));
// Retornamos los registros

  $a_json = Array();
  while ($row = mysql_fetch_row($ConsultaID)) {
    $a_json_row["id"] = $row[0];
    $a_json_row["value"] = utf8_encode($row[1]);
    $a_json_row["label"] = utf8_encode($row[1]);
    array_push($a_json, $a_json_row);
  }

  echo json_encode($a_json);
}

function buscarCirujanos( $Conexion_ID, $Doctor ) {

  $Where =  array();
  if ($_GET["term"]){
    $Where[] = " LOWER(snombre) like '%". strtolower($_GET["term"])."%'";
    $Where[] = " LOWER(sapellido) like '%". strtolower($_GET["term"])."%'";
  }

  $whereJ = "(".join(" OR ", $Where).")";

  $Where_1[] = " me.bactivo = 1 ";
  $Where_1[] = " me.id_especialidad = '1' ";

  $whereJ .= " AND ".join(" AND ", $Where_1);

  $ConsultaID = $Doctor->consulta($Conexion_ID, $whereJ);
// Retornamos los registros

  $a_json = Array();
  while ($row = mysql_fetch_row($ConsultaID)) {
    $a_json_row["id"] = $row[0];
    $a_json_row["value"] = utf8_encode($row[2].", ".$row[1]);
    $a_json_row["label"] = utf8_encode($row[2].", ".$row[1]);
    array_push($a_json, $a_json_row);
  }

  echo json_encode($a_json);
}

?>
