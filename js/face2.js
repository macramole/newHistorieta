
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
    	if(amigo==null) {
    		alert("primero debes elegir a un amigo,  pasando el mouse sobre su avatar.");
    		return;
    	}
    	var c = $('#caract').val();
    	document.location = "controller.php?op=goFacebook3&amigo="+ amigo.id + "&caract=" + c;
    });
    
    $('#cancelar').click(function(){ 
    	document.location = "index.php";
    });    
    
    //Slider de facebook
    $('#ant').click(function(e){
    	if(actual>5) {
    		actual-=5;
    	} else {
    		actual=cant-1;
    	}
    	$('#facelist').stop().animate({marginLeft:-(actual*60)+'px'},450);
        e.preventDefault();
    });
    $('#sig').click(function(e){
    	if(actual<(cant-6)) {
    		actual+=5;
    	} else {
    		actual=0;
    	}
    	$('#facelist').stop().animate({marginLeft:-(actual*60)+'px'},450);
        e.preventDefault();
    });
    
    $('#facelist div').live('hover',function(e){
    	var x = $(this).attr('x');
    	$('#nombre_amigo').html(amigos[x].name);
    	amigo = amigos[x];
    });
    
    getFacebookFriends();
});

var amigos = [];
var actual = 0;
var cant = 0;
var amigo = null;

function getFacebookFriends() {
	//Trato de obtener los datos de FB
    var r = $.ajax({
    	url:'controller.php',
    	dataType:'json',
    	data: {'op':'amigosFB'}
    });
    
    r.done( function(json) { 
    	//console.log("Amigos recuperados -> " + json.data.length ); 
    	//console.log("Amigos recuperados Paging -> " + json.paging.next ); 
    	var b = "";
    	amigos = json.data;
    	for(var j=0;j<amigos.length;j++) {
    		b = b + '<div x="' + j + '"><img src="https://graph.facebook.com/' + amigos[j].id + '/picture"></div>';
    	}
    	$('#facelist').html(b);	
    	cant = amigos.length;
    });
    r.fail( function(jqXHR, textStatus) { 
    	//console.error("Ingreso a FB: " + textStatus); 
    });
}