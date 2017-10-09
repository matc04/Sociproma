{include file='encabezado.tpl'}
{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 

<script language="javascript">
function runMode( accion ){
  forma = document.getElementById('fupload');

  forma.accion.value = accion;
  forma.action = "CtrlPagosLote.php";

  if (validate_fupload(forma)){ forma.submit() };
}

function validate_fupload (form) {
  var alertstr = '';
  var invalid  = 0;

// fileToUpload: standard text, hidden, password, or textarea box
  var sfile = form.elements['fileToUpload'].value;
  if (sfile == null || sfile == "" ) {
    alertstr += '- Indique un valor válido para el campo "Archivo"\n';
    invalid++;
  }

  // fechapago: standard text, hidden, password, or textarea box
  var sfile = form.elements['fechapago'].value;
  if (sfile == null || sfile == "" ) {
    alertstr += '- Indique un valor válido para el campo "Fecha Pago"\n';
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

<FORM METHOD="post" name="fupload" id="fupload" ACTION="CtrlPagosLote.php" enctype= "multipart/form-data">
<fieldset style="background-color : #e3e3e3">
<legend>Pagos en Lote</legend>
<table width="100%">
  <tr>
    <td><b>(*) Seleccione el archivo a Procesar:</b></td>
    <td><input type="file" name="fileToUpload" id="fileToUpload"></td>
  </tr>  
  </tr>
    <td width="20%"><b>Fecha Pago:</b></td>
      <td>
        <INPUT TYPE="text" NAME="fechapago" id="fechapago" SIZE="10" value="{$fechapago}">
        {literal}
          <script language="JavaScript"> 
            new tcal ({
                // form name
                'formname': 'fupload',
                // input name
                'controlname': 'fechapago'
            });
         </script>
        {/literal} (*)
    </td>
  </tr>
  <tr>
    <td colspan="2" align="left" ><font color="#0F305A" size="0" > (*) Estos campos son obligatorios</font></td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><input type="reset" id="btnCancelar" value="Cancelar"><input type="button" id="btnEnviarUp" value="Enviar"></td>
  </tr>
</table>
</fieldset>

<input type="hidden" name="accion" id="accion">
</FORM>

<div align="center" id='resumen' style='display:none'>
  <table>
    <tr>
      <td><b>Registros Procesados:</b></td>
      <td><p id="procesados"></p></td>
    </tr>
    <tr>
      <td><b>Registros Actualizados:</b></td>
      <td><p id="actualizados"></p></td>
    </tr>
    <tr>
      <td><b>Registros No Existen:</b></td>
      <td><p id="noexisten"></p></td>
    </tr>
  </table>
</div>

<div align="center" id='loadingmessage' style='display:none'>
  <img src='imagenes/loading.gif'/>
</div>

