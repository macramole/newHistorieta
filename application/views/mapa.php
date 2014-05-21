<section id="sectionMapa">
	<style>
	    #share { 
            top: 720px;
            left: 755px;
        }
        #gallery { height: 790px }
	    #mapita {position:absolute;top:193px;left:190px;width:700px;height:520px;background: url(../images/marconegroyblanco.png);}
		body {background: #8cc63f;}
	     /*{top: 26px;left:362px;}*/
        #mapa, #pajarito, #paseo { display: none }
    </style>
    <div id="mapita"></div>
    <script type="text/javascript" src="js/mapa.js"></script>
    <script>
        var markers;
        
        <?php if ( count($markers) ) : ?>
        $( function() {
            markers = <?= magico_arrPHP2JS($markers, false); ?>;
            placeMarkers();
        });
        <?php endif; ?>
    </script>
	
</section>