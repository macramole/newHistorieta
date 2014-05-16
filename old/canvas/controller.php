<?php
include '../configuracion.php';
include 'templates.php';
include 'dbaccess.php';
include 'mail.php';
include 'bitly.php';

session_start();
$cnt = new controller();

$fn = (isset($_REQUEST["op"]) ? $_REQUEST["op"] : "goHome");
if($fn) {
	error_log("controller::{$fn}()");
	if( is_callable(array($cnt, $fn)) )
		$cnt->$fn();
	else 
		$cnt->goHome();
}
exit();


class controller {
			
	static function goHome() {
		$t = new template();
		echo $t->render(null,"landing.html");
	}
	
	static function goMapa() {
		$t = new template();
		echo $t->render(null,"mapa.html");
	}

	static function goInaguracion() {
		$t = new template();
		echo $t->render(null,"inaguracion.html");
	}

	static function goMuseo() {
		$t = new template();
		echo $t->render(null,"museo.html");
	}
	
	static function goAppEnvio() {
		$t = new template();
		echo $t->render(null,"envio1.html");
	}

	static function goFacebook() {
		$t = new template();
		echo $t->render(null,"face1.html");
	}

	static function goTrivia() {
		$t = new template();
		echo $t->render(null,"trivia.html");
	}
	
	// Requiere el OK de la persona para continuar
	static function appLogin() {

		$code = (isset($_REQUEST["code"]) ? $_REQUEST["code"] : "");
		$error = (isset($_REQUEST["error"]) ? $_REQUEST["error"] : "");
					
		if($code=="") {
			/**
			 * El usuario NO ACEPTA la App, responde NO al dialogo anterior.
			 * Estoy en un popup, debo destruir el popup y llamar al parent para resetearlo
			 */
			error_log("Ingreso a Facebook -> App rechazada por el usuario.");
			//echo '<html><head></head><body><script type="text/javascript">window.opener.abort();close();<script></body></html>';
			//header("Location: controller.php?op=goFacebook");
			self::goHome();
		}
		else 
		{		
			/**
			 * App autorizada, aviso al padre y cierro el popup
			 */
			error_log("Ingreso a Facebook -> App autorizada. Codigo = $code");
			$_SESSION['code'] = $code;
			//echo '<html><head></head><body><script type="text/javascript">window.opener.retry();close();<script></body></html>';
			//header("Location: controller.php");
			self::goFacebook();
		}
	}
	
	
	static function getFBData() {

		//$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$protocol = "https://";
		
		//Tengo el CODE que mando FB
		if( isset($_SESSION['code']) && oauthFB($_SESSION['code'], $protocol . config::$appLoginUrl) ) {		
			//Una vez autenticado por OAuth puedo pedir los datos del usuario
			$user = getFBuserData("me");
			$_SESSION["user_profile"] = $user;
			
			//Salvo en la base de datos los datos del usuario
			self::crearUsuario($user);
			
			//Lista de amigos del usuario
			$amigos_json = getFB("https://graph.facebook.com/me/friends?limit=5000&access_token=".$_SESSION['access_token']);
			$amigos = json_decode($amigos_json);
			$_SESSION["friends"] = $amigos;
			
			echo json_encode(array('status'=>'ok','id'=>$user->id,'first_name'=>$user->first_name, 'friends'=>$amigos));	
		}
		else
		{
			
			$url = "https://www.facebook.com/dialog/oauth?client_id="
				. config::$appId
				. "&scope=publish_stream,email"
				. "&redirect_uri=" . urlencode($protocol . config::$appLoginUrl);
			
			echo json_encode(array('status'=>'login','url'=>$url));
		}	
	}
		
