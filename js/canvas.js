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
            $('#menuAutores').hide();
    		$('#menu').animate({height:'380px'},500,function(){menu_busy=false;});
    		menu_state=1;
    	} else {
    		$('#menuAutores').show();
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
    
    $('#menu a').click( function(e) {
       if ( $(this).attr('href') == document.location.href ) {
           e.preventDefault();
            $('#m_menu').click();
       }
    });
    
    $('#pajarito').hover(animarPajarito);
    setInterval(animarPajarito,2000);
    
    $('#ba').click(function(){
    	document.location = 'controller.php';
    });
});

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
			$('#globa_personaje').css({top:'169px',left:'225px'}).html('<img src="images/globa_mafalda.png" width="350"/>');
			$('#personaje').css({top:'340px',left:'330px'}).html('<img src="images/mafalda.png"/>').show();
			$('#texto_home').show();
		}
		if(actual==1) { //isidoro
			$('#globa_personaje').css({top:'95px',left:'300px'}).html('<img src="images/globa_isidoro.png" width="350"/>');
			$('#personaje').css({top:'248px',left:'330px'}).html('<img src="images/isidoro.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==2) { //larguirucho
			$('#globa_personaje').css({top:'85px',left:'400px'}).html('<img src="images/globa_larguirucho.png" width="350"/>');
			$('#personaje').css({top:'269px',left:'323px'}).html('<img src="images/larguirucho.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==3) { //matias
			$('#globa_personaje').css({top:'180px',left:'35px'}).html('<img src="images/globa_matias.png" width="350"/>');
			$('#personaje').css({top:'355px',left:'380px'}).html('<img src="images/matias.png"/>').show();
			$('#texto_home').hide();
		}
		/*if(actual==4) { //loco chavez
			$('#globa_personaje').css({top:'61px',left:'151px'}).html('<img src="images/globa_loco_chavez.png" width="350"/>');
			$('#personaje').css({top:'232px',left:'272px'}).html('<img src="images/loco_chavez.png"/>').show();
			$('#texto_home').hide();
		}*/
		if(actual==4) { //divito
			$('#globa_personaje').css({top:'110px',left:'287px'}).html('<img src="images/globa_divito.png" width="350"/>');
			$('#personaje').css({top:'240px',left:'350px'}).html('<img src="images/divito.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==5) { //don fulgencio
			$('#globa_personaje').css({top:'130px',left:'370px'}).html('<img src="images/globa_don_fulgencio.png" width="350"/>');
			$('#personaje').css({top:'270px',left:'310px'}).html('<img src="images/don_fulgencio.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==6) { //clemente
			$('#globa_personaje').css({top:'170px',left:'347px'}).html('<img src="images/globa_clemente.png" width="350"/>');
			$('#personaje').css({top:'415px',left:'160px'}).html('<img src="images/clemente.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==7) { //patoruzu
			$('#globa_personaje').css({top:'95px',left:'320px'}).html('<img src="images/globa_patoruzu.png" width="350"/>');
			$('#personaje').css({top:'233px',left:'356px'}).html('<img src="images/patoruzu.png"/>').show();
			$('#texto_home').hide();
		}
		if(actual==8) { //gaturro
			$('#globa_personaje').css({top:'190px',left:'360px'}).html('<img src="images/globa_gaturro.png" width="350"/>');
			$('#personaje').css({top:'320px',left:'308px'}).html('<img src="images/gaturro.png"/>').show();
			$('#texto_home').hide();
		}
        if(actual==9) { //don nicola
			$('#globa_personaje').css({top:'80px',left:'358px'}).html('<img src="images/Globa-Don-Nicola.png" width="350"/>');
			$('#personaje').css({top:'212px',left:'285px'}).html('<img src="images/Don-Nicola.png" width="400"/>').show();
			$('#texto_home').hide();
		}
		if(actual==10) { //diogenes
			$('#globa_personaje').css({top:'170px',left:'355px'}).html('<img src="images/Globa-Diogenes-y-el-Linyera.png" width="350"/>');
			$('#personaje').css({top:'225px',left:'290px'}).html('<img src="images/Diogenes-y-el-Linyera.png" width="350"/>').show();
			$('#texto_home').hide();
		}
		if(actual==11) { //jirafa
			$('#globa_personaje').css({top:'0px',left:'420px'}).html('<img src="images/Globa-Jirafa-Mordillo.png" width="350"/>');
			$('#personaje').css({top:'125px',left:'300px'}).html('<img src="images/Jirafa-Mordillo.png" width="385"/>').show();
			$('#texto_home').hide();
		}		
		if(actual==12) { //negrazon
			$('#globa_personaje').css({top:'127px',left:'357px'}).html('<img src="images/Globa-Negrazon-y-Chaveta.png" width="350"/>');
			$('#personaje').css({top:'220px',left:'260px'}).html('<img src="images/Negrazon-y-Chaveta.png" width="400"/>').show();
			$('#texto_home').hide();
		}
		if(actual==13) { //Inodoro Pereyra
			$('#globa_personaje').css({top:'118px',left:'339px'}).html('<img src="images/Globa-Inodoro-Pereyra.png" width="330"/>');
			$('#personaje').css({top:'235px',left:'280px'}).html('<img src="images/Inodoro-Pereyra.png" width="380"/>').show();
			$('#texto_home').hide();
		}
		if(actual==14) { //Langostino-y-Corina
			$('#globa_personaje').css({top:'57px',left:'390px'}).html('<img src="images/Globa-Langostino-y-Corina.png" width="320"/>');
			$('#personaje').css({top:'160px',left:'305px'}).html('<img src="images/Langostino-y-Corina.png" width="355"/>').show();
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