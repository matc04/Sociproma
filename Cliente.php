<?php

class Cliente {


/* variables de la clase Cliente */

  var $snombre;

  var $sapellido;

  var $fnacimeinto;

  var $sdireccion;

/* M�todo Constructor: Cada vez que creemos una variable

de esta clase, se ejecutar� esta funci�n */

function DB_mysql($snombre = "", $sapellido = "", $fnacimiento = "", $pass = "sdireccion") {
  $this->snombre     = $snombre;
  $this->sapellido   = $sapellido;
  $this->fnacimiento = $fnacimeinto;
  $this->sdireccion  = $sdireccion;
}
/* Ejecuta un consulta */

function consulta($Conexion_ID, $Where = ""){

  $SQL =  "SELECT * FROM cliente";

  if (!empty($Where)) {

    $SQL .= " WHERE " . $Where;
  }

//ejecutamos la consulta

  $this->Consulta_ID = @mysql_query($SQL, $Conexion_ID);

  if (!$this->Consulta_ID) {

    $this->Errno = mysql_errno();

    $this->Error = mysql_error();

  }

/* Si hemos tenido �xito en la consulta devuelve el identificador de la conexi�n, sino devuelve 0 */

  return $this->Consulta_ID;

}

/* Ejecuta un Insercion */

function create($Conexion_ID, $datos = ""){

  $miFecha = new Fecha;
	
  $query = "INSERT INTO cliente (snombre,sapellido,fnacimiento,sdireccion) 
            VALUES ('".$datos['snombre']."','".$datos['sapellido']."','".$datos['fnacimiento']."','".$datos['sdireccion']."')";

  $response = mysql_query($query, $Conexion_ID);

  print mysql_error($Conexion_ID);

  return $response;
}

/* Ejecuta un Actualizaci�n */

function actualiza($Conexion_ID, $Where = "", $datos = ""){

  print "EN ACTUALIZA";

  $miFecha = new Fecha;
	
  $query = "UPDATE cliente set snombre = '". $datos["snombre"] ."', sapellido = '". $datos['sapellido']."',fnacimiento = '". 
	  $datos["fnacimiento"]. "', sdireccion = '". $datos["sdireccion"] ."' WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  print mysql_error($Conexion_ID);

  return $response;
}

/* Ejecuta un Actualizaci�n del campo bactivo */

function elimina($Conexion_ID, $Where = ""){

  $query = "UPDATE cliente set bactivo = 0  WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  print mysql_error($Conexion_ID);

  return $response;
}

/* Devuelve el n�mero de campos de una consulta */

function numcampos() {

  return mysql_num_fields($this->Consulta_ID);

}

/* Devuelve el n�mero de registros de una consulta */

function numregistros(){

  return mysql_num_rows($this->Consulta_ID);

}

/* Devuelve el nombre de un campo de una consulta */

function nombrecampo($numcampo) {

  return mysql_field_name($this->Consulta_ID, $numcampo);

}

}
?>
