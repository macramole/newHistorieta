<?php

class popMessage 
{
	public $m_headers;
	public $m_subject;
	public $m_body;
	public $m_body_alt;
	public $m_parts;
	public $m_from;
	
	public function build(&$msg)
	{
		$this->m_body="";
		$this->m_body_alt="";
		$this->m_parts = array();
		
		$p = strpos($msg,"\r\n\r\n");
		$headers = substr($msg,0,$p);
		$body = substr($msg,$p+4);
				
		//Recupero headers
		$this->m_headers = array_change_key_case(iconv_mime_decode_headers($headers, 0, "UTF-8"));
				
		//Determino el subject
		$this->m_subject = ( array_key_exists("subject",$this->m_headers) ? trim($this->m_headers["subject"]) : "" );

		//Determino el from
		$this->m_from = ( array_key_exists("from",$this->m_headers) ? trim($this->m_headers["from"]) : "" );
		
		//Como es el Content-Transfer-Encoding?
		$transfer = strtolower( trim( array_key_exists("content-transfer-encoding", $this->m_headers) ? $this->m_headers["content-transfer-encoding"] : "7bit" ));
		
		//Que tipo de mensaje es?
		if( array_key_exists("content-type", $this->m_headers) )
		{	
			//Determino el charset usado
			$contentType = $this->m_headers['content-type'];
			$encoding = $this->getEncoding($contentType,"ISO-8859-1");
			
			//Tipo MIME del cuerpo
			$mime = $this->getMime($contentType);	
			if( $mime=="multipart/mixed" || $mime=="multipart/report" || $mime=="multipart/alternative" )
			{
				//Mensaje multiparte, cual es el separador?
				$boundary = $this->getBoundary($contentType);
				if( $boundary!="" )
				{
					echo "MULTIPART detectado, separador: $boundary \n";	
					$begin = strpos($body,$boundary);
					$end = 0;
					$j=1;
					while($begin!==false)
					{
						$end = strpos($body,$boundary,$begin+1);
						if($end===false)
						{
							break;
						}
						echo "PARTE #$j de $begin a $end ----- \n";
						$part = trim(substr($body,$begin+strlen($boundary),$end-$begin-strlen($boundary)));	
						
						//Es la parte del body o es un archivo adjunto??
						$p = strpos($part,"\r\n\r\n");
						$p_headers = substr($part,0,$p);
						$p_body = substr($part,$p+4);

						//Decodifico los headers
						$_headers = array_change_key_case(iconv_mime_decode_headers($p_headers, 0, "UTF-8"));
		
						//Tipo MIME del header
						$_contentType = ( array_key_exists("content-type", $_headers) ? $_headers['content-type'] : "text/plain" );
						$_mime = $this->getMime($_contentType);
						
						//La parte tiene un encoding diferente?
						$_encoding = $this->getEncoding($_contentType,$encoding);
			
						//La parte tiene un transfer diferente?
						$_transfer = strtolower( trim( array_key_exists("content-transfer-encoding", $_headers) ? $_headers["content-transfer-encoding"] : $transfer ));
						
						echo "    mime=$_mime\n";
						echo "    transfer=$_transfer\n";
						
						//Proceso la parte segun el tipo MIME
						if( $_mime == "text/html" && $this->m_body=="" )
						{
							$this->m_body = $this->transferAdjust($p_body,$_transfer,$_encoding);			
							echo "    set body\n";
						}
						elseif( $_mime == "text/plain" && $this->m_body_alt=="" )
						{
							$this->m_body_alt = $this->transferAdjust($p_body,$_transfer,$_encoding);
							echo "    set body_alt\n";			
						}
						elseif( $_mime == "multipart/related" || $_mime == "multipart/mixed" )
						{
							//El body html suele venir como una parte del multipart
							$parts = $this->decodeMultipart($p_body, $_headers, $encoding, $transfer);
							$this->m_parts = array_merge($this->m_parts, $parts);
							
							//Si no esta seteado el body, busco la parte mas probable
							if($this->m_body=="")
							{
								foreach($parts as $part)
								{
									if($part->m_mime_type=="text/html")
									{
										$this->m_body = $part->m_content;
										echo "    set body\n";
										break;
									}
								}
							}
						}
						else //Es un adjunto cualquiera
						{
							$mp = new popMessagePart();
							$mp->m_content = $this->transferAdjust($p_body,$_transfer,$_encoding);		
							$mp->m_headers = $_headers;
							$mp->m_mime_type = $_mime;	
						}
						
						//Paso a la siguiente parte del mensaje
						$begin = $end;		
						$j++;			
					}
				}
				else
				{
					echo "ERROR no esta definido el boundary en el mensaje multi-part \n";
					print_r($this->m_headers);
					echo "\n";
				}
			}
			else
			{
				//Mensaje simple
				$this->m_body = $this->transferAdjust($body,$transfer,$encoding);
				$this->m_body_alt = $this->m_body;
			}		
		}
		else
		{
			//Si no tiene un content-type, entonces debe ser un mensaje de texto basico
			$this->m_body = $this->transferAdjust($body,$transfer,"ISO-8859-1");
			$this->m_body_alt = $this->m_body;
		}
	}
	
