<?php
class template {
	private $content = array();
	private $template_file = "";
	
	function setTemplate($template) {
		if($template!="") {
			$path = dirname(dirname(__FILE__))."/templates/";
			$this->template_file = $path.$template;
		}
	}
	
	function setContent($content) {
		$this->content = $content;
	}
	
	function addContent($content,$value="") {
		if(is_array($content))
			$this->content = array_merge($this->content, $content);
		
		if(is_string($content))
			$this->content[$content]=$value;
	}
	
	function createMeta() {
		return '
		<meta property="og:title" 	content="Paseo de la Historieta"/>
		<meta property="og:type" 	content="article"/>
		<meta property="og:image" 	content="'.config::$appImage.'"/>
		<meta property="og:url" 	content="'.config::$appCanvas.'"/>
		<meta property="og:site_name" content="Paseo de la historieta"/>
		<meta property="og:description" content="El Paseo de la Historieta rinde homenaje al humor, la creatividad y la autocrítica que distinguen a nuestros artistas en todo el mundo."/>
		<meta property="fb:app_id" 	content="'.config::$appId.'"/>
		<link rel="canonical" href="'.config::$appCanvas.'"/>
		<script type="text/javascript">var g_url_corta="'.config::$appCanvasShort.'";</script>
		
		<script type="text/javascript">
  			var _gaq = _gaq || [];
  			_gaq.push(["_setAccount", "UA-17784640-33"]);
  			_gaq.push(["_trackPageview"]);
  			(function() {
    				var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
    				ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
    				var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
  			})();
		</script>
		';
	}
	
	function render($content=array(),$template="") {
		$html = "";
		$content["meta"] = $this->createMeta();
		
		$this->addContent($content);
		$this->setTemplate($template);
		
		if( is_readable($this->template_file) ) {
			$src = file_get_contents($this->template_file);
			if(!is_array($content)) {
				$html = $src;
			}
			else {
				if( count($content)>0 ) {
					$buscar = array_keys($content);
					array_walk($buscar, function(&$v) { $v="#$v#"; } );
					$reemplazar = array_values($content);
				
					$html = str_replace($buscar, $reemplazar, $src);
				} else 
					$html = $src;
			}
		} else {
			error_log("template::render() No se encuentra el template '{$this->template_file}'");
		}
		return $html;	
	}
}