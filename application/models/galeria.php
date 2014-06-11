<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Galeria extends MY_Model {
	
	/*** Datos básicos ***/
	public static $name = "Galeria";
	public static $table = "galerias";
	public static $returnURL = 'galeria/{title}';
	
	function __construct($id = null)
	{
		/*** Fields ***/
		$this->fields['title'] = new Textbox('Nombre');
		$this->fields['imagenes'] = new FileUpload();
		
		/*** Extras ***/	
		$this->fields['imagenes']->dimensionesRecomendadas = '735x428';
		
		$this->setListableFields(array('title'));
		
		parent::__construct($id);
	}
	
    function delete() {
        if ( $this->id == 2 )
            return;
        else
            parent::delete();
    }
    
	function validate()
	{
		parent::validate();
		
		$this->ci->form_validation->set_rules('title','','required');
		
		if ($this->ci->form_validation->run() == true)
			return null;
		else
		{
			return $this->ci->form_validation->get_error_array();
		}
	}
}