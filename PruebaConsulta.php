<body>

<html>

<?php

  session_start();

  require ("MysqlDB.php");
  require ("Cliente.php");
  require ("Fecha.php");
  require('c:\wamp\Smartylibs\Smarty.class.php');

  $miFecha = new Fecha;

  $smarty  = new Smarty;

  $smarty->template_dir = 'C:\wamp\www\templates';
  $smarty->config_dir = ' C:\wamp\www\Smartyconfig';
  $smarty->cache_dir = 'C:\wamp\Smartycache';
  $smarty->compile_dir = 'C:\wamp\Smartytemplates_c';

  $smarty->assign('usuario', $_SESSION['usuario']);

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miCliente  = new Cliente;

  $miconexion->conectar("anestesia", "localhost", "root", "mt2212");

  if (!empty($_POST['accion']) ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ){

#Se muestran los datos asociados al id en tratamiento
      $Where = " id = " . $_POST["id"];
      $ConsultaId = $miCliente->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_assoc($ConsultaId);
      if ($row){
        $smarty->assign('id',          $row["id"]);
        $smarty->assign('snombre',     $row["snombre"]);
        $smarty->assign('sapellido',   $row["sapellido"]);
        $smarty->assign('fnacimiento', $miFecha->formatoFecha($row["fnacimiento"]));
        $smarty->assign('sdireccion',  $row["sdireccion"]);
      }
    }

#Se hace la inserciòn de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      crear( $miconexion->Conexion_ID, $miCliente );
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      actualiza( $miconexion->Conexion_ID, $miCliente );
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      elimina( $miconexion->Conexion_ID, $miCliente );
    }
  }

  $Where = " bactivo = 1 ";
  $ConsultaID = $miCliente->consulta($miconexion->Conexion_ID, $Where);


  verconsulta( $smarty, $ConsultaID );

/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miCliente ){

  $miFecha = new Fecha;

  $datos = array( "snombre"     => $_POST["snombre"],
		  "sapellido"   => $_POST["sapellido"],
		  "fnacimiento" => $miFecha->formatoDbFecha($_POST["fnacimiento"]),
		  "sdireccion"  => $_POST["sdireccion"] );

  
  $resultado = $miCliente->create( $Conexion_ID, $datos );

}

/* Realiza la actualizacón de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miCliente ){


  $miFecha = new Fecha;

  $Where = " id = " . $_POST["id"];

  $datos = array( "id"          => $_POST["id"],
		  "snombre"     => $_POST["snombre"],
		  "sapellido"   => $_POST["sapellido"],
		  "fnacimiento" => $miFecha->formatoDbFecha($_POST["fnacimiento"]),
		  "sdireccion"  => $_POST["sdireccion"] );

  
  $resultado = $miCliente->actualiza( $Conexion_ID, $Where, $datos );

}

/* Realiza la actualizacón de los datos indicados en la forma */
function elimina( $Conexion_ID, $miCliente ){

  $miFecha = new Fecha;

  $Where = " id = " . $_POST["id"];

  $resultado = $miCliente->elimina( $Conexion_ID, $Where );

}

/* Muestra los datos de una consulta */

function verconsulta( $smarty, $ConsultaID ) {

  $miFecha = new Fecha;

// mostrarmos los registros
  
  $smarty->assign('titulo','Pacientes Anestesia');

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']          = $row[0];
    $Datos['snombre']     = $row[1];
    $Datos['sapellido']   = $row[2];
    $Datos['fnacimiento'] = $miFecha->formatoFecha($row[3]);
    $Datos['sdireccion']  = $row[4];
    $Datos['clase']       = $clase;

    $Clientes[$row[0]] = $Datos;
  }

  $smarty->assign('ArrClientes', $Clientes);
  $smarty->display('Clientes.tpl');

}

?>

</body>

</html>

