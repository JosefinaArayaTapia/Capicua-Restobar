
$(document).ready(function() 
{
// comentario
$("#update_button").click(function() 
{
	var updateval = $("#inputField").val();
	var dataString = 'update='+ updateval;
		
		$.ajax({
			type: "POST",
			url: "comentario_insertar.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
				$("#comentarios").prepend($(html).fadeIn('slow'));
				$("#inputField").val('');	
				$("#inputField").focus();
				$("#stexpand").oembed(updateval);
  			}
 		});
	return false;
});

//Eliminar
$('.stdelete').live("click",function() 
{
var ID = $(this).attr("id");
var dataString = 'id_comentario='+ ID;

if(confirm("¿Estás seguro que deseas  borrar el comentario?"))
{
	$.ajax({
		type: "POST",
		url: "comentario_eliminar.php",
		data: dataString,
		cache: false,
		success: function(html){
		$("#stbody"+ID).slideUp();
		}
 	});
}
return false;
});

});