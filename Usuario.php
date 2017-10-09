<?php

class Usuario {


/* variables de la clase Usuario */

  var $snombre;

  var $sapellido;

  var $susuario;

  var $scontrasena;

  var $scorreo;

  var $badministrador;

/* Método Constructor: Cada vez que creemos una variable

de esta clase, se ejecutará esta función */

function Usuario($snombre = "", $sapellido = "", $susuario = "", $scontrasena = "", $scorreo = "", $badministrador = "" ) {
  $this->snombre        = $snombre;
  $this->sapellido      = $sapellido;
  $this->susuario       = $susuario;
  $this->scontrasena    = $scontrasena;
  $this->scorreo        = $scorreo;
  $this->badministrador = $badministrador;
}
/* Ejecuta un consulta */

function consulta($Conexion_ID, $Where = ""){

  $SQL =  "SELECT * FROM usuario";

  if (!empty($Where)) {

    $SQL .= " WHERE " . $Where;
  }

//ejecutamos la consulta

  $this->Consulta_ID = @mysql_query($SQL, $Conexion_ID);

  if (!$this->Consulta_ID) {

    $this->Errno = mysql_errno();

    $this->Error = mysql_error();

  }

/* Si hemos tenido éxito en la consulta devuelve el identificador de la conexión, sino devuelve 0 */

  return $this->Consulta_ID;

}

/* Ejecuta un Insercion */

function create($Conexion_ID, $datos = ""){

  $query = "INSERT INTO usuario (snombre,sapellido,susuario,scontrasena,scorreo,badministrador, id_doctor) 
	    VALUES ('".$datos['snombre']."','".$datos['sapellido']."','".$datos['susuario']."','".sha1($datos['scontrasena'])."','".
	            $datos['scorreo']."','".$datos['badministrador'][0]."','".$datos['id_doctor']."')";

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización */

function actualiza($Conexion_ID, $Where = "", $datos = ""){

  $query = "UPDATE usuario set snombre = '". $datos["snombre"] .
		           "', sapellido = '". $datos['sapellido']. 
	                   "', susuario = '". $datos['susuario'].
		           "', scontrasena = '". sha1($datos['scontrasena']).
		           "', scorreo = '". $datos['scorreo'].
	                   "', badministrador = '". $datos['badministrador'][0]."' WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización del campo bactivo */

function elimina($Conexion_ID, $Where = ""){

  $query = "UPDATE usuario set bactivo = 0  WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Devuelve el número de campos de una consulta */

function numcampos() {

  return mysql_num_fields($this->Consulta_ID);

}

/* Devuelve el número de registros de una consulta */

function numregistros(){

  return mysql_num_rows($this->Consulta_ID);

}

/* Devuelve el nombre de un campo de una consulta */

function nombrecampo($numcampo) {

  return mysql_field_name($this->Consulta_ID, $numcampo);

}

}
?>
