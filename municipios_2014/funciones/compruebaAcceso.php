<?php
$ip = array('::1','172.17.92.161');

if (!in_array(getRealIP(), $ip)) {
	header("location:../municipios_2014/denegado.php");
	//echo "sin acceso";
}

function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
    //return '172.17.92.166';
}
?>