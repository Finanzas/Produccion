<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>VENTANA</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="FerBooK">
	<!-- Date: 2013-05-25 -->
	<link rel="shortcut icon" href="../imagenes/oaxaca_logo.png" />
	<link href="../css/estilo_pagina.css" rel="stylesheet" type="text/css" />
	<link href="../css/menu.css" rel="stylesheet" type="text/css" />
	
	<!-- ARCHIVOS JQUERY -->
	<script type="text/javascript" src="../js/jquery-1.8.2.js"></script>
	
	<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css">
	<script src="../js/jquery-ui.min.js"></script>
	
	<!-- FUNCIONES PROPIAS JAVASCRIPT-->
	<script type='text/javascript' src='../js/propios/js_pagina.js'></script>
	
	<style>
	.ui-autocomplete-loading {
	background: white url('../imagenes/ui-anim_basic_16x16.gif') right center no-repeat;
	}
	</style>
	<!--FIN ARCHIVOS JQUERY -->
	
	<!-- JQUERY ANIDADO-->
	<script type="text/javascript"> 
	$(document).ready(function(e)
	{
		//AYUDA
		$("#algo").live('click',function(){});
		$(".algo").live('keyup',function(event) {});
		//FIN AYUDA
		
		
	});//FIN $(document).ready
	
	//FUNCIONES JAVASCRIPT
	
	function algo(){} //FIN FUNCION ALGO -GUIA
	
	//FIN FUNCIONES JAVASCRIPT
	</script>
	<!-- FIN JQUERY ANIDADO-->
	
</head>
<body>
	<?php
	require "../funciones/funciones.php";
	setEncabezado();
	?>
	
	<div id="zona_menu">
		<?php
		Navegacion();
		?>
	</div>
	
	<div id="contenido" >
		<div id="contenido_izquierda" style="float: left; width: 50%;">
			<fieldset> 
				<legend align="center"><strong>CONSULTA</strong></legend>
				<?php
				setFormEjemplo();
				?>
			</fieldset>
		</div>
		<div id="contenido_derecha" style="display:none">
			<fieldset> 
				<legend align="center"><strong>OTRA COSA</strong></legend>
			</fieldset>
		</div>
		<div id="contenido_centro" style="float: right; width: 100%;padding-top:25px">
			<?php
			setTablaEjemplo();
			?>
		</div>
	</div>


	<!-- DIALOG Y BLOQUEADOR -->
	<div id="bloquea" class="pantalla_bloquea">
		<div style="color: white; padding-top: 150px; text-align: center;">
			<p style="font-size:38px;font-weight: bold;">CARGANDO</p>
			<img src="../imagenes/loader_2.gif" style="">
		</div>
	</div>
	
	<!-- DIALOG LAYOUT-->
	<div id="dialog" title="Titulo" style="display:none">
		<fieldset>
			
		</fieldset>
	</div>
	<!-- FIN DIALOG LAYOUT-->
</body>
</html>

<!-- FUNCIONES DE EJEMPLO PHP -->
<?php
function setFormEjemplo()
{
?>
	<form id="formulario" name="formulario" action="algo.php" target="_blank">
		<table id="tabla_formualario">
			<tr>
				<td><p>Numero Cere-Cereco</p></td>
				<td>
					<input type="text" class="texto">
				</td>
			</tr>
			
			<tr>
				<td><p>Seleccione</p></td>
				<td>
					<select id="tipo_pagadora" name="tipo_pagadora">
						<option value="bancomer">Bancomer</option> 
						<option value="banorte">Banorte</option>  
						<option value="banamex">Banamex</option>  
					</select>
				</td>
			</tr>
			
			<tr>
				<td></td>
				<td>
					<input type="button" id="boton" class="boton" value="Ocultar">
					<input type="button" id="boton_2" class="boton" value="Mostrar">
					<input type="button" id="loader" class="boton" value="Loader">
				</td>
			</tr>
		</table>
	</form>
<?php	
}
?>
<?php
function setTablaEjemplo()
{
?>
	<fieldset style="width:98%;"> 
		<legend align="center"><strong>INFORMACION</strong></legend>
		<table class="tabla" border="1" style="width:100%;border: 1px solid;">
		<tbody>
		<tr style="border: 1px solid;">
		<th>FE. PAGO</th>
		<th>LOTE</th>
		<th>CERE</th>
		<th>CLC</th>
		<th>BENEFICIARIO</th>
		<th>IMPORTE</th>
		<th>VP</th>
		<th>BANCO</th>
		<th>CUENTA CARGO</th>
		<th>NOMBRE CUENTA</th>
		<th>CUENTA DESTINO</th>
		<th>CVE. FIN.</th>
		<th>ID LOTE</th>
		</tr>
		<tr id="1" style="border: 1px solid;">
		<td>03/07/2013</td>
		<td>0426C13CE</td>
		<td>12797</td>
		<td>94</td>
		<td>H.CONGRESO DEL ESTADO</td>
		<td style="text-align: right;">1,039,014.52</td>
		<td>T</td>
		<td>BANCOMER</td>
		<td>[00450918199]</td>
		<td>00450918199 - SECRETARIA DE FINANZAS DEL GOBIERNO DEL ESTADO DE OAX</td>
		<td>[012610001922513887]</td>
		<td>AAAA0113</td>
		<td>T07001</td>
		</tr>
		<tr style="border:double">
		<td style="border: 0 none;"></td>
		<td style="border: 0 none;"></td>
		<td style="border: 0 none;"></td>
		<td style="border: 0 none;"></td>
		<td style="font-weight: bold;text-align: right;">TOTAL $</td>
		<td style="font-weight: bold;text-align: right;">1,039,014.52</td>
		</tr>
		</tbody>
		</table>
	</fieldset>
<?php	
}
?>