	//Retorna un array con las partes, que son objetos tipo "popMessagePart"
	private function decodeMultipart(&$body, &$headers, $encoding, $transfer)
	{
		$ret = array();
		$contentType = $headers['content-type'];
		$boundary = $this->getBoundary($contentType);
		if($boundary!="")
		{
			$begin = strpos($body,$boundary);
			$end = 0;
			$j=1;
			while($begin!==false)
			{
				$end = strpos($body,$boundary,$begin+1);
				if($end===false)
				{
					break;
				}
				echo "  SUB PARTE #$j de $begin a $end ----- \n";
				$part = trim(substr($body,$begin+strlen($boundary),$end-$begin-strlen($boundary)));	
				
				//Es la parte del body o es un archivo adjunto??
				$p = strpos($part,"\r\n\r\n");
				$p_headers = substr($part,0,$p);
				$p_body = substr($part,$p+4);

				//Decodifico los headers
				$_headers = array_change_key_case(iconv_mime_decode_headers($p_headers, 0, "UTF-8"));

				//Tipo MIME del header
				$_contentType = ( array_key_exists("content-type", $_headers) ? $_headers['content-type'] : "text/plain" );
				$_mime = $this->getMime($_contentType);
				
				//La parte tiene un encoding diferente?
				$_encoding = $this->getEncoding($_contentType,$encoding);
	
				//La parte tiene un transfer diferente?
				$_transfer = strtolower( trim( array_key_exists("content-transfer-encoding", $_headers) ? $_headers["content-transfer-encoding"] : $transfer ));

				//Content-Disposition
				$_contentDisposition = $this->getDisposition($_headers);
				
				//Attachment Name
				$name = $this->getName($_contentType);
				
				echo "    mime=$_mime\n";
				echo "    transfer=$_transfer\n";
				echo "    name=$name\n";	
				echo "    disposition=$_contentDisposition\n";	
				
				//Los archivos binarios no deben recodificarse a UTF-8
				if( substr($_mime,0,4)!="text" )
				{
					$_encoding = "";
				}
				
				$mp = new popMessagePart();
				$mp->m_content = $this->transferAdjust($p_body,$_transfer,$_encoding);		
				$mp->m_headers = $_headers;
				$mp->m_mime_type = $_mime;	
				$mp->m_name = $name;
				$mp->m_file_name = $this->getFileName($_contentDisposition,$name);
				
				$ret[] = $mp;
				
				//Paso a la siguiente parte del mensaje
				$begin = $end;		
				$j++;			
			}						
		}
		return $ret;
	}
	
