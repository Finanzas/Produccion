<?php
include("crea_pay.php");
include("contador.php");

$archivo 		= $_FILES['archivo-0']['tmp_name'];
$archivoNombre 	= $_FILES["archivo-0"]['name'];
$destino 		= "../archivos_csv/".$archivoNombre;
$banco 			= $_GET['banco'];

if (copy($archivo, $destino)){
	if($banco=='banorte') 		procesarBanorte($destino);
	if($banco=='interacciones') procesarInter($destino);
	if($banco=='banamex') 		procesarBanamexCity($destino);
	
}
else{echo "Error al Copiar el Archivo:".$archivo."<br>";} 

function procesarBanorte($destino){
	//[0]		[1]			[2]			[3]			[4]			[5]					[6]								[7]		[8]		[9]
	//NUMERO	MUNICIPIO	CONCEPTO	MONTO		BANCO		RAMO 28[DESTINO]	CUENTA RAMO 28[CARGO]			OTB		TIPO 	PROGRAMA
	//1			ABEJONES	SEGUNDA...	27,522.50	BANORTE		07261200.....	  	0300261653				  		17040	OTB-OP 	FFM o FMP
	
	$nombre_archivo =	"";
	$fila 			= 	1;
	$suma_importes 	=	0;
	$linea_excel 	= 	"";
	$linea 			= 	"";

	$estado_archivo =	TRUE;
	$programa 		=	"";

	$linea_interna_file = "";
	$linea_externa_file = "";

	$contador_interno = 0;
	$contador_externo = 0;

	//$tipo_layout	= 	getTipoLayout($destino);
	
	ini_set('auto_detect_line_endings',TRUE);//activar la detencion de fin de linea
	
	if (($gestor = fopen($destino, "r")) !== FALSE) 
	{
		while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) 
		{
			if($fila > 1)
			{
				if($datos[0]!='' && (is_numeric($datos[0])) == true)
				{
					$nombre_archivo = 'LAYOUT_BANORTE_CSV';

					$numero_municipio 	=	str_pad($datos[0],3, "0", STR_PAD_LEFT);
					$numero_cuenta 		=	str_replace(' ', '', $datos[5]);
					$importe 			=	str_replace(',', '', $datos[3]);
					
					$suma_importes 		= 	$suma_importes+$importe;

					$cuenta_guarda   	= 	strtoupper($numero_cuenta);
					if(strlen($cuenta_guarda)<18)
					{
						$estado_archivo =	FALSE;
						echo "UNO DE LAS CUENTAS DEL MUNICIPIO NO TIENE L0S 18 DIGITOS"; break;
					}
					$importe_guarda   	= 	strtoupper($importe);
					$municipio_guarda   = 	strtoupper($numero_municipio.substr($datos[1],0,50));
					$concepto_guarda 	= 	strtoupper($datos[2]);

					$importe_formateado = 	number_format(floatval($importe_guarda), 2, '.', '');
					$importe_guarda 	= 	str_pad($importe_formateado,15, "0", STR_PAD_LEFT);

					$municipio_guarda 	= 	str_replace("ñ","n",$municipio_guarda);
					$municipio_guarda 	= 	str_pad($municipio_guarda,55, " ", STR_PAD_RIGHT);

					$concepto_guarda 	= 	str_pad($concepto_guarda,50, " ", STR_PAD_RIGHT);
					
					//parametros
					$orden 			= $numero_municipio.$datos[7]; 			//[clc-No_Mun]
					$ctaban_cargo 	= str_replace(' ', '', $datos[6]);
					$ctaban 		= str_replace(' ', '', $cuenta_guarda);
					$beneficiario 	= $municipio_guarda;
					$importe 		= $importe_guarda;
					$concepto 		= $concepto_guarda;

					//$linea .= $cuenta_guarda.$importe_guarda.$municipio_guarda.$concepto_guarda.chr(13).chr(10); 
					
					if(substr($numero_cuenta,0,3)=='072'){
						$contador_interno++;
						$linea_interna = internaBanorte2014('', $orden, $ctaban_cargo,  $ctaban, $beneficiario, $importe,  $concepto, $VP, '', '','', '', '');
						$linea_interna_file .= $linea_interna.chr(13).chr(10);

					}
					else{
						$contador_externo++;
						$linea_interbancaria = interbancariaBanorte2014('', $orden, $ctaban_cargo,  $ctaban, $beneficiario, $importe,  $concepto, $VP, '', '' );
						$linea_externa_file .= $linea_interbancaria.chr(13).chr(10);
					}					
				}				
			}
			
			$fila++;
	    }//fin WHILE
	    fclose($gestor);
	}//fin IF
	ini_set('auto_detect_line_endings',FALSE);// fin de la deteccion de fin de linea

	//echo "<p>Total Interno:".$contador_interno;
	//echo "<p>Total Externo:".$contador_externo;

	$total_archivos 	= 0;
	$carpeta_interno 	= '';
	$carpeta_externo 	= '';
	$archivo_interno 	= 'N/A';
	$archivo_externo 	= 'N/A';

	if($contador_interno>0){
		$total_archivos++;
		$archivo_interno = generaArchivo('INTERNA_'.$nombre_archivo,$linea_interna_file);
	}
	if($contador_externo>0){
		$total_archivos++;
		$archivo_externo = generaArchivo('EXTERNA_'.$nombre_archivo,$linea_externa_file);
	}

	$fecha_ruta = date("d_m_Y");

	$informacion[] = array (
		'archivos_generados' =>$total_archivos,
		'total_interno' 	=>$contador_interno,
		'total_externo' 	=>$contador_externo,
		'fecha_ruta' 		=>$fecha_ruta,
		'archivo_interno' 	=>$archivo_interno,
		'archivo_externo' 	=>$archivo_externo,
	);
	echo json_encode($informacion);
}

