
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

    
    $('#facebook').click(function(){ 
    	document.location = "controller.php?op=goFacebook";
    });
    
    $('#twitter').click(function(){ 
    	document.location = "controller.php?op=goTwitter";
    });

    $('#mail').click(function(){ 
    	document.location = "controller.php?op=goMail";
    });


});