	private function transferAdjust(&$data,$format,$encoding="")
	{
		if($format=="quoted-printable")
		{
			if($encoding!="")
			{
				return iconv($encoding,"UTF-8", quoted_printable_decode($data));
			}
			else
			{
				return quoted_printable_decode($data);
			}
		}
		else
		if($format=="base64")
		{
			if($encoding!="")
			{
				return iconv($encoding,"UTF-8", base64_decode($data));
			}
			else
			{
				return base64_decode($data);
			}
		}
		else
		{	// 7bit 8bit etc.
			if($encoding!="")
			{
				return iconv($encoding,"UTF-8",$data);	
			}
			else
			{
				return $data;
			}
		}
	}
	
	
	private function getEncoding($header,$default)
	{
		$encoding = "";
		$parts = explode(";",strtolower($header));
		foreach($parts as $part)
		{
			$e = explode("=",$part,2);
			if( trim($e[0]) == "charset" )
			{
				$encoding = $e[1]; 		
				break;	
			}
		}
		
		return ($encoding=="" ? $default : $encoding);
	}
	
	
	private function getDisposition($headers)
	{
		$disposition = trim(array_key_exists("content-disposition", $headers) ? $headers['content-disposition'] : "inline");
		$p = strpos($disposition,";");
		if( $p!==false )
		{
			$disposition = substr($disposition,0,$p);
		}		
		return $disposition;
	}
	
	
	private function getBoundary($header)
	{
		$parts = explode(";",$header);
		foreach($parts as $part)
		{
			$e = explode("=",$part,2);
			if( trim(strtolower($e[0])) == "boundary" )
			{
				return "--".$e[1];
			}
		}
		
		return "";
	}
	
	//Procesa el content-type
	private function getName($header)
	{
		$parts = explode(";",$header);
		foreach($parts as $part)
		{
			$e = explode("=",$part,2);
			if( trim(strtolower($e[0])) == "name" )
			{
				return trim(str_replace('"','',$e[1]));
			}
		}
		return "";
	}

	//Procesa el content-disposition
	private function getFileName($header,$default)
	{
		$parts = explode(";",$header);
		foreach($parts as $part)
		{
			$e = explode("=",$part,2);
			if( trim(strtolower($e[0])) == "filename" )
			{
				return trim(str_replace('"','',$e[1]));
			}
		}
		return $default;
	}
	
	private function getMime($header)
	{
		$e = explode(";",$header);
		return trim($e[0]);
	}
}

class popMessagePart 
{
	public $m_headers;
	public $m_mime_type;
	public $m_content;
	public $m_name;
	public $m_file_name; 
	
	public function Save($name="",$folder="")
	{
		if($folder!="" && substr($folder,-1)!="/")
		{
			$folder = $folder."/";
		}
				
		$arch = ($folder=="" ? HOME_PATH."temp/" : $folder).($name=="" ? $this->m_file_name : $name);
		file_put_contents($arch,$this->m_content);
	}
}

class POP3 {
  /**
   * Default POP3 port
   * @var int
   */
  public $POP3_PORT = 110;

  /**
   * Default Timeout
   * @var int
   */
  public $POP3_TIMEOUT = 30;

  /**
   * POP3 Carriage Return + Line Feed
   * @var string
   */
  public $CRLF = "\r\n";

  /**
   * Displaying Debug warnings? (0 = now, 1+ = yes)
   * @var int
   */
  public $do_debug = 2;

  /**
   * POP3 Mail Server
   * @var string
   */
  public $host;

  /**
   * POP3 Port
   * @var int
   */
  public $port;

  /**
   * POP3 Timeout Value
   * @var int
   */
  public $tval;

  /**
   * POP3 Username
   * @var string
   */
  public $username;

  /**
   * POP3 Password
   * @var string
   */
  public $password;

  /**#@+
   * @access private
   */
  private $pop_conn;
  private $connected;
  private $error;     //  Error log array
  /**#@-*/

  /**
   * Constructor, sets the initial values
   * @access public
   * @return POP3
   */
  public function __construct() 
  {
    $this->pop_conn  = 0;
    $this->connected = false;
    $this->error     = null;
  }

