<?php

class FilePagoLote {


/* variables de la clase FilePagoLote */

  var $id_pacienteintervencion;

  var $smd5file;

/* M�todo Constructor: Cada vez que creemos una variable

de esta clase, se ejecutar� esta funci�n */

    function FilePagoLote($id_pacienteintervencion = "", $md5file = "" ) {
      $this->id_pacienteintervencion = $id_pacienteintervencion;
      $this->smd5file = $md5file;
    }
/* Ejecuta un consulta */

    function consulta($Conexion_ID, $Where = ""){

      $SQL =  "SELECT me.id, me.id_pacienteintervencion,
		      me.smd5file
		      FROM filepagolote me ";

      if (!empty($Where)) {
        $SQL .= " WHERE " . $Where;
      }

      //ejecutamos la consulta

      $this->Consulta_ID = mysql_query($SQL, $Conexion_ID);

      if (!$this->Consulta_ID) {

        $this->Errno = mysql_errno();
        $this->Error = mysql_error();

      }

/* Si hemos tenido �xito en la consulta devuelve el identificador de la conexi�n, sino devuelve 0 */

      return $this->Consulta_ID;
    }

/* Ejecuta un Insercion */
    
    function create($Conexion_ID, $datos = ""){

      $query = "INSERT INTO filepagolote (id_pacienteintervencio, smd5file) 
	        VALUES ('".$datos['id_pacienteintervencion']."','".$datos['md5file']."')";

      $response = mysql_query($query, $Conexion_ID);

      print mysql_error();
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
