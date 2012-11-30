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
        $this->load->library('pagination');
        $this->load->library('Ajax_pagination');
    }

    /**
     * List all the instantiation records with pagination and filters. 
     * 
     */
    public function index()
    {
//        echo '<pre>';print_r($this->session->userdata);exit;
        // List all the instantiations records active records
//        $data['records'] = $this->instantiation->list_all();
        $params = array('search' => '');
        if (isAjax())
        {
            $this->unset_facet_search();
		    $search['organization'] = $this->input->post('organization_main_search');
            $search['nomination'] = $this->input->post('nomination_status_main_search');
            $search['media_type'] = $this->input->post('media_type_main_search');
            $search['physical_format'] = $this->input->post('physical_format_main_search');
            $search['digital_format'] = $this->input->post('digital_format_main_search');
            $search['generation'] = $this->input->post('generation_main_search');
            $search['file_size'] = $this->input->post('file_size_main_search');
            $search['event_type'] = $this->input->post('event_type_main_search');
            $search['event_outcome'] = $this->input->post('event_outcome_main_search');
            $this->set_facet_search($search);
            foreach ($search as $key => $value)
            {
                $params[$key] = str_replace("|||", " | ", trim($value));
            }
           
        }
        $data['stations'] = $this->station_model->get_all();
        $data['nomination_status'] = $this->instantiation->get_nomination_status();
        $data['media_types'] = $this->instantiation->get_media_types();
        $data['physical_formats'] = $this->instantiation->get_physical_formats();
        $data['digital_formats'] = $this->instantiation->get_digital_formats();
        $data['generations'] = $this->instantiation->get_generations();
        $data['file_size'] = $this->instantiation->get_file_size();
        $data['event_types'] = $this->instantiation->get_event_type();
        $data['event_outcome'] = $this->instantiation->get_event_outcome();



        $data['isAjax'] = FALSE;
        $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;



        $records = $this->sphinx->instantiations_list($params, $offset);
        $data['total'] = $records['total_count'];
        $config['total_rows'] = $data['total'];
        $config['per_page'] = 100;
        $data['records'] = $records['records'];
//        echo '<pre>';print_r($data['records']);exit;
        $data['count'] = count($data['records']);
        if ($data['count'] > 0 && $offset == 0)
        {
            $data['start'] = 1;
            $data['end'] = $data['count'];
        } else
        {
            $data['start'] = $offset;
            $data['end'] = intval($offset) + intval($data['count']);
        }
		$data['facet_search_url']=site_url('instantiations/index');
        $config['prev_link'] = '<i class="icon-chevron-left"></i>';
        $config['prev_tag_open'] = '<span class="btn" style="margin:10px 0px;">';
        $config['prev_tag_close'] = '</span>';
        $config['next_link'] = '<i class="icon-chevron-right"></i>';
        $config['next_tag_open'] = '<span class="btn" style="margin:10px 0px;">';
        $config['next_tag_close'] = '</span>';
        $config['use_page_numbers'] = FALSE;
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['display_pages'] = FALSE;
        $config['js_method'] = 'facet_search';
        $config['postVar'] = 'page';
        $this->ajax_pagination->initialize($config);
        if (isAjax())
        {
            $data['isAjax'] = TRUE;
            echo $this->load->view('instantiations/index', $data, TRUE);
            exit;
        }
        $this->load->view('instantiations/index', $data);
    }

    public function detail()
    {
        show_404();
    }

    function unset_facet_search()
    {
        $this->session->unset_userdata('organization');
        $this->session->unset_userdata('nomination');
        $this->session->unset_userdata('media_type');
        $this->session->unset_userdata('physical_format');
        $this->session->unset_userdata('digital_format');
        $this->session->unset_userdata('generation');
        $this->session->unset_userdata('file_size');
        $this->session->unset_userdata('event_type');
        $this->session->unset_userdata('event_outcome');
    }

    function set_facet_search($search_values)
    {
        foreach ($search_values as $key => $value)
        {
            $this->session->set_userdata($key, $value);
        }
    }

}

// END Instantiations Controller

/* End of file instantiations.php */
/* Location: ./application/controllers/instantiations.php */