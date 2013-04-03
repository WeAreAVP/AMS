<?php

/**
 * Reports Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    CPB http://nouman.com
 * @version    GIT: <$Id>
 * @link       http://amsqa.avpreserve.com
 */

/**
 * Reports Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    CPB http://nouman.com
 * @link       http://amsqa.avpreserve.com
 */
class Reports extends MY_Controller
{

	/**
	 * Constructor
	 * 
	 * Load the layout
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('report_model');
		if ($this->is_station_user)
		{
			redirect('records/index');
		}
	}

	/**
	 * List All types of reports
	 * 
	 * @return reports/index view 
	 */
	function index()
	{
		$data['station_records'] = $this->station_model->get_all();
		$this->load->view('reports/index', $data);
	}

	/**
	 * To List All Email Sent Through this System With Possible Filters
	 * 
	 * @return mixed
	 *
	 */
	function emailalerts()
	{
		$this->report_model->get_email_queue();
	}

	public function alerts_report()
	{
		$this->load->library('dompdf_lib');
		$file_name = 'Digitization_Statistics_' . time();
		$data['dsd_report'] = $this->report_model->scheduled_for_digitization_report();
		$data['material_at_crawford_report'] = $this->report_model->materials_at_crawford_report();
		$data['shipment_report'] = $this->report_model->shipment_return_report();
		$data['hd_return_report'] = $this->report_model->hard_disk_return_report();

		$this->dompdf_lib->convert_html_to_pdf($data, $file_name);
	}

	public function standalone()
	{
		$this->load->model('sphinx_model', 'sphinx');
		$this->load->library('pagination');
		$this->load->library('Ajax_pagination');
		
		$report_id = $this->uri->segment(3);
		$offset = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		if ( ! empty($report_id))
		{

			$report_info = $this->report_model->get_report_by_id(base64_decode($report_id));
			if (count($report_info) > 0)
			{

				$is_hidden = array();

				$params = array('search' => '');
				$data['hidden_fields'] = $is_hidden;
				$records = $this->sphinx->instantiations_list($params, $offset);
				$data['total'] = $records['total_count'];
				$config['total_rows'] = $data['total'];
				$config['per_page'] = 100;
				$data['records'] = $records['records'];

				$data['count'] = count($data['records']);
				if ($data['count'] > 0 && $offset === 0)
				{
					$data['start'] = 1;
					$data['end'] = $data['count'];
				}
				else
				{
					$data['start'] = $offset;
					$data['end'] = intval($offset) + intval($data['count']);
				}
				
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
					echo $this->load->view('reports/standalone_report', TRUE);
					exit_function();
				}
				$this->load->view('reports/standalone_report');
			}
			else
			{
				show_error('Not a valid report url.');
			}
		}
		else
		{
			show_error('Not a valid report url.');
		}
	}

	public function generate_report()
	{
		$other = 0;
		$standalone = 0;
		$session_keys = array('date_range', 'custom_search', 'organization', 'states', 'nomination', 'media_type', 'physical_format',
			'digital_format', 'generation', 'digitized', 'migration_failed');
		foreach ($session_keys as $value)
		{
			if ($value == 'digitized' && $this->session->userdata[$value] == 1)
				$standalone = 1;
			else if ($value != 'date_range')
			{
				if (isset($this->session->userdata[$value]) && $this->session->userdata[$value] != '')
					$other = 1;
			}
		}
		if ($standalone == 1 && $other == 0)
		{
			$data['filters'] = json_encode($this->session->userdata['date_range']);
			$data['user_id'] = $this->user_id;
			$data['report_type'] = 'standalone';
			$report_id = $this->report_model->insert_report($data);
			$url = site_url() . "reports/standalone/" . base64_encode($report_id);
			echo json_encode(array('msg' => "<a href='$url' target='_blank'>$url</a>"));
		}
		else
		{
			echo json_encode(array('msg' => "Please apply digitized filter and date filter only for standalone report."));
		}

		exit_function();
	}

}

// END Reports Controller

// End of file reports.php
/* Location: ./application/controllers/reports.php */