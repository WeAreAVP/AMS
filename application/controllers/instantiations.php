<?php

/**
 * Instantiations
 * 


 * @category	Controllers
 * @package		AMS
 * @subpackage	Instantiations
 * @author		Nouman Tayyab <nouman@geekschicago.com>
 * @link        http://http://amsqa.avpreserve.com/
 */
class Instantiations extends MY_Controller
{

    /**
     * Constructor
     * 
     * Load the layout, Models and Libraries
     * 
     */
    function __construct()
    {
        parent::__construct();
        $this->layout = 'main_layout.php';
        $this->load->model('instantiations_model', 'instantiation');
        $this->load->model('assets_model');
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

        $params = array('search' => '');
        if (isAjax())
        {
            $this->unset_facet_search();
            $search['custom_search'] = $this->input->post('keyword_field_main_search');
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
        $data['get_column_name'] = $this->make_array();
        $data['stations'] = $this->station_model->get_all();
        $data['nomination_status'] = $this->instantiation->get_nomination_status();
        $data['media_types'] = $this->instantiation->get_media_types();
        $data['physical_formats'] = $this->instantiation->get_physical_formats();
        $data['digital_formats'] = $this->instantiation->get_digital_formats();
        $data['generations'] = $this->instantiation->get_generations();
        $data['file_size'] = $this->instantiation->get_file_size();
        $data['event_types'] = $this->instantiation->get_event_type();
        $data['event_outcome'] = $this->instantiation->get_event_outcome();
        $is_hidden = array();
        $data['table_type'] = 'instantiation';
        foreach ($this->column_order as $key => $value)
        {
            if ($value['hidden'] == 1)
                $is_hidden[] = $key;
        }
        $data['hidden_fields'] = $is_hidden;
        $data['isAjax'] = FALSE;
        $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $records = $this->sphinx->instantiations_list($params, $offset);
        $data['total'] = $records['total_count'];
        $config['total_rows'] = $data['total'];
        $config['per_page'] = 100;
        $data['records'] = $records['records'];
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
        $data['facet_search_url'] = site_url('instantiations/index');
        $config['prev_link'] = '<i class="icon-chevron-left"></i>';
        $config['next_link'] = '<i class="icon-chevron-right"></i>';
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

    /**
     * Show the detail of an instantiation
     *  
     */
    public function detail()
    {
        $instantiation_id = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : FALSE;
        if ($instantiation_id)
        {
            $detail = $this->instantiation->get_by_id($instantiation_id);
            if (count($detail) > 0)
            {
                $data['asset_id'] = $detail->assets_id;
                $data['inst_id'] = $instantiation_id;
                $data['instantiation_detail'] = $this->sphinx->instantiations_list(array('asset_id' => $detail->assets_id, 'search' => ''));
                $data['instantiation_detail'] = $data['instantiation_detail']['records'][0];
                $data['asset_details'] = $this->assets_model->get_asset_by_asset_id($detail->assets_id);
                $data['asset_instantiations'] = $this->sphinx->instantiations_list(array('asset_id' => $detail->assets_id, 'search' => ''));
                $this->load->view('instantiations/detail', $data);
            } else
            {
                show_404();
            }
        } else
        {
            show_404();
        }
    }

    /**
     * Set last state of table view
     *  
     */
    public function update_user_settings()
    {
        if (isAjax())
        {
            $user_id = $this->user_id;
            $settings = $this->input->post('settings');
            $freeze_columns = $this->input->post('frozen_column');
            $table_type = $this->input->post('table_type');
            $settings = json_encode($settings);
            $data = array('view_settings' => $settings, 'frozen_column' => $freeze_columns);
            $this->user_settings->update_setting($user_id, $table_type, $data);
            echo json_encode(array('success' => TRUE));
            exit;
        }
        show_404();
    }

}

// END Instantiations Controller

/* End of file instantiations.php */
/* Location: ./application/controllers/instantiations.php */