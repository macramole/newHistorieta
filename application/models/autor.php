<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Autor extends MY_Model {
	
	/*** Datos básicos ***/
	public static $name = "Autor";
	public static $table = "autores";
	public static $returnURL = 'autores/{title}';
	
	function __construct($id = null)
	{
		/*** Fields ***/
		$this->fields['title'] = new Textbox('Nombre');
		$this->fields['imagenPrincipal'] = new FileUpload(0, false, 'Imagen principal');
		$this->fields['imagenCuerpo'] = new FileUpload(1, false, 'Imagen cuerpo');
        $this->fields['personajes'] = new ForeignModel('Personaje');
        $this->fields['texto'] = new TextEditor();
        //$this->fields['bio'] = new TextEditor();
		
		/*** Extras ***/	
		$this->fields['imagenPrincipal']->dimensionesRecomendadas = '128x165';
        $this->fields['imagenPrincipal']->isImage(1);
                
		$this->fields['imagenCuerpo']->dimensionesRecomendadas = '690x427';
        $this->fields['imagenCuerpo']->isImage(1);
        
        /*$this->fields['bio']->extraTags = array('Título' => 'h2', 'Subtítulo' => 'h3');
        $this->fields['bio']->allowImages = true;*/
        $this->fields['texto']->allowLinks = false;
		
		$this->setListableFields(array('title'));
		
		parent::__construct($id);
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