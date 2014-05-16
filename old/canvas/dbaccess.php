<?php
class dbaccess {
	static function conexionDB() {
		return new mysqli(config::$db_host, config::$db_user, config::$db_password,config::$db_database);
	}
	
	static function queryString( $sql, $db = null ) {
		if($db==null)
			$db = self::conexionDB();
	
		$rs = $db->query($sql);
		if($rs) {
			$row = $rs->fetch_array();
			return $row[0];
		}
	
		return "";
	}
	
	static function queryArray( $sql, $db = null ) {
		if($db==null)
			$db = self::conexionDB();
	
		$rs = $db->query($sql);
		if($rs) {
			return $rs->fetch_array();
		}
	
		return array();
	}
	
	static function insertID($db) {
		return $db->insert_id;
	}
	
	/**
	 * Limpio el campo de cualquier porqueria que sea SQL Inyection
	 * Tambien controlo que no se zarpe la longitud del campo
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $length
	 */
	static function clean($data, $length=0) {
		$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
		$data = str_replace(
				array("'" ,"//","--","/*"  ), 
				array("''","/ ","- ","/ * "), 
				$data);
		if($length>0)
			$data = substr($data, 0, $length);
		return $data;
	}
}