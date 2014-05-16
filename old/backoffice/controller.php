<?php
include '../configuracion.php';
include '../canvas/dbaccess.php';

session_start();
$cnt = new controller();

$fn = (isset($_REQUEST["op"]) ? $_REQUEST["op"] : "");
if($fn) {
	error_log("controller::{$fn}()");
	if( is_callable(array($cnt, $fn)) )
		$cnt->$fn();
}
exit();


class controller {
	static function apruebaFoto() {
		$db = dbaccess::conexionDB();
		$foto = dbaccess::clean( (isset($_REQUEST["f"]) ? $_REQUEST["f"] : ""), 200);
				
		try {
			//Marco la foto como aprobada
			$sql = "update fotos set fot_estado='APROBADA' where fot_codigo='{$foto}'";
			$db->query($sql);
			
			//Copio el archivo a la carpeta de la galeria
			//Estoy en ../backoffice
			$destino = pathinfo(__FILE__, PATHINFO_DIRNAME);
			$destino = substr($destino, 0, strrpos($destino, "/"))."/images/fotos/";
			
			//Ubicacion de la foto original
			$original = dbaccess::queryString("select fot_archivo from fotos where fot_codigo='{$foto}'", $db);
			
			//Creo foto full-res
			copiarImagen($original, $destino."foto_{$foto}.jpg", null);

			//Creo foto pequeña
			copiarImagen($original, $destino."small_foto_{$foto}.jpg", 600);
			
			//Creo foto thumb
			copiarImagen($original, $destino."thumb_foto_{$foto}.jpg", 128);
			
			echo json_encode(array('status'=>'ok'));
		} catch (Exception $ex) {
			echo json_encode(array('status'=>'error'));
		}		
	}
	
	static function rechazaFoto() {
		$db = dbaccess::conexionDB();
		$foto = dbaccess::clean( (isset($_REQUEST["f"]) ? $_REQUEST["f"] : ""), 200);
				
		try {
			$sql = "update fotos set fot_estado='RECHAZADA' where fot_codigo='{$foto}'";
			$db->query($sql);
			
			echo json_encode(array('status'=>'ok'));
		} catch (Exception $ex) {
			echo json_encode(array('status'=>'error'));
		}		
	}
}

function copiarImagen($origen, $destino, $size) {
	try {	
		$extension = strtolower( pathinfo($origen, PATHINFO_EXTENSION) );
		switch($extension) {
			case "png":
				$im = imagecreatefrompng($origen);
				break;
			case "jpeg":
			case "jpg":
				$im = imagecreatefromjpeg($origen);
				break;
			case "gif":
				$im = imagecreatefromgif($origen);
				break;
		}
		
		//Dimension de la foto original
		$x = imagesx($im);
		$y = imagesy($im);
		
		if($size==null) {
			//No hay cambio de tamaño con el original
			$nx = $x;
			$ny = $y;
		} else {	
			//Radios de ajuste para llegar al tamaño deseado
			$kx = $size / $x;
			$ky = $size / $y;
			$k = ($kx>$ky ? $ky : $kx);
			
			//Nuevas dimensiones del thumb
			$nx = intval($x * $k,10);
			$ny = intval($y * $k,10);
		}
				
		$nim = imagecreatetruecolor($nx,$ny);
		imagecopyresampled($nim, $im, 0, 0, 0, 0, $nx , $ny , $x , $y);
		imagejpeg($nim, $destino);
		return true;
	} catch(Exception $ex) {
		return false;
	}
}
