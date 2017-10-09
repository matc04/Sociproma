<?php

class Intervencion {


/* variables de la clase Intervencion */

  var $sdescripcion;

  var $bactivo;

/* Método Constructor: Cada vez que creemos una variable

de esta clase, se ejecutará esta función */

function Intervencion($sdescripcion = "", $nmonto_ref = "", $bactivo = "" ) {
  $this->sdescripcion = $sdescripcion;
  $this->nmonto_ref   = $nmonto_ref;
  $this->bactivo      = $bactivo;
}
/* Ejecuta un consulta */

function consulta($Conexion_ID, $Where = ""){

  $SQL =  "SELECT * FROM intervencion";

  if (!empty($Where)) {

    $SQL .= " WHERE " . $Where;
  }

  $SQL .= " ORDER BY sdescripcion ";

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

  $Monto = ($_POST["nmonto_ref"]) ? $_POST["nmonto_ref"] : 0;
  $query = "INSERT INTO intervencion (sdescripcion, nmonto_ref) VALUES ('".$datos['sdescripcion']."','". $Monto ."')";

  $response = mysql_query($query, $Conexion_ID);

  mysql_error($Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización */

function actualiza($Conexion_ID, $Where = "", $datos = ""){

  $Monto = ($_POST["nmonto_ref"]) ? $_POST["nmonto_ref"] : 0;
  $query = "UPDATE intervencion set sdescripcion = '". $datos["sdescripcion"] . "', nmonto_ ref = '". $Monto ."' WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización del campo bactivo */

function elimina($Conexion_ID, $Where = ""){

  $query = "UPDATE intervencion set bactivo = 0  WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

function listarIntervencion($Conexion_ID){

  $query = "SELECT id,sdescripcion FROM intervencion  WHERE bactivo = 1 ";

//ejecutamos la consulta

  $this->Consulta_ID = @mysql_query($query, $Conexion_ID);

  if (!$this->Consulta_ID) {

    $this->Errno = mysql_errno();

    $this->Error = mysql_error();

  }

  $Datos[0] = "Seleccione -----";
  while ($row = mysql_fetch_row($this->Consulta_ID)) {

    $Datos[$row[0]] = $row[1];

  }

  return $Datos;
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
