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
<h2>Listado de usuarios</h2>
<table>

<tr>
<th>id</th>
<th>nombre completo</th>
<th>nombre</th>
<th>inicial</th>
<th>apellido</th>
<th>enlace</th>
<th>genero</th>
<th>zona</th>
<th>locale</th>
<th>actualizado</th>
<th>ingreso</th>
<th>ultimo uso</th>
<th>email</th>
</tr>

<?php 
$db = dbaccess::conexionDB();
$rs = $db->query("select * from usuarios order by app_ingreso");
if($rs) {
	while($row = $rs->fetch_array() ) {
		echo "<tr>
				<td>{$row['id']}</td>
				<td>{$row['name']}</td>
				<td>{$row['first_name']}</td>
				<td>{$row['middle_name']}</td>
				<td>{$row['last_name']}</td>
				<td>{$row['link']}</td>
				<td>{$row['gender']}</td>
				<td>{$row['timezone']}</td>
				<td>{$row['locale']}</td>
				<td>{$row['updated_time']}</td>
				<td>{$row['app_ingreso']}</td>
				<td>{$row['app_ultimo']}</td>
				<td>{$row['email']}</td>
			  </tr>";		
	}
}
?>
</table>

</body>
</html>