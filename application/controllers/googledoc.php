<?php

/**
 * Google Doc controller.
 *
 * @package    AMS
 * @subpackage 	Google Documents Controller
 * @category	Controllers
 * @author		Ali Raza <ali@geekschicago.com>
 */
class Googledoc extends MY_Controller
{

    /**
     * Constructor.
     * 
     * Load the layout. Sphinx and tracking model
     *  
     */
    function __construct()
    {
        parent::__construct();
        $this->layout = 'main_layout.php';

			
    }
    function test()
    {
		/* Load the Zend Gdata classes. */
		
		$this->load->library('google_spreadsheet',array("user"=>'ali@geekschicago.com',"pass"=>'purelogics12','ss'=>'test_archive','ws'=>'Template'));
		echo "<pre>";
		print_r($this->google_spreadsheet->getRows());
		exit();
    }

}

// END Stations Controller

/* End of file stations.php */
/* Location: ./application/controllers/stations.php */