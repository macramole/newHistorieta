<section class="galeria">
	<style>
        #share {top:10px;left:210px;}
        #galleria {position: absolute;top: 220px;left: 183px;width: 670px;height: 498px;}
        #pajarito {top: 46px;}
    </style>
    
    <div id="ppal">		
		<div id="galleria"></div>
	</div>
    <script>
        $( function() {
            data = [
            <?php if ( count($galeria['imagenes']) ) : ?>
                <?php foreach( $galeria['imagenes'] as $imagen ) : ?>
                    {
                        image: '<?= $imagen  ?>',
                        //title: 'Cachorra',
                        //description: 'Dante Quinterno',
                        link: '<?= $imagen  ?>'
                    },
                <?php endforeach; ?>
            <?php endif; ?>
            ];
        
            var conf = {
                imageCrop: true,
                transition: 'fade',
                lightbox: false,
                dataSource: data
            };
            Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
            Galleria.run('#galleria',conf); 
            
            $('.galleria-img').click(function(e) {
                preventDefault();
            });
        });
    </script>
</section>
<script type="text/javascript" src="galleria/galleria-1.2.7.min.js"></script>

<?php magico_setMainData($galeria['id'], 'Galeria') ?>