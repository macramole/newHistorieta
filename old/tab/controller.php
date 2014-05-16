<?php
include '../canvas/templates.php';
include '../canvas/dbaccess.php';
include '../configuracion.php';
session_start();

$fn = (isset($_REQUEST["op"]) ? $_REQUEST["op"] : "goHome");
if($fn) {
	error_log("controller::{$fn}()");
	controller::$fn();
}
exit();

class controller {
			
	static function goHome() {
		$t = new template();
		echo $t->render(array("link"=>config::$appCanvas),"tab.html");
	}
		
}

?>