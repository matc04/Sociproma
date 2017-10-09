{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 

<script language="javascript">
function runMode( accion, Id ){
  forma = document.getElementById('fdoctor');

  forma.accion.value = accion;
  forma.action = "CtrlDoctor.php";
  forma.id.value = Id;

  if (accion != "elimina" && accion != "actualiza" ){
    if (validate_fdoctor(forma)){ forma.submit() };
  }
  else{
    forma.submit();
  }
}

function validate_fdoctor (form) {
  var alertstr = '';
  var invalid  = 0;

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

// stelefono: standard text, hidden, password, or textarea box
  var stelefono = form.elements['stelefono'].value;
  if (stelefono == null || stelefono == "" ) {
    alertstr += '- Indique un valor válido para el campo "Número Telefónico"\n';
    invalid++;
  }
  else{
    if (!stelefono.match(/^[0-9]+-[0-9]*$/)) {
      alertstr += '- El número telefónico debe cumplir con el formato que se indica "0212-12345678"\n';
      invalid++;
    }
  }

// id_especialidad: select list, always assume it's multiple to get all values
  var id_especialidad = 0;
  var selected_id_especialidad = 0;
  for (var loop = 0; loop < form.elements['id_especialidad'].options.length; loop++) {
    if (form.elements['id_especialidad'].options[loop].selected) {
      id_especialidad = form.elements['id_especialidad'].options[loop].value;
      selected_id_especialidad++;
      if (id_especialidad == 0 || id_especialidad === 0) {
        alertstr += '- Seleccione una opción para el campo "Especialidad"\n';
        invalid++;
      }
    } // if
  } // for id_especialidad

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

{include file='encabezado.tpl'}

<LINK HREF="css/anestesia.css" REL="stylesheet" TYPE="text/css">
<link rel="stylesheet" href="calendar.css"> 
<table width="100%">
<tr>
<td class="tituloforma" align="center">{$titulo}</td>
</tr>
</table>

<FORM METHOD="post" name="fdoctor" id="fdoctor" ACTION="CtrlDoctor.php">
<fieldset style="background-color : #e3e3e3">
<legend>Datos Doctor</legend>
<table width="100%">
  <tr>
    <td width="15%"><b>Nombre:</b></td>
    <td align="left"><input type="text" name="snombre" id="snombre" size="30" value="{$snombre}"> <b>*</b></td>
  </tr>
  <tr>
    <td width="15%"><b>Apellido:</b></td>
    <td align="left"><input type="text" name="sapellido" id="sapellido" size="30" value="{$sapellido}"> <b>*</b></td>
  </tr>
  <tr>
    <td><b>Número Telefónico:</b></td>
    <td><input type="text" name="stelefono" id="stelefono" size="14" value="{$stelefono}"><b>Ejm. 0414-12345678 *</b></td>
  </tr>
  <tr>
    <td><b>Número Telefónico Secundario:</b></td>
    <td><input type="text" name="stelefono_1" id="stelefono_1" size="14" value="{$stelefono_1}"><b>Ejm. 0414-12345678 (Opcional)</b></td>
  </tr>
  <tr>
    <td><b>Especialidad:</b></td>
    <td><select name="id_especialidad" id="id_especialidad">
          {html_options options=$esp_options selected=$id_especialidad}
        </select>
    <b>*</b> </td>
  </tr>
  <tr>
    <td colspan="2" align="left" ><font color="#0F305A" size="0" > (*) Estos campos son obligatorios</font></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="reset" id="btnCancelar" value="Cancelar"><input type="button" id="btnEnviar" onClick=" runMode('enviar', '{$id}');" value="Enviar"></td>
  </tr>
</table>
</fieldset>
<br/><br/>
<table width="75%" cellpadding="1" align="center">
  <tr class="titulodonforojo">
     <th>Nombre</th>
     <th>Apellido</th>
     <th>Teléfono</th>
     <th>Teléfono Sec.</th>
     <th>Especialidad</th>
     <th></th>
     <th></th>
  </tr>
  {foreach from=$ArrDoctores key=Id item=field}
    <tr class={$field.clase}>
      <td>{$field.snombre}</td>
      <td>{$field.sapellido}</td>
      <td>{$field.stelefono}</td>
      <td>{$field.stelefono_1}</td>
      <td>{$field.desc_especialidad}</td>
      <td align="center"><a href="#" onclick="runMode( 'actualiza', {$field.id} );"><img width="15xp" heith="15xp" src="/sociproma/imagenes/b_edit.png"></a></td>
      <td align="center"><a href="javascript:if (confirm('Desea eliminar el registro'))runMode( 'elimina', {$field.id} );"><img src="/sociproma/imagenes/b_drop.png"></a></td>
    </tr>
  {/foreach}
</table>
<input type="hidden" name="accion" id="accion">
<input type="hidden" name="id" id="id">
</FORM>
