<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>VENTANA INICIO</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="FerBooK">
	<!-- Date: 2013-05-25 -->
	<link rel="shortcut icon" href="imagenes/oaxaca_logo.png" />
	<link href="css/estilo_pagina.css" rel="stylesheet" type="text/css" />
	<link href="css/menu.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<?php
	require "funciones/funciones.php";
	setEncabezadoIndex();
	?>
	
	<div id="zona_menu">
		<?php
		//Navegacion();
		?>
	</div>
	
	<div id="content" >
		
		<div class="login">
			<p style="text-align: center;"><strong>ACCESO DE USUARIO</strong></p>
			<form action="sql/revisaUsuario.php" method="post" accept-charset="utf-8">
				<table>
					<tr><td>Usuario</td><td><input type="text" id="usuario" name="usuario"></td></tr>
					<tr><td style="padding-top: 20px;">Contraseña</td>
						<td style="padding-top: 20px;"><input type="password" id="pass" name="pass"></td>
					</tr>
				</table>
				
				<table><tr><td style="width:80px"></td><td>
							<p><input type="submit" style="height: 40px;width: 160px;border-radius:10px" value="INICIAR SESION"></p>
						</td></tr>
				</table>
			</form>
		</div>
		
		
	</div>

</body>
</html>
