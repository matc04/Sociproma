<?php

class DetallePacienteInterven {


/* variables de la clase DetallePacienteInterven */

  var $id_paciente_intervencion;

  var $id_intervencion;

  var $nmonto;

  var $sobservacion;

/* Método Constructor: Cada vez que creemos una variable

de esta clase, se ejecutará esta función */

function DetallePacienteInterven($id_paciente_intervencion = "", $id_intervencion = "", $nmonto = "", $sobservacion = ""  ) {

  $this->id_paciente_intervencion = $id_paciente_intervencion;
  $this->id_intervencion          = $id_intervencion;
  $this->nmonto                   = $nmonto;
  $this->sobservacion             = $sobservacion;
}

/* Ejecuta un consulta */

function consulta($Conexion_ID, $Where = ""){

  $SQL =  " SELECT me.id, me.id_paciente_intervencion, me.id_intervencion,
	           me.nmonto, me.sobservacion
              FROM detalle_paciente_interven me
             WHERE  ";

  if (!empty($Where)) {
    $SQL .= $Where;
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

  $query = "INSERT INTO detalle_paciente_interven ( id_paciente_intervencion, id_intervencion, nmonto, sobservacion ) 
	                                VALUES    ('".$datos["id_paciente_inter"]."','".$datos["id_intervencion"]."','".
	                                              $datos["nmonto"]."','".$datos["sobservacion"]."')";

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización del campo bactivo */

function elimina($Conexion_ID, $Where = ""){

  $query = "DELETE FROM detalle_paciente_interven WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

}
?>