function procesarInter($destino){
	//[0]		[1]			[2]			[3]			[4]			[5]					[6]								[7]		[8]		[9]
	//NUMERO	MUNICIPIO	CONCEPTO	MONTO		BANCO		RAMO 28[DESTINO]	CUENTA RAMO 28[CARGO]			OTB		TIPO 	PROGRAMA
	//1			ABEJONES	SEGUNDA...	27,522.50	BANORTE		07261200.....	  	0300261653				  		17040	OTB-OP 	FFM o FMP
	
	$nombre_archivo =	"";
	$fila 			= 	1;
	$suma_importes 	=	0;
	$linea_excel 	= 	"";
	$linea 			= 	"";

	$estado_archivo =	TRUE;
	$programa 		=	"";

	$linea_interna_file = "";
	$linea_externa_file = "";

	$contador_interno = 0;
	$contador_externo = 0;

	//$tipo_layout	= 	getTipoLayout($destino);
	
	ini_set('auto_detect_line_endings',TRUE);//activar la detencion de fin de linea
	
	if (($gestor = fopen($destino, "r")) !== FALSE) 
	{
		while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) 
		{
			if($fila > 1)
			{
				if($datos[0]!='' && (is_numeric($datos[0])) == true)
				{
					$nombre_archivo = 'LAYOUT_INTERACCION_CSV';

					$numero_municipio 	=	str_pad($datos[0],3, "0", STR_PAD_LEFT);
					$numero_cuenta 		=	str_replace(' ', '', $datos[5]);
					$importe 			=	str_replace(',', '', $datos[3]);
					
					$suma_importes 		= 	$suma_importes+$importe;

					$cuenta_guarda   	= 	strtoupper($numero_cuenta);
					if(strlen($cuenta_guarda)<18)
					{
						$estado_archivo =	FALSE;
						echo "UNO DE LAS CUENTAS DEL MUNICIPIO NO TIENE L0S 18 DIGITOS"; break;
					}
					$importe_guarda   	= 	strtoupper($importe);
					$municipio_guarda   = 	strtoupper($numero_municipio.substr($datos[1],0,50));
					$concepto_guarda 	= 	strtoupper($datos[2]);

					$importe_formateado = 	number_format(floatval($importe_guarda), 2, '.', '');
					$importe_guarda 	= 	str_pad($importe_formateado,15, "0", STR_PAD_LEFT);

					$municipio_guarda 	= 	str_replace("ñ","n",$municipio_guarda);
					$municipio_guarda 	= 	str_pad($municipio_guarda,55, " ", STR_PAD_RIGHT);

					$concepto_guarda 	= 	str_pad($concepto_guarda,50, " ", STR_PAD_RIGHT);
					
					//parametros
					$orden 			= $numero_municipio.$datos[7]; 			//[clc-No_Mun]
					$ctaban_cargo 	= str_replace(' ', '', $datos[6]);
					$ctaban 		= str_replace(' ', '', $cuenta_guarda);
					$beneficiario 	= $municipio_guarda;
					$importe 		= $importe_guarda;
					$concepto 		= $concepto_guarda;

					$linea .= $cuenta_guarda.$importe_guarda.$municipio_guarda.$concepto_guarda.chr(13).chr(10);
				}				
			}
			
			$fila++;
	    }//fin WHILE
	    fclose($gestor);
	}//fin IF
	ini_set('auto_detect_line_endings',FALSE);// fin de la deteccion de fin de linea

	
	$archivo = generaArchivo($nombre_archivo,$linea);
	$fecha_ruta = date("d_m_Y");

	$informacion_archivo[] = array (
		'archivos_generados' 	=>'1',
		'fecha_ruta' 			=>$fecha_ruta,
		'archivo' 				=>$archivo,
	);
	echo json_encode($informacion_archivo);
}

