var positions = new Array();
var cant = 0;
var actual = 0;
var anterior = 0;

$(document).ready(function(){
    var totWidth=0;
    
    $('#slider .slide').each(function(i){
        positions[i]= totWidth;
        totWidth += $(this).width();
    });
    $('#slider').width(totWidth);	

    //Numero de slides
    cant = positions.length;
    
    //Event retroceder slide
    $('#ant').click(function(e){
    	if(actual>0) {
    		actual--;
    	} else {
    		actual=cant-1;
    	}
		dibujarEstacion();
        e.preventDefault();
    });

    //Event avanzar slide
    $('#sig').click(function(e){
    	if(actual<(cant-1)) {
    		actual++;
    	} else {
    		actual=0;
    	}
		dibujarEstacion();
        e.preventDefault();
    });
    
    $('#personaje')
    	.on('mouseenter',function(){ 
    		$('#globa_personaje').show(); 
    	})
    	.on('mouseleave',function(){ 
    		$('#globa_personaje').hide(); 
    	});
                
    dibujarEstacion();
    
    //Cargar los otros fondos (de a poco)
    if( $('#slider img').length>1 ) {
	    for(var j=2;j<10;j++) {
		    var img = $('<img id="f'+j+'" src="images/fondos/fondo'+j+'.png"/>');
		    img.promise().done(function( arg1 ) {
		    	 //console.log( "Listo" + j );
		    	 $('#f'+j).parent().html(arg1);
		    });
	    }
    }
    
    //Menu de la app
    var menu_state=0;
    var menu_busy=false;
    $('#m_menu').click(function(){
    	if(menu_busy)
    		return;
    	menu_busy=true;
    	if(menu_state==0) {
    		$('#menu').animate({height:'358px'},500,function(){menu_busy=false;});
    		menu_state=1;
    	} else {
    		$('#menu').animate({height:'157px'},500,function(){menu_busy=false;});
    		menu_state=0;
    	}
    });

    
    var menu_timer = null;
    $('#menu')
	    .bind('mouseout',function(){
	    	if(menu_state==1)
	    		menu_timer = setTimeout(function(){
	    			if(menu_state==1 && menu_busy==false)
	    				$('#m_menu').click();	
	    		},1000);
	    })
	    .bind('mouseover',function(){
	    	if(menu_state==1 && menu_timer!=null)
	    		clearTimeout(menu_timer);
	    });
    
    $('#pajarito').hover(animarPajarito);
    setInterval(animarPajarito,2000);
    
    $('#ba').click(function(){
    	document.location = 'controller.php';
    });
});

//Facebook (requiere que este declarada la URL corta)
function fb_click() {
//	var u=g_url_corta;
//	var t="Paseo de la historieta";
//	window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t), "sharer","toolbar=0,status=0,width=660,height=300");
	return false;
}

//Twitter
function twt_click() {
//	var u="Descubrí todos tus personajes favoritos en el #PaseoHistorietaBA. Ingresá en " + g_url_corta; 
//	window.open('http://twitter.com/home?status='+encodeURIComponent(u), "sharer","toolbar=0,status=0,width=626,height=236");
	return false;
}    

//Animacion de la globa del pajarito
var pajarito_state=0;
function animarPajarito() {
	var p = $('#globa_pajarito');
	pajarito_state++;
	
	switch(pajarito_state) {
	case 1:
		p.attr('src','images/globa_trivia.png').show();
		break;
	case 2:
		p.attr('src','images/hace_click.png').show();
		break;
	case 3:
		p.hide();
		pajarito_state = 0;
		break;
	}	
}

