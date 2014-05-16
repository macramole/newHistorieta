<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('MasterController.php');

class Galeria extends MasterController
{	
	public function index()
	{
		redirect('galeria/fotos');
	}
    
    public function galerias()
    {
        $galeria = magico_getByUrlClean();
        
        if ( $galeria )
        {
            magico_getImagesToRow($galeria, 'galerias', 650, 430);
            
            $this->addContentPage('galeria', array('galeria' => $galeria));
            $this->addFondo(10, 1);
            $this->show();
        }
        else
            redirect();
        
    }
}