function procesarBanamexCity($destino){
	$fila 			= 1;
	$suma_importes 	= 0;
	$consecutivo	=	regresa_ultimo();
	
	ini_set('auto_detect_line_endings',TRUE);//activar la detencion de fin de linea
	
	if (($gestor = fopen($destino, "r")) !== FALSE) 
	{
		while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) 
		{
			if($fila > 1)
			{
				if($datos[0]!='' && (is_numeric($datos[0])) == true)
				{
					$numero_municipio 	= 	str_pad($datos[0],3, "0", STR_PAD_LEFT);
					$numero_cuenta 		=	str_replace(' ', '', $datos[5]);
					$importe 			=	str_replace(',', '', $datos[3]);
						
					$suma_importes 		= 	$suma_importes+$importe;

					$cuenta_guarda   	= 	strtoupper($numero_cuenta);
					$importe_guarda   	= 	strtoupper($importe);
					$municipio_guarda   = 	strtoupper($numero_municipio.$datos[1]);
					$concepto_guarda 	= 	$datos[2];

					$orden_guarda		= 	strtoupper($datos[7]);

					$tipo_guarda		= 	"OP";
					$clave_guarda		=	"801";
					$fecha 				= 	date("Y-m-d");

					$row['ctaban_cargo']	=	"002640700617974494";
					
					$row['ctaban'] 			=	$cuenta_guarda;			//CUENTA DEL MUNICIPIO
					$row['tra_fecha'] 		= 	$fecha; 				//FECHA SISTEMA
					$row['ur_clave'] 		=	$clave_guarda;			//UR 801
					$row['ur_siglas'] 		=	'';						//PARA MANDAR
					$row['benef'] 			=	$municipio_guarda ;		//NOMBRE DEL MUNICIPIO
					$row['importe'] 		=	$importe_guarda;		//IMPORTE
					//$row['orden'] 		=	$orden_guarda;			//NUMERO CERE-CERECO-CLC
					//$row['tipo'] 			=	$tipo_guarda;			//OTB-OP-CLC
					//$row['concepto'] 		=	$concepto_guarda;		//CONCEPTO CAPTURADO EN LAS VISTA BUSCAR

					$row['orden'] 			=	$orden_guarda."-".$numero_municipio;			//NUMERO CERE-CERECO-CLC
					$row['tipo'] 			=	$tipo_guarda;			//OTB-OP-CLC
					$row['concepto'] 		=	$concepto_guarda."-".$numero_municipio;
					
					$linea .= crea_pay($row,++$consecutivo);			//FUNCION DE CREAR_PAY.PHP
					
					$total_transacciones++;
					// *************************    FIN CITY   *************************	

				}				
			}
			
			$fila++;
	    }//fin WHILE
	    fclose($gestor);
	}//fin IF
	ini_set('auto_detect_line_endings',FALSE);// fin de la deteccion de fin de linea
	
	// *************************    ULTIMA LINEA CITY   *************************
	$linea2 	= 	registro_totales($total_transacciones, $suma_importes);
	$linea 		= 	$linea.$linea2;
	nuevo_contador($consecutivo);
	// *************************    FIN ULTIMA LINEA CITY   *************************
	
	$nombre_archivo = 'LAYOUT_BANAMEX_CSV';
	$archivo = generaArchivo($nombre_archivo,$linea);
	$fecha_ruta = date("d_m_Y");

	$informacion_archivo[] = array (
		'archivos_generados' 	=>'1',
		'fecha_ruta' 			=>$fecha_ruta,
		'archivo' 				=>$archivo,
	);
	echo json_encode($informacion_archivo);
}