	//Confirma la postal antes de enviar
	static function getPostal() {
			$postal = (isset($_REQUEST["p"]) ? $_REQUEST["p"] : "");
			$nombre = (isset($_REQUEST["f"]) ? $_REQUEST["f"] : "");
			$apellido = (isset($_REQUEST["l"]) ? $_REQUEST["l"] : "");
			$id = (isset($_REQUEST["i"]) ? $_REQUEST["i"] : "");
			$genero = substr($postal,0,1); //M o F
		
			//Imagen de cada postal
			$img = quePostal($postal);
		
			//Salvo estos datos
			$_SESSION["postal"] = array("tipo"=>"facebook","estado"=>"sin enviar", "id"=>$id, "amigo_nombre"=>$nombre, "amigo_apellido"=>$apellido, "postal"=>$img, "genero"=>$genero);
		
			echo json_encode(array('status'=>'ok', 'postal'=>$img, 'genero'=>$genero));
	}

	static function usuarioFotos() {
		$db = dbaccess::conexionDB();
		$nombre = dbaccess::clean( (isset($_REQUEST["nombre"]) ? $_REQUEST["nombre"] : ""), 200);
		$correo = dbaccess::clean( (isset($_REQUEST["correo"]) ? $_REQUEST["correo"] : ""), 200);
	
		try {
			$_SESSION['foto'] = array("nombre"=>$nombre, "correo"=>$correo);	
			echo json_encode(array('status'=>'ok'));
		} catch (Exception $ex) {
			echo json_encode(array('status'=>'error'));
		}
	}
	
