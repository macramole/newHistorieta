<style>
#pajarito, #mapa, #share { display: none; }
#gallery { height: 1000px }
</style>
<link href="css/jquery.jscrollpane.css" media="screen,print" type="text/css" rel="stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

<div id="menuAutores">
    <img src='images/menu_autores.png' />
    <?php if ( count($autores) ) : ?>
    <ul>
        <?php foreach( $autores as $itemAutor ) : ?>
        <li class="<?= $itemAutor['selected'] ? 'selected' : '' ?>"><a href="<?= $itemAutor['url'] ?>"><?= $itemAutor['title'] ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php magico_setData( $autores, 'Autor', '#menuAutores ul li', MAGICO_SORTABLE ) ?>
    <?php endif; ?>
</div>

<section id="autores">
    <div class="autor left">
        <div class="left">
            <div class="avatar">
                <img src="<?= $autor['imagen'] ?>" alt="Quino">
            </div>
            <h1 class="titulo"><?= $autor['title'] ?></h1>
            <div class="textoWrapper">
                <?= $autor['texto'] ?>
            </div>
            <?php magico_setEditable( $autor['id'], 'Autor', 'texto', '.textoWrapper') ?>
            <div class="clear"></div>
        </div>
        <?php if ( count($autor['personajes']) ) : ?>
        <div class="galeria right">
            <div class="flecha izquierda">
                <img src="images/flechaI.png" alt="Flecha">
            </div>
            <div id="wrapper">
                <ul>
                    <?php foreach( $autor['personajes'] as $personaje ) : ?>
                    <li>
                        <img src="<?= $personaje['imagen'] ?>" alt="Mafalda">
                        <?php if ( $personaje['latitud'] ) : ?>
                        <a href="<?= site_url('mapa') ?>" class="mapa"><img src="images/ver_mapa.png" alt="Mapa"></a>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                    <?php magico_setData($autor['personajes'], 'Personaje', '.galeria.right #wrapper ul li') ?>
                </ul>
            </div>
            <div class="flecha derecha">
                <img src="images/flechaD.png" alt="Flecha">
            </div>
        </div>
        <?php endif; ?>
        <div class="clear"></div>
    </div>

    <?php if ( count($autor['entradas']) ) : ?>
    <div class="notaWrapper left">
        <div class="notaInnerWrapper">
            <?php foreach( $autor['entradas'] as $entrada ) : ?>
            <div class="nota">
                <div class="info">
                    <div class="head">
                        <h2><?= $entrada['title'] ?></h2>
                        <span class="fecha"><?= date('d/m/Y', strtotime($entrada['fecha'])) ?></span>
                    </div>
                    <div class="clear"></div>
                    <div class="bioWrapper">
                        <?= $entrada['cuerpo'] ?>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
            <?php magico_setData($autor['entradas'], 'Entrada', '.nota') ?> 
            <?php magico_setEditables($autor['entradas'], 'Entrada', 'cuerpo', '.bioWrapper'); ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<?php magico_setMainData($autor['id'], 'Autor') ?>

<script type="text/javascript" src="js/jquery.mousewheel.js"></script>	  
<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>	  
<script type="text/javascript">
    $( function() {
        $('.galeria #wrapper ul').width( $('.galeria #wrapper ul li').length * $('.galeria #wrapper ul li').first().width() );

        $('.flecha.derecha').click( function() {

            if ( $('#wrapper').scrollLeft() < $('#wrapper').width() * $('#wrapper li').length )

            $('#wrapper').animate({
                    scrollLeft: '+=' + $('#wrapper').width() + 'px'
                },{ duration: 300 });
        } );

        $('.flecha.izquierda').click( function() {
            if ( $('#wrapper').scrollLeft() > 0 )
            {
                $('#wrapper').animate({
                    scrollLeft: '-=' + $('#wrapper').width()
                },{ duration: 300 } );
            }
        });
        
        $('.notaInnerWrapper').jScrollPane({verticalDragMaxHeight: 700});
    });		
</script>