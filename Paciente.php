<?php

class Paciente {


/* variables de la clase Paciente */

  var $shistoria;

  var $snombre;

  var $sapellido;

/* Método Constructor: Cada vez que creemos una variable

de esta clase, se ejecutará esta función */

function Paciente($shistoria = "", $snombre = "", $sapellido = "", $edad = '' ) {
  $this->shistoria = $shistoria;
  $this->snombre   = $snombre;
  $this->sapellido = $sapellido;
  $this->edad      = $edad;
}
/* Ejecuta un consulta */

function consulta($Conexion_ID, $Where = "", $Order = ""){

  $SQL =  "SELECT * FROM paciente";

  if (!empty($Where)) {

    $SQL .= " WHERE " . $Where;
  }

  if (!empty($Order)) {

    $SQL .= " ORDER BY " . $Order;
  }

//ejecutamos la consulta

  $this->Consulta_ID = @mysql_query($SQL, $Conexion_ID);

  if (!$this->Consulta_ID) {

    $this->Errno = mysql_errno();

    $this->error = mysql_error();
    print $this->error;

  }

/* Si hemos tenido éxito en la consulta devuelve el identificador de la conexión, sino devuelve 0 */

  return $this->Consulta_ID;

}

/* Ejecuta un Insercion */

function create($Conexion_ID, $datos = ""){

  $query = "INSERT INTO paciente (shistoria,snombre,sapellido,edad) 
            VALUES ('".$datos['shistoria']."','".
                       strtoupper(utf8_decode($datos['snombre']))."','".
                       strtoupper(utf8_decode($datos['sapellido']))."','".
                       $datos['edad']."')";

  $response = mysql_query($query, $Conexion_ID);

  mysql_error($Conexion_ID);

  $this->error = mysql_error();

  return $response;
}

/* Ejecuta un Actualización */

function actualiza($Conexion_ID, $Where = "", $datos = ""){

  $query = "UPDATE paciente set shistoria = '". $datos["shistoria"].
           "', snombre = '". strtoupper(utf8_decode($datos["snombre"])).
           "', sapellido = '". strtoupper(utf8_decode($datos['sapellido'])). 
           "', edad = '".$datos['edad']."' WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización del campo bactivo */

function elimina($Conexion_ID, $Where = ""){

  $query = "UPDATE paciente set bactivo = 0  WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

function listarPaciente($Conexion_ID){

  $query = "SELECT id,shistoria,sapellido,snombre, edad FROM paciente  WHERE bactivo = 1  ORDER BY sapellido, snombre";

//ejecutamos la consulta

  $this->Consulta_ID = @mysql_query($query, $Conexion_ID);

  if (!$this->Consulta_ID) {

    $this->Errno = mysql_errno();

    $this->error = mysql_error();

  }

  $Datos[0] = "Seleccione -----";
  while ($row = mysql_fetch_row($this->Consulta_ID)) {

    $Datos[$row[0]] = $row[1]." -- ".utf8_encode(strtoupper($row[2])).", ".strtoupper($row[3]);

  }

  return $Datos;
}

function listarPaciente1($Conexion_ID){

  $query = "SELECT id,shistoria,sapellido,snombre, edad FROM paciente  WHERE bactivo = 1  ORDER BY sapellido, snombre";

//ejecutamos la consulta

  $this->Consulta_ID = @mysql_query($query, $Conexion_ID);

  if (!$this->Consulta_ID) {

    $this->Errno = mysql_errno();

    $this->error = mysql_error();

  }

  $Datos[0] = "Seleccione -----";
  while ($row = mysql_fetch_row($this->Consulta_ID)) {

    $Datos[$row[0]] = $row[1]." -- ".utf8_encode(strtoupper($row[2])).", ".strtoupper($row[3]);

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