	static function usuarioTrivia() {
		//Salvo la postal en la base de datos
		$db = dbaccess::conexionDB();
		$nombre = dbaccess::clean( (isset($_REQUEST["nombre"]) ? $_REQUEST["nombre"] : ""), 200);
		$correo = dbaccess::clean( (isset($_REQUEST["correo"]) ? $_REQUEST["correo"] : ""), 200);
		
		try {
			$db->query("INSERT INTO trivia
					(`tri_nombre`,`tri_correo`,`tri_fecha`)
					VALUES
					('{$nombre}' ,'{$correo}' ,NOW()      );");
			
			echo json_encode(array('status'=>'ok', 'user'=> $db->insert_id));
		} catch (Exception $ex) {
			echo json_encode(array('status'=>'error'));
		}
	}
	
	static function invitacionTrivia() {
		//Salvo la postal en la base de datos
		$db = dbaccess::conexionDB();
		$nombre = dbaccess::clean( (isset($_REQUEST["nombre"]) ? $_REQUEST["nombre"] : ""), 200);
		$correo = dbaccess::clean( (isset($_REQUEST["correo"]) ? $_REQUEST["correo"] : ""), 200);
		$user = intval( dbaccess::clean( (isset($_REQUEST["user"]) ? $_REQUEST["user"] : ""), 20) );
		
		try {
			$sql = "INSERT INTO invita_trivia
					(`tri_codigo`,`itr_nombre`,`itr_correo`,`itr_fecha`)
					VALUES
					('{$user}'   ,'{$nombre}'  ,'{$correo}' ,NOW()      )";
			$db->query($sql);
			error_log("invitacionTrivia() $sql");
	
			//Envio el mensaje
			$params = array(
					'nombre'=>$nombre,
					'logo'=>config::$appImage
			 );
			$t = new template();
			$body = $t->render($params,"mail_invitacion.html");
			$subject = $nombre . " te manda una invitación a visitar el Paseo de la Historieta";
			mail::sendMessage($correo, $subject, $body);
			
			echo json_encode(array('status'=>'ok'));
		} catch (Exception $ex) {
			echo json_encode(array('status'=>'error'));
		}
	}
	
	
	static function puntajeTrivia() {
		//Salvo la postal en la base de datos
		$db = dbaccess::conexionDB();
		$user = dbaccess::clean( (isset($_REQUEST["user"]) ? $_REQUEST["user"] : ""), 200);
		$puntos = dbaccess::clean( (isset($_REQUEST["puntos"]) ? $_REQUEST["puntos"] : ""), 200);
	
		try {
			$db->query("UPDATE trivia SET tri_puntaje={$puntos} WHERE tri_codigo={$user}");	
			echo json_encode(array('status'=>'ok'));
		} catch (Exception $ex) {
			echo json_encode(array('status'=>'error'));
		}
	}
	
	/**
	 * Envio de una postal a Facebook
	 * 
	 * Cosas que pueden salir mal: access_token vencido, sin sesion de PHP activa, datos faltantes.
	 */
	static function postearFacebook() {
		
		//Salvo la postal en la base de datos
		$db = dbaccess::conexionDB();
		$user = $_SESSION["user_profile"];
		$postal = $_SESSION['postal'];
		$hash = md5($postal['id'].time());
		
		$db->query("INSERT INTO `postales`
					(`pos_hash`,`pos_id`     ,`pos_nombre`         ,`pos_apellido`      ,`pos_amigo_id`   ,`pos_amigo_nombre`         ,`pos_amigo_apellido`         ,`pos_postal`         ,`pos_saludo`, pos_tipo )
					VALUES
					('{$hash}' ,'{$user->id}','{$user->first_name}','{$user->last_name}','{$postal['id']}','{$postal['amigo_nombre']}','{$postal['amigo_apellido']}','{$postal['postal']}',''          , 'Facebook');");
		
		//Posteo la postal en el muro
		$message="Un amigo te envía esta postal en tu día.";
		$url = "https://graph.facebook.com/{$postal['id']}/feed";
		
		//Para DEBUG
		//$url = "https://graph.facebook.com/me/feed";
		
		$data = array(
				'access_token'  => $_SESSION['access_token'],
		        'message'       => $message,
		        'name'          => "Paseo de la historieta",
		        'link'          => config::$appPostal.$hash,
		        'description'   => "Feliz día del amigo!",
		        'picture'       => config::$appImage
		);
		
		$buffer = getFB($url,$data);		
		error_log("Envio a Facebook: ".$buffer);
		
		//Como salio la publicacion?.
		$resp = json_decode($buffer);
		if(isset($resp->id))
			echo json_encode(array('status'=>'ok'));
		else
			echo json_encode(array('status'=>'error', 'error'=>$resp->error->message));
	}
	
	/** Pido los datos para hacer el tweet */
	static function goTwitter() {
		$t = new template();
		echo $t->render(null,"twitter1.html");
	}

	/** Selecciono la postal segun sexo y caracteristica */
	static function preparaTwitter() {
		$postal = dbaccess::clean( (isset($_REQUEST["p"]) ? $_REQUEST["p"] : ""), 5);
		$amigo  = dbaccess::clean( (isset($_REQUEST["a"]) ? $_REQUEST["a"] : ""), 100);
		$twitter= dbaccess::clean( (isset($_REQUEST["t"]) ? $_REQUEST["t"] : ""), 100);
		$genero = substr($postal,0,1); //M o F
		
		//Imagen de cada postal
		$img = quePostal($postal);
		
		//Salvo estos datos
		$_SESSION["postal"] = array("tipo"=>"twitter","estado"=>"sin enviar", "twitter"=>$twitter, "amigo"=>$amigo, "postal"=>$img, "genero"=>$genero);

		echo json_encode(array("status"=>"ok", "genero"=>$genero, "postal"=>$img));
	}
	
	/** Genero el tweet */
	static function enviarTweet() {
		//Hay un tweet pendiente?
		$postal = ( isset($_SESSION['postal']) ? $_SESSION['postal'] : null);
		if(!$postal)
			return json_encode(array("status"=>"error","hash"=>"","amigo"=>"","cuenta"=>""));

		$amigo = $postal['amigo'];
		$cuenta = $postal['twitter'];
		$postal = $postal['postal'];
		
		//Salvo la postal en la base de datos
		$db = dbaccess::conexionDB();
		$hash = md5($amigo.time());
		$db->query("INSERT INTO `postales`
				(`pos_hash`,`pos_id`,`pos_nombre`,`pos_apellido`,`pos_amigo_id`,`pos_amigo_nombre`,`pos_amigo_apellido`,`pos_postal`,`pos_saludo`, pos_tipo)
				VALUES
				('{$hash}',''       ,''          ,''            ,'{$cuenta}'   ,'{$amigo}'        ,''                  ,'{$postal}' ,''          , 'Twitter');");
		
		//URL compacta
		$bt = new Bitly();
		$url = $bt->shorten(config::$appPostal.$hash);
		
		//Retorno la postal
		unset($_SESSION['postal']);
		echo json_encode(array("status"=>"ok","hash"=>$url,"amigo"=>$amigo,"cuenta"=>$cuenta));
	}
	
	static function goTwitter3() {
		$t = new template();
		echo $t->render(null,"twitter3.html");
	}
	
	static function goMail() {
		$t = new template();
		echo $t->render(null,"mail1.html");
	}
	
	static function preparaMail() {
		$postal = dbaccess::clean( (isset($_REQUEST["p"]) ? $_REQUEST["p"] : ""), 100);
		$amigo =  dbaccess::clean( (isset($_REQUEST["a"]) ? $_REQUEST["a"] : ""), 100);
		$correo = dbaccess::clean( (isset($_REQUEST["c"]) ? $_REQUEST["c"] : ""), 200);
		$nombre = dbaccess::clean( (isset($_REQUEST["n"]) ? $_REQUEST["n"] : ""), 100);
		$genero = substr($postal,0,1); //M o F
		
		//Imagen de cada postal
		$img = quePostal($postal);
		
		//Salvo estos datos
		$_SESSION["postal"] = array("tipo"=>"mail","estado"=>"sin enviar", "correo"=>$correo, "amigo"=>$amigo, "postal"=>$img, "genero"=>$genero, "nombre"=>$nombre);
		
		echo json_encode(array('status'=>'ok', 'genero'=>$genero, 'postal'=>$img));
	}
	
	
	/**
	 * Envio de postal por mail. Falta definir la postal.
	 */
	static function enviarMail() {
		$db = dbaccess::conexionDB();
		$postal = $_SESSION['postal'];
		
		/* Cuantas postales ya recibio este mail? No mando mas de 10 */
		$cant = intval( dbaccess::queryString("select count(*) from postales where pos_tipo='Mail' and pos_amigo_id='{$postal['correo']}'", $db));
		if($cant<10) {		
			$hash = md5($postal['correo'].time());
			$db->query("INSERT INTO `postales`
					(`pos_hash`,`pos_id`,`pos_nombre`         ,`pos_apellido` ,`pos_amigo_id`       ,`pos_amigo_nombre`  ,`pos_amigo_apellido` ,`pos_postal`         ,`pos_saludo`, pos_tipo)
					VALUES
					('{$hash}' ,''      ,'{$postal['nombre']}',''             ,'{$postal['correo']}','{$postal['amigo']}',''                   ,'{$postal['postal']}',''          , 'Mail'   );");
			
			//URL compacta
			$bt = new Bitly();
			$url = $bt->shorten(config::$appPostal.$hash);
			
		
			//Envio el mensaje
			$params = array(
					'url'=>$url,
					'app'=>config::$appCanvas,
					'postal'=>"images/mail/postalmail.jpg", 
					'banner'=>"images/mail/piepagina.png" );
			$t = new template();
			$body = $t->render($params,"mail_postal.html");
			$subject = $postal['nombre'] . " te manda una Postal del Paseo de la Historieta";
			mail::sendMessage($postal['correo'], $subject, $body);
			
			echo json_encode(array('status'=>'ok'));
		}
		else
		{
			echo json_encode(array('status'=>'error', 'error'=>'Esta dirección ya ha recibido demasiadas postales.'));
		}
	}
	
	static function goContacto() {
		$t = new template();
		echo $t->render(null,"contacto.html");
	}
	
	static function enviarContacto() {
		$db = dbaccess::conexionDB();
		$mensaje = $_REQUEST['mensaje'];
		$nombre = $_REQUEST['nombre'];
		$mail = $_REQUEST['mail'];
		
		//Limpio los campos
		$nombre = dbaccess::clean($nombre,200);
		$mail = dbaccess::clean($mail,200);
		$mensaje = dbaccess::clean($mensaje);
		
		$db->query("INSERT INTO contactos
				(con_nombre ,con_mail , con_mensaje,con_fecha  )
				VALUES
				('{$nombre}','{$mail}','{$mensaje}',NOW()      )");
		
		//Envio el contacto por mail a quien procesa los contactos
		$t = new template();
		$body = $t->render(array('nombre'=>$nombre,'mail'=>$mail,'mensaje'=>$mensaje, 'logo'=>config::$appImage),"mail_contacto.html");
		
		//mail::sendMessage(config::$mailContacto, "Nuevo contacto ingresado", $body);
		
		echo json_encode(array('status'=>'ok'));
	}
	
	static function goFoto() {
		$t = new template();
		echo $t->render(null,"fotos.html");
	}
	
	static function goWallpaper() {
		$t = new template();
		echo $t->render(null,"wallpaper.html");
	}

	static function goAutores() {
		$t = new template();
		echo $t->render(null,"autores.html");
	}
	
	static function usuarioFacebook() {
		$id = ( isset($_REQUEST['id']) ? $_REQUEST['id'] : "");
		echo json_encode( getFBuserData($id) );
	}
	
// --- Funciones en JSON -------------------------	

	/**
	 * Hace una lista de las fotos cargadas sobre la carpeta /images/fotos
	 * Lo necesita la galeria de fotos, para mostrar el contenido.
	 */
	static function fotosCargadas() {
		echo '[{"image":"..\/images\/fotos\/small_IMG00174-20120718-1034.jpg","link":"..\/images\/fotos\/IMG00174-20120718-1034.jpg","thumb":"..\/images\/fotos\/thumb_IMG00174-20120718-1034.jpg"},{"image":"..\/images\/fotos\/small_IMG00175-20120718-1038.jpg","link":"..\/images\/fotos\/IMG00175-20120718-1038.jpg","thumb":"..\/images\/fotos\/thumb_IMG00175-20120718-1038.jpg"},{"image":"..\/images\/fotos\/small_IMG00177-20120718-1047.jpg","link":"..\/images\/fotos\/IMG00177-20120718-1047.jpg","thumb":"..\/images\/fotos\/thumb_IMG00177-20120718-1047.jpg"},{"image":"..\/images\/fotos\/small_IMG00178-20120718-1053.jpg","link":"..\/images\/fotos\/IMG00178-20120718-1053.jpg","thumb":"..\/images\/fotos\/thumb_IMG00178-20120718-1053.jpg"},{"image":"..\/images\/fotos\/small_IMG_0086.jpg","link":"..\/images\/fotos\/IMG_0086.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0086.jpg"},{"image":"..\/images\/fotos\/small_IMG_0258.jpg","link":"..\/images\/fotos\/IMG_0258.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0258.jpg"},{"image":"..\/images\/fotos\/small_IMG_0260.jpg","link":"..\/images\/fotos\/IMG_0260.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0260.jpg"},{"image":"..\/images\/fotos\/small_IMG_0261.jpg","link":"..\/images\/fotos\/IMG_0261.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0261.jpg"},{"image":"..\/images\/fotos\/small_IMG_0262.jpg","link":"..\/images\/fotos\/IMG_0262.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0262.jpg"},{"image":"..\/images\/fotos\/small_IMG_0263.jpg","link":"..\/images\/fotos\/IMG_0263.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0263.jpg"},{"image":"..\/images\/fotos\/small_IMG_0270.jpg","link":"..\/images\/fotos\/IMG_0270.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0270.jpg"},{"image":"..\/images\/fotos\/small_IMG_0275.jpg","link":"..\/images\/fotos\/IMG_0275.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0275.jpg"},{"image":"..\/images\/fotos\/small_IMG_0277.jpg","link":"..\/images\/fotos\/IMG_0277.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0277.jpg"},{"image":"..\/images\/fotos\/small_IMG_0303.jpg","link":"..\/images\/fotos\/IMG_0303.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0303.jpg"}]';
		
		//Para Kiosk
		//echo '[{"image":"..\/images\/fotos\/small_IMG00174-20120718-1034.jpg","thumb":"..\/images\/fotos\/thumb_IMG00174-20120718-1034.jpg"},{"image":"..\/images\/fotos\/small_IMG00175-20120718-1038.jpg","thumb":"..\/images\/fotos\/thumb_IMG00175-20120718-1038.jpg"},{"image":"..\/images\/fotos\/small_IMG00177-20120718-1047.jpg","thumb":"..\/images\/fotos\/thumb_IMG00177-20120718-1047.jpg"},{"image":"..\/images\/fotos\/small_IMG00178-20120718-1053.jpg","thumb":"..\/images\/fotos\/thumb_IMG00178-20120718-1053.jpg"},{"image":"..\/images\/fotos\/small_IMG_0086.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0086.jpg"},{"image":"..\/images\/fotos\/small_IMG_0258.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0258.jpg"},{"image":"..\/images\/fotos\/small_IMG_0260.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0260.jpg"},{"image":"..\/images\/fotos\/small_IMG_0261.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0261.jpg"},{"image":"..\/images\/fotos\/small_IMG_0262.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0262.jpg"},{"image":"..\/images\/fotos\/small_IMG_0263.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0263.jpg"},{"image":"..\/images\/fotos\/small_IMG_0270.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0270.jpg"},{"image":"..\/images\/fotos\/small_IMG_0275.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0275.jpg"},{"image":"..\/images\/fotos\/small_IMG_0277.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0277.jpg"},{"image":"..\/images\/fotos\/small_IMG_0303.jpg","thumb":"..\/images\/fotos\/thumb_IMG_0303.jpg"}]';
		
		return;
		
		//Cargo el contenido del directorio
		$f = array();
		$folder = pathinfo(__FILE__,PATHINFO_DIRNAME); //Ubicacion de /canvas
		$folder = substr($folder,0,strrpos($folder,DIRECTORY_SEPARATOR)).DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'fotos'.DIRECTORY_SEPARATOR; //Ubicacion raiz
				
		if ($handle = opendir( $folder )) {		
			while (false !== ($entry = readdir($handle))) {
												
				$ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
				if($ext=="jpg" || $ext=="jpeg") {
					if(substr($entry,0,6)!="thumb_" && substr($entry,0,6)!="small_") {	
						$foto = $entry;
						
						//Creo thumb
						if(!file_exists($folder.'thumb_'.$foto))
							getImage($folder.$foto, $folder.'thumb_'.$foto, 128);
						
						//Creo small
						if(!file_exists($folder.'small_'.$foto))
							getImage($folder.$foto, $folder.'small_'.$foto, 600);
						
						$f[] = array(
								"image"	=>	"../images/fotos/small_".$foto, 
								"link"	=>	"../images/fotos/".$foto,
								"thumb"	=>	"../images/fotos/thumb_".$foto
						);
					}
				}
			}
		}
		
		$todas = json_encode($f);
		error_log("fotosCargadas: ".$todas);
		echo $todas;
	}

	/**
	 * Crea un usuario de la App en la base de datos.
	 * 
	 * @param object $user (objeto user de Facebook)
	 */
	static function crearUsuario($user=null) {
		$u = ($user==null ? $_SESSION["user_profile"] : $user);
		$db = dbaccess::conexionDB();
		
		//Existe el registro?
		$cant = dbaccess::queryString("SELECT count(*) FROM usuarios WHERE id='{$u->id}'", $db);
		error_log("crearUsuario -> Existe {$u->id}? Resultado:$cant");
		
		if($cant!="0") {
			//Actualizo el usuario existente
			error_log("crearUsuario -> Actualizo perfil de {$u->id}");
			$r = $db->query("UPDATE usuarios SET app_ultimo=NOW() WHERE id='{$u->id}'");
		} else {				
			//Creo uno nuevo
			error_log("crearUsuario -> Nuevo perfil para {$u->id}");
			$r = $db->query("INSERT INTO usuarios
						(id        ,name        ,first_name        ,middle_name ,last_name        ,link        ,gender        ,timezone        ,locale        ,updated_time        ,app_ingreso,app_ultimo, email       )
						VALUES
						('{$u->id}','{$u->name}','{$u->first_name}',''          ,'{$u->last_name}','{$u->link}','{$u->gender}','{$u->timezone}','{$u->locale}','{$u->updated_time}',NOW()      ,NOW()     ,'{$u->email}')");
		}					

		//Como salio la consulta?
		if($r===false) {
			error_log("crearUsuario -> Error en la consulta: ".$db->error);
		}
	}
	
}

//------------------------ FUNCIONES DE FACEBOOK ------------------------------------------
/**
 * Autenticacion de OAuth con Facebook
 * @param string $code (codigo retornado por la llamada de autorizacion de la App)
 * @param string $url (url usada en la llamada de autorizacion)
 */
function oauthFB($code, $url) {
	
	$token_url = "https://graph.facebook.com/oauth/access_token?"
	. "client_id=" . config::$appId
	. "&client_secret=" . config::$appSecret
	. "&redirect_uri=" . urlencode($url)
	. "&code=" . $code;
	
	$response = getFB($token_url);
	
	$params = null;
	parse_str($response, $params);
	
	if( isset($params['access_token']) ) {
		$_SESSION['access_token'] = $params['access_token'];
		$_SESSION['access_token_exp'] = time() + $params['expires'];
		return true;
	} else {
		unset($_SESSION['access_token']);
		return false;
	}
}

/**
 * Retorna un objeto con los datos del usuario pedido.
 * @param string $id (user id de facebook o "me")
 */
function getFBuserData($id) {
	$graph_url = "https://graph.facebook.com/{$id}?access_token=".$_SESSION['access_token'];
	return json_decode(getFB($graph_url));	
}

/**
 * Conexion al server de Facebook
 * @param string $url
 * @param array $data (solo para el caso de POST, es opcional)
 */
function getFB($url,$data=null) {
	error_log("getFB($url)");
	try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		if(is_array($data)) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$go = curl_exec($ch);
		curl_close ($ch);	

		error_log("getFB(...) => $go");
		return $go;
	} 
	catch(Exception $e) {
		error_log("getFB(...) Error: $e");
		return "";
	}
}

/**
 * Determina que postal le corresponde al genero y caracteristica elegida
 * @param string $carac
 */
function quePostal($carac) {
	$img = "";
	switch($carac){
		case "M1":
			$img = "clemente_pos.jpg";
			break;
		case "M2":
			$img = "patoruzu_pos.jpg";
			break;
		case "M3":
			$img = "isidoroycachorra_pos.jpg";
			break;
		case "M4":
			$img = "donfulgencio_pos.jpg";
			break;
		case "M5":
			$img = "drmerengue_pos.jpg";
			break;
		case "F1":
			$img = "pochitamorfoni_pos.jpg";
			break;
		case "F2":
			$img = "gaturroynovia_pos.jpg";
			break;
		case "F3":
			$img = "chicasdivito_pos.jpg";
			break;
		case "F4":
			$img = "cachorra_pos.jpg";
			break;
		case "F5":
			$img = "chachamama_pos.jpg";
	}
	return $img;
}


function getImage($file_in, $file_out, $size )
{
	$extension = strtolower(pathinfo($file_in, PATHINFO_EXTENSION));
	
	try {
	if($extension=="png")
		$im = imagecreatefrompng($file_in);
	else
		$im = imagecreatefromjpeg($file_in);
	} catch(Exception $e) {
		return;
	}
	
	//Dimension de la foto original
	$x = imagesx($im);
	$y = imagesy($im);

	//Radios de ajuste para llegar al tamaño deseado
	if($x>0 && $y>0) {
		$kx = $size / $x;
		$ky = $size / $y;
		$k = ($kx>$ky ? $ky : $kx);

		//Nuevas dimensiones del thumb
		$nx = intval($x * $k,10);
		$ny = intval($y * $k,10);

		if($extension=="png")
			$nim = imagecreate($nx,$ny);
		else
			$nim = imagecreatetruecolor($nx,$ny);
	
		imagecopyresampled($nim, $im, 0, 0, 0, 0, $nx , $ny , $x , $y);
	
		if($extension=="png")
			imagepng($nim,$file_out);
		else
			imagejpeg($nim,$file_out);
	}
}


?>