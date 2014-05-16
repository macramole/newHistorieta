
$(document).ready(function(){
	$('#menu').click(function(){
		document.location = "index.php";
	});

	$('#ba').click(function(){
		document.location = "index.php";
	});

    $('#mapa').click(function(){ 
    	document.location = "controller.php?op=goMapa";
    });
    
    $('#continuar').click(function(){ 
    	document.location = "controller.php?op=goFacebook4";
    });
    
    $('#cancelar').click(function(){ 
    	document.location = "index.php";
    });    
    
});
