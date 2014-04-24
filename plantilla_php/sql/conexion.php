<?php
	//$link = mysql_pconnect("172.17.92.185", "Prueba", "12345");
	//$link_base = mysql_pconnect('187.141.78.22', 'ingresos', '1ngresos');
	
	$link = mysql_pconnect('localhost', 'root', '');
	$link_base = mysql_pconnect('localhost', 'root', '');

	
	if (!$link || !$link_base) 
	{
    		die('No pudo conectarse: ' . mysql_error());
	}
	
?>