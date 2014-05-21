<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('MasterController.php');

class GaleriaController extends MasterController
{	
	public function index()
	{
		$this->load->model('Galeria');
        $galerias = Galeria::getListArray(null, null, null, 't.id <> 2');
        
        if ( count($galerias) ) {
            redirect($galerias[0]['url']);
        }
        else {
            $this->load->model('Personaje');
            $personajes = Personaje::getListArray(735, 428, 2);
            
             foreach( $personajes as $key => &$personaje ) {
                if ( !$personaje['imagen'] )
                    unset($personajes[$key]);
            }
            
            if ( count($personajes) ) {
                $personaje = current($personajes);
                redirect( $personaje['url'] );
            }
        }
	}
    
    public function getGaleria($id, $tipo) //ajax
    {
        if ( $tipo == 'galerias' ) {
            $this->load->model('Galeria');
            $galeria = Galeria::getRowArray($id);
            magico_getImagesToRow($galeria, 'galerias', 735, 428);
        } else {
            $this->load->model('Personaje');
            $galeria = Personaje::getRowArray($id);
            magico_getImagesToRow($galeria, 'personajes', 735, 428, 2);
        }
        
        if ( $id == 2 ) {
            foreach( $galeria['imagenes'] as $key => &$item ) {
                $galeria['downloads'][] = site_url("galeriacontroller/download/$id/$tipo/$key");
            }
        } else {
            $galeria['downloads'] = null;
        }
        
        
        return $galeria;
    }
    
    public function wallpapers()
    {
        $this->load->model('Galeria');
        $galerias = array( Galeria::getRowArray(2) );
        
        $galeria = $this->getGaleria(2, 'galerias');
        
        $this->setFacebookDescription("Galeria de $galeria[title] en el Paseo de la historieta BA");
        $this->setFacebookImage($galeria['imagenes'][0]);
        $this->setSectionTitle("Galeria de $galeria[title]");
        
        $this->addContentPage('galeria', array('galerias' => $galerias, 'personajes' => array(), 'galeriaSeleccionada' => $galeria ));
        $this->addFondo(10, 1);
        $this->show();
    }
    
    public function download($id, $tipo, $index)
    {
        if ( $tipo == 'galerias' ) {
            $files = magico_getFiles('galerias', $id);
        } else {
            $files = magico_getFiles('personajes', $id, 2);
        }
        
        if ( count($files) ) {
            foreach( $files as $key => $file ) {
                if ( $key == $index ) {
                    header('Content-type: image');
                    header("Content-Disposition: attachment; filename='$file[filename]'");
                    
                    echo file_get_contents(UPLOAD_DIR . $file['filename']);
                    exit;
                }
            }
        }
    }
    
    public function galerias()
    {
        $galeria = magico_getByUrlClean();
       
        if ( $galeria ) {
            $type = 'galerias';
            
            if ( $galeria['idAutor'] )
                $type = 'personajes';
            
            $this->load->model('Galeria');
            $this->load->model('Personaje');

            $galerias = Galeria::getListArray(null, null, null, 't.id <> 2');
            $personajes = Personaje::getListArray(735, 428, 2);

            foreach( $personajes as $key => &$personaje ) {
                if ( !$personaje['imagen'] )
                    unset($personajes[$key]);
            }
            
            $galeria = $this->getGaleria($galeria['id'], $type);
            
            if ( $type == 'galerias' )
                $galeria['type'] = 'Galeria';
            else
                $galeria['type'] = 'Personaje';

            $this->setFacebookDescription("Galeria de $galeria[title] en el Paseo de la historieta BA");
            $this->setFacebookImage($galeria['imagenes'][0]);
            $this->setSectionTitle("Galeria de $galeria[title]");
            
            $this->addContentPage('galeria', array('galerias' => $galerias, 'personajes' => $personajes, 'galeriaSeleccionada' => $galeria));
            $this->addFondo(10, 1);
            $this->show();
        } else {
            redirect();
        }
    }
}
