<body>

<html>

<?php

  session_start();

  require("MysqlDB.php");
  require("Doctor.php");
  require("Especialidad.php");
  require('SmartyIni.php');
  require('Session.php');
  require_once('ParamConf.php');

  $miParamConf = new ParamConf;

  $miSession = new Session;

  $miSession->delete_session();

  $smarty  = new SmartyIni;
  $miEspecialidad = new Especialidad;

  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION['apellido_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);
  $smarty->assign('subtitulo', '');
  $smarty->assign('error_msg', '');
  $smarty->assign('direccion', $miParamConf->getLocalhost());


  if(!$_SESSION['usuario_log']){
    $localhost = "location: ".$miParamConf->getLocalhost()."/iniciosesion.php";
    header($localhost);
  }

  $ConsultaId;

  $miconexion = new DB_mysql;

  $miDoctor  = new Doctor;

  $miconexion->conectar("", "", "", "");

  $Especialidad = $miEspecialidad->listarEspecialidad( $miconexion->Conexion_ID );
  $smarty->assign('esp_options', $Especialidad);

  if (!empty($_POST['accion']) ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ){

#Se muestran los datos asociados al id en tratamiento
      $Where = " me.id = " . $_POST["id"];
      $ConsultaId = $miDoctor->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_assoc($ConsultaId);
      if ($row){
        $smarty->assign('id',              $row["id"]);
        $smarty->assign('snombre',         $row["snombre"]);
        $smarty->assign('sapellido',       $row["sapellido"]);
        $smarty->assign('stelefono',       $row["stelefono"]);
	      $smarty->assign('stelefono_1',     $row["stelefono_1"]);
        $smarty->assign('id_especialidad', $row["esp_id"]);
      }
    }

#Se hace la inserciòn de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      if (crear( $miconexion->Conexion_ID, $miDoctor )){
        $smarty->assign('id',              "");
        $smarty->assign('snombre',         "");
        $smarty->assign('sapellido',       "");
        $smarty->assign('stelefono',       "");
        $smarty->assign('stelefono_1',     "");
        $smarty->assign('id_especialidad', "");
        $smarty->assign('error_msg', 'La creación de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la creación de los datos');
      }
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miDoctor )){
        $smarty->assign('id',              "");
        $smarty->assign('snombre',         "");
        $smarty->assign('sapellido',       "");
        $smarty->assign('stelefono',       "");
        $smarty->assign('stelefono_1',     "");
        $smarty->assign('id_especialidad', "");
        $smarty->assign('error_msg', 'La acualización de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualización de los datos');
      }
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      if (elimina( $miconexion->Conexion_ID, $miDoctor )){
        $smarty->assign('id',              "");
        $smarty->assign('snombre',         "");
        $smarty->assign('sapellido',       "");
        $smarty->assign('stelefono',       "");
        $smarty->assign('stelefono_1',     "");
        $smarty->assign('id_especialidad', "");
        $smarty->assign('error_msg', 'La Eliminación del registro se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de eliminar el registro');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miDoctor );
    }
  }
  else{
    $smarty->assign('id',              "");
    $smarty->assign('snombre',         "");
    $smarty->assign('sapellido',       "");
    $smarty->assign('stelefono',       "");
    $smarty->assign('stelefono_1',     "");
    $smarty->assign('id_especialidad', "");
  }

  $Where = " me.bactivo = 1 ";
  $ConsultaID = $miDoctor->consulta($miconexion->Conexion_ID, $Where);

  verconsulta( $smarty, $ConsultaID );


  $smarty->display('Doctor.tpl');




/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miDoctor ){

  $datos = array( "snombre"         => $_POST["snombre"],
	          "sapellido"       => $_POST["sapellido"],
		  "stelefono"       => $_POST["stelefono"],
		  "stelefono_1"     => $_POST["stelefono_1"],
		  "id_especialidad" => $_POST["id_especialidad"]);
  
  $resultado = $miDoctor->create( $Conexion_ID, $datos );

  return $resultado;

}

/* Realiza la actualizacón de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miDoctor ){

  $Where = " id = " . $_POST["id"];

  $datos = array( "id"              => $_POST["id"],
		  "snombre"         => $_POST["snombre"],
		  "sapellido"       => $_POST["sapellido"],
		  "stelefono"       => $_POST["stelefono"],
		  "stelefono_1"     => $_POST["stelefono_1"],
	          "id_especialidad" => $_POST["id_especialidad"] );
  
  $resultado = $miDoctor->actualiza( $Conexion_ID, $Where, $datos );

  return $resultado;
}

/* Realiza la actualizacón de los datos indicados en la forma */
function elimina( $Conexion_ID, $miDoctor ){

  $Where = " id = " . $_POST["id"];

  $resultado = $miDoctor->elimina( $Conexion_ID, $Where );

  return $resultado;

}

/* Muestra los datos de una consulta */

function verconsulta( $smarty, $ConsultaID ) {


// mostrarmos los registros
  
  $smarty->assign('titulo','Doctores');

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_object($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']                = $row->id;
    $Datos['snombre']           = strtoupper($row->snombre);
    $Datos['sapellido']         = strtoupper($row->sapellido);
    $Datos['stelefono']         = $row->stelefono;
    $Datos['stelefono_1']       = $row->stelefono_1;
    $Datos['desc_especialidad'] = utf8_encode(strtoupper($row->esp_descrip));
    $Datos['clase']             = $clase;

    $Doctores[$row->id] = $Datos;
  }

  $smarty->assign('ArrDoctores', $Doctores);

}

function buscar( $smarty, $Conexion_ID, $Doctor ) {

  $Where =  array();
  if ($_POST["snombre"]){
    $Where[] = " LOWER(snombre) like '%". strtolower($_POST["snombre"])."%'";
  }
  if ($_POST["sapellido"]){
    $Where[] = " LOWER(sapellido) like '%". strtolower($_POST["sapellido"])."%'";
  }
  if ($_POST["stelefono"]){
    $Where[] = " LOWER(stelefono) like '%". strtolower($_POST["stelefono"])."%'";
  }
  if ($_POST["id_especialidad"]){
    $Where[] = " id_especialidad = ". $_POST["id_especialidad"];
  }

  $ConsultaID = $Doctor->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']                = $row[0];
    $Datos['snombre']           = strtoupper($row[1]);
    $Datos['sapellido']         = strtoupper($row[2]);
    $Datos['stelefono']         = $row[3];
    $Datos['stelefono_1']       = $row[4];
    $Datos['desc_especialidad'] = $row[8];
    $Datos['clase']             = $clase;

    $Doctores[$row[0]] = $Datos;
  }

  $smarty->assign('ArrDoctores', $Doctores);

}

?>

</body>

</html>

