<?php 
class Bitly {

	private $login;
	private $appkey;

	function __construct($login=null, $appkey=null) {
		$this->login = ($login!=null ? $login : config::$bitlyUser);
		$this->appkey = ($appkey!=null ? $appkey : config::$bitlyKey);
	}

	function shorten($url) {
		return $this->curl_get_result("http://api.bit.ly/v3/shorten?login={$this->login}&apiKey={$this->appkey}&uri=".urlencode($url)."&format=txt");
	}

	function expand($url) {
		return $this->curl_get_result("http://api.bit.ly/v3/expand?login={$this->login}&apiKey={$this->appkey}&shortUrl=".urlencode($url)."&format=txt");
	}

	private function curl_get_result($url) {
		$ch = curl_init();
		$timeout = 15;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		
		
		if($data===false) {
			error_log("Bitly::curl_get_result($url) ".curl_error($ch) );
			$data = $url;
		}
		curl_close($ch);
		
		return $data;
	}
}

?>