function generaArchivo($nombre_archivo,$linea){
	$fecha_sistema = date("d_m_Y");
	$ruta 			= "../layouts/".$fecha_sistema.'/';
	$nombre_archivo = $nombre_archivo."_".$fecha_sistema.".txt";

	if (!is_dir($ruta)) mkdir($ruta);
	
	$file = fopen($ruta.$nombre_archivo,"w") or die ("Problemas en la creacion");
	fwrite($file, $linea);
	fclose($file);
	return $nombre_archivo;
}

function interbancariaBanorte2014($_x_tipo, $orden, $ctaban_cargo,  $ctaban, $beneficiario, $importe,  $concepto, $VP, $_x_consecutivo, $_x_ur_clave ){
	//setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
			
	$i			= 0;
	$mult		= "";
	$total		= 0;
	$espacio 	= chr(32);
	$tabulador 	= chr(9);
	$salto 		= chr(10);
	$dia 		= strftime("%d");
	$mes 		= strftime("%m");
	$anio 		= strftime("%Y");
	$fecha 		= $dia."/".$mes."/".$anio;
	$fecha_formateada=$dia.$mes.$anio;
	$fecha_base = substr($anio,2,2).$mes.$dia."000000";
   
	//if ($banco_cuenta_cargo= substr ($ctaban_cargo,0,3)== "072" AND $banco_cuenta_cargo= substr ($ctaban,0,3)<> "072" )
	//{
		$tipo_layout 	= "31";	
		$operacion 		= "04";
		$clave_id 		= str_pad("567981",13,$espacio);
		$cuenta_cargo 	= $ctaban_cargo;
		$cuenta_origen 	= str_pad($cuenta_cargo, 20, "0", STR_PAD_LEFT);
		$cuenta_destino = str_pad($ctaban, 20, "0", STR_PAD_LEFT);
		$trans 			= array("À" => "A","È" => "E","Ì" => "I","Ò" => "O","Ù" => "U","{" => " ","}" => " ","$" => " ","|" => " ","\"" => " " ,"Á" => "A","É" => "E","Í" => "I","Ó" => "O","Ú" => "U","º" => " ", "ª" => " ","(" => "",")" => "","." => "","%" => "", "/" => "", "-" => "", "," => "", ":" => "", ";" => "", "_" => "", ":" => "","Ñ" => "N");
		$importes 		= $importe;
		$importe_sin_punto = strtr($importes, $trans);
		$importe2 		= str_pad($importe_sin_punto, 14, "0", STR_PAD_LEFT);
		$referencia 	= str_pad($orden, 10, "0", STR_PAD_LEFT);	
		$string_mayus 	= strtoupper(substr(strtr($concepto, $trans),0,30));
		$concepto 		= str_pad($string_mayus, 30,$espacio);
		$moneda 		= 1;
		$rfc 			= "GEO621201KIA ";
		$iva 			= "00000000000000";
		$email 			= str_pad(" ", 39,$espacio);
		$fecha_aplicacion = str_pad(" ", 8,$espacio);
		$string_bene 	= strtoupper(substr(strtr($beneficiario, $trans),0,70));
		$instruccion 	= str_pad($string_bene, 70,$espacio);
		
		$lay_linea 		= 	$operacion.		$clave_id.			$cuenta_origen.	$cuenta_destino.	$importe2.
							$referencia.	$concepto.			$moneda.		$moneda.			$rfc.		$iva.
							$email.			$fecha_aplicacion.	$instruccion;

		
	//}

	return $lay_linea;
}

