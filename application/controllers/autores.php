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
        
        if ( $autor )
        {
            $autores = magico_getList('autores', null, null, null, 'weight ASC');
            
            magico_getImageToRow($autor, 'autores', 128, 165);
            $autor['personajes'] = magico_getList('personajes', 112, 116, "idAutor = '$autor[id]'", 'weight ASC');
            $autor['entradas'] = $this->db->get_where('entradas', array('idAutor' => $autor['id']))->result_array();
            
            foreach( $autores as &$itemAutor )
                if ( $itemAutor['id'] == $autor['id'] )
                    $itemAutor['selected'] = true;
            
            $this->addContentPage('autores', array('autor' => $autor, 'autores' => $autores));
            $this->addFondo(10, 1);
            $this->show();
        }
        else
            redirect();
        
    }
}
