<?php 
/*
 Mâgico
 http://www.parleboo.com
 Copyright 2012 Leandro Garber <leandrogarber@gmail.com>
 Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0)
*/

if ( ! defined ( 'BASEPATH' ) ) exit ( 'No direct script access allowed.' );

class SiteUser {
    
	const LOGGED_IN = 1;
	const NOT_LOGGED_IN = 2;
	const NOT_ACTIVE = 3;
	
	const USER_SESSION = 'usuario';
    
    const TABLE_USUARIOS = 'usuarios';
    const TABLE_USUARIOS_DIRECCIONES = 'usuarios_direcciones';
    const TABLE_DISTRIBUIDORES = 'distribucion';
    const TABLE_DISTRIBUIDORES_DIRECCIONES = 'distribucion_direcciones';
	
	private $ci;
    
    var $transDistribuidorFields = array(
      'id' => 'COD_DISTRIBUIDOR',
      'nombre' => 'NOMBRE',
      'apellidos' => 'APELLIDOS',
      'fechadenacimiento' => 'NACIMIENTO',
      'direccion' => 'DIRECCION',
      'cp' => 'CP',
      'provincia' => 'PROVINCIA',
      'poblacion' => 'POBLACION',
      'pais' => 'PAIS',
      'telefono' => 'TELEFONO',
      'email' => 'EMAIL',
      'pass' => 'PASS'
    );
	
	function __construct()
	{
		$this->ci =& get_instance();
	}
	
	function login($user, $pass, $distribuidor = false)
	{
		$pass = sha1($pass);
        
        if ( !$distribuidor )
        {
            $table = self::TABLE_USUARIOS;
            $sql = "SELECT * FROM $table WHERE email=? AND pass=? AND tipo = ? LIMIT 1";
            $user = $this->ci->db->query($sql,array($user,$pass, 'Normal'))->row_array();
        }
        else
        {
            $table = self::TABLE_DISTRIBUIDORES;
            $sql = "SELECT * FROM $table WHERE EMAIL=? AND PASS=? LIMIT 1";
            $user = $this->ci->db->query($sql,array($user,$pass))->row_array();
            
            if ( !count($user) )
                return self::NOT_LOGGED_IN;
                
            if ( $user['AUTORIZADO'] != '1' )
                return self::NOT_ACTIVE;
            
            $user = $this->translateFields($user, true);
            $user['tipo'] = 'Distribuidor';
            $this->ci->cart->destroy(); //por si habia algo en el carrito de usuario normal
        }
		
		
		if ($user)
		{
            $this->setUserData($user);
			
			/*if ($remember)
				setcookie(self::AUTOLOGIN_COOKIE_NAME, base64_encode($user->user.':'.$pass), time()+60*60*24*30, '/'); //30 días
			*/
			
			return self::LOGGED_IN;
		}
		else
		{
			return self::NOT_LOGGED_IN;
		}
	}
	
	public function setUserData($arrUser)
	{
		$_SESSION[self::USER_SESSION] = $arrUser;
	}
	
	public function getUserData($key = null)
	{
		if ( $this->isLogged() )
		{
			if ( $key )
				return $_SESSION[self::USER_SESSION][$key];
			else
				return $_SESSION[self::USER_SESSION];
		}
		else
			return null;
	}
	
	public function esDistribuidor()
	{
		return $this->getUserData('tipo') == 'Distribuidor';
	}
	
	public function esDistribuidorLogged()
	{
		return $this->isLogged() && $this->esDistribuidor();
	}
	
	public function isLogged()
	{
		return isset($_SESSION[self::USER_SESSION]);
	}
	
	function cookieCheck()
	{
		/*if ( $_COOKIE[self::AUTOLOGIN_COOKIE_NAME] )
		{
			$autoLogin = base64_decode($_COOKIE[self::AUTOLOGIN_COOKIE_NAME]);
			list($user, $pass) = explode(':',$autoLogin);
			
			$sql = "SELECT * FROM admins WHERE user=? AND password=? LIMIT 1";
			$user = $this->ci->db->query($sql,array($user,$pass))->row();

			if ($user)
			{
				$_SESSION['admin'] = 1;
				return self::LOGGED_IN;
			}
		}
		
		return self::NOT_LOGGED_IN;*/
	}
	
	function logout()
	{
		unset($_SESSION[self::USER_SESSION]);
		$this->ci->cart->destroy();
		//setcookie(self::AUTOLOGIN_COOKIE_NAME,'',time() - 3600, '/');
	}
    
    private function translateFields($data, $reverse = false)
    {
        $newData = array();
            
        if ( !$reverse )
        {
            foreach( $data as $field => $value )
            {
                if ( isset($this->transDistribuidorFields[$field]) )
                    $newData[ $this->transDistribuidorFields[$field] ] = $value;
            }
        }
        else
        {
            foreach( $data as $field => $value )
            {
                $search = array_search($field, $this->transDistribuidorFields);
                
                if ( $search !== false )
                    $newData[ $search ] = $value;
            }
        }
        
        
        return $newData;
    }
    
