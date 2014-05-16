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
<h2>Listado de postales</h2>
<table>

<tr>
<th>codigo</th>
<th>hash</th>
<th>id</th>
<th>nombre</th>
<th>apellido</th>
<th>id amigo</th>
<th>nombre amigo</th>
<th>apellido amigo</th>
<th>postal</th>
<th>saludo</th>
<th>tipo</th>
</tr>

<?php 
$db = dbaccess::conexionDB();
$rs = $db->query("select * from postales order by pos_tipo");
if($rs) {
	while($row = $rs->fetch_array() ) {
		echo "<tr>
				<td>{$row['pos_codigo']}</td>
				<td>{$row['pos_hash']}</td>
				<td>{$row['pos_id']}</td>
				<td>{$row['pos_nombre']}</td>
				<td>{$row['pos_apellido']}</td>
				<td>{$row['pos_amigo_id']}</td>
				<td>{$row['pos_amigo_nombre']}</td>
				<td>{$row['pos_amigo_apellido']}</td>
				<td>{$row['pos_postal']}</td>
				<td>{$row['pos_saludo']}</td>
				<td>{$row['pos_tipo']}</td>
			  </tr>";		
	}
}
?>
</table>

</body>
</html>