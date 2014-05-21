<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('MasterController.php');

class Museo extends MasterController
{	
	public function index()
	{
		
        $this->setFacebookDescription("Museo del humor");
        $this->setSectionTitle("Museo del humor");
        
        $this->addContentPage('museo');
        $this->addFondo(16, 1);
		$this->show();
	}
}
