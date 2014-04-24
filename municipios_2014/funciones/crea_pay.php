<?php
	function crea_pay($row,$cont){
		//************************************ variables de inicio **************************************/
		//echo "<pre>";var_dump($row);	
		extract($row);//crear variables del arreglo del row automaticamente
		//echo "<pre>";var_dump($row);
		
		$espacio = chr(32);//insertar un caracter de espacio
		$salto = chr(13).chr(10);//avanza una linea
		
/**FIXME:*****************************************inicio del proceso************************************/
		//obtener valores de los bancos
		$banco_cargo= substr ($ctaban_cargo,0,3);//clave del banco a donde se cargara el importe
		$banco_abono= substr ($ctaban,0,3);//clave del banco a donde se abonara el importe
		
// FIXME:***** Pos 1 ************************************************* /		
		$linea1 = "PAY485";
		
// FIXME:***** Pos 7 ************************************************* /
		$linea1.= rellena_con($espacio, 10);
		
// FIXME:***** Pos 17 ************************************************* /
		$linea1.= AAMMDD($tra_fecha);//cambia la fecha a formato AAMMDD
		
// FIXME:***** Pos 23 ************************************************* /
		$linea1.=$banco_abono=="002"?"072":"001";// solo se crean layouts para tranferencia

		/*
		$codigo_transacccion=$banco_abono=="002"?"072":"001";
		echo "[".$ctaban."]"."\n";
		echo "[".$banco_abono."]"."\n";
		echo "[".$codigo_transacccion."]"."\n";
		echo "[------------------------]"."\n";
		*/
		
// FIXME:***** Pos 26 ************************************************* /
		$linea1.=rellena_con($espacio,15,"$tipo$orden");//naturaleza del movimiento + numero del movimiento
		
// FIXME:***** Pos 41 ************************************************* /
		$linea1.=rellena_con("0",8,$cont,"izq");//numero secuencial de la transaccion
		
// FIXME:***** Pos 49 ************************************************* /
		$linea1.=rellena_con($espacio,20,"X");
		
// FIXME:***** Pos 69 ************************************************* /
		$linea1.="MXN";//moneda del pago (fijo)
		
		//**************************** CODIGO DEL BENEFICIARIO ****************************
// FIXME:***** Pos 72 ************************************************* /
		$benf = substr("UR".$ur_clave." ".$ur_siglas,0,20);//Codigo del Beneficiaro -> clc_beneficiario
		if(strlen($benf)<20){
			$benf = rellena_con($espacio, 20, limpiar($benf,"cd_b"));
		}
		$linea1.=$benf;//insertar beneficiario
		
// FIXME:***** Pos 92 ************************************************* /
		$import = $importe * 100;//quitar el punto decimal del importe multiplicandolo por 100
		
			if(strlen($import)<15)
			{
				$import = rellena_con("0", 15,$import,"izq");
			}
			else
			{
				echo "ERROR DE LONGITUD EN EL IMPORTE";
				die;
			}
		$linea1.=$import;//insertar importe
		
// FIXME:***** Pos 107 ************************************************* /
		$linea1.= rellena_con($espacio, 6);//como siempre seran transacciones este campo no se necesita poner
		
// FIXME:***** Pos 113 ************************************************* /
		///*
		$arreglo = explode("-", $orden);
		$numero_folio = $arreglo[0];
		$linea1.= rellena_con($espacio, 35,$numero_folio);
		//*/

		//$linea1.= rellena_con($espacio, 35,$orden);//referencia numerica
		
		/***************************** referencia alfanumerica********************************/
// FIXME:***** Pos 148 ************************************************* /
		//$ref_alfa = limpiar($tipo." ".$orden." ".$concepto,"ref");
		$ref_alfa = limpiar($concepto,"ref");
			
			if(strlen($ref_alfa)>=35)
			{
				$ref_alfa = substr($ref_alfa, 0,35);
			}
			else
			{
				$ref_alfa = rellena_con($espacio, 35,$ref_alfa);
			}
		$linea1.=$ref_alfa;//insertar referencia alfanumerica en este caso concepto del pago
		
// FIXME:***** Pos 183 ************************************************* /
		/*************************** mas detalles ******************/
		$linea1.= rellena_con($espacio, 70);
		
// FIXME:***** Pos 253 ************************************************* /
		/************************** comprobande fiscal ?? *********************/
		$linea1.="05";//05 sin comprobante, 06 con oomprobante
		
// FIXME:***** Pos 255 ************************************************* /
		$linea1.="01";//tipo de cuenta CHEQUES
		
		/*************************** NOMBRE Y DIRECCIONES DEL BENEFICIARIO ****************/
// FIXME:***** Pos 257 ************************************************* /
		$benf = substr(limpiar($benef,"nm_b"),0,35);//Codigo del Beneficiaro -> clc_beneficiario
			
			if(strlen($benf)<35)
			{
				$benf = rellena_con($espacio, 35, $benf);
			}
		$linea1.=$benf;//insertar nombre del beneficiario solo 35 caracteres para mexico
		
		//completar los 80 espacios reservados
		$linea1.= rellena_con($espacio, 45);
		
// FIXME:***** Pos 337 ************************************************* /		
		//70 espacios por las direcciones del beneficiario
		$linea1.= rellena_con($espacio, 70,"X");
		
// FIXME:***** Pos 407 ************************************************* /
		//otros 45 en blanco 
		$linea1.= rellena_con($espacio, 45);
		
// FIXME:***** Pos 452 ************************************************* /
		$linea1.= $banco_abono=="002"?"000":$banco_abono;//codigo del banco del beneficiario

// FIXME:***** Pos 455 ************************************************* /
		$linea1.= rellena_con($espacio, 8);

// FIXME:***** Pos 463 ************************************************* /		
		/*********************** cuenta del beneficiario **********************/
		$linea1.= rellena_con($espacio, 35,$ctaban);
		
// FIXME:***** Pos 498 ************************************************* /
		$linea1.="05";//para CLABE interbancaria
		
// FIXME:***** Pos 500 ************************************************* /
		$linea1.= rellena_con($espacio, 30,"BcoBenef");
		
// FIXME:***** Pos 530 ************************************************* /
		/************************* impuesto ****************************/
		$linea1.= rellena_con("0", 17);
		
// FIXME:***** Pos 547 y 548 ************************************************* /
		$linea1.="N";//TODO: prioridad de dispersion ??
		$linea1.="N";//TODO: confidencialS ??
		
// FIXME:***** Pos 549 ************************************************* /
		$linea1.=$banco_abono=="002"?rellena_con($espacio,2):"01";//SPEI
		
// FIXME:***** Pos 551 ************************************************* /		
		/*********************** cuenta CITIDIRECT *******************/
		$linea1.= rellena_con($espacio, 20, substr($ctaban_cargo, 6,11));//TODO: cual es la cuenta CITI ??

// FIXME:***** Pos 571 ************************************************* /	
		$linea1.= rellena_con($espacio, 116);
		
// FIXME:***** Pos 687 ************************************************* /
		$linea1.="00000"; //codigo de activacion

// FIXME:***** Pos 692 ************************************************* /		
		$linea1.=rellena_con($espacio, 50, "sergio.enriquez@finanzasoaxaca.gob.mx");
		
// FIXME:***** Pos 742 ************************************************* /
		$linea1.=rellena_con(9, 15);
		
// FIXME:***** Pos 757 ************************************************* /
		$linea1.=$espacio;
		
// FIXME:***** Pos 758 ************************************************* /
		$linea1.=rellena_con($espacio, 11,"E-Mail");
		
// FIXME:***** Pos 769 ************************************************* /
		$linea1.=rellena_con($espacio, 256);
		$linea1.=$salto;
		
/**FIXME:****************************** detalles complementarios ***********************/
/*
		$linea1.="VOI485";
		$linea1.=rellena_con($espacio, 10);
		$linea1.=rellena_con($espacio,15,"$tipo$orden");//naturaleza del movimiento + numero del moviemiento
		$linea1.=rellena_con("0",8,$cont,"izq");//numero secuencial de la transaccion
		$linea1.=rellena_con("0",4,$cont,"izq");//TODO: que es la sub secuencia
		
		$concepto = limpiar($concepto,"det");
		$conc = substr($concepto,0,75);
		
			if(strlen($conc)<75)
			{
				$conc = rellena_con($espacio,75,$conc);
			}
		
		$linea1.=$conc;
		$linea1.=rellena_con($espacio, 132);
		$linea1.=$salto;*/
		
		return $linea1;//regresar las dos lineas creadas
	}

	function registro_totales($cont,$importe_total){
		$espacio = chr(32);//insertar un caracter de espacio
		$salto = chr(13).chr(10);//avanza una linea
		
		$linea1="TRL";
		$linea1.=rellena_con("0",15,$cont,"izq");//registro de pagos
		
		$import = $importe_total*100;
		
			if(strlen($import)<15)
			{
				$import = rellena_con("0", 15,$import,"izq");
			}
			else
			{
				echo "ERROR DE LONGITUD EN EL IMPORTE";
				die;
			}
		
		$linea1.=$import;
		$linea1.=rellena_con("0", 15);
		$linea1.=rellena_con("0",15,($cont*1),"izq");//es el total de linea con registro-detalle en nuestrp caso siempre sera el doble
		$linea1.=rellena_con($espacio, 37);
		
		return $linea1;
	}
	
	function rellena_con($relleno,$cuantos,$cadena="",$hacia = "der"){
		$hacia = $hacia=="der"?STR_PAD_RIGHT:STR_PAD_LEFT;
		$cadena=str_pad($cadena, $cuantos,$relleno,$hacia);
		return $cadena;
	}
	
	function AAMMDD($fecha){
		if ($fecha==0) return('');
	      $parts = explode('-', $fecha);
		if ($parts[0]!="0000"){
			$part_u = explode(" ", $parts[2]);
	      $newDate = substr($parts[0],2,2).$parts[1].$part_u[0]; // AAMMDD
	      return $newDate; 
		} else return "";
	}
	
	function limpiar($cadena,$tipo="def"){
		$espacio = chr(32);//insertar un caracter de espacio
		$cadena = utf8_encode($cadena);
		//Aun no se usan los dos primeros por ser constantes	
		$arreglo["tr_c"] = array("!",'"',"#","$","%","/","(",")","=","?","¡","¨","*","[","]",";",":","_","'","¿","´","+","{","}",",",">","<","°","|","@","¬","\\","~","`","^");
		$arreglo["rfc"] = 	array("!",'"',"#","$","%","/","(",")","=","?","¡","¨","*","[","]",";",":","_","'","¿","´","+","{","}",",",".","-",">","<","°","|","@","¬","\\","~","`","^"," ");
		
		$arreglo["cd_b"] = array("!",'"',"#","$","%","&","=","¡","¨","*","[","]",";","_","¿","´",">","<","°","|","@","¬","\\","~","`","^");
		$arreglo["ref"] = array("!",'"',"$","%","&","/","(",")","=","?","¡","¨","*","[","]",";",":","_","'","¿","´","+","{","}",",",".","-",">","<","°","|","@","¬","\\","~","`","^");
		$arreglo["nm_b"] = array("!",'"',"#","$","%","/","(",")","=","?","¡","¨","*","[","]",";",":","_","¿","´","+","{","}",",",".","-",">","<","°","|","@","¬","\\","~","`","^");
		$arreglo["dr_b"] = array("!",'"',"#","$","%","(",")","=","?","¡","¨","*","[","]",";",":","_","¿","´","+","{","}",".","-",">","<","°","|","¬","\\","~","`","^");
		$arreglo["e_b"] = array("!",'"',"$","%","&","/","(",")","=","?","¡","¨","*","[","]",";",":","'","¿","´","+","{","}",",",">","<","°","|","¬","\\","~","`","^");
		$arreglo["det"] = array("!","=","¡","¨","[","]",":","¿","´","{","}",">","<","°","|","¬","\\","~","`","^");
		$arreglo["def"] = array("");
		
		$arreglo["acento"]=array("Á","É","Í","Ó","Ú","Ñ","á","é","í","ó","ú","ñ","Ü","ü");
		$arreglo["sacento"]=array("A","E","I","O","U","N","a","e","i","o","u","n","U","u");
			
			//quitar acentos
			$cadena = str_replace(
		        $arreglo["acento"],
		        $arreglo["sacento"],
		        $cadena
		    );	
			
			$cadena = str_replace(
		        $arreglo["$tipo"],
		        $espacio,
		        $cadena
		    );
		
		//echo "<br>Termino asi -> ".$cadena;
		return $cadena;		
	}
