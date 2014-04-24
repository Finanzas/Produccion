<?php
require "../sql/conexion.php";
$nombre_archivo = 'buscaInfomunicipio.php';

$modo_busqueda = $_GET['opcion'];

switch($modo_busqueda)
{
	case 'normal'		:buscaMun();		break;
	case 'autocomplete'	:autocomplete();	break;
}

cerrarConexion();

function buscaMun(){
	$mun_tipo 	= $_POST['mun_tipo'];
	$mun_id 	= $_POST['mun_id'];
	$mun_nombre = $_POST['mun_nombre'];

	$info_mun = array();

	$condicion = " WHERE programa='$mun_tipo' ";

	if($mun_id!='') 	$condicion .= " AND id_municipio=$mun_id";
	if($mun_nombre!='') $condicion .= " AND nombre_municipio like '%$mun_nombre%' ";;

	$sql 		= "SELECT * FROM municipios_cuentas.cuentas ".$condicion;
	$ejecuta 	= mysql_query($sql, $GLOBALS['link_local']) or die (errorSQL($sql,	mysql_error(),	__LINE__));

	if(mysql_num_rows($ejecuta)==1){
		$arreglo = mysql_fetch_array($ejecuta);

		$info_mun = array(
			'programa' 	=> $arreglo['programa'],
			'id' 		=> $arreglo['id_municipio'],
			'nombre' 	=> $arreglo['nombre_municipio'],
			'cuenta' 	=> $arreglo['cuenta']
		);
	}

	echo json_encode($info_mun);
}

function autocomplete(){
	$mun_nombre = $_GET['term'];

	$sql 		= "SELECT * FROM municipios_cuentas.cuentas WHERE programa='RAMO' AND nombre_municipio like '%$mun_nombre%'";
	$ejecuta 	= mysql_query($sql, $GLOBALS['link_local']) or die (errorSQL($sql,	mysql_error(),	__LINE__));

	$respuesta = array();
	while($reg = mysql_fetch_array($ejecuta)){
		$respuesta[]= array(
			'label'=>$reg['nombre_municipio']
		);
	}
	echo json_encode($respuesta);	
}

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