function internaBanorte2014($_x_tipo, $orden, $ctaban_cargo,  $ctaban, $beneficiario, $importe,  $concepto, $VP, $_x_banco_nombre_abono, $_x_banco_plaza_abono,$_x_banco_chesucur_abono, $_x_consecutivo, $_x_ur_clave ){
	
	$i 				= 0;
	$mult 			= "";
	$total 			= 0;
	$espacio 		= chr(32);
	$tabulador 		= chr(9);
	$salto 			= chr(10);
	$dia 			= strftime("%d");
	$mes 			= strftime("%m");
	$anio 			= strftime("%Y");
	$fecha 			= $dia."/".$mes."/".$anio;
	$fecha_formateada 	= $dia.$mes.$anio;
	$fecha_base 	= substr($anio,2,2).$mes.$dia."000000";

	//if ($banco_cuenta_cargo= substr ($ctaban_cargo,0,3)== "072" AND $banco_cuenta_cargo= substr ($ctaban,0,3)== "072" )
	//{
		$tipo_layout 	= "32";
		$operacion 		= "02";
		$clave_id 		= str_pad("567981",13,$espacio);
		$cuenta_cargo 	= $ctaban_cargo;
		$cuenta_origen 	= str_pad($cuenta_cargo, 20, "0", STR_PAD_LEFT);
		
		$cuenta_abono 	= substr($ctaban,6,11);
		$cuenta_destino = str_pad($cuenta_abono, 20, "0", STR_PAD_LEFT);
		$trans 			= array("À" => "A","È" => "E","Ì" => "I","Ò" => "O","Ù" => "U","{" => " ","}" => " ","$" => " ","|" => " ","\"" => " " ,"Á" => "A","É" => "E","Í" => "I","Ó" => "O","Ú" => "U","º" => " ", "ª" => " ","(" => "",")" => "","." => "","%" => "", "/" => "", "-" => "", "," => "", ":" => "", ";" => "", "_" => "", ":" => "","Ñ" => "N");
		$importes 		= $importe;
		$importe_sin_punto = strtr($importes, $trans);
		$importe2 		= str_pad($importe_sin_punto, 14, "0", STR_PAD_LEFT);
		$referencia 	= str_pad($orden, 10, "0", STR_PAD_LEFT);
		$string_mayus 	= strtoupper(substr(strtr($concepto, $trans),0,30));
		
		//$concepto 		= str_pad(" ", 30,$espacio);
		$concepto 		= str_pad($string_mayus, 30,$espacio);
		
		$moneda 		= 1;
		$rfc 			= "GEO621201KIA ";
		$iva 			= "00000000000000";
		$email 			= str_pad(" ", 39,$espacio);
		$fecha_aplicacion = str_pad(" ", 8,$espacio);
		$string_bene 	= strtoupper(substr(strtr($beneficiario, $trans),0,70));
		
		//$instruccion 	= str_pad(" ", 70,$espacio);
		$instruccion 	= str_pad($string_bene, 70,$espacio);
		
		$lay_linea = 	$operacion.		$clave_id.	$cuenta_origen.		$cuenta_destino.	$importe2.
						$referencia.	$concepto.	$moneda.$moneda.	$rfc.				$iva.		$email.
						$fecha_aplicacion.			$instruccion;
		
	//}
	return $lay_linea;
}
?>