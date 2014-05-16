<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Use this class to add some common functionality to your site. Like a navigation menu or whatever you like.
 *  
 */
class MasterController extends MY_Controller {
	
	protected $_pageTitle = 'Paseo de la historieta';
	
    public function addFondo( $fondoIndex, $cantidad )
    {
        $this->addContentPage("fondos", array( 'fondoIndex' => $fondoIndex, 'cantidad' => $cantidad ), "Fondos");
    }
    
	public function show($additionalData = array())
	{
		/* Add some code here for example	.
		 * 
		 */
		
		parent::show($additionalData);
	}
}