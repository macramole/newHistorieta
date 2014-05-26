<section class="galeria">
	<style>
        #share { display: none; }
        #shareCopy { 
            float: right;
            position: relative;
            right: 30px;
            top: 10px;
        }
        #shareCopy img { width: 120px; }
        #gallery { height: 830px }
        #galleria {width: 755px;height: 498px;}
        #ppal { position: absolute;top: 150px;left: 183px; }
        #ppal ul { list-style: none; padding: 0; margin: 0 ; overflow: hidden; float: left; }
        #ppal ul li { float: left; margin-right: 10px }
        #ppal ul a { color: black; text-decoration: none; font-size: 16px }
        #ppal ul a:hover { text-decoration: underline; }
        #categoriasWrapper { 
            background-color: #FFF;
            margin: 0px 0px 10px;
            padding: 5px 15px;
            border-radius: 5px;
            width: 725px;
        }
        #ppal ul a.active { color: #F2629D }
        #pajarito {display: none}
        #paseo { top: 30px;}
        #imgComparti { display: inline-block; margin-top: 10px; }
    </style>
    
    <div id="ppal">
        <div id="categoriasWrapper">
            <?php if ( count($galerias) || count($personajes) ) : ?>
                <ul class="galerias">
                <?php if ( count($galerias) ) : ?>
                    <?php foreach( $galerias as $galeria ) : ?>
                        <li class="galerias">
                            <a class="<?= $galeriaSeleccionada['id'] == $galeria['id'] && !$galeriaSeleccionada['idAutor'] ? 'active' : '' ?>" 
                               href="<?= $galeria['url'] ?>" rel="<?= $galeria['id'] ?>"><?= $galeria['title'] ?></a>
                        </li>
                    <?php endforeach; ?>
                    <?php magico_setData($galerias, 'Galeria', 'ul li.galerias') ?>
                <?php endif; ?>
                <?php if ( count($personajes) ) : ?>
                    <?php foreach( $personajes as $personaje ) : ?>
                        <li class="personajes">
                            <a class="<?= $galeriaSeleccionada['id'] == $personaje['id'] && $galeriaSeleccionada['idAutor'] ? 'active' : '' ?>"
                                href="<?= $personaje['url'] ?>" rel="<?= $personaje['id'] ?>"><?= $personaje['title'] ?></a>
                        </li>
                    <?php endforeach; ?>
                    <?php magico_setData($personajes, 'Personaje', 'ul li.personajes') ?>
                <?php endif; ?>
                </ul>
            
            
            <?php endif; ?>
            
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
		<div id="galleria"></div>
         
        <?php if ( $galeriaSeleccionada['id'] != 2 ) :  ?> 
        <a href="https://facebook.com/construccionciudadanaba" id="imgComparti" target="_blank">
            <img src="images/boton_comparti.jpg" />
        </a>
        <?php endif; ?>
        
        <div id="shareCopy"><img src="images/share.png"/>
            <a id="tweet" href="https://twitter.com/CiudadaniaBA" target="_blank" ><img src="images/transparent.png"/></a>
            <a id="fb" href="https://facebook.com/construccionciudadanaba" target="_blank" ><img src="images/transparent.png"/></a>
        </div>	
	</div>
    <script>
        $( function() {
            var conf = {
                imageCrop: true,
                transition: 'fade',
                lightbox: false,
                popupLinks: true,
                dataSource: [
                    <?php if ( count($galeriaSeleccionada['imagenes']) ) : ?>
                        <?php foreach( $galeriaSeleccionada['imagenes'] as $key => $imagen ) : ?>
                            <?php if ( count($galeriaSeleccionada['downloads']) ) : ?>
                                {
                                    image: '<?= $imagen ?>',
                                    link: '<?= $galeriaSeleccionada['downloads'][$key] ?>'
                                }
                            <?php else : ?>
                                {
                                    image: '<?= $imagen ?>'
                                }
                            <?php endif; ?>
                            ,
                        <?php endforeach; ?>
                    <?php endif; ?>
                ]
            };
            Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
            Galleria.run('#galleria', conf); 
        });
    </script>
</section>
<script type="text/javascript" src="galleria/galleria-1.2.7.min.js"></script>

<?php magico_setMainData($galeriaSeleccionada['id'], $galeriaSeleccionada['type']) ?>