<?php

  set_time_limit(36000); 

  require ("MysqlDB.php");
  require ("Paciente.php");
  require ("TipoOperacion.php");
  require ("Doctor.php");
  require ("Responsable.php");
  require ("Intervencion.php");

  $miconexion = new DB_mysql ;

  $miPaciente  = new Paciente;

  $miDoctor  = new Doctor;

  $miResponsable  = new Responsable;

  $miTipoOpera  = new TipoOPeracion;

  $miIntervencion  = new Intervencion;

  $miconexion->conectar("", "", "", "");

  $SQL =  "SELECT * FROM `2009`";

   print $SQL;
  $Consulta_ID = @mysql_query($SQL, $miconexion->Conexion_ID);

  while ($row = mysql_fetch_object($Consulta_ID)) {
    
#Se busca el tipo de operacion
#   IF ($row->HISTORIA){
      $Where = " shistoria = '".$row->HISTORIA."'";
      $ConsultaPaci = $miPaciente->consulta($miconexion->Conexion_ID, $Where);
      $rowPaci = mysql_fetch_object($ConsultaPaci);
      if ($rowPaci){
        $id_paciente = $rowPaci->id;
      }
      else{
        $id_paciente = '';
      }

#Se busca el tipo de operacion
      $Where = " sdescripcion = '".$row->TIPOOPERA."'";
      $ConsultaTipo = $miTipoOpera->consulta($miconexion->Conexion_ID, $Where);
      $rowTipo = mysql_fetch_object($ConsultaTipo);
      if ($rowTipo){
        $id_tpoperacion = $rowTipo->id;
      }
#Se busca el Doctor Cirujano
      $Where = " sapellido = '".$row->CIRUJANO."' AND id_especialidad = '1'";
      $ConsultaCirujano = $miDoctor->consulta($miconexion->Conexion_ID, $Where);
      $rowCirujano = mysql_fetch_object($ConsultaCirujano);
      if ($rowCirujano){
        $id_doctor_cirujano = $rowCirujano->id;
      }
#Se busca el Doctor Anestesiologo
      $Where = " sapellido = '".$row->ANESTES."' AND id_especialidad = '2'";
      $ConsultaAnestes = $miDoctor->consulta($miconexion->Conexion_ID, $Where);
      $rowAnestes = mysql_fetch_object($ConsultaAnestes);
      if ($rowAnestes){
        $id_doctor_anestesia = $rowAnestes->id;
      }
#Se busca el Responsable
      $Where = " sdescripcion = '".$row->RESPONSABL."'";
      $ConsultaRespon = $miResponsable->consulta($miconexion->Conexion_ID, $Where);
      $rowRespon = mysql_fetch_object($ConsultaRespon);
      if ($rowRespon){
        $id_responsable = $rowRespon->id;
      }

      $Estatus = ($row->FECHAPAGO AND $row->MONTOPAGO) ? 2 : 1;

      $FechaArr = explode('-',$row->FECHA);
      $FechaArr[0] += 100;

      $PagoArr = explode('-',$row->FECHAPAGO);
      $PagoArr[0] += 100;


      $query = "INSERT INTO paciente_intervencion ( num_recibo, id_paciente,
                                                    id_tpoperacion, id_doctor_cirujano, 
                                                    id_doctor_anestesia, monto_total, 
						    id_responsable, fecha_pago,
						    fecha_intervencion, monto_pagado,
                                                    sobservacion, id_estatus ) 
			                 VALUES ('".$row->RECIBO."','".$id_paciente."','".
                                                    $id_tpoperacion."','".$id_doctor_cirujano."','".
                                                    $id_doctor_anestesia."','".$row->MONTO."','".
						    $id_responsable."','".join("-",$PagoArr)."','".
						    join("-",$FechaArr)."','".$row->MONTOPAGO."','".
						    $row->OBSERVACIO."','".$Estatus."')";


      if (mysql_query($query, $miconexion->Conexion_ID)){
        $Recibo = mysql_insert_id();

#Se crea el detalle del recibo
      if ( preg_match('/\+/', $row->INTERVENCI) ){
        $InterVen = explode("+",$row->INTERVENCI);
        foreach ($InterVen as $Inter){
#Se busca la intervencion Responsable
          $Where = " sdescripcion = '".$Inter."'";
          $ConsultaInter = $miIntervencion->consulta($miconexion->Conexion_ID, $Where);
          $rowInter = mysql_fetch_object($ConsultaInter);
          if ($rowInter){
            $id_intervencion = $rowInter->id;
          }

          $query = "INSERT INTO detalle_paciente_interven ( id_paciente_intervencion, id_intervencion, nmonto, sobservacion ) 
  		VALUES    ('".$Recibo."','".$id_intervencion."','0','')";
          $response = mysql_query($query, $miconexion->Conexion_ID);
        }
      }
      else{

#Se busca la intervencion Responsable
          $Where = " sdescripcion = '".$row->INTERVENCI."'";
          $ConsultaInter = $miIntervencion->consulta($miconexion->Conexion_ID, $Where);
          $rowInter = mysql_fetch_object($ConsultaInter);
          if ($rowInter){
            $id_intervencion = $rowInter->id;
          }

          $query = "INSERT INTO detalle_paciente_interven ( id_paciente_intervencion, id_intervencion, nmonto, sobservacion ) 
	                                  VALUES    ('".$Recibo."','".$id_intervencion."','".
	                                                $row->MONTO."','')";
          $response = mysql_query($query, $miconexion->Conexion_ID);
        }
        print "OK ...<br/>";
      }
#   }
  }
?>
