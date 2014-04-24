<?php
require "../sql/conexion.php";
$nombre_archivo = 'consulta.php';
$base_sinpres 	= 'ctrl2014';
$base_servidor 	= '';

$opcion 	= $_POST['opcion'];
$numero 	= $_POST['numero'];
$municipios = $_POST['municipios'];
$programa 	= $_POST['programa'];
$concepto 	= strtoupper ( $_POST['concepto']);
if($concepto=='') $concepto = 'N/A';

$condicion_municipios = '';
if($municipios!=''){
	$condicion_municipios = " AND mun_id in($municipios)";
}

$sql_presupuestal = "SELECT
						'PRESUPUESTAL' TABLA,
						gpo_id GPO,
						ur_id UR,
						clc_id CLC,
						mun_id MUN,
						mun_nombre MUNICIPIO,
						rfc_id RFC,
						ncp_concepto CONCEPTO,
						ncp_referencia REFERENCIA,
						ncp_percepcionNeto IMPORTE,
						'+' MOV
					FROM
						$base_sinpres.scp_Presupuestal
					INNER JOIN $base_sinpres.cat_Municipio ON(ue_id=mun_id)
					WHERE
						gpo_id = 8
					AND ur_id = 1
					AND clc_id = $numero $condicion_municipios ORDER BY mun_id";

$sql_contable = "SELECT
					'CONTABLE' TABLA,
					gpo_id GPO,
					ur_id UR,
					clc_id CLC,
					mun_id MUN,
					mun_nombre NOMBRE,
					rfc_id RFC,
					ncc_concepto CONCEPTO,
					ncc_referencia REFERENCIA,
					ABS(ncc_percepcion - ncc_retencion) IMPORTE,
					IF (ncc_percepcion - ncc_retencion >= 0,'+','-') MOV
				FROM
					$base_sinpres.scp_Contable
				INNER JOIN $base_sinpres.cat_Municipio on(SUBSTR(cta_id, 2, 3)=mun_id)
				WHERE
					gpo_id = 8
				AND ur_id = 1
				AND clc_id = $numero $condicion_municipios ORDER BY mun_id";


$ejecuta_pres 	= mysql_query($sql_presupuestal, $GLOBALS['link_sinpres']) 	or die (errorSQL($sql_presupuestal,	mysql_error(),	__LINE__));
$ejecuta_cont 	= mysql_query($sql_contable, $GLOBALS['link_sinpres']) 		or die (errorSQL($sql_contable,		mysql_error(),	__LINE__));

$total_presupuestal = mysql_num_rows($ejecuta_pres);
$total_contable 	= mysql_num_rows($ejecuta_cont);

if($total_presupuestal > 0 || $total_contable > 0){
	$tabla = '';
	$i = 1;
	$importe_total = 0;
	while($row  = mysql_fetch_object($ejecuta_pres)){
		//echo "<pre>";var_dump($row);
		$mun_pre[$row->MUN] =  $row->IMPORTE ;
	}
	
	while($row  = mysql_fetch_object($ejecuta_cont)){
		//echo "<pre>";var_dump($row);
		$mun_con[$row->MUN] =  $mun_con[$row->MUN] + $row->IMPORTE ;

	}

	foreach ($mun_pre as $key => $value) {
		$mun_importe_total[$key] = $mun_pre[$key]-$mun_con[$key];
		$comprobar [] = array (
			'mun_id' 	=> $key,
			'pre' 		=> number_format($mun_pre[$key],2),
			'con' 		=> number_format($mun_con[$key],2),
			'res' 		=> number_format($mun_importe_total[$key],2),
		);
	}

	//echo "<pre>";var_dump($comprobar);

	mysql_data_seek( $ejecuta_pres , 0 );
	
	while($row  = mysql_fetch_object($ejecuta_pres)){
		$nombre_mun 		= utf8_encode($row->MUNICIPIO);
		$importe_mun 		= $mun_importe_total[$row->MUN];
		$importe_mun_forma 	= number_format($mun_importe_total[$row->MUN],2);
		$importe_total  	+= $mun_importe_total[$row->MUN];

		$verificacion_concepto = '<input type="hidden" value="'.$row->CONCEPTO.'">';

		$tabla .='<tr id="fila_'.$i .'">'.
					'<td>'.'<img  class="img_eliminar" ref="'.$i.'" importe ="'.$row->IMPORTE.'" src="imagenes/error.png" width="15" height="auto" >'.'</td>'.
					'<td>'.$row->CLC 		.'<input type="hidden" id="tabla_clc_'.$i.'" class="tabla_clc" name="clc[]" value="'.$row->CLC.'">'				.'</td>'.
					'<td>'.$row->MUN 		.'<input type="hidden" id="tabla_mun_id_'.$i.'" class="tabla_mun_id" name="mun_id[]" value="'.$row->MUN .'">'			.'</td>'.
					'<td>'.$nombre_mun 		.'<input type="hidden" id="tabla_nombre_id_'.$i.'" class="tabla_nombre_mun" name="nombre_mun[]" value="'.$nombre_mun .'">'	.'</td>'.
					'<td>'.$concepto  		.'<input type="hidden" id="tabla_concepto_'.$i.'" class="tabla_concepto" name="concepto[]" value="'.$concepto .'">'		.$verificacion_concepto.'</td>'.
					'<td>'.cuentaMun($row->MUN)		.'<input type="hidden" id="tabla_ref_'.$i.'" class="tabla_referencia" name="cuenta_destino[]" value="'.cuentaMun($row->MUN) .'">'	.'</td>'.
					'<td class="importe">'.$importe_mun_forma 	.'<input type="hidden" id="tabla_importe_'.$i.'" class="tabla_importe" name="importe[]" value="'.$importe_mun.'">'.'</td>'.
				'</tr>';
		$i++;
	}

	$resultado = array('total_registro' => $total_presupuestal+$total_contable,'html'=>$tabla,'importe_total_clc'=>'$ '.number_format($importe_total,2));
	echo json_encode($resultado);
}
else{
	//echo "S/R";
	$resultado = array('total_registro' => '0','mensaje'=>'La Cosulta a la CLC '.$numero.' No Regreso Ningun Resultado');
	echo json_encode($resultado);
}


function cuentaMun($mun_id){
	$numero_cuenta 	= 'N/A';
	$programa 		= $_POST['programa'];
	//$programa 		= 'RAMO 28';//QUITAR

	$sql 			= "SELECT cuenta FROM municipios_cuentas.cuentas WHERE id_municipio=$mun_id AND programa = '$programa'";
	$ejecuta 		= mysql_query($sql, $GLOBALS['link_local']) or die (errorSQL($sql,	mysql_error(),	__LINE__));
	if(mysql_num_rows($ejecuta)>0){
		$arreglo_cuenta = mysql_fetch_array($ejecuta);
		$numero_cuenta 	= $arreglo_cuenta['cuenta'];
		if($numero_cuenta=='') $numero_cuenta 	= 'N/A';
		if(strlen($numero_cuenta)!=18) $numero_cuenta 	= 'N/A';
	}
	//$numero_cuenta 	= 'N/A';//QUITAR
	//$numero_cuenta 	= '072610002193867880';//QUITAR
	//$numero_cuenta 	= '002610700769608428';//QUITAR


	return $numero_cuenta;
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

cerrarConexion();

 
?>