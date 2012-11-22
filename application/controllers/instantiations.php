<?php

/**
 * Instantiations Tracking Controller
 * 
 * @package		AMS
 * @subpackage	Tracking Controller
 * @category	Controllers
 * @author		Nouman Tayyab <nouman@geekschicago.com>
 */
class Instantiations extends MY_Controller
{

    /**
     * Constructor
     * 
     * Load the layout and Instantiations model
     * 
     */
    function __construct()
    {
        parent::__construct();
        $this->layout = 'main_layout.php';
        $this->load->model('instantiations_model', 'instantiation');
    }

    /**
     * 
     * 
     */
    public function index()
    {
        
    }

    public function detail()
    {
        
    }

}

// END Tracking Controller

/* End of file instantiations.php */
/* Location: ./application/controllers/instantiations.php */