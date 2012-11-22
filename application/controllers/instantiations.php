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
     * Load the layout, Instantiations model,sphinx_model
     * 
     */
    function __construct()
    {
        parent::__construct();
        $this->layout = 'main_layout.php';
        $this->load->model('instantiations_model', 'instantiation');
        $this->load->model('sphinx_model', 'sphinx');
    }

    /**
     * 
     * 
     */
    public function index()
    {
        $param = array('search' => '');
        $records = $this->sphinx->instantiations_list($param);
        $data['total']=$records['total_count'];
//        echo '<pre>';print_r($records);exit;
        $data['records']=$records['records'];
        
        // List all the instantiations records active records
//        $data['records'] = $this->instantiation->list_all();
        $this->load->view('instantiations/index', $data);
    }

    public function detail()
    {
        
    }

}

// END Instantiations Controller

/* End of file instantiations.php */
/* Location: ./application/controllers/instantiations.php */