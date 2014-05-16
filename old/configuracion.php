<?php
class config {
	
/* Database */
	public static $db_host = "localhost";
	public static $db_user = "historieta";
	public static $db_password = "34hJrhryi4";
	public static $db_database = "historieta";

/* Facebook */	
	public static $appId = "368774669843511";
	public static $appSecret = "edefe93d59186a8cf486c6cfa9ee0a8c";
	public static $appRedirect = 'https://apps.facebook.com/paseodelahistorieta/';
	public static $appCanvas = 'https://fb.buenosaires.gob.ar/historietas/canvas/';

	public static $appCanvasShort = 'https://fb.buenosaires.gob.ar/historietas/canvas/';
	
	public static $appImage = 'https://fb.buenosaires.gob.ar/historietas/images/logo200.png';
	public static $appTab = 'http://www.facebook.com/dialog/pagetab?app_id=368774669843511&next=https://fb.buenosaires.gob.ar/historietas/tab/index.php';
	public static $appPostal = 'https://fb.buenosaires.gob.ar/historietas/postal.php?h=';
	public static $appHome = 'https://fb.buenosaires.gob.ar/';

	//No poner http o https delante. El programa completa esa parte de forma automatica.
	public static $appLoginUrl = "fb.buenosaires.gob.ar/historietas/canvas/controller.php?op=appLogin";
	
/* Mail */
	public static $mailContacto = 'paseodelahistorieta@buenosaires.gob.ar';
	public static $mailAccount = 'paseodelahistorieta@buenosaires.gob.ar';
	public static $mailAccountName = 'Paseo de la historieta';
	public static $mailUser = 'paseodelahistorieta@buenosaires.gob.ar';
	public static $mailPassword = 'vevaruge';
	public static $mailServer = 'mail.buenosaires.gob.ar';
	
/* BitLy */
	public static $bitlyUser = "o_3rl0g8vhqv";
	public static $bitlyKey = "R_61de8f569e278e46bc82061e864750b3";

	
}