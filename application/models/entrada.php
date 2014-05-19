<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entrada extends MY_Model {
	
	/*** Datos básicos ***/
	public static $name = "Entrada";
	public static $table = "entradas";
	public static $returnURL = '{idAutor}/{title}';
    public static $hayPaginaIndividual = true;
	
	function __construct($id = null)
	{
		/*** Fields ***/
        $this->fields['idAutor'] = new DatabaseSelect(new Autor());
		$this->fields['fecha'] = new DatePicker();
		$this->fields['title'] = new Textbox('Título');
        $this->fields['cuerpo'] = new TextEditor();
		
		/*** Extras ***/	
        $this->fields['cuerpo']->extraTags = array('Subtítulo' => 'h3');
        $this->fields['cuerpo']->allowImages = true;
		
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