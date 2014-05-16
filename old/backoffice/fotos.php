<?php 
include '../configuracion.php';
include '../canvas/dbaccess.php';
session_start();
if(!isset($_SESSION['user'])) {
	header("Location: index.php");
	exit;
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
    	th,td {border: solid 1px #888;padding:2px;}
    	td {background:#fff;}
    </style>
    <script type="text/javascript">
    function aprobar(foto) {
 		var r = $.ajax({
		   	url:'controller.php',
		   	dataType:'json',
		   	data: {'op':'apruebaFoto','f':foto}
	    });
		r.done(function(json) { 
			if(json.status=="ok") {
				$('#foto_'+foto).remove();
			} else {
				msg("Error", "Se ha producido un error al aprobar la foto. Intentá nuevamente en unos minutos.");
			}
		});
		r.fail(function(jqXHR, textStatus) {
			msg("Error", "Se ha producido un error al aprobar la foto. Intentá nuevamente en unos minutos.");
		});
    
    }
    function rechazar(foto) {
 		var r = $.ajax({
		   	url:'controller.php',
		   	dataType:'json',
		   	data: {'op':'rechazaFoto','f':foto}
	    });
		r.done(function(json) { 
			if(json.status=="ok") {
				$('#foto_'+foto).remove();
			} else {
				msg("Error", "Se ha producido un error al aprobar la foto. Intentá nuevamente en unos minutos.");
			}
		});
		r.fail(function(jqXHR, textStatus) {
			msg("Error", "Se ha producido un error al aprobar la foto. Intentá nuevamente en unos minutos.");
		});
}    
    </script>
</head>
<body>
<h2>Moderación de fotos</h2>
<table>

<tr>
<th>Código</th>
<th>Foto</th>
<th>Fecha</th>
<th>Nombre</th>
<th>Correo</th>
<th>Estado</th>
</tr>

<?php 
$db = dbaccess::conexionDB();
$rs = $db->query("select * from fotos where fot_estado='PRELIMINAR'");
if($rs) {
	while($row = $rs->fetch_array() ) {
		echo "<tr id=\"foto_{$row['fot_codigo']}\">
				<td>{$row['fot_codigo']}</td>
				<td><img src=\"thumb.php?f={$row['fot_archivo']}\"></td>
				<td>{$row['fot_fecha']}</td>
				<td>{$row['fot_nombre']}</td>
				<td>{$row['fot_correo']}</td>
				<td><button onclick=\"aprobar({$row['fot_codigo']})\">Aprobar</button> <button onclick=\"rechazar({$row['fot_codigo']})\">Rechazar</button></td>
			  </tr>";		
	}
}
?>
</table>

</body>
</html>