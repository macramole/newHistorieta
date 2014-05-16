<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('MasterController.php');

class Common extends MasterController
{	
	public function index()
	{
		$this->addContentPage('home');
        $this->addFondo(1, 15);
		$this->show();
	}
    
    public function inauguraciones()
	{
		$this->addContentPage('inauguraciones');
        $this->addFondo(11, 1);
		$this->show();
	}
}
