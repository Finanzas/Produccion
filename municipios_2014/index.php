<?php
if(!isset($_GET['key']))require "funciones/compruebaAcceso.php";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="imagenes/oaxaca_logo.png" rel="shortcut icon" />
    <title>Municipios 2014</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

    <!-- CSS Sistema-->
    <link href="css/jquery_tema/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">
    <link href="css/sistema.css" rel="stylesheet">

    <!--script src="js/jquery-1.10.2.js"></script-->
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>

    <style type="text/css">
      .barra_lateral{
        color: #FFFFFF;font-size: 20px;
      }
      .row {
          padding-bottom: 3%;
      }
      .mensajes {
          font-size: 45px;
          text-align: center;
      }
      #tabla_mun_pag td{
        width: 100%;
      }
      #tabla_mun_pag td{
        border: 2.5px solid #fff;
      }
      .importe{
        text-align: right;;
      }

      .img_eliminar{
        border-radius: 10px;
        cursor: pointer;
        text-align: center;
        width: 100%;
      }
      .file_download{
        background: none repeat scroll 0 0 #DBA901;
        border: 1px solid #FFA500;
        border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        color: #FFFFFF;
        display: block;
        font-size: 18px;
        text-align: center;
        text-decoration: none;
        width: 100%;
      }

      #ventanaCuentasMunicipios label{
        width: 100px;
      }
      #link_cuentas_mun:hover{
        background: #DBA901;
        color: black;
      }
    </style>

    <script>
    $(document).ready(function(e){
      $('.genera_lay').attr('disabled','disabled');

      $("#mensaje").dialog({
        modal: true,autoOpen:false,width:400,
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

      $("#eliminaMun").dialog({
        modal: true,autoOpen:false,width:400,
        open:  function( event, ui ) {
          var ref = $(this).data('ref');
          var municipio = $('#tabla_nombre_id_'+ref).val();
          $(this).html('<p> ¿Esta Seguro de Eliminar '+municipio+' ?');
        },
        buttons:{
          Aceptar: function(){
            $( this ).dialog( "close" ); 
            var ref = $(this).data('ref');
            $('#fila_'+ref).remove();
            recalculaImporteTotal();            
          },
          Cancelar: function(){$( this ).dialog( "close" ); 
          },
        }
      });

      $("#descarga_ventana").dialog({
        modal: true,autoOpen:false,width:400,
        open:  function( event, ui ) {},
        buttons:{
          Aceptar: function(){
            $( this ).dialog( "close" ); 
          }
        }
      });

      $("#ventanaCuentasMunicipios").dialog({
        modal: true,autoOpen:false,width:600,
        open:  function( event, ui ) {
          var parametro = $(this).data('mensaje');
          $(this).html(parametro);
        },
        buttons:{
          Guardar: function(){

            $("#confirmaEdicion").data({'mensaje':'<p>¿Esta Seguro de Editar la Cuenta ?'}).dialog('open');
          },
          Cancelar: function(){
            $( this ).dialog( "close" ); 
            //$('.pantalla_bloquea').hide();
          }
        }
      });

      
      $("#confirmaEdicion").dialog({
        modal: true,autoOpen:false,width:600,
        open:  function( event, ui ) {
          var parametro = $(this).data('mensaje');
          $(this).html(parametro);
        },
        buttons:{
          Aceptar: function(){
            editarCuentas();
            
          },
          Cancelar: function(){
            $( this ).dialog( "close" ); 
            //$('.pantalla_bloquea').hide();
          }
        }
      });

      //VALIDACION DEL CONCEPTO
      $("#concepto").on('keyup',function(e){
        var concepto  = $(this).val();
        var tamanio   = concepto.length;
        if(tamanio>30)
        {
          $("#mensaje").data({'mensaje':imgError+'<p>Maximo 30 Caracteres'}).dialog('open');
          $(this).val(concepto.substring(0,30));
        }
      });

      $('#cuenta_bancaria').on('keypress', function(evt){
          var charCode = (evt.which) ? evt.which : event.keyCode
             if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

             return true;
      });

      $("#cuenta_bancaria").on('keyup',function(evt){
        var cuenta  = $(this).val();
        var tamanio   = cuenta.length;
        if(tamanio>18)
        {
          $("#mensaje").data({'mensaje':imgError+'<p>Solo 18 Digitos'}).dialog('open');
          $(this).val(cuenta.substring(0,18));
        }
      });

      //PROCESAR ARCHIVO
      $('#procesar').on('click',function(){
        var ext = $('#archivo').val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['csv']) == -1) {
            $("#mensaje").data({'mensaje':imgError+'<p>Solo se Procesan Archivos CSV'}).dialog('open');
        }
        else{
          //$("#mensaje").data({'mensaje':imgOK+'<p>Procesando CSV'}).dialog('open');

          var banco = $('#tipo_banco').val();
          var data  = new FormData();
          $.each($('#archivo')[0].files, function(i, file) {
              data.append('archivo-'+i, file);
          });

          //var parametros = '?tipo_layout='+opcion_procesa+'&modo='+modo+'&tipo_archivo='+tipo;
          var parametros = '?banco='+banco;
            
          $.ajax({
            url         : 'funciones/procesa_archivo.php'+parametros,
            type        : 'POST',
            async       : false,
            data        : data,
            dataType    : "json",
            cache       : false,
            contentType : false,
            processData : false,
          }).done(function(respuesta){
            console.log(respuesta);
            
            if(banco=='banorte')        archivoDescargaBanorte(respuesta);
            if(banco=='interacciones')  archivoDescargaInter(respuesta);
            if(banco=='banamex')        archivoDescargaBanamex(respuesta);
          })
          .fail(function(jqXHR, textStatus){ 
            alert( "Respuesta Inesperada:" + jqXHR +"-"+ textStatus ); 
          });
        }   
      });

      // BOTON BUSCAR
      $('#buscar').on('click',function(){
        $('#tabla_resultado').html('');
        $('#tabla_resultado').append('<tr> <th style="width: 50px;"> DEL </th> <th>CLC</th><th>ID MUN</th> <th style="width: 300px;">NOMBRE MUNICIPIO</th> <th>CONCEPTO LAYOUT</th> <th>REFERENCIA</th> <th>IMPORTE</th> </tr>'); 
        $('#importe_clc').html('$ 0.00'); 
        $('.genera_lay').attr('disabled','disabled');
        var numero      = $('#numero').val();
        var municipios  = $('#municipios_sel').val();
        var concepto    = $('#concepto').val();
        var programa    = $('#programa').val();
        var valida_mun  = true;

        if(municipios!='') valida_mun = validaMunicipios(municipios);

        if(numero=='' || isNaN(numero)){
          $("#mensaje").data({'mensaje':imgError+'<p>El Valor Numerico es Erroneo por algun motivo:<p>A)Campo Numero está Vacio.<p>B)Solo se Permite 1 Numero'}).dialog('open');

        }else{
          if(valida_mun==true){
            if(concepto!=''){
              $('#bloqueo').show();
              $('.genera_lay').attr('disabled','disabled');
              ajaxEnviado = $.ajax({
                url:        "sql/consulta.php",
                async:      false,
                data:       {'opcion': 'clc','numero': numero,'municipios':municipios,'concepto':concepto,'programa':programa},
                dataType:   'json',
                type:       'POST',
              }).done(function(respuesta){
                $('#bloqueo').hide();
                var total_registro = respuesta.total_registro;
                if(total_registro>0){
                  $('.genera_lay').removeAttr('disabled');
                  
                  $('#tabla_resultado').append(respuesta.html);
                  $('#importe_clc').html(respuesta.importe_total_clc);
                  if(respuesta.html==null) $("#mensaje").data({'mensaje':imgError+'<p>Sin Datos para Cargar ['+respuesta.html+']'}).dialog('open');

                }else {
                  $("#mensaje").data({'mensaje':imgError+'<p>'+respuesta.mensaje}).dialog('open');
                }
              })
              .fail(function(jqXHR, textStatus){
                $('#bloqueo').hide();
                $("#mensaje").data({'mensaje':imgError+'<p>Respuesta Inesperada'+'<p>'+jqXHR.responseText+'<p>'+textStatus}).dialog('open');
              });

            }else $("#mensaje").data({'mensaje':imgError+'<p>Captura un Concepto para Generar el Layotu'}).dialog('open');

          }else{
            $('.genera_lay').attr('disabled','disabled');
            $("#mensaje").data({'mensaje':imgError+'<p>Los Municipios Introducidos No son Validos'}).dialog('open');
          }

        }
      });
      
      //IMG ELIMINAR X
      $('#formulario_info').on('click','.img_eliminar',function(){
        var ref = $(this).attr('ref');
        $("#eliminaMun").data({'ref':ref}).dialog('open');
      });

      //GENERA LAYOUT INTER - BANORTE
      $('.genera_lay').on('click',function(){
        var programa    = $('#programa').val();
        var banco       = $(this).attr('banco');
        var informacion = [];
        var total_filas = 0;
        $.each($('.img_eliminar'),function(key,value){
          total_filas++;
          var ref = $(this).attr('ref');
          var detalle= {
            clc:        $('#tabla_clc_'+ref).val(),
            mun_id:     $('#tabla_mun_id_'+ref).val(),
            mun_nombre: $('#tabla_nombre_id_'+ref).val(),
            concepto:   $('#tabla_concepto_'+ref).val(),
            referencia: $('#tabla_ref_'+ref).val(),
            importe:    $('#tabla_importe_'+ref).val(),
          };
          informacion.push(detalle);
        });

        if(total_filas>0){
          $('#bloqueo').show();
          ajaxEnviado = $.ajax({
              url:        "funciones/generaLayout.php",
              async:      false,
              data:       {'informacion':informacion,'banco':banco,'programa':programa},
              dataType:   'json',
              type:       'POST',
            }).done(function(respuesta){
              $('#bloqueo').hide();

              if(banco=='banorte')        archivoDescargaBanorte(respuesta);
              if(banco=='interacciones')  archivoDescargaInter(respuesta);
              if(banco=='banamex')        archivoDescargaBanamex(respuesta);
              
            })
            .fail(function(jqXHR, textStatus){
              $('#bloqueo').hide();
              $("#mensaje").data({'mensaje':imgError+'<p>Respuesta Inesperada'+'<p>'+jqXHR.responseText+'<p>'+textStatus}).dialog('open');
            });
        }
        else $("#mensaje").data({'mensaje':imgError+'<p>No Hay Registros a Procesar'}).dialog('open');
      });

      $('#link_cuentas_mun').on('click',function(){
        $('#mun_id_busca').val('');
        $('#mun_nombre_busca').val('');
        $('#cuenta_bancaria').val('');
        $("#ventanaCuentasMunicipios").dialog('open');
        $('#cuenta_bancaria').removeAttr('style');
      });

      $('#mun_id_busca').on('keypress',function(){
        $('#mun_nombre_busca').val('');
        $('#cuenta_bancaria').val('');
        $('#cuenta_bancaria').removeAttr('style');
      });

      $('#mun_nombre_busca').on('keypress',function(){
        $('#mun_id_busca').val('');
        $('#cuenta_bancaria').val('');
        $('#cuenta_bancaria').removeAttr('style');
      });

      $('#mun_tipo').on('change',function(){
        $('#mun_id_busca').val('');
        $('#mun_nombre_busca').val('');
        $('#cuenta_bancaria').val('');
        $('#cuenta_bancaria').removeAttr('style');
      });
      
      $('#mun_id_busca,#mun_nombre_busca').keypress(function(e) {
          if(e.which == 13) {
            buscaInfoMunicipio();
            
          }
      });

      var cache = {},lastXhr;
  
      $( "#mun_nombre_busca" ).autocomplete({
        source: function( request, response )
        {
          var term = request.term;
          if ( term in cache ) {response( cache[ term ] );return;}
          lastXhr = $.getJSON( "sql/buscaInfoMunicipio.php?opcion=autocomplete", request,function( data, status, xhr )
          {
            cache[ term ] = data;
            if ( xhr === lastXhr ) {response( data );}
          });
        },select: function( event, ui ){
          $('#mun_id_busca').val('');
          $('#mun_nombre_busca').val(ui.item.value);
          buscaInfoMunicipio();

        }
      });

      //CANCELAR
      $('#cancelar').on('click',function(){
        $('#bloqueo').hide();
        ajaxEnviado.abort();
        
      });

    });//--FIN JQUERY


    var ajaxEnviado   = null;

    var imgOK    = '<div class="mensajes">Correcto<img  src="imagenes/ok.png" width="45" height="auto" ></div>';
    var imgError = '<div class="mensajes">Atención<img  src="imagenes/error.png" width="50" height="auto" ></div>';


    function alpha(e) {
        var k;
        document.all ? k = e.keyCode : k = e.which;
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
    }

    function validaMunicipios(texto){
      var validacion = true;
      var arreglo = texto.split(',');

      $.each(arreglo, function(key, val){
        if(isNaN(val) || val == ''){
          validacion = false;
          return false;
        }

      });

      return validacion;
    }

    function recalculaImporteTotal(){
      var total = 0;
      var importe = 0;
      $.each($('.img_eliminar'),function(key,value){
        importe += parseFloat($(this).attr('importe'));
        total++;
      });
      $('#importe_clc').html('$ '+formatoMoneda(importe,2));
      if(total==0) $('.genera_lay').attr('disabled','disabled');
      console.log(formatoMoneda(importe,2));
    }

    function archivoDescargaBanorte(respuesta){
      var archivo_externo = '';
      var archivo_interno = '';
      var fecha_ruta    = '';
      var total_generados = 0;

      $.each(respuesta, function(key, val){
        total_generados  = val.archivos_generados;
        archivo_externo  = val.archivo_externo;
        archivo_interno  = val.archivo_interno;
        fecha_ruta     = val.fecha_ruta+'/';
      });

      if(total_generados>0){
        var carpeta = "../layouts/"+fecha_ruta+'/';
        $('#mensaje_descarga').html('Archivos Generados: ['+total_generados+']');

        var link_1 = '';
        var link_2 = '';

        if(archivo_interno!='N/A') link_1 = '<p><a class="file_download" href="funciones/descarga_archivo.php?carpeta='+carpeta+'&file='+archivo_interno+'">INTERNA</a>';
        if(archivo_externo!='N/A') link_2 = '<p><a class="file_download" href="funciones/descarga_archivo.php?carpeta='+carpeta+'&file='+archivo_externo+'">INTERBANCARIA</a>';
        
        $('#link_descarga_archivo').html(''+link_1+link_2+'');
        $("#descarga_ventana").dialog('open');
      }
      else{
        $("#mensaje").data({'mensaje':imgError+'<p>Descarga No Disponible'}).dialog('open');
      }
    }

    function archivoDescargaInter(respuesta){
      var archivo = '';
      
      var fecha_ruta    = '';
      var total_generados = 0;

      $.each(respuesta, function(key, val){
        total_generados  = val.archivos_generados;
        archivo  = val.archivo;
        
        fecha_ruta     = val.fecha_ruta+'/';
      });

      if(total_generados>0){
        var carpeta = "../layouts/"+fecha_ruta+'/';
        $('#mensaje_descarga').html('Archivos Generados: ['+total_generados+']');

        var link_1 = '';
        

        if(archivo!='N/A') link_1 = '<p><a class="file_download" href="funciones/descarga_archivo.php?carpeta='+carpeta+'&file='+archivo+'">INTERACCIONES</a>';
        
        
        $('#link_descarga_archivo').html(''+link_1+'');
        $("#descarga_ventana").dialog('open');
      }
      else{
        $("#mensaje").data({'mensaje':imgError+'<p>Descarga No Disponible'}).dialog('open');
      }
    }

    function archivoDescargaBanamex(respuesta){
      var archivo = '';
      
      var fecha_ruta    = '';
      var total_generados = 0;

      $.each(respuesta, function(key, val){
        total_generados  = val.archivos_generados;
        archivo  = val.archivo;
        
        fecha_ruta     = val.fecha_ruta+'/';
      });

      if(total_generados>0){
        var carpeta = "../layouts/"+fecha_ruta+'/';
        $('#mensaje_descarga').html('Archivos Generados: ['+total_generados+']');

        var link_1 = '';
        

        if(archivo!='N/A') link_1 = '<p><a class="file_download" href="funciones/descarga_archivo.php?carpeta='+carpeta+'&file='+archivo+'">BANAMEX</a>';
        
        
        $('#link_descarga_archivo').html(''+link_1+'');
        $("#descarga_ventana").dialog('open');
      }
      else{
        $("#mensaje").data({'mensaje':imgError+'<p>Descarga No Disponible'}).dialog('open');
      }
    }

    function buscaInfoMunicipio(){
      $('#img_busca').show();
      $('#cuenta_bancaria').removeAttr('style');
      var mun_tipo    = $('#mun_tipo').val();
      var mun_id      = $('#mun_id_busca').val();
      var mun_nombre  = $('#mun_nombre_busca').val();

      ajaxEnviado = $.ajax({
        url:        "sql/buscaInfoMunicipio.php?opcion=normal",
        async:      false,
        data:       {'mun_tipo':mun_tipo,'mun_id':mun_id,'mun_nombre':mun_nombre},
        dataType:   "json",
        type:       'POST',
      }).done(function(respuesta){
        $('#img_busca').hide();
        if(respuesta!=''){
          $('#mun_id_busca').val(respuesta.id);
          $('#mun_nombre_busca').val(respuesta.nombre);
          $('#cuenta_bancaria').val(respuesta.cuenta);
          if(respuesta.cuenta=='') $("#mensaje").data({'mensaje':imgError+'<p>El Municipio de '+respuesta.nombre+' No Tiene Cuenta Bancaria en este Programa'}).dialog('open');
          if(respuesta.cuenta.length==18) $('#cuenta_bancaria').attr('style','border: 2.5px solid #008000;');
          else $('#cuenta_bancaria').attr('style','border: 2.5px solid #FF0000;');
          

        }
        else {
          $('#img_busca').hide();
          $("#mensaje").data({'mensaje':imgError+'<p>Municipio No Encontrado'}).dialog('open');
        }
      })
      .fail(function(jqXHR, textStatus){
        $('#img_busca').hide();
        $("#mensaje").data({'mensaje':imgError+'<p>Ocurrio Algo Inesperado:<p>'+jqXHR.responseText}).dialog('open');
        
      });
    }

    function editarCuentas(){
      var mun_tipo    = $('#mun_tipo').val();
      var mun_id      = $('#mun_id_busca').val();
      var mun_nombre  = $('#mun_nombre_busca').val();
      var cuenta      = $('#cuenta_bancaria').val();
      if((cuenta.length==18 || cuenta.length==0)&&mun_id!=''&&mun_nombre!='') {
        $('#cuenta_bancaria').attr('style','border: 2.5px solid #008000;');
        ajaxEnviado = $.ajax({
          url:        "sql/guardaCuentaMun.php",
          async:      false,
          data:       {'mun_tipo':mun_tipo,'mun_id':mun_id,'mun_nombre':mun_nombre,'mun_cuenta':cuenta},
          dataType:   "json",
          type:       'POST',
        }).done(function(respuesta){
          
          if(respuesta!=''){
            var html = '';
            if(respuesta.edicion=='ok'){
              html =  '<p><label>Cuenta Editada Correctamente con la Siguiente Informacion'+
                      '<p><label style="width:100px">Programa:</label><label>'+respuesta.programa+
                      '<p><label style="width:100px">Municipio:</label><label>'+respuesta.nombre+
                      '<p><label style="width:100px">Cuenta:</label><label>'+respuesta.cuenta;
            }
            else{
              html =  '<p><label>La Informacion del Municipio '+mun_nombre+ ' Se Ha Guardado Sin Cambios';

            }
            
            $("#ventanaCuentasMunicipios").dialog('close');
            $("#confirmaEdicion").dialog('close');

            $("#mensaje").data({'mensaje':imgOK+html}).dialog('open');

            
          }
          else $("#mensaje").data({'mensaje':imgError+'<p>Edicion de Cuenta Incompleta'}).dialog('open');
        })
        .fail(function(jqXHR, textStatus){
          $('#img_busca').hide();
          $("#mensaje").data({'mensaje':imgError+'<p>Ocurrio Algo Inesperado:<p>'+jqXHR.responseText}).dialog('open');
          
        });
      }
      else {
        $("#mensaje").data({'mensaje':imgError+'<p>Los Campos Numero y Nombre de Municipio Deben Tener un Valor y la Cuenta Bancaria Debe Tener 18 o 0 Digitos<p>'}).dialog('open');
      }
    }

    function formatoMoneda(cnt, cents) {
      cnt = cnt.toString().replace(/\$|\u20AC|\,/g,'');
      if (isNaN(cnt))
        return 0; 
      var sgn = (cnt == (cnt = Math.abs(cnt)));
      cnt = Math.floor(cnt * 100 + 0.5);
      cvs = cnt % 100;
      cnt = Math.floor(cnt / 100).toString();
      if (cvs < 10)
      cvs = '0' + cvs;
      for (var i = 0; i < Math.floor((cnt.length - (1 + i)) / 3); i++)
        cnt = cnt.substring(0, cnt.length - (4 * i + 3)) + ',' 
                            + cnt.substring(cnt.length - (4 * i + 3));

      return (((sgn) ? '' : '-') + cnt) + ( cents ?  '.' + cvs : '');
    }

    </script>

  </head>

  <body>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><img src="imagenes/logo_izquierda.png" class="img-thumbnail" width="150" height="auto">
            <label style="padding-left: 60px;font-size:28px">SECRETARIA DE FINANZAS</label></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li class="active"><div id="link_cuentas_mun" class="barra_lateral" style="cursor:pointer">CUENTAS MUNICIPIOS</div>

            <li class="active">
              <div class="barra_lateral">IMPORTE TOTAL</div>
              <div class="barra_lateral" style="text-align: right;" id="importe_clc">0.00</div>
            </li>
            <li class="active">
              <div class="barra_lateral">MUNICIPIOS PAGADOS</div>
              <div class="barra_lateral" id="zona_pagados" style="height:250px;font-size:14px;overflow:scroll;">
                <table id="tabla_mun_pag" style="background-color: #808080;">
                  <!--tr><td>ABEJONES ABEJONES ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr>
                  <tr><td>ABEJONES</td><td>150,000,00</td></tr-->
                </table>
              </div>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            
            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo "[".getRealIPLocal()."]";?> </a>
              
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">
        <!-- EN BLANCO -->
        <div class="row">
          <fieldset style="margin-top: 30px;">
            <legend align="center"><strong></strong></legend>
          </fieldset>
        </div><!-- /.row -->

        <!-- GENERACION POR BUSQUEDA -->
        <div class="row alert-success">
          <fieldset style="">
            <legend align="center"><strong>GENERACION POR BUSQUEDA</strong></legend>
              <form id="form_busqueda">
                <table border="0" cellspacing="5" cellpadding="5">
                <tr>
                  <td style="width: 150px;">OPCION BUSQUEDA</td>
                  <td style="width:200px">
                    <input type="radio" id="radio_clc" name="opcion" value="clc" checked> CLC<br>
                  </td>
                  <td>NUMERO</td>
                  <td>
                    <input type="text" id="numero" name="numero" value="" class="form-control">
                  </td>
                  
                  <td style="text-align: right;">
                    <input type="button" id="buscar" class="btn btn-warning" name="buscar" value="BUSCAR" >
                  </td>
                </tr>
              </table>
              <table>
                <tr>
                  <td style="width: 150px;">PROGRAMA</td>
                  <td>
                    <select name="programa" id="programa" class="form-control">
                      <option value="RAMO">RAMO 28</option>
                      <option value="INFRA">INFRAESTRUCTURA</option>
                      <option value="FORTA">FORTALECIMIENTO</option>
                    </select>
                  </td>
                </tr>
              </table>
              <table>
                <tr>
                  <td style="width: 150px;">CONCEPTO LAYOUT</td>
                  <td>
                    <input type="text" id="concepto" name="concepto" style="text-transform: uppercase;width:445px" class="form-control" placeholder="Hasta 30 Caracteres" onkeypress="return alpha(event)">
                  </td>
                </tr>
              </table>
              <table>
                <tr>
                  <td style="width: 150px;">FILTRO MUNICIPIOS</td>
                  <td>
                    <textarea class="form-control" rows="3" id="municipios_sel" style="text-transform: uppercase;width:445px"></textarea>
                  </td>
                </tr>
              </table>
            </form>
          </fieldset>
        </div><!-- /.row -->

        <!-- GENERACION POR ARCHIVO -->
        <div class="row alert-info">
          <fieldset style="">
            <legend align="center"><strong>GENERACION POR ARCHIVO</strong></legend>
              <table style="padding-top: 25px;" align="center">
              <tr>
                <td>
                  <select name="tipo_banco" id="tipo_banco" class="form-control">
                    <option value="banorte">BANORTE</option>
                    <option value="interacciones">INTERACCIONES</option>
                    <option value="banamex">BANAMEX</option>
                  </select>
                </td>
                <td>
                  <input id="archivo" name="archivo[]" type="file" >
                </td>
                <td>
                  <input id="procesar" name="procesar" type="button" class="btn btn-warning" value="PROCESAR Y GENERAR">
                </td>
              </tr>
            </table>
          </fieldset>
        </div><!-- /.row -->

        <!-- INFORMACION DE BUSQUEDA -->
        <div class="row">
          <fieldset style="margin-top: 25px;">
            <legend align="center"><strong>INFORMACION DE BUSQUEDA</strong></legend>
            <div style="padding-bottom: 3%;">
              <table style="padding-top: 25px;" align="center">
                <tr>
                  <td>
                    <input id="lay_inter" name="lay_inter" type="button" class="btn btn-warning genera_lay" banco="interacciones" disabled="disabled" value="LAYOUT INTERACCIONES">
                  </td>
                  <td>
                    <input id="lay_banorte" name="lay_banorte" type="button" class="btn btn-warning genera_lay"  banco="banorte" disabled="disabled" value="LAYOUT BANORTE">
                  </td>
                  <td>
                    <input id="lay_banamex" name="lay_banamex" type="button" class="btn btn-warning genera_lay"  banco="banamex" disabled="disabled" value="LAYOUT BANAMEX">
                  </td>
                </tr>
              </table>
            </div>
            <div id="informacion">
            <form id="formulario_info">
              <table id="tabla_resultado" class="table table-hover table-bordered">
                <tr>
                  <th>ACCION</th>
                  <th>CLC</th>
                  <th>ID MUN</th>
                  <th>NOMBRE MUNICIPIO</th>
                  <th>CONCEPTO LAYOUT</th>
                  <th>REFERENCIA</th>
                  <th>IMPORTE</th>
                </tr>
              </table>
            </form>
            </div>
          </fieldset>
        </div><!-- /.row -->

        <!-- EN BLANCO -->
        <div class="row">
          <fieldset style="margin-top: 30px;">
            <legend align="center"><strong></strong></legend>
          </fieldset>
        </div><!-- /.row -->

        
      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <!-- VENTANAS DIALOG -->
    <div id="mensaje" title="Mensaje">
    </div>

    <div id="eliminaMun" title="Eliminar Municipio">
    </div>

    <div id="descarga_ventana" title="Listado de Archivos">
      <div id="mensaje_descarga" style="text-align: center;font-size:24px"></div>
      <div id="link_descarga_archivo"></div>
    </div>

    <div id="ventanaCuentasMunicipios" title="Edicion de Cuentas Bancarias">
      <div>
        <div><label>Municipio</label></div>
        <div>
          <table>
            <tr>
              <td style="width: 20%">
                <select id="mun_tipo" class="form-control">
                  <option value="RAMO">RAMO 28</option>
                  <option value="INFRA">INFRA</option>
                  <option value="FORTA">FORTA</option>
                </select>
              </td>
              <td style="width: 15%"><input type="text" class="form-control" id="mun_id_busca" placeholder="Numero"></td>
              <td style="width: 60%"><input type="text" class="form-control" id="mun_nombre_busca" placeholder="Nombre del Municipio"></td>
              <td style="width: 10%"><img id="img_busca" style="display:none;width: 40px;" src="imagenes/loader_flecha.gif"></td>
            </tr>
          </table>
        </div>
      </div>
      <div>
        <label>Cuenta</label>
        <input type="text" class="form-control" id="cuenta_bancaria" placeholder="Cuenta Bancaria del Municipio">
      </div>
    </div>

    <div id="confirmaEdicion" title="Confirmacion">
    </div>

    <!-- BLOQUEO -->
    <div id="bloqueo" class="progress progress-striped active" style="height: 100%;position: fixed;top: 15%;width: 100%;display:none">
      <div class="progress-bar progress-bar-warning"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
        <div style="font-size: 48px;padding-top: 15%;padding-bottom: 1%;">Cargando..</div>
        <div style="padding-top: 5%;"><input id="cancelar"  type="button" class="btn btn-danger" value="CANCELAR"></div>
      </div>
    </div>


  </body>
</html>

<?php
function getRealIPLocal() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
}
?>

