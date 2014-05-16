<section id="contacto">
    <style>
        #share {top:10px;left:210px;}
        #dialogo {position: absolute;top: 193px;left: 175px;font:14pt KiComicRegular;width:700px;height:515px;text-align:left;background: url(images/marconegroyblanco.png);}
        #dialogo #cont {padding:20px;}
        #dialogo #cont div {margin-top:10px;}
        input, select, option, textarea {font:12pt KiComicRegular;}
        #casoM, #casoF, #nombreAmigo, #userAmigo {display:none;}
        #acciones a {float:left;}
        #acciones {padding-left:300px;}
        body {background: #8cc63f;} 
        .paloma {width:80px;}
        #msg {width: 650px;height: 190px;}
        #tu_nombre, #correo {width: 350px;}
        #pajarito {top: 25px;left: 364px;}
    </style>
    
    <div id="dialogo"><div id="cont">
		<div>¡Hola!<br>Escribí tu mensaje para contactarte con nosotros. <br>¡Gracias!</div>
		
		<div><label>Tu nombre:</label>			<input type="text" id="tu_nombre"></div>
		<div><label>Correo:</label>				<input type="text" id="correo"></div>
		<div><label>Mensaje:</label>			<textarea id="msg"></textarea></div>
		
		<div id="acciones">
			<a id="cancela" href="<?= site_url() ?>"><img src="images/botoncancelar.png"/></a>
			<a id="sigue" href="javascript:void(0)" onclick="siguiente()"><img src="images/botonsiguiente.png"/></a>
		</div> 
		
	</div></div>
    
    <script type="text/javascript">

        function siguiente() {
            var mensaje = $('#msg').val();
            if( mensaje=="" ) {
                msg("Completá más datos","Para avanzar, por favor escribí algún mensaje.");
                return false;
            }

            var nombre = $('#tu_nombre').val();
            if( nombre=="" ) {
                msg("Completá más datos","Para avanzar, por favor escribí tu nombre.");
                return false;
            }

            var mail = $('#correo').val();
            if( mail=="" ) {
                msg("Completá más datos","Para avanzar, por favor escribí tu correo electrónico.");
                return false;
            }

            msg("Aguardá un momento por favor",'<div class="espera">Realizando el envío...<br/><img src="images/loading2.gif"/></div>',false);

            var r = $.ajax({
                url:'<?= site_url('contacto/enviarContacto') ?>',
                dataType:'json',
                data: {'op':'enviarContacto','mensaje':mensaje, 'nombre':nombre, 'mail':mail}
            });

            r.done(function(json) { 
                if(json.status=="ok") {
                    msg("",'Tu mensaje se envió con éxito!',true, "irAHome");
                } else {
                    msg("Error al enviar el mensaje", "Se ha producido un error al enviar tu mensaje. Reintentá nuevamente en unos minutos.");
                }
            });

            r.fail(function(jqXHR, textStatus) { 
                msg("Error al enviar mensaje", "Se ha producido un error al enviar tu mensaje. Reintentá nuevamente en unos minutos.") 
            });	
            return false;
        }
        
        function irAHome()
        {
            document.location.href = '<?=  site_url() ?>';
        }
        
</script>
    
</section>