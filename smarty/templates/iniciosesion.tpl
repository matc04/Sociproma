{include file="encabezado.tpl"}
<title>{$titulo}</title>
<!-- hojas de estilo generales -->
{include file="estilosgenerales.tpl"}

<!-- archivos de javascript generales (cargar en todas las pÃ¡ginas) -->
{include file="jsgenerales.tpl"}

{literal}
<script language="JavaScript" type="text/javascript">

delete_cookie ( "anestesia_session" );

function forma_enviar(){
  forma = document.getElementById('iniciosesion');

  if ( validate_iniciosesion( forma )){
    forma.submit();
  } 
}

function validate_iniciosesion( form ){
  var alertstr = '';
  var invalid  = 0;

// susuario: standard text, hidden, password, or textarea box
  var susuario = form.elements['susuario'].value;
  if (susuario == null || susuario == "" ) {
    alertstr += '- Indique un valor válido para el campo "Usuario"\n';
    invalid++;
  }

// scontrasena: standard text, hidden, password, or textarea box
  var scontrasena = form.elements['scontrasena'].value;
  if (scontrasena == null || scontrasena == "" ) {
    alertstr += '- Indique un valor válido para el campo "Contraseña"\n';
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

<div style="background-image:url('/sociproma/imagenes/anestesia_centro.jpeg');background-repeat:no-repeat;height:500px;  background-position: 50% 50%;background-size: 100% 100%;">
  <!-- Login form -->
  <form method='post' name='iniciosesion' id='iniciosesion' action='{$direccion}/iniciosesion.php'>
  <table align='center'>
    <tr>
      <td>Usuario:</td>
      <td><INPUT type='text' size='10' id='susuario' name='susuario'></td>
    </tr>
    <tr>
      <td>Contrase&ntilde;a:</td>
      <td><INPUT type='password' size='10' id='scontrasena' name='scontrasena'></td>
    </tr>
    <tr>
      <td colspan='2' align='center'><INPUT type='button' value='Enviar' onclick=' forma_enviar() '></td>
    </tr>
  </table>
  </form>
</div>
</body>
</html>
