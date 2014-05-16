<?php
include 'canvas/templates.php';
include 'canvas/dbaccess.php';
include 'configuracion.php';

$hash = (isset($_REQUEST["h"]) ? $_REQUEST["h"] : "");
$data = null;

if($hash!="") {
	$hash = substr($hash,0,32);
	$hash = str_replace(array("'","--","/","<",">"), array("''","__","/ ","&lt;","&gt;"), $hash);
	$postal = dbaccess::queryArray("select * from postales where pos_hash='{$hash}'");
	if($postal) {
		
		if( $postal['pos_nombre']=="" )
			$postal['pos_nombre'] = "tu amigo";
		
		$data = array(
				"titulo" => "Feliz día del amigo!",
				"image" => config::$appImage,
				"url" => config::$appPostal.$hash,
				"amigo_nombre" => $postal['pos_amigo_nombre'],
				"amigo_apellido" => $postal['pos_amigo_apellido'],
				"saludo" => $postal['pos_saludo'],
				"nombre"=> $postal['pos_nombre'],
				"apellido"=> $postal['pos_apellido'],
				"postal"=>$postal['pos_postal'],
				"canvas"=>config::$appCanvas,
				"app"=>config::$appCanvas."controller.php?op=goAppEnvio"
		);
	}
}

if(!$data) {
	$data = array(
			"titulo" => "Feliz día del amigo!",
			"image" => config::$appImage,
			"url" => config::$appPostal.$hash,
			"amigo_nombre" => "",
			"amigo_apellido" => "",
			"saludo" => "",
			"nombre"=> "",
			"apellido"=> "",
			"postal"=>"donfulgencio_pos.jpg",
			"canvas"=>config::$appCanvas,
			"app"=>config::$appCanvas."controller.php?op=goAppEnvio"
	);
}

$t = new template();
echo $t->render($data,"postal.html");
?>