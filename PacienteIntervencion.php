<?php

class PacienteIntervencion {


/* Método Constructor: Cada vez que creemos una variable

de esta clase, se ejecutará esta función */

  function PacienteIntervencion($num_recibo = "", $id_paciente = "", $id_tpoperacion = "", $id_doctor_cirujano = "", 
	                              $id_doctor_anestesia = "", $monto_total = "", $id_responsable = "", $fecha_pago = "", 
				                        $fecha_intervencion = "", $monto_pagado = "", $sobservacion = "", $monto_sap = "", $monto_preva = '',
                                $id_intervencion = '', $monto_anestesia = '' ) {

  $this->num_recibo          = $num_recibo;
  $this->id_paciente         = $id_paciente;
  $this->id_tpoperacion      = $id_tpoperacion;
  $this->id_doctor_cirujano  = $id_doctor_cirujano;
  $this->id_doctor_anestesia = $id_doctor_anestesia;
  $this->monto_total         = $monto_total;
  $this->id_responsable      = $id_responsable;
  $this->fecha_pago          = $fecha_pago;
  $this->fecha_intervencion  = $fecha_intervencion;
  $this->monto_pagado        = $monto_pagado;
  $this->sobservacion        = $sobservacion;
  $this->monto_sap           = $monto_sap;
  $this->monto_preva         = $monto_preva;
  $this->id_intervencion     = $id_intervencion;
  $this->monto_anestesia     = $monto_anestesia;
}
/* Ejecuta un consulta */

function consulta($Conexion_ID, $Where = ""){

  $SQL =  " SELECT me.id, me.num_recibo, me.id_paciente,
	                 me.id_tpoperacion, me.id_doctor_cirujano,
		               me.id_doctor_anestesia, me.monto_total,
		               me.id_responsable, me.fecha_pago,
		               me.fecha_intervencion, me.monto_pagado,
		               me.sobservacion, me.id_estatus, 
		               me.monto_sap, me.monto_preva, 
                               me.monto_anestesia, pac.snombre AS nombrepac, 
		               pac.sapellido AS apellidopac, ciru.snombre AS nombreciru, 
		               ciru.sapellido AS apellidociru, anes.snombre AS nombreanes, 
		               anes.sapellido AS apellidoanes, tpopera.sdescripcion AS desctpopera, 
		               respon.sdescripcion AS descrespon, est.sdescripcion AS descestatus,
			       me.id_intervencion, tpinterven.sdescripcion AS descintervencion,
                               pac.shistoria AS historia
              FROM paciente_intervencion me, paciente pac, 
	                 doctor ciru, doctor anes, 
	                 tipo_operacion tpopera, 
		               responsable respon, estatus est,
                   intervencion tpinterven
             WHERE me.id_paciente = pac.id
               AND me.id_doctor_cirujano = ciru.id
               AND me.id_doctor_anestesia = anes.id
               AND me.id_tpoperacion = tpopera.id
               AND me.id_responsable = respon.id
	             AND me.id_estatus = est.id
               AND me.id_intervencion = tpinterven.id ";


  if (!empty($Where)) {
    $SQL .= " AND " . $Where;
  }

//ejecutamos la consulta

  $this->Consulta_ID = @mysql_query($SQL, $Conexion_ID);

  if (!$this->Consulta_ID) {

    $this->Errno = mysql_errno();

    $this->Error = mysql_error();

    print $this->Error;

  }

/* Si hemos tenido éxito en la consulta devuelve el identificador de la conexión, sino devuelve 0 */

  return $this->Consulta_ID;

}

/* Ejecuta un Insercion */

function create($Conexion_ID, $datos = ""){

  if (strpos($datos['monto_total'], ",")){
    $monto_total = str_replace(".","",$datos['monto_total']);
    $monto_total = str_replace(",",".",$monto_total);
  }
  if (strpos($datos['monto_sap'], ",")){
    $monto_sap = str_replace(".","",$datos['monto_sap']);
    $monto_sap = str_replace(",",".",$monto_sap);
  }
  if (strpos($datos['monto_preva'], ",")){
    $monto_preva = str_replace(".","",$datos['monto_preva']);
    $monto_preva = str_replace(",",".",$monto_preva);
  }
  if (strpos($datos['monto_preva'], ",")){
    $monto_anestesia = str_replace(".","",$datos['monto_anestesia']);
    $monto_anestesia = str_replace(",",".",$monto_anestesia);
  }

  if (!$datos['id_responsable']){
    $datos['id_responsable'] = 99999999;
  }
  


  $query = "INSERT INTO paciente_intervencion ( num_recibo, id_paciente,
                                                id_tpoperacion, id_doctor_cirujano, 
                                                id_doctor_anestesia, monto_total, 
                                                id_responsable, fecha_intervencion, 
						                                    sobservacion, monto_sap, monto_preva,
                                                id_intervencion, monto_anestesia ) 
			               VALUES ('".$datos['num_recibo']."','".$datos['id_paciente']."','".
                                $datos['id_tpoperacion']."','".$datos['id_doctor_cirujano']."','".
                                $datos['id_doctor_anestesia']."','".$monto_total."','".
						                    $datos['id_responsable']."','".$datos['fecha']."','".
						                    $datos['sobservacion']."','". $monto_sap."','". $monto_preva."','".
						                    $datos['id_intervencion']."','".$monto_anestesia."')";

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización */

function actualiza($Conexion_ID, $Where = "", $datos = ""){

  if (strpos($datos['monto_total'], ",")){
    $monto_total = str_replace(".","",$datos['monto_total']);
    $monto_total = str_replace(",",".",$monto_total);
  }
  if (strpos($datos['monto_sap'], ",")){
    $monto_sap = str_replace(".","",$datos['monto_sap']);
    $monto_sap = str_replace(",",".",$monto_sap);
  }
  if (strpos($datos['monto_preva'], ",")){
    $monto_preva = str_replace(".","",$datos['monto_preva']);
    $monto_preva = str_replace(",",".",$monto_preva);
  }
  if (strpos($datos['monto_anestesia'], ",")){
    $monto_anestesia = str_replace(".","",$datos['monto_anestesia']);
    $monto_anestesia = str_replace(",",".",$monto_anestesia);
  }

  if (!$datos['id_responsable']){
    $datos['id_responsable'] = 99999999;
  }

  $query = "UPDATE paciente_intervencion set id_paciente = '". $datos["id_paciente"] ."', id_tpoperacion = '". $datos['id_tpoperacion']. 
	                         "', id_doctor_cirujano = '". $datos['id_doctor_cirujano'].
	                         "', id_doctor_anestesia = '". $datos['id_doctor_anestesia'].
	                         "', monto_total = '". $monto_total.
	                         "', id_responsable = '". $datos['id_responsable'].
	                         "', fecha_intervencion = '". $datos['fecha'].
				                   "', sobservacion = '". $datos['sobservacion'].
				                   "', monto_sap = '". $monto_sap.
				                   "', id_intervencion = '". $datos['id_intervencion'].
                           "', monto_anestesia = '". $monto_anestesia.
				                   "', monto_preva = '". $monto_preva."' WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response;
}

/* Ejecuta un Actualización para dar el recibo como pagado */

function pagar($Conexion_ID, $Where = "", $datos = ""){

  if (strpos($datos['monto_pagado'], ",")){
    $datos['monto_pagado'] = str_replace(".","",$datos['monto_pagado']);
    $datos['monto_pagado'] = str_replace(",",".",$datos['monto_pagado']);
  }

  #Se busca el monto pagado del recibo en tratamiento

  $SQL =  " SELECT me.id, me.id_estatus, me.monto_total,
                   me.monto_pagado
              FROM paciente_intervencion me WHERE " . $Where;

  $this->Consulta_ID = mysql_query($SQL, $Conexion_ID);

  if ($row = mysql_fetch_row($this->Consulta_ID)) {
    $monto_total = 0;
    $monto_pagado = 0;
    if (isset($row[0])){
      if (strpos($row[3], ",")){
        $monto_pagado = str_replace(".","",$row[3]);
        $monto_pagado = str_replace(",",".",$monto_pagado);
      }

      if (strpos($row[2], ",")){
        $monto_total = str_replace(".","",$row[2]);
        $monto_total = str_replace(",",".",$monto_total);
      }
  
      if ($monto_total == $datos['monto_pagado'] || ($monto_pagado + $datos['monto_pagado']) == $monto_total){
        $datos['estatus'] = 2;
      } else {
        $datos['estatus'] = 3; 
      } 
    }
  }

  $query = "UPDATE paciente_intervencion ".
           " set monto_pagado = (monto_pagado + ".$datos['monto_pagado']."),".
           " fecha_pago = '". $datos['fecha_pago']. "', ".
           " id_estatus = ". $datos['estatus'] ."  WHERE ";

  if (!empty($Where)){
    $query .= $Where;
  }

  $response = mysql_query($query, $Conexion_ID);

  return $response; 
}

/* Ejecuta un Actualización del campo bactivo */

function elimina($Conexion_ID, $Where = ""){

  $query = "UPDATE paciente_intervencion set bactivo = 0  WHERE ";

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

function get_historia($Conexion_ID, $historia){
  
    $SQL =  " SELECT me.id, me.monto_total, me.monto_pagado
                FROM paciente_intervencion me, paciente pac
               WHERE me.id_paciente = pac.id
                 AND me.id_estatus IN (1,3) 
		 AND pac.shistoria = $historia";

  //ejecutamos la consulta
    $this->Consulta_ID = mysql_query($SQL, $Conexion_ID);
  
    if (!$this->Consulta_ID) {
  
      $this->Errno = mysql_errno();
  
      $this->Error = mysql_error();
    }
  
  /* Si hemos tenido exito en la consulta devuelve el identificador de la conexion, sino devuelve 0 */
  
    return $this->Consulta_ID;
  }
  

}
?>
