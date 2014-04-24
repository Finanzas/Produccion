<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="imagenes/oaxaca_logo.png" rel="shortcut icon" />
    <title>Acceso Denegado</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- CSS Sistema-->
    <link href="css/jquery_tema/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">
    <link href="css/sistema.css" rel="stylesheet">

    <!--script src="js/jquery-1.10.2.js"></script-->
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>

    <script>
    $(document).ready(function(e){

      $("#mensaje").dialog({
        modal: true,width:400,
        open:  function( event, ui ) {
          var parametro = $(this).data('mensaje');
          $(this).html(parametro);
        },
        buttons:{
          Aceptar: function(){
            $( this ).dialog( "close" ); 
            //$('.pantalla_bloquea').hide();
          }
        }
      });
    });//--FIN JQUERy
    </script>

  </head>
  <body>
  <div id="mensaje" title="Acceso Denegado">
    <div>No Tiene Permiso para Accesar</div>
  </div>
  

  <div class="panel panel-danger">
    <div class="panel-heading">
      <h3 class="panel-title">Acceso Denegado <?php echo '['.getRealIP().']';?></h3>
    </div>
    <div class="panel-body"> Lo Sentimos Su Computadora No Tiene Acceso a al Pagina Solicitada Contacte al Administrador del Sistema para Otorgarle Permisos, Gracias
    </div>
  </div>
  </body>
</html>

<?php
function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
}