  /**
   * Combination of public events - connect, login, disconnect
   * @access public
   * @param string $host
   * @param integer $port
   * @param integer $tval
   * @param string $username
   * @param string $password
   */
  public function Authorise ($host, $port = false, $tval = false, $username, $password, $debug_level = 0) 
  {
    $this->host = $host;

    //  If no port value is passed, retrieve it
    if ($port == false) 
    {
      $this->port = $this->POP3_PORT;
    } 
    else 
    {
      $this->port = $port;
    }

    //  If no port value is passed, retrieve it
    if ($tval == false) 
    {
      $this->tval = $this->POP3_TIMEOUT;
    } 
    else 
    {
      $this->tval = $tval;
    }

    $this->do_debug = $debug_level;
    $this->username = $username;
    $this->password = $password;

    //  Refresh the error log
    $this->error = null;

    //  Connect
    $result = $this->Connect($this->host, $this->port, $this->tval);

    if ($result) 
    {
      $login_result = $this->Login($this->username, $this->password);

      if ($login_result) 
      {
        $this->Disconnect();
        return true;
      }
    }

    //  We need to disconnect regardless if the login succeeded
    $this->Disconnect();
    return false;
  }

  /**
   * Connect to the POP3 server
   * @access public
   * @param string $host
   * @param integer $port
   * @param integer $tval
   * @return boolean
   */
  public function Connect ($host, $port = false, $tval = 30) 
  {
    //  Are we already connected?
    if ($this->connected) 
    {
      return true;
    }

    /*
    On Windows this will raise a PHP Warning error if the hostname doesn't exist.
    Rather than supress it with @fsockopen, let's capture it cleanly instead
    */

    set_error_handler(array(&$this, 'catchWarning'));

    //  Connect to the POP3 server
    $this->pop_conn = fsockopen($host,    //  POP3 Host
                  $port,    //  Port #
                  $errno,   //  Error Number
                  $errstr,  //  Error Message
                  $tval);   //  Timeout (seconds)

    //  Restore the error handler
    restore_error_handler();

    //  Does the Error Log now contain anything?
    if ($this->error && defined("DEBUG")) 
    {
      $this->displayErrors();
    }

    //  Did we connect?
    if ($this->pop_conn == false) 
    {
      //  It would appear not...
      $this->error = array(
        'error' => "Failed to connect to server $host on port $port",
        'errno' => $errno,
        'errstr' => $errstr
      );

      if (defined("DEBUG")) 
      {
      	echo "Conexion a server falló";
        $this->displayErrors();
      }

      return false;
    }

    //  Increase the stream time-out

    //  Check for PHP 4.3.0 or later
    if (version_compare(phpversion(), '4.3.0', 'ge')) 
    {
      stream_set_timeout($this->pop_conn, $tval, 0);
    } 
    else 
    {
      //  Does not work on Windows
      if (substr(PHP_OS, 0, 3) !== 'WIN') 
      {
        socket_set_timeout($this->pop_conn, $tval, 0);
      }
    }

    //  Get the POP3 server response
    $pop3_response = $this->getResponse();

    //  Check for the +OK
    if ($this->checkResponse($pop3_response)) 
    {
    	//  The connection is established and the POP3 server is talking
    	$this->connected = true;
      	return true;
    }

  }

  /**
   * Login to the POP3 server (does not support APOP yet)
   * @access public
   * @param string $username
   * @param string $password
   * @return boolean
   */
  public function Login($username='', $password='') 
  {
  		if($this->connected == false) 
	    {
		      $this->error = 'Not connected to POP3 server';
		
		      if (defined("DEBUG")) 
		      {
		        $this->displayErrors();
		      }
	    }
	
	    if(empty($username)) 
	    {
		      $username = $this->username;
	    }
	
	    if(empty($password)) 
	    {
		      $password = $this->password;
	    }
	
	    $pop_username = "USER $username".$this->CRLF;
	    $pop_password = "PASS $password".$this->CRLF;
	
	    //Send the Username
	    $this->sendString($pop_username);
	    $pop3_response = $this->getResponse();
	
	    if($this->checkResponse($pop3_response)) 
	    {
		      //Send the Password
		      $this->sendString($pop_password);
		      $pop3_response = $this->getResponse();
		
		      if ($this->checkResponse($pop3_response)) 
		      {
		        	return true;
		      } 
		      else 
		      {
		        	return false;
		      }
	    } 
	    else 
	    {
	   		return false;
	    }
  }

