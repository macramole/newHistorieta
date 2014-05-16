<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personaje extends MY_Model {
	
	/*** Datos básicos ***/
	public static $name = "Personaje";
	public static $table = "personajes";
	public static $returnURL = 'autores/';
    public static $hayPaginaIndividual = false;
	
	function __construct($id = null)
	{
		/*** Fields ***/
		$this->fields['idAutor'] = new DatabaseSelect(new Autor());
		$this->fields['title'] = new Textbox('Nombre');
		$this->fields['descripcion'] = new TextArea('Pequeña descripción', 'Se utilizará en el mapa.');
		$this->fields['imagenPrincipal'] = new FileUpload(0, false, 'Imagen en autores');
		$this->fields['imagenMapa'] = new FileUpload(1, false, 'Imagen en mapa');
        $this->fields['imagenesGaleria'] = new FileUpload(2, false, 'Imagenes galeria');
        $this->fields['mapa'] = new GoogleMapField('Mapa', 'buenos aires');
        
        
		/*** Extras ***/	
        $this->fields['imagenPrincipal']->isImage(1);
		$this->fields['imagenPrincipal']->dimensionesRecomendadas = '112x116';
        $this->fields['imagenMapa']->isImage(1);
		$this->fields['imagenMapa']->dimensionesRecomendadas = '35x35';
        $this->fields['imagenesGaleria']->isImage();
        
		
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