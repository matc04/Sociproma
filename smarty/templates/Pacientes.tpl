{include file='encabezado.tpl'}
{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 

<script language="javascript">
function runMode( accion, Id ){
  forma = document.getElementById('fpaciente');

  forma.accion.value = accion;
  forma.action = "CtrlPaciente.php";
  forma.id.value = Id;

  if (accion != "elimina" && accion != "actualiza" && accion != "buscar" && accion != "volver" ){
    if (validate_fpaciente(forma)){ forma.submit() };
  }
  else{
     if (accion == "volver"){
       forma.action = forma.referente.value;
     }
    forma.submit();
  }
}

function validate_fpaciente (form) {
  var alertstr = '';
  var invalid  = 0;

// shistoria: standard text, hidden, password, or textarea box
  var shistoria = form.elements['shistoria'].value;
  if (shistoria == null || shistoria == "" ) {
    alertstr += '- Indique un valor válido para el campo "Historia"\n';
    invalid++;
  }

// snombre: standard text, hidden, password, or textarea box
  var snombre = form.elements['snombre'].value;
  if (snombre == null || snombre == "" ) {
    alertstr += '- Indique un valor válido para el campo "Nombre"\n';
    invalid++;
  }

// sapellido: standard text, hidden, password, or textarea box
  var sapellido = form.elements['sapellido'].value;
  if (sapellido == null || sapellido == "" ) {
    alertstr += '- Indique un valor válido para el campo "Apellido"\n';
    invalid++;
  }

  if (invalid > 0 || alertstr != '') {
    if (! invalid) invalid = 'Los Siguiemtes';   // catch for programmer error
    alert(''+invalid+' error(es) fueron encontrados al enviar la información:'+'\n\n'
    +alertstr+'\n'+'- Por favor corrija los campos y trate de nuevo');
    return false;
  }
  return true;  // all checked ok
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

<FORM METHOD="post" name="fpaciente" id="fpaciente" ACTION="CtrlPaciente.php">
<fieldset style="background-color : #e3e3e3">
<legend>Datos Pacientes</legend>
<table width="100%">
  <tr>
    <td width="15%"><b>Historia:</b></td>
    <td align="left"><input type="text" name="shistoria" id="shistoria" size="10" value="{$shistoria}"><b>*</b></td>
  </tr>
  <tr>
    <td><b>Apellido:</b></td>
    <td><input type="text" name="sapellido" id="sapellido" size="30" value="{$sapellido}"><b>*</b></td>
  </tr>
  <tr>
    <td width="15%"><b>Nombre:</b></td>
    <td align="left"><input type="text" name="snombre" id="snombre" size="30" value="{$snombre}"><b>*</b></td>
  </tr>
  <tr>
    <td width="15%"><b>Edad:</b></td>
    <td align="left"><input type="text" name="edad" id="edad" size="3" value="{$edad}"></td>
  </tr>
  <tr>
    <td colspan="2"><input type="button" name="btnBuscar" id="btnBuscar" value="Buscar" onClick=" runMode('buscar','');"></td>
  </tr>
  <tr>
    <td colspan="2" align="left" ><font color="#0F305A" size="0" > (*) Estos campos son obligatorios</font></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="reset" id="btnCancelar" value="Cancelar">
      <input type="button" id="btnEnviar" onClick=" runMode('enviar', '{$id}');" value="Enviar">
      {if $referente != ''}
        <input type="button" id="btnVolver" onClick=" runMode('volver', '');" value="Volver">
      {/if}
     </td>
  </tr>
</table>
</fieldset>
<br/><br/>
<table width="75%" cellpadding="1" align="center">
  <tr class="titulodonforojo">
     <th>Historia</th>
     <th>Nombre</th>
     <th>Apellido</th>
     <th>Edad</th>
     <th></th>
     <th></th>
  </tr>
  {foreach from=$ArrPacientes key=Id item=field}
    <tr class={$field.clase}>
      <td align="center">{$field.shistoria}</td>
      <td>{$field.snombre}</td>
      <td>{$field.sapellido}</td>
      <td align="center">{$field.edad}</td>
      <td align="center"><a href="#" onclick="runMode( 'actualiza', {$field.id} );"><img width="15xp" heith="15xp" src="/sociproma/imagenes/b_edit.png"></a></td>
      <td align="center"><a href="javascript:if (confirm('Desea eliminar el registro'))runMode( 'elimina', {$field.id} );"><img src="/sociproma/imagenes/b_drop.png"></a></td>
    </tr>
  {/foreach}
</table>
<input type="hidden" name="accion" id="accion">
<input type="hidden" name="referente" id="referente" value="{$referente}">
<input type="hidden" name="id" id="id" value="{$id}">
</FORM>
