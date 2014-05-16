<?php 
session_start();
if(isset($_REQUEST['user'])) {
	if( $_REQUEST['user']=="admin" && $_REQUEST['pass']=="5tgb6yhn" )
		$_SESSION['user'] = "admin";
}
if(isset($_REQUEST['logout'])) {
	unset($_SESSION['user']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
   	<meta http-equiv="content-language" content="es" />
 	
	<title>Paseo de la historieta</title>
	<link href="../css/canvas.css" media="screen,print" type="text/css" rel="stylesheet" />
	
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="../js/jquery-1.7.2.min.js"><\/script>')</script>
	<script type="text/javascript" src="../js/canvas.js"></script>
	    
    <style>
    	#opciones {position:absolute; width:600px; height: 500px; top: 50px; left: 50px;}
    	ul {text-align:left;}
    	li {height:30px;}
    	#login {position:absolute; width:600px; height: 150px; top: 50px; left: 50px; background:#fff;box-shadow: 3px 3px 15px #222;}
    	body {background: #8cc63f;} 
    </style>
</head>
<body>

<div id="gallery">
	<div id="slider">
		<div class="slide"><img id="f1" src="../images/fondos/fondo11.png" width="960" height="770"/></div>
	</div>
	
<?php if(isset($_SESSION['user'])) {?>		
	<div id="opciones">
		<h2>Backoffice</h2>
		<ul>
			<li><a href="fotos.php">Moderación de fotos</a></li>
			<li><a href="fotos_todas.php">Ver todas las fotos</a></li>
			<li><a href="usuarios.php">Consulta de usuarios</a></li>
			<li><a href="postales.php">Consulta de postales</a></li>
			<li><a href="contactos.php">Consulta de contactos</a></li>
			<li><a href="index.php?logout=1">Salir</a></li>
			</ul>
	</div>
<?php } else {?>

	<div id="login">
		<h2>Ingreso al Backoffice</h2>
		<form action="index.php">
			<div>Usuario: <input type="text" name="user"></div>
			<div>Clave: <input type="password" name="pass"></div>
			<div><button type="submit">Ingresar</button></div>
		</form>
	</div>

<?php }?>	
</div>
</body>
</html>