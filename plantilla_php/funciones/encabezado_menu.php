<?php
function setEncabezadoIndex()
{
?>
<div style="height:25px;background:#7F581D;border-radius: 10px;"></div>
<div id="header">
	<div id="logo_izq" style="float: left;">
		<img  src="imagenes/logo_izquierda.png" width="auto" height="auto" >
	</div>
	
	<div id="centro" style="float: left;width: 59.5%;;">
		<div align="center"><strong>Secretaria de Finanzas de Oaxaca</strong></div>
		<div align="center"><strong>Direcci&oacute;n de Ingresos</strong></div>
		<div align="center"><strong>Unidad de Planeaci&oacute;n Financiera</strong></div>
	</div>
	
	<div id="logo_der" style="float: left;">
		<img  src="imagenes/logo_derecha.png" width="auto" height="auto" >
	</div>
</div>

<?php	
}
?>

<?php
function setEncabezado()
{
?>
<div style="height:25px;background:#7F581D;border-radius: 10px;"></div>
<div id="header">
	<div id="logo_izq" style="float: left;">
		<img  src="../imagenes/logo_izquierda.png" width="auto" height="auto" >
	</div>
	
	<div id="centro" style="float: left;width: 59.5%;;">
		<div align="center"><strong>Secretaria de Finanzas de Oaxaca</strong></div>
		<div align="center"><strong>Direcci&oacute;n de Ingresos</strong></div>
		<div align="center"><strong>Unidad de Planeaci&oacute;n Financiera</strong></div>
	</div>
	
	<div id="logo_der" style="float: left;">
		<img  src="../imagenes/logo_derecha.png" width="auto" height="auto" >
	</div>
</div>

<?php	
}
?>

<?php
function Navegacion()
{
?>
<div id="menu" style="float: left; width: 75%; height: 50px;">
	<ul>
		<li class="nivel1"><a href="#" class="nivel1">OPCIONES</a>
			<ul class="nivel2">
				<li><a href="../vistas/generacion.php">Generacion</a></li>
				<li><a href="http://localhost/secretario/principal_propuestas.php">Generacion Seleccionable</a></li>
				<li><a>Busquedas</a>
					<ul class="nivel3">
						<li><a href="../vistas/regularizar.php">Manuales</a></li>
						<li><a href="../vistas/busquedas_generadas.php">Generadas</a></li>
						<li><a href="../vistas/validadas.php">Validadas</a></li>
					</ul>
					</li>
				<li><a href="../vistas/captura.php">Captura Manual</a></li>
				<li><a href="../vistas/regularizar.php">Regularizacion</a></li>
				<li><a href="../vistas/busquedas_generadas.php">Registrar Pago</a></li>
			</ul>
  		</li>
		<!-- MENU A LA DERECHA-->
		<!--li class="nivel1"><a href="#" class="nivel1">Opcion Vertical</a>
			<ul class="nivel2">
				<li><a href="../vistas/generacion.php">Vertical</a></li>
			</ul>
		</li-->
		<!-- MENU A LA DERECHA-->
		
	</ul>
</div>

<div id="info_usuario" style="height: 50px; float: left; width: 19%;color:#fff">
	<?php
	$nombre_usuario = "Jose Alberto Martinez Gomez";
	echo '<p style="height: 5px;"><strong>BIENVENIDO: <p style="height: 5px;">'.$nombre_usuario.'</strong></p>';
	?>
	
</div>

<div id="salida" style="height: 50px; float: right; background: none repeat scroll 0% 0% red; border-radius: 10px 10px 10px 10px;">
	<a href="../index.php">
		<img src="../imagenes/salir.png" width="65px" height="auto" title="Salir del Sistema" style=""></a>
</div>

<?php	
}
?>

