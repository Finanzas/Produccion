<?php

	function regresa_ultimo(){
		$hoy = date("d_m_Y");
		$archivo = "../contador/Cont_$hoy.txt";
		if(file_exists($archivo)){
			//echo "Ya existe";
			//leer la primera linea del archivo y regresarla
			$file = fopen($archivo, "r") or exit("No se a podido abrir el archivo");
			$contNum = fgets($file);
			return $contNum;
			
		}else{
			//recorrer el directorio y eliminar todos los archivos que contenga
			$dir = opendir("../contador");
		    while ($elemento = readdir($dir)){
		        if( $elemento != "." && $elemento != ".."){
		        	//eliminar estos elementos
		        	if(!is_dir("../contador/$elemento")){
						unlink("../contador/$elemento");	        	
		        	}
				}
			}
			
			//crear el archivo buscado anteriormente dado que si entro aqui esta es la primera ejecucion
			$contF=fopen($archivo,"w+") or die("No se pudo crear el archivo");
	  			fputs($contF,"5000");
			fclose($contF);
		    return 5000;       
		}
	}
	
	function nuevo_contador($cont){
		$hoy = date("d_m_Y");
		$archivo = "../contador/Cont_$hoy.txt";
		$contF=fopen($archivo,"w+") or die("No se pudo crear el archivo");
	  		fputs($contF,$cont);
		fclose($contF);
	}
	
	function ultimo_inversion(){
		$archivo = "../contador/inversion/cont_inv.txt";
		if(file_exists($archivo)){
			//echo "Ya existe";
			//leer la primera linea del archivo y regresarla
			$file = fopen($archivo, "r") or exit("No se a podido abrir el archivo");
			$contNum = fgets($file);
			return $contNum;
			
		}else{
			//crear el archivo buscado anteriormente dado que si entro aqui esta es la primera ejecucion
			$contF=fopen($archivo,"w+") or die("No se pudo crear el archivo");
	  			fputs($contF,"0");
			fclose($contF);
		    return 0;      
		}
	}
	
	function nuevo_contador_inv($cont){
		$archivo = "../contador/inversion/cont_inv.txt";
		$contF=fopen($archivo,"w+") or die("No se pudo crear el archivo");
	  		fputs($contF,$cont);
		fclose($contF);
	}
