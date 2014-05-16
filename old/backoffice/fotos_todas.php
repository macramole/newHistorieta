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
</head>
<body>
<h2>Listado de fotos</h2>
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
$rs = $db->query("select * from fotos order by fot_fecha");
if($rs) {
	while($row = $rs->fetch_array() ) {
		echo "<tr>
				<td>{$row['fot_codigo']}</td>
				<td><img src=\"thumb.php?f={$row['fot_archivo']}\"></td>
				<td>{$row['fot_fecha']}</td>
				<td>{$row['fot_nombre']}</td>
				<td>{$row['fot_correo']}</td>
				<td>{$row['fot_estado']}</td>
			  </tr>";		
	}
}
?>
</table>

</body>
</html>