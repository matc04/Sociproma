{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 
<script language="javascript">
function runMode( accion, Id ){
  forma = document.getElementById('fusuario');

  forma.accion.value = accion;
  forma.action = "CtrlUsuario.php";
  forma.id.value = Id;

  if (accion != "elimina" && accion != "actualiza" && accion != "buscar" ){
    if (validate_fusuario(forma)){ forma.submit() };
  }
  else{
    forma.submit();
  }
  
}

function validate_fusuario (form) {
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

// susuario: standard text, hidden, password, or textarea box
  var susuario = form.elements['susuario'].value;
  if (susuario == null || susuario == "" ) {
    alertstr += '- Indique un valor válido para el campo "Usuario"\n';
    invalid++;
  }

// scontrasena: standard text, hidden, password, or textarea box
  var scontrasena = form.elements['scontrasena'].value;
  if (scontrasena == null || susuario == "" ) {
    alertstr += '- Indique un valor válido para el campo "Contrase&ntile;a"\n';
    invalid++;
  }

// sconfirma: standard text, hidden, password, or textarea box
  var sconfirma = form.elements['scontrasena'].value;
  if (sconfirma == null || sconfirma == "" ) {
    alertstr += '- Indique un valor válido para el campo "Confirmar Contrase&ntilde;a"\n';
    invalid++;
  }

  if (scontrasena && sconfirma){
    if (scontrasena != sconfirma){
      alertstr += '- El valor indicado en la casilla de confirmaci&oacute;n es distinto a la contrase&ntilde;a\n';
      invalid++;
    }
  }

  var scorreo = form.scorreo.value;
  if (scorreo){
    if (!scorreo.match(/^\w+([\.-]?\w+)*@[a-zA-Z0-9][-a-zA-Z0-9\.]*\.[a-zA-Z]+$/)){
        alertstr += '- El valor indicado en "Correo electr&oacute;nico" debe ser una direcci&oacute;n de correo valida\n';
        invalid++;
    }
  }

  if (invalid > 0 || alertstr != '') {
    if (! invalid) invalid = 'Los Siguiemtes';   // catch for programmer error
    alert(''+invalid+' error(es) fueron encontrados al enviar la informaci&oacute;n:'+'\n\n'
    +alertstr+'\n'+'- Por favor corrija los campos y trate de nuevo');
    return false;
  }
  return true;  // all checked ok
}

function llenaValores(){
  form = document.getElementById('fusuario');
  var id_doctor = form.id_doctor.value;

 for (var loop = 0; loop < form.elements['id_doctor'].options.length; loop++) {
    if (form.elements['id_doctor'].options[loop].selected) {
      var CadenaNombre = form.elements['id_doctor'].options[loop].label;
      var Nombres = CadenaNombre.split(',');
    } // if
  } // for id_doctor

  if (id_doctor != 99 && id_doctor != 0){
    form.snombre.value   = Nombres[1];
    form.sapellido.value = Nombres[0];
    form.snombre.readOnly = "true";
    form.sapellido.readOnly = 1;
  }

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

<FORM METHOD="post" name="fusuario" id="fusuario" ACTION="CtrlUsuario.php">
<fieldset style="background-color : #e3e3e3">
<legend>Datos Usuarios</legend>
<table width="100%">
  <tr>
    <td nowrap><b>Si el usuario es doctor seleccionelo:</b></td>
    <td><select name="id_doctor" id="id_doctor" onchange=" llenaValores(); ">
          {html_options options=$doctor_options selected=$id_doctor}
        </select>
    </td>
  </tr>
  <tr>
    <td width="15%"><b>Nombre:</b></td>
    <td align="left"><input type="text" name="snombre" id="snombre" size="30" value="{$snombre}"><b>*</b></td>
  </tr>
  <tr>
    <td width="15%"><b>Apellido:</b></td>
    <td align="left"><input type="text" name="sapellido" id="sapellido" size="30" value="{$sapellido}"><b>*</b></td>
  </tr>
  <tr>
    <td><b>Usuario:</b></td>
    <td><input type="text" name="susuario" id="susuario" size="30" value="{$susuario}"><b>*</b></td>
  </tr>
  <tr>
    <td><b>Contrase&ntilde;a:</b></td>
    <td><input type="password" name="scontrasena" id="scontrasena" size="10" value="{$scontrasena}"><b>*</b></td>
  </tr>
  <tr>
    <td><b>Confirmar Contrase&ntilde;a:</b></td>
    <td><input type="password" name="sconfirma" id="sconfirma" size="10" value="{$sconfirma}"><b>*</b></td>
  </tr>
  <tr>
    <td><b>Correo Electr&oacute;nico:</b></td>
    <td><input type="scorreo" name="scorreo" id="scorreo" size="30" value="{$scorreo}"></td>
  </tr>
  <tr>
    <td><b>Administrador:</b></td>
    <td>{html_checkboxes name="badministrador" values=$adm_ids output=$adm_names selected=$adm_id separator="<br />"}</td>
  </tr>
  <tr>
    <td colspan="2"><input type="button" name="btnBuscar" id="btnBuscar" value="Buscar" onClick=" runMode('buscar','');"></td>
  </tr>
  <tr>
    <td colspan="2" align="left" ><font color="#0F305A" size="0" > (*) Estos campos son obligatorios</font></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="reset" id="btnCancelar" value="Cancelar"><input type="button" id="btnEnviar" onClick=" runMode('enviar', '{$id}');" value="Enviar">
    </td>
  </tr>
</table>
</fieldset>
<br/><br/>
<table width="75%" cellpadding="1" align="center">
  <tr class="titulodonforojo">
     <th>Nombre</th>
     <th>Apellido</th>
     <th>Usuario</th>
     <th>Correo Electr&oacute;nico</th>
     <th></th>
     <th></th>
  </tr>
  {foreach from=$ArrUsuarios key=Id item=field}
    <tr class={$field.clase}>
      <td>{$field.snombre}</td>
      <td>{$field.sapellido}</td>
      <td>{$field.susuario}</td>
      <td>{$field.scorreo}</td>
      <td align="center"><a href="#" onclick="runMode( 'actualiza', {$field.id} );"><img width="15xp" heith="15xp" src="/sociproma/imagenes/b_edit.png"></a></td>
      <td align="center"><a href="javascript:if (confirm('Desea eliminar el registro'))runMode( 'elimina', {$field.id} );"><img src="/sociproma/imagenes/b_drop.png"></a></td>
    </tr>
  {/foreach}
</table>
<input type="hidden" name="accion" id="accion">
<input type="hidden" name="id" id="id">
</FORM>
