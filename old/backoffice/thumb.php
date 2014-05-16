<?php 
$f = (isset($_REQUEST["f"]) ? $_REQUEST["f"] : "");
$s = (isset($_REQUEST["s"]) ? intval($_REQUEST["s"]) : 128);

function getImage($file, $size )
{
	$extension = strtolower(substr($file,strrpos($file,".")+1));
	header('Cache-Control: public'); // needed for i.e.

	switch($extension) {
		case "png":
			header('Content-Type: image/png');
			$im = imagecreatefrompng($file);
			break;
		case "jpeg":
		case "jpg":
			header('Content-Type: image/jpeg');
			$im = imagecreatefromjpeg($file);
			break;
		case "gif":
			header('Content-Type: image/gif');
			$im = imagecreatefromgif($file);
			break;
	}

	//Dimension de la foto original
	$x = imagesx($im);
	$y = imagesy($im);

	//Radios de ajuste para llegar al tamaño deseado
	$kx = $size / $x;
	$ky = $size / $y;
	$k = ($kx>$ky ? $ky : $kx);

	//Nuevas dimensiones del thumb
	$nx = intval($x * $k,10);
	$ny = intval($y * $k,10);


	switch($extension) {
		case "png":
			$nim = imagecreate($nx,$ny);
			imagecopyresampled($nim, $im, 0, 0, 0, 0, $nx , $ny , $x , $y);
			imagepng($nim);
			break;
		case "jpeg":
		case "jpg":
			$nim = imagecreatetruecolor($nx,$ny);
			imagecopyresampled($nim, $im, 0, 0, 0, 0, $nx , $ny , $x , $y);
			imagejpeg($nim);
			break;
		case "gif":
			$nim = imagecreate($nx,$ny);
			imagecopyresampled($nim, $im, 0, 0, 0, 0, $nx , $ny , $x , $y);
			imagegif($nim);
			break;
	}
}

getImage($f, $s) ;
exit();

?>