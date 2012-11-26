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

        $this->load->library('pagination');
        $param = array('search' => '');

        $records = $this->sphinx->instantiations_list($param);
        $data['total'] = $records['total_count'];
        $config['total_rows'] = $data['total'];
        $config['per_page'] = 100;
        $data['records'] = $records['records'];
        $data['count'] = count($data['records']);

        // List all the instantiations records active records
//        $data['records'] = $this->instantiation->list_all();
        $config['base_url'] = $this->config->item('base_url').$this->config->item('index_page')."instantiations/index/";
        $config['prev_link'] = '<i class="icon-chevron-left"></i>';
        $config['prev_tag_open'] = '<span class="btn">';
        $config['prev_tag_close'] = '</span>';
        $config['next_link'] = '<i class="icon-chevron-right"></i>';
        $config['next_tag_open'] = '<span class="btn">';
        $config['next_tag_close'] = '</span>';
        $config['use_page_numbers'] = FALSE;
//        $config['num_links'] = 0;
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['display_pages'] = FALSE;
        $this->pagination->initialize($config);
        $this->load->view('instantiations/index', $data);
    }

    public function detail()
    {
        
    }

}

// END Instantiations Controller

/* End of file instantiations.php */
/* Location: ./application/controllers/instantiations.php */