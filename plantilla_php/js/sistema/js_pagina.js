$(document).ready(function(e)
{
	//AYUDA
	$("#algo").live('click',function(){});
	$(".algo").live('keyup',function(event) {});
	//FIN AYUDA
	
	$("#boton").live('click',function()
	{
		$('#dialog').dialog({ 
	        show: "scale",hide: "scale",width:500,
	        draggable: true,
	        modal: true 
	    });
	});
	
	$("#boton_2").live('click',function()
	{
		
		peticionJSON();
	});
	
	$("#loader").live('click',function()
	{
		$('#contenido_centro').slideUp('slow');
		$('#bloquea').show();
		setTimeout(function()
		{
			$('#contenido_centro').slideDown('slow');
			$('#bloquea').hide();
		}, 1000*2.5);
	});
	
	
});//FIN $(document).ready

//FUNCIONES JAVASCRIPT

function algo(){} //FIN FUNCION ALGO -GUIA

function setMensaje()
{
	alert('Tambien Funciona');
}

function peticionNormal()
{
	var request = $.ajax({
	url: "../sql/prueba.php",
	type: "POST",
	data: {id : 'hola'},
	dataType: "html"
	});
	request.done(function(respuesta) {
	alert(respuesta.lote);
	});
	request.fail(function(jqXHR, textStatus) {
	alert( "Error: " + textStatus );
	});
}

function peticionJSON()
{
	var request = $.post("../sql/prueba_1.php",{ name: "John", time: "2pm" }, function(data) {
	},"json")
	.done(function(data)
	{ 
		$.each(data, function(key, val){console.log( "JSON Data: " + val.lote );});
	})
	.fail(function(jqXHR, textStatus) { alert( "Error: " + jqXHR +"-"+ textStatus ); });	
}

//FIN FUNCIONES JAVASCRIPT