//
function dibujarEstacion(){
	//Antes del slide
	$('#globa_personaje').hide();
	$('#personaje').hide();
		
	//Slide
	$('#slider').stop().animate({marginLeft:-positions[actual]+'px'},450, function(){

		//Post slide
		if(actual==0) { //mafalda
			$('#globa_personaje').css({top:'169px',left:'118px'}).html('<img src="images/globa_mafalda.png" width="350"/>');
			$('#personaje').css({top:'340px',left:'212px'}).html('<img src="images/mafalda.png"/>').show();
			$('#texto_home').show();
		}
		if(actual==1) { //isidoro
			$('#globa_personaje').css({top:'146px',left:'169px'}).html('<img src="images/globa_isidoro.png" width="350"/>');
			$('#personaje').css({top:'248px',left:'200px'}).html('<img src="images/isidoro.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==2) { //larguirucho
			$('#globa_personaje').css({top:'170px',left:'118px'}).html('<img src="images/globa_larguirucho.png" width="350"/>');
			$('#personaje').css({top:'269px',left:'203px'}).html('<img src="images/larguirucho.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==3) { //matias
			$('#globa_personaje').css({top:'188px',left:'118px'}).html('<img src="images/globa_matias.png" width="350"/>');
			$('#personaje').css({top:'355px',left:'246px'}).html('<img src="images/matias.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==4) { //loco chavez
			$('#globa_personaje').css({top:'61px',left:'151px'}).html('<img src="images/globa_loco_chavez.png" width="350"/>');
			$('#personaje').css({top:'232px',left:'272px'}).html('<img src="images/loco_chavez.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==5) { //divito
			$('#globa_personaje').css({top:'83px',left:'167px'}).html('<img src="images/globa_divito.png" width="350"/>');
			$('#personaje').css({top:'240px',left:'230px'}).html('<img src="images/divito.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==6) { //don fulgencio
			$('#globa_personaje').css({top:'178px',left:'193px'}).html('<img src="images/globa_don_fulgencio.png" width="350"/>');
			$('#personaje').css({top:'270px',left:'190px'}).html('<img src="images/don_fulgencio.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==7) { //clemente
			$('#globa_personaje').css({top:'170px',left:'227px'}).html('<img src="images/globa_clemente.png" width="350"/>');
			$('#personaje').css({top:'358px',left:'196px'}).html('<img src="images/clemente.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==8) { //patoruzu
			$('#globa_personaje').css({top:'113px',left:'342px'}).html('<img src="images/globa_patoruzu.png" width="350"/>');
			$('#personaje').css({top:'233px',left:'246px'}).html('<img src="images/patoruzu.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==9) { //gaturro
			$('#globa_personaje').css({top:'170px',left:'118px'}).html('<img src="images/globa_gaturro.png" width="350"/>');
			$('#personaje').css({top:'320px',left:'188px'}).html('<img src="images/gaturro.png"/>').show();
			$('#texto_home').hide();
		}
        if(actual==10) { //don nicola
			$('#globa_personaje').css({top:'80px',left:'218px'}).html('<img src="images/Globa-Don-Nicola.png" width="350"/>');
			$('#personaje').css({top:'212px',left:'145px'}).html('<img src="images/Don-Nicola.png" width="400"/>').show();
			$('#texto_home').hide();
		}
		if(actual==11) { //diogenes
			$('#globa_personaje').css({top:'170px',left:'235px'}).html('<img src="images/Globa-Diogenes-y-el-Linyera.png" width="350"/>');
			$('#personaje').css({top:'225px',left:'170px'}).html('<img src="images/Diogenes-y-el-Linyera.png" width="400"/>').show();
			$('#texto_home').hide();
		}
		if(actual==12) { //jirafa
			$('#globa_personaje').css({top:'0px',left:'275px'}).html('<img src="images/Globa-Jirafa-Mordillo.png" width="350"/>');
			$('#personaje').css({top:'125px',left:'157px'}).html('<img src="images/Jirafa-Mordillo.png" width="400"/>').show();
			$('#texto_home').hide();
		}		
		if(actual==13) { //negrazon
			$('#globa_personaje').css({top:'127px',left:'237px'}).html('<img src="images/Globa-Negrazon-y-Chaveta.png" width="350"/>');
			$('#personaje').css({top:'220px',left:'140px'}).html('<img src="images/Negrazon-y-Chaveta.png" width="400"/>').show();
			$('#texto_home').hide();
		}
		if(actual==14) { //Inodoro Pereyra
			$('#globa_personaje').css({top:'118px',left:'209px'}).html('<img src="images/Globa-Inodoro-Pereyra.png" width="350"/>');
			$('#personaje').css({top:'235px',left:'150px'}).html('<img src="images/Inodoro-Pereyra.png" width="400"/>').show();
			$('#texto_home').hide();
		}
		if(actual==15) { //Langostino-y-Corina
			$('#globa_personaje').css({top:'57px',left:'230px'}).html('<img src="images/Globa-Langostino-y-Corina.png" width="350"/>');
			$('#personaje').css({top:'160px',left:'145px'}).html('<img src="images/Langostino-y-Corina.png" width="400"/>').show();
			$('#texto_home').hide();
		}
		animarPajarito();
	});	 
	
	anterior = actual;
}

function msg(titulo,contenido,button,onOk) {
	$('#popup').remove();
	$('#curtain').remove();
	var b = '<div id="curtain"></div>';
	$('#gallery').append(b);
	$('#curtain').css('opacity',0.6);
	b = '<div id="popup"><div><h2>'+titulo+'</h2><p>'+contenido+'</p>';
	if(typeof button=="undefined" || (typeof button=="boolean" && button==true) )
		b+='<p class="acciones"><a href="javascript:void(0)" onclick="msg_ok('+onOk+')"><img src="images/botonsiguiente.png"/></a></p>';
	b+='</div></div>';
	$('#gallery').append(b);
	return false;
}

function msg_ok( onOk ) {
	$('#popup').remove();
	$('#curtain').remove();
	if(typeof onOk=="function")
		onOk();
	return false;
};