  /**
   * Disconnect from the POP3 server
   * @access public
   */
  public function Disconnect () 
  {
    $this->sendString('QUIT'.$this->CRLF);
	$c = $this->getResponse();
    fclose($this->pop_conn);
  }

  
  
  public function ListAll()
  {
  	if ($this->connected == false) 
	{
      	$this->error = 'Not connected to POP3 server';
      	return array();
    }

    //Send the List
    $this->sendString("LIST".$this->CRLF);
    $r = "";
    while( true )
    {
    	$c = $this->getResponse();
    	$r.=$c;
    	if( substr($r,-5)=="\r\n.\r\n" )
    	{
    		break;
    	}
    }
    
    //Proceso la respuesta
	$lineas = explode("\r\n",$r);
	if( substr($lineas[0],0,3)!="+OK" )
	{
      	$this->error = 'Cannot list messages';
      	return array();
	}
	
	return $lineas;
  }
  
  public function Delete($msg_id)
  {
  	if ($this->connected == false) 
	{
      	$this->error = 'Not connected to POP3 server';
      	return false;
    }

    //Send the Delete Command
    $this->sendString("DELE $msg_id".$this->CRLF);
    $c = $this->getResponse();
    
    //Proceso la respuesta
	if( strpos($c,"+OK")===false )
	{
      	$this->error = 'Cannot DELETE message';
      	return false;
	}
	
	return true;
  }
  
  public function Retrieve($msg_id)
  {
	if ($this->connected == false) 
	{
      	$this->error = 'Not connected to POP3 server';
      	return null;
    }

    //Send the RETRIEVE Command
    $this->sendString("RETR $msg_id".$this->CRLF);
    
    //Transfiero la respuesta
    $r = "";
    while( true )
    {
    	$c = $this->getResponse();
    	if( $c=="" )
    	{
    		break;
    	}
    	$r.=$c;
    	if( substr($r,-5)=="\r\n.\r\n" )
    	{
    		break;
    	}
    }
  
    //Proceso la respuesta
	if( substr($r,0,3)!="+OK" )
	{
      	$this->error = 'Cannot RETRIEVE message';
      	return null;
	}
	
	return $r;
  }
  
  /////////////////////////////////////////////////
  //  Private Methods
  /////////////////////////////////////////////////

  /**
   * Get the socket response back.
   * $size is the maximum number of bytes to retrieve
   * @access private
   * @param integer $size
   * @return string
   */
  private function getResponse ($size = 256) 
  {
    $pop3_response = fgets($this->pop_conn, $size);

    if(defined("DEBUG"))
    {
    	//echo "<- $pop3_response \n";	
    }
    
    return $pop3_response;
  }

  /**
   * Send a string down the open socket connection to the POP3 server
   * @access private
   * @param string $string
   * @return integer
   */
  private function sendString ($string) 
  {
  	if(defined("DEBUG"))
  	{
  		echo "->$string \n";
  	}
    $bytes_sent = fwrite($this->pop_conn, $string, strlen($string));
    return $bytes_sent;
  }

  /**
   * Checks the POP3 server response for +OK or -ERR
   * @access private
   * @param string $string
   * @return boolean
   */
  private function checkResponse ($string) 
  {
    if (substr($string, 0, 3) !== '+OK') 
    {
      $this->error = array(
        'error' => "Server reported an error: $string",
        'errno' => 0,
        'errstr' => ''
      );

      if (defined("DEBUG")) 
      {
      	$this->displayErrors();
      }

      return false;
    } 
    else 
    {
      return true;
    }
  }

  /**
   * If debug is enabled, display the error message array
   * @access private
   */
  private function displayErrors () 
  {
    foreach ($this->error as $single_error) 
    {
      print_r($single_error);
      echo '\n';
    }
  }

  /**
   * Takes over from PHP for the socket warning handler
   * @access private
   * @param integer $errno
   * @param string $errstr
   * @param string $errfile
   * @param integer $errline
   */
  private function catchWarning ($errno, $errstr, $errfile, $errline) 
  {
    $this->error[] = array(
      'error' => "Connecting to the POP3 server raised a PHP warning: ",
      'errno' => $errno,
      'errstr' => $errstr
    );
  }

  //  End of class
}
?>
