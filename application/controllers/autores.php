<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('MasterController.php');

class Autores extends MasterController
{	
	public function index()
	{
		$sqlPrimerAutor = '
            SELECT
                cu.url
            FROM
                autores a
            INNER JOIN
                clean_urls cu ON
                cu.node_id = a.id
            WHERE
                cu.`table` = "autores"
            ORDER BY
                a.weight ASC
            LIMIT 1
            ';
        
        $url = $this->db->query($sqlPrimerAutor)->row_array();
        redirect($url['url']);
	}
    
    public function autor()
    {
        $autor = magico_getByUrlClean();
        
        if ( $autor ) //Esto puede recibir o un autor o una entrada individual
        {
            $autores = magico_getList('autores', null, null, null, 'weight ASC');
            
            if ( $autor['idAutor'] )  { //si es una entrada
                $this->load->model('Autor');
                $entrada = $autor;
                $autor = Autor::getRowArray($entrada['idAutor']);
                $this->setFacebookDescription($autor['title'] . ' // ' . $entrada['title']);
            }
            
            $this->config->set_item('image_default', 'default_image.gif');
            magico_getImageToRow($autor, 'autores', 128, 165);
            
            $this->load->model('Personaje');
            
            
            $autor['personajes'] = Personaje::getListArray(112, 116, 0, "idAutor = '$autor[id]'", 'weight ASC');
            
            if ( !$entrada ) {
                $this->load->model('Entrada');
                $autor['entradas'] = Entrada::getListArray(null, null, null, "idAutor = '$autor[id]'");
            } else {
                $autor['entradas'][] = $entrada;
                $autor['entradaUnica'] = true;
            }
            
            foreach( $autores as &$itemAutor )
                if ( $itemAutor['id'] == $autor['id'] )
                    $itemAutor['selected'] = true;
            
            $this->setFacebookImage($autor['imagen']);
            $this->addContentPage('autores', array('autor' => $autor, 'autores' => $autores));
            $this->addFondo(10, 1);
            $this->show();
        }
        else
            redirect();
    }
}
