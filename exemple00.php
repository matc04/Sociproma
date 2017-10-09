<?php
/**
 * Logiciel : exemple d'utilisation de HTML2PDF
 * 
 * Convertisseur HTML => PDF
 * Distribué sous la licence LGPL. 
 *
 * @author		Laurent MINGUET <webmaster@html2pdf.fr>
 * 
 * isset($_GET['vuehtml']) n'est pas obligatoire
 * il permet juste d'afficher le résultat au format HTML
 * si le paramètre 'vuehtml' est passé en paramètre _GET
 */

  require ("PacienteIntervencion.php");
  require ("DetallePacienteInterven.php");
  require ("Fecha.php");
  require ("SmartyIni.php");


  $Conexion_ID = mysql_connect("localhost", "root", "mt2212");
  mysql_select_db("anestesia", $Conexion_ID)) {

  $smarty  = new SmartyIni;

 	// récupération du contenu HTML
#	ob_start();
#	include('localhost://exemple00.php');
	$content = $smarty->fetch('RecibosPdf.tpl');
	
	// conversion HTML => PDF
	require_once('c:\wamp\www\html2pdf\html2pdf.class.php');
	try
	{
		$html2pdf = new HTML2PDF('P','A4','fr', false, 'ISO-8859-15');
//		$html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        	$html2pdf->Output('exemple00.pdf');
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