    public function addUser($data)
    {
        if ( $data['tipo'] == 'Normal' )
        {
            $this->ci->db->insert(self::TABLE_USUARIOS, $data);
        }
        else
        {
            $this->ci->db->insert(self::TABLE_DISTRIBUIDORES, $this->translateFields($data) );
        }
        
        return $this->ci->db->insert_id();
    }
    
    public function editUser($data)
    {
        if ( $this->isLogged() )
        {
            $data['id'] = $this->getUserData('id');
            
            if ( $data['tipo'] == 'Normal' )
            {
                $this->ci->db->update(self::TABLE_USUARIOS, $data, array('id' => $data['id'] ));
            }
            else
            {
                $this->ci->db->update(self::TABLE_DISTRIBUIDORES, $this->translateFields($data), array('COD_DISTRIBUIDOR' => $data['id'] ));
            }
        }
        
        $this->setUserData( $data );
    }
    
    public function addDirecciones($data, $tipos = array('Entrega', 'Facturación'))
    {
        $table = null;
        
        if ( $data['tipo'] == 'Normal' )
            $table = self::TABLE_USUARIOS_DIRECCIONES;
        else
            $table = self::TABLE_DISTRIBUIDORES_DIRECCIONES;
        
        unset($data['tipo']);
        
        
        foreach( $tipos as $tipo )
        {
            $data['tipoDireccion'] = $tipo;
            $this->ci->db->insert($table, $data);
        }
    }
    
    public function addDireccionFromUserData($tipo)
    {
        $data = $this->getUserData();
        unset($data['email']);
        unset($data['pass']);
        unset($data['fechadenacimiento']);
        $data['idUsuario'] = $data['id'];
        unset($data['id']);
        
        $this->addDirecciones($data, array($tipo));
        
        return $data;
    }
    
    public function editDireccion($data, $id)
    {
        $table = null;
        
        if ( !$this->esDistribuidor() == 'Normal' )
            $table = self::TABLE_USUARIOS_DIRECCIONES;
        else
            $table = self::TABLE_DISTRIBUIDORES_DIRECCIONES;
        
        $this->ci->db->update($table, $data, array('id' => $id));
    }
    
    public function getDireccion( $tipo )
    {
        $table = null;
        
        if ( !$this->esDistribuidor() )
            $table = self::TABLE_USUARIOS_DIRECCIONES;
        else
            $table = self::TABLE_DISTRIBUIDORES_DIRECCIONES;
        
        $direccion = $this->ci->db->get_where($table, array('tipoDireccion' => $tipo, 'idUsuario' => $this->getUserData('id')))->row_array();
        
        if ( count($direccion) )
            return $direccion;
        else
            return $this->addDireccionFromUserData ($tipo);
    }
    
    public function olvidoPass($email)
    {
        $usuario = $this->ci->db->get_where(self::TABLE_USUARIOS, array('email' => $email))->row_array();
        
        if ( count($usuario) )
        {
            $pass = $this->randomPassword();
            $this->ci->db->update(self::TABLE_USUARIOS, array('pass' => sha1($pass)), array('id' => $usuario['id']));
            magico_sendmail($usuario['email'], 'Larome Perfumes - Nueva contraseña', 'Su nueva contraseña es: ' . $pass, 'no-reply@perfumeslarome.com');
            return true;
        }
        else
        {
            $usuario = $this->ci->db->get_where(self::TABLE_DISTRIBUIDORES, array('email' => $email))->row_array();
            
            if ( count($usuario) )
            {
                $pass = $this->randomPassword();
                $this->ci->db->update(self::TABLE_DISTRIBUIDORES, array('PASS' => sha1($pass)), array('COD_DISTRIBUIDOR' => $usuario['COD_DISTRIBUIDOR']));
                magico_sendmail($usuario['EMAIL'], 'Larome Perfumes - Nueva contraseña', 'Su nueva contraseña es: ' . $pass, 'no-reply@perfumeslarome.com');
                return true;
            }
            else
                return false;
        }
            
    }
    
    public function checkEmail($email)
    {
        $checkUsuarios = $this->ci->db->get_where(self::TABLE_USUARIOS, array('email' => $email))->row_array();
        
        if ( count($checkUsuarios) )
            return $checkUsuarios;
        
        $checkDistribuidores = $this->ci->db->get_where(self::TABLE_DISTRIBUIDORES, array('EMAIL' => $email))->row_array();
        
        if ( count($checkDistribuidores) )
            return $this->translateFields ($checkDistribuidores, true);
        
        return array();
    }
    
    private function randomPassword()
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr( str_shuffle( $chars ), 0, 6 );
		return $password;
	}
}
?>