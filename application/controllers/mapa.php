<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('MasterController.php');

class Mapa extends MasterController
{	
	public function index() {
		
        $this->load->model('Personaje');
        
        $markers =  Personaje::getListArray(35, 35, 1, 'latitud <> ""'); //$this->db->get('personajes')->result_array();
        $markersFront = array();
        
        //magico_getImageToArray($markers, 'personajes', 35, 35, 1);
        magico_getImageToArray($markers, 'personajes', 240, 160, 2, 'imagenGaleria', false, false);
		
        foreach($markers as $marker) {
			$text = '';
            
            if ( $marker[imagenGaleria] )
                $text = "<img src=\"$marker[imagenGaleria]\" /><br />";
            
            $text .= '<strong>' . $marker['title'] . '</strong>';
            
            if ( $marker['descripcion'] )
                $text .= ": $marker[descripcion]";
            
            $text .= '<br />';
            $text .= "<strong>Dirección</strong>: $marker[direccion]<br />";
            
            if ( $marker[imagenGaleria] )
                $text .= '<a href="' . $marker['url'] . '">ver fotos</a>';
            
            $markersFront[] = "[$marker[id], $marker[latitud], $marker[longitud], '$text', '$marker[imagen]' ]";
		}
        
        $this->addContentPage('mapa', array('markers' => $markersFront));
        $this->addFondo(11, 1);
		$this->show();
	}
}
