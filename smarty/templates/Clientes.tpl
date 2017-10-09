{include file='encabezado.tpl'}
{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 

<script language="javascript">
function runMode( accion, Id ){
  forma = document.getElementById('fclientes');

  forma.accion.value = accion;
  forma.action = "PruebaConsulta.php";
  forma.id.value = Id;

  forma.submit();
  
}
</script>
{/literal}


<LINK HREF="css/anestesia.css" REL="stylesheet" TYPE="text/css">
<link rel="stylesheet" href="calendar.css"> 
<table width="100%">
<tr>
<td class="tituloforma" align="center">{$titulo}</td>
</tr>
</table

<FORM METHOD="post" name="fclientes" id="fclientes" ACTION="CtrlClientes.php">
<fieldset style="background-color : #e3e3e3">
<legend>Datos Pacientes</legend>
<table width="100%">
  <tr>
    <td width="15%"><b>Nombre:</b></td>
    <td align="left"><input type="text" name="snombre" id="snombre" size="30" value="{$snombre}"></td>
  </tr>
  <tr>
    <td><b>Apellido:</b></td>
    <td><input type="text" name="sapellido" id="sapellido" size="30" value="{$sapellido}"></td>
  </tr>
  <tr>
    <td><b>Fecha Nacimiento:</b></td>
    <td>
      <INPUT TYPE="text" NAME="fnacimiento" id="fnacimiento" SIZE="10" value="{$fnacimiento}">
{literal}
      <script language="JavaScript"> 
	new tcal ({
		// form name
		'formname': 'fclientes',
		// input name
		'controlname': 'fnacimiento'
	});
       </script>
{/literal}
    </td>
  </tr>
  <tr>
    <td><b>Dirección:</b></td>
    <td><input type="text" name="sdireccion" id="sdireccion" size="65" value="{$sdireccion}"></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="reset" id="btnCancelar" value="Cancelar"><input type="button" id="btnEnviar" onClick=" runMode('enviar', '{$id}');" value="Enviar"></td>
  </tr>
</table>
</fieldset>
<br/><br/>
<table width="75%" cellpadding="1" align="center">
  <tr class="titulodonforojo">
     <th>Nro. Registro</th>
     <th>Nombre</th>
     <th>Apellido</th>
     <th>Fecha Nacimiento</th>
     <th>Dirección</th>
     <th></th>
     <th></th>
  </tr>
  {foreach from=$ArrClientes key=Id item=field}
    <tr class={$field.clase}>
      <td align="center">{$Id}</td>
      <td>{$field.snombre}</td>
      <td>{$field.sapellido}</td>
      <td align="center">{$field.fnacimiento}</td>
      <td>{$field.sdireccion}</td>
      <td align="center"><a href="#" onclick="runMode( 'actualiza', {$field.id} );"><img width="15xp" heith="15xp" src="/imagenes/b_edit.png"></a></td>
      <td align="center"><a href="#" onclick="runMode( 'elimina', {$field.id} );"><img src="/imagenes/b_drop.png"></a></td>
    </tr>
  {/foreach}
</table>
<input type="hidden" name="accion" id="accion">
<input type="hidden" name="id" id="id">
</FORM>
