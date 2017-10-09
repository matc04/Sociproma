<html>
<!-- INICIO HEADER VENEZUELA -->
<head>
<link rel="stylesheet" href="/sociproma/menu/menu.css">
<LINK HREF="/sociproma/css/anestesia.css" REL="stylesheet" TYPE="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta charset="utf-8"> 

<link rel="stylesheet" type="text/css" href="/sociproma/css/jquerycssmenu.css" />
<link href="/sociproma/menu/estilos.css" rel="stylesheet" type="text/css" /> 
<link href="/sociproma/menu/ADxMenuHoriz.css" rel="stylesheet" type="text/css" media="screen, tv, projection" /> 
<!--[if lte IE 6]>
<link href="ADxMenuHoriz-IE6.css" rel="stylesheet" type="text/css" media="screen, tv, projection" />
<![endif]--> 
 
<script type="text/javascript" src="/sociproma/menu/ADxMenu.js"></script> 
<script language="JavaScript" src="js/generales.js"></script> 

<link href="js/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>

  <!-- (2) define two jquery functions we need (default input focus, and autocomplete) -->
{literal}
<script>

jQuery(document).ready(function() {
    // tell the autocomplete function to get its data from our php script
  if (jQuery('#paciente').length){  
    jQuery('#paciente').autocomplete({
      source: "/sociproma/AutoComplete.php?accion=buscarPacientes",
      minLength: 4,
      select: function(event, ui) {
                jQuery('#id_paciente').val(ui.item.id);
              }
    });
  }

  if (jQuery('#intervencion').length){  
    jQuery('#intervencion').autocomplete({
      source: "/sociproma/AutoComplete.php?accion=buscarIntervenciones",
      minLength: 4,
      select: function(event, ui) {
                jQuery('#id_intervencion').val(ui.item.id);
              }
    });
  }

  if (jQuery('#responsable').length){  
    jQuery('#responsable').autocomplete({
      source: "/sociproma/AutoComplete.php?accion=buscarResponsables",
      minLength: 4,
      select: function(event, ui) {
                jQuery('#id_responsable').val(ui.item.id);
              }
    });
  }

  if (jQuery('#cirujano').length){  
    jQuery('#cirujano').autocomplete({
      source: "/sociproma/AutoComplete.php?accion=buscarCirujanos",
      minLength: 4,
      select: function(event, ui) {
                jQuery('#id_doctor_cirujano').val(ui.item.id);
              }
    });
  }

  if (jQuery('#btnCancelaConsulta').length){  
    jQuery('#btnCancelaConsulta').click(function() {
      jQuery.ajax({
        url: "/sociproma/CleanSession.php",
        type: 'POST',
        error: function(xhr, status){
          alert("Hubo error");
        },
        complete: function(){
          jQuery("#busca_recibo")[0].reset();
          window.location.replace("http://localhost/sociproma/CtrlBuscarRecibo.php");
        } 
      });
    });
  }

  if (jQuery('#btnEnviarUp').length){  
    jQuery('#btnEnviarUp').click(function() {
      forma = document.getElementById('fupload');
      if (validate_fupload(forma)){

        jQuery('#loadingmessage').show();  // show the loading message.
        jQuery('#accion').val('upload');
        jQuery.ajax({
            url: 'http://localhost/sociproma/CtrlPagosLote.php',
            type: 'POST',
            data: new FormData(jQuery("#fupload")[0]) ,
            processData: false, 
            contentType: false, 
            success: function (response) {
                data = jQuery.parseJSON(response);
                jQuery('#procesados').text(data.procesados);
                jQuery('#actualizados').text(data.actualizados);
                jQuery('#noexisten').text(data.noexisten);
                jQuery('#resumen').show();
                jQuery('#loadingmessage').hide();  // show the loading message.
            },
            error: function () {
                alert("error");
            }
        }); 
      }
    });
  }
});
</script>

{/literal}

</head>

<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="/sociproma/imagenes/soci.png" alt="soci.png" /></td>
    <td colspan="2" width="100%" align="right" class="titulosistema" background="/sociproma/imagenes/banner_bandes_cen.jpg">Sistema Control de Intervenciones<br/></td>
    <td><img src="/sociproma/imagenes/soci.png" alt="soci.png" /></td>

  </tr>
  <tr>
    <td colspan="4" align="right">{$nombre_log}&nbsp;{$smarty.now|date_format:"%d/%m/%Y %H:%M:%S"}</td>
  </tr>
</table>
<!-- FIN HEADER VENEZUELA -->
<!-- INICIO MENU -->
{if $usuario_log}
<div class="ejemplo"> 
<ul class="adxm menu"> 
  {if $badministra}
    <li><a href="javascript:void();" title="Administración del Sistema">Administración</a>
      <ul> 
        <li><a href="{$direccion}/CtrlUsuario.php" title="Registro de Usuarios">Usuarios</a></li> 
        <li><a href="{$direccion}/CtrlDoctor.php" title="Registro de Doctores">Doctores</a></li> 
        <li><a href="{$direccion}/CtrlPaciente.php" title="Registro de Pacientes">Pacientes</a></li> 
        <li><a href="{$direccion}/CtrlResponsable.php" title="Registro de Responsables">Responsables</a></li> 
        <li><a href="{$direccion}/CtrlIntervencion.php" title="Registro de Tipo de Intervenciones">Tipo de Intervenciones</a></li> 
        <li><a href="{$direccion}/CtrlPagosLote.php" title="Pagos en Lote">Pagos en Lote</a></li> 
      </ul> 
    </li>
   
  </li>  
  {/if}
  <li><a href="javascript:void()" title="Recibos">Recibos</a>
    <ul> 
      <li><div><a href="{$direccion}/CtrlPacienteIntervencion.php?bcompleto=1" title="Cargar Recibo" >Completo</a></div></li>
      <li><div><a href="{$direccion}/CtrlPacienteIntervencion.php?bcompleto=0" title="Cargar Recibo" >Paciente Existe</a></div></li>
    </ul> 
  </li> 
  <li><a href="javascript:void();" title="Consultas del Sistema">Consultas</a>
    <ul> 
      <li><div><a href="{$direccion}/CtrlBuscarRecibo.php" title="Consulta de Recibos" >Consulta Recibos</a></div></li> 
    </ul> 
  </li>
   
  <li><a href="{$direccion}/Salir.php" title="Salir del Sistema">Salir</a></li> 
</ul> 
</div>  

{/if}

<!-- FIN MENU -->
<br/>
{if $subtitulo }
<div class="titulonegro" align="center">{$subtitulo }</div>
{/if}
<div align="center">
<span class="titulodonfomarillo" id="error_msg">{$error_msg}</span>
</div>
{literal}
<script type="text/javascript" language="Javascript">
//ocultar mensajes de error luego de 10 segundos
if (document.getElementById) {
  if (document.getElementById('error_msg')) {
    window.setTimeout("document.getElementById('error_msg').style.display = 'none'",6000);
  }
}
</script>
<script type="text/javascript" src="/sociproma/js/tooltip.js"></script>
{/literal}
<div id="cargando" style="display: none">
  <table> 
    <tr> 
      <td> 
        <span style="color: #000000">Cargando</span>
      </td> 
    </tr>
    <tr> 
      <td align="center">
        <img alt="Cargando" src="/sociproma/imagenes/cargando.gif" /> 
      </td> 
    </tr>  
  </table> 
</div>
