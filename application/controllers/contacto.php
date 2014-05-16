<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('MasterController.php');

class Contacto extends MasterController
{	
	const MAIL_CONTACTO = 'leandrogarber@gmail.com';//'paseodelahistorieta@buenosaires.gob.ar';
    
    public function index()
	{
		$this->addContentPage('contacto');
        $this->addFondo(11, 1);
		$this->show();
	}
    
    public function enviarContacto() //ajax
    {
        $mail = $this->load->view('mails/contacto', array('nombre'=>$_GET['nombre'],'mail'=>$_GET['mail'],'mensaje'=>$_GET['mensaje']), true);

        magico_sendmail(self::MAIL_CONTACTO, "Nuevo contacto ingresado", $mail, 'noreply@paseodelahistorieta.com');
		
		echo json_encode(array('status'=>'ok'));
    }
}
