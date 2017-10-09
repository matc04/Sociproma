{include file='encabezado.tpl'}
{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 
<script type="text/javascript" src="js/prototype/prototype.js"></script>
<script type="text/javascript" src="js/seleccionarItemList.js"></script>

<script language="javascript">

function runMode( accion, Id ){
  forma = document.getElementById('fpaciente_intervencion');

  forma.accion.value = accion;
  forma.action = "CtrlPacienteIntervencion.php";
  forma.id.value = Id;

  if (accion != "elimina" && accion != "actualiza" && 
      accion != "buscar" && accion != "crear_paciente" && 
      accion != "crear_responsable" && accion != "volver" &&
      accion != "pagar_recibo" ){
{/literal}
    {if $bpagar == 1}
      if (validate_fpacienteInterven_existe(forma)){ forma.submit() };
    {else}
      {if $bcompleto == 1}
        {literal}
          if (validate_fpacienteInterven_completo(forma)){ forma.submit() };
        {/literal}
      {else}
        {literal}
          if (validate_fpacienteInterven_existe(forma)){ forma.submit() };
        {/literal}
      {/if}
    {/if}      
{literal}
  } else if( accion == "crear_paciente"){
    forma.action = "CtrlPaciente.php";
    forma.submit();
  } else if( accion == "crear_responsable"){
    forma.action = "CtrlResponsable.php";
    forma.submit();
  } else if( accion == "pagar_recibo"){
    if (validate_fpacienteInterven_pago(forma)){
      forma.action = "CtrlPacienteIntervencion.php";
      forma.submit();
    }
  }else if( accion == "volver"){
    forma.submit();
  }
  else{
    forma.submit();
  }
}
{/literal}

{if $bpagar == 1}
  {include file='js/validate_fpacienteInterven_pago.js'}
{else}
  {if $bcompleto == 1}
    {include file='js/validate_fpacienteInterven_completo.js'}
  {else}
    {include file='js/validate_fpacienteInterven_existe.js'}
  {/if}
{/if}


{literal}
function calcula_monto( ){
  var value_sap       = document.fpaciente_intervencion.monto_sap.value;
  var value_preva     = document.fpaciente_intervencion.monto_preva.value;
  var value_anestesia = document.fpaciente_intervencion.monto_anestesia.value;

  if (value_sap.indexOf(",") != -1 ){
    value_sap = value_sap.replace(".",'');
    value_sap = value_sap.replace(",",'.');
  }
  if (value_preva.indexOf(",") != -1 ){
    value_preva = value_preva.replace(".",'');
    value_preva = value_preva.replace(",",'.');
  }
  if (value_anestesia.indexOf(",") != -1 ){
    value_anestesia = value_anestesia.replace(".",'');
    value_anestesia = value_anestesia.replace(",",'.');
  }
  var monto_sap       = parseFloat(value_sap).toFixed(2);
  var monto_preva     = parseFloat(value_preva).toFixed(2);
  var monto_anestesia = parseFloat(value_anestesia).toFixed(2);
  var monto_total;

  monto_total = parseFloat(monto_sap) + parseFloat(monto_preva) + parseFloat(monto_anestesia);

  if (value_sap.indexOf(",") == -1){
   document.fpaciente_intervencion.monto_sap.value = formatearMoneda(value_sap);
  }
  if (value_preva.indexOf(",") == -1){
    document.fpaciente_intervencion.monto_preva.value = formatearMoneda(value_preva);
  }
  if (value_anestesia.indexOf(",") == -1){
    document.fpaciente_intervencion.monto_anestesia.value = formatearMoneda(value_anestesia);
  }
  document.fpaciente_intervencion.monto_total.value = formatearMoneda(monto_total);
                                                      
}
</script>
{/literal}

<LINK HREF="css/anestesia.css" REL="stylesheet" TYPE="text/css">
<link rel="stylesheet" href="calendar.css"> 
<table width="100%">
<tr>
<td class="tituloforma" align="center">{$titulo}</td>
</tr>
</table>

<FORM METHOD="post" name="fpaciente_intervencion" id="fpaciente_intervencion" ACTION="CtrlPaciente_Intervencion.php">
{if $bpagar != 1}
  <fieldset style="background-color : #e3e3e3">
  <legend>Datos Intervención por Paciente</legend>
  {if $bcompleto == 1}
    <table width="100%">
      <tr>
        <td width="15%"><b>Número Historia:</b></td>
        <td align="left"><input type="text" name="shistoria" id="shistoria" size="10" value="{$shistoria}">&nbsp;<b>*</b></td>
      </tr>
      <tr>
        <td><b>Apellidos Paciente:</b></td>
        <td><input type="text" name="sapellido" id="sapellido" size="30" value="{$sapellido}">&nbsp;<b>*</b></td>
      </tr>
      <tr>
        <td width="15%"><b>Nombres Paciente:</b></td>
        <td align="left"><input type="text" name="snombre" id="snombre" size="30" value="{$snombre}">&nbsp;<b>*</b></td>
      </tr>
      <tr>
        <td width="15%"><b>Edad Paciente:</b></td>
        <td align="left"><input type="text" name="edad" id="edad" size="3" value="{$edad}"></td>
      </tr>
    </table>  
  {/if}
  <table width="100%" border=0>
    <tr>
      <td width="15%"><b>Número de Recibo/Presupuesto:</b></td>
      <td align="left"><input type="text" name="num_recibo" id="num_recibo" size="10" value="{$num_recibo}">&nbsp;<b>*</b></td>
    </tr>
    {if $id_paciente != ''}
      <tr>
        <td width="15%"><b>Paciente:</b></td>
        <td align="left"><b>{$nombre_paciente}</b><input name="id_paciente" id="id_paciente" type="hidden" value="{$id_paciente}"></td>
      </tr>
    {else}
      {if $bcompleto != 1}    
        <tr>
          <td width="15%"><b>Historia/Paciente:</b></td>
          <td>
            <input type="text" id="paciente" name="paciente" size="50"/><input type="hidden" id="id_paciente" name="id_paciente"/>
          </td>
        </tr>
      {/if}  
    {/if}
    <tr>
      <td width="15%"><b>Fecha de la Intervención:</b></td>
      <td>
        <INPUT TYPE="text" NAME="fecha" id="fecha" SIZE="10" value="{$fecha}">
  {literal}
        <script language="JavaScript"> 
    new tcal ({
      // form name
      'formname': 'fpaciente_intervencion',
      // input name
      'controlname': 'fecha'
    });
         </script>
  {/literal}
         &nbsp;<b>*</b>
      </td>
    <tr>
      <td><b>Tipo de Operación:</b></td>
       <td align="left">
        <select name="id_tpoperacion" id="id_tpoperacion">
          {html_options options=$tpopera_options selected=$id_tpoperacion}
        </select>&nbsp;<b>*</b>
      </td>
    </tr>
    <tr>
      <td><b>Cirujano:</b></td>
      <td>
        <input type="text" id="cirujano" name="cirujano" size="50"/><input type="hidden" id="id_doctor_cirujano" name="id_doctor_cirujano"/>&nbsp;<b>*</b>
      </td>
    </tr>
    <tr>
      <td><b>Anestesiólogo:</b></td>
      <td>
        <select name="id_doctor_anestesia" id="id_doctor_anestesia">
          {html_options options=$doctorAnes_options selected=$id_doctor_anestesia}
        </select>&nbsp;<b>*</b>  
      </td>
    </tr>
    <tr>
      <td><b>Monto SAP:</b></td>
      <td><input type="text" name="monto_sap" id="monto_sap" size="17" value="{$monto_sap}" onchange="calcula_monto()"></td>
    </tr>
    <tr>
      <td><b>Monto Pre-Anestesia:</b></td>
      <td><input type="text" name="monto_preva" id="monto_preva" size="17" value="{$monto_preva}" onchange="calcula_monto()"></td>
    </tr>
    <tr>
      <td><b>Monto Anestesia:</b></td>
      <td><input type="text" name="monto_anestesia" id="monto_anestesia" size="17" value="{$monto_anestesia}" onchange="calcula_monto()"></td>
    </tr>
    <tr>
      <td><b>Monto Total:</b></td>
      <td><input type="text" name="monto_total" id="monto_total" size="17" value="{$monto_total}" onchange=" this.value=formatearMoneda(this.value)">&nbsp;<b>*</b></td>
    </tr>
    <tr>
      <td><b>Tipo Intervención:</b></td>
      <td>
        <input type="text" id="intervencion" name="intervencion" size="50"/><input type="hidden" id="id_intervencion" name="id_intervencion"/>&nbsp;<b>*</b>
      </td>
    </tr>
    <tr>
      <td><b>Responsable:</b></td>
      <td>
        <input type="text" id="responsable" name="responsable" size="50"/><input type="hidden" id="id_responsable" name="id_responsable"/>
      </td>
    </tr>
    <tr>
      <td><b>Observación:</b></td>
      <td><input type="text" name="sobservacion" id="sobservacion" size="60" value="{$sobservacion}"></td>
    </tr>
    <tr>
      <td colspan="2" align="left" ><font color="#0F305A" size="0" > (*) Estos campos son obligatorios</font></td>
    </tr>
  </table>
  </fieldset>
{else}
  <fieldset style="background-color : #e3e3e3">
  <legend>Datos del Pago del Recibo/Presupuesto</legend>
  <table width="100%" border=0>
    <tr>
      <td width="15%"><b>Número de Recibo/Presupuesto:</b></td>
      <td align="left"><b>{$num_recibo}</b></td>       
    </tr>
    <tr>
      <td width="15%"><b>Nombre Paciente:</b></td>
      <td align="left"><b>{$nombre_paciente}</b></td>       
    </tr>
    <tr>
      <td width="15%"><b>Nombre Cirujano:</b></td>
      <td align="left"><b>{$nombre_cirujano}</b></td>       
    </tr>
    <tr>
      <td width="15%"><b>Nombre Anestesiólogo:</b></td>
      <td align="left"><b>{$nombre_anestesiologo}</b></td>       
    </tr>
    <tr>
      <td width="15%"><b>Monto Pagado:</b></td>
      <td align="left">
        <input type="text" name="monto_pagado" id="monto_pagado" size="15" value="0" onchange="this.value = formatearMoneda(this.value)">
      </td>       
    </tr>
    <tr>
      <td width="15%"><b>Fecha de Pago:</b></td>
    <td>
      <INPUT TYPE="text" NAME="fecha_pago" id="fecha_pago" SIZE="10">
{literal}
      <script language="JavaScript"> 
  new tcal ({
    // form name
    'formname': 'fpaciente_intervencion',
    // input name
    'controlname': 'fecha_pago'
  });
       </script>
{/literal}
       &nbsp;<b>*</b>
    </td>
    </tr>
    
  </table>
  </fieldset>
{/if}  
<br/><br/>
<table width="75%" cellpadding="1" align="center" id="tabla">
    <tr>
      <td align="center">
        <input type="reset" id="btnCancelar" value="Cancelar">
        {if $bpagar != 1}
          <input type="button" id="btnEnviar" onClick=" runMode('enviar', '{$id}');" value="Enviar">
        {else}
          <input type="button" id="btnEnviar" onClick=" runMode('pagar_recibo', '{$id}');" value="Enviar">
        {/if}
        {if $bvolver == 1}
          <input type="button" id="btnVolver" onClick=" runMode('volver');" value="Volver">
        {/if}
      </td>
    </tr>
</table>
<input type="hidden" name="accion" id="accion">
<input type="hidden" name="id" id="id" value="{$id}">
<input type="hidden" name="bcompleto" id="bcompleto" value="{$bcompleto}">

</FORM>

</body>
</html>
