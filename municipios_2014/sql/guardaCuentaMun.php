<?php 
require "../sql/conexion.php";
$nombre_archivo = 'guardaCuentaMun.php';

$mun_tipo 	= $_POST['mun_tipo'];
$mun_id 	= $_POST['mun_id'];
$mun_nombre = $_POST['mun_nombre'];
$mun_cuenta = $_POST['mun_cuenta'];

$cuenta_ant = 'N/A';
$sql_cuenta = "SELECT cuenta FROM municipios_cuentas.cuentas WHERE programa='$mun_tipo' AND id_municipio=$mun_id AND nombre_municipio like '%$mun_nombre%'";
$ejecuta_cuenta 	= mysql_query($sql_cuenta, $GLOBALS['link_local']) or die (errorSQL($sql_cuenta,	mysql_error(),	__LINE__));
if(mysql_num_rows($ejecuta_cuenta)==1){
	$arreglo = mysql_fetch_array($ejecuta_cuenta);
	$cuenta_ant = $arreglo['cuenta'];
}

$info_mun 	= array();
$sql 		= "UPDATE municipios_cuentas.cuentas SET cuenta='$mun_cuenta' WHERE programa='$mun_tipo' AND id_municipio=$mun_id AND nombre_municipio like '%$mun_nombre%'";
$ejecuta 	= mysql_query($sql, $GLOBALS['link_local']) or die (errorSQL($sql,	mysql_error(),	__LINE__));
$afectatos 	= mysql_affected_rows($GLOBALS['link_local']);
if($afectatos==1){
	$info_mun = array(
		'edicion' 	=> 'ok',
		'programa' 	=> $mun_tipo,
		'id' 		=> $mun_id,
		'nombre' 	=> $mun_nombre,
		'cuenta_ant'=> $cuenta_ant,
		'cuenta' 	=> $mun_cuenta,
	);
	guardaLogTxt($info_mun);
}
else{
	$info_mun = array(
		'edicion' 	=> 'Sin Afectacion',
		'programa' 	=> $mun_tipo,
		'id' 		=> $mun_id,
		'nombre' 	=> $mun_nombre,
		'cuenta_ant'=> $cuenta_ant,
		'cuenta' 	=> $mun_cuenta
	);
	guardaLogTxt($info_mun);

}

echo json_encode($info_mun);
cerrarConexion();


function errorSQL($sql,$mensaje_sql,$numero_linea){
	$error = array(
		'archivo' 	=> $GLOBALS['nombre_archivo'],
		'mensaje' 	=> $mensaje_sql ,
		'linea' 	=> $numero_linea,
		'sql' 		=> $sql,
		);
	echo '<pre>';
	var_dump($error);
}

function guardaLogTxt($linea){	
	$usuario 		= getRealIP();
	$fecha_sistema 	= date("d-m-Y H:i:s A");
	$ruta 			= "../log/";
	$nombre_archivo = "edicionCuentas.txt";
	
	$file = fopen($ruta.$nombre_archivo,"a") or die ("Problemas en la creacion");
	fwrite($file,$fecha_sistema.chr(9).$usuario.chr(9).print_r($linea,true).chr(13).chr(10));
	fclose($file);
}

function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
}