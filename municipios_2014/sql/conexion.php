<?php 
$link_local = mysql_pconnect("172.17.92.185", "Prueba", "12345");
//$link_sinpres = mysql_connect('172.17.90.1', 'ingresos', '1ngresos');
$link_sinpres = mysql_connect('172.17.90.2', 'ingresos', '1ngresos');

//$link_local = mysql_pconnect('localhost', 'root', '');
//$link_sinpres = mysql_pconnect('localhost', 'root', '');


if (!$link_local || !$link_sinpres) 
{
		die('No pudo conectarse: ' . mysql_error());
}

function cerrarConexion(){
	mysql_close($GLOBALS['link_local']);
	mysql_close($GLOBALS['link_sinpres']);
}
?>