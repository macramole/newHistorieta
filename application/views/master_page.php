<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Description of the site" />

    <meta property="og:title" content="<?= $title ?> <?= $sectionTitle ? "| $sectionTitle" : '' ?>"/>
    <meta property="og:url" content="<?= current_url() ?>"/>
    <meta property="og:description" content="<?= $og_description ?>"/>
    <meta property="og:image" content="<?= $og_image?>" />
 	
    <link rel="icon" type="image/png" href="favicon.ico">
    
    <base href="<?= base_url() ?>" />
    
	<title><?= $title ?> <?= $sectionTitle ? "| $sectionTitle" : '' ?></title>
	<link href="css/canvas.css" media="screen,print" type="text/css" rel="stylesheet" />
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="js/canvas.js"></script>
	
	<?= $head ?>
</head>
    <body>
        <div id="gallery">
            <div id="slider">
                <mp:Fondos />
            </div>

            <!-- Elementos fijos -->
            <div id="menu">
                <a id="m_home" 		href="<?= site_url() ?>"></a>
                <a id="m_menu" 		href="javascript:void(0)"></a>
                <a id="m_foto" 		href="<?= site_url('galeria/fotos') ?>"></a>
                <a id="m_wallpaper" href="<?= site_url('galeria/wallpapers') ?>"></a>
                <a id="m_autores" 	href="<?= site_url('autores') ?>"></a>
                <a id="m_museo" 	href="<?= site_url('museo') ?>"></a>
                <a id="m_mapa" 	href="<?= site_url('mapa') ?>"></a>
                <a id="m_contacto" 	href="<?= site_url('contacto') ?>"></a>
            </div>
            
            <a id="mapa" href="<?= site_url('mapa') ?>"><img src="images/mapa.png"/></a>
            <div id="share"><img src="images/share.png"/>
                <a id="tweet" href="https://twitter.com/CiudadaniaBA" target="_blank" ><img src="images/transparent.png"/></a>
                <a id="fb" href="https://facebook.com/construccionciudadanaba" target="_blank" ><img src="images/transparent.png"/></a>
            </div>	

            <a id="pajarito" href="javascript:void(0)">
                <img id="globa_pajarito" src="images/globa_trivia.png"/>
                <img id="vuelo" src="images/pajarito-animado.gif"/>
            </a>

            <mp:Content />

        </div>    
        <mp:Magico />
    </body>
</html>