<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Reports Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
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
		$this->load->model('sphinx_model', 'sphinx');
		$this->load->library('pagination');
		$this->load->library('Ajax_pagination');
		$this->load->helper('datatable');
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

		$report_id = $this->uri->segment(3);
		$offset = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$this->session->set_userdata('stand_offset', $offset);
		if ( ! empty($report_id))
		{
			$report_info = $this->report_model->get_report_by_id(base64_decode($report_id));
			if (count($report_info) > 0)
			{
				$data['isAjax'] = FALSE;


				$this->session->set_userdata('stand_date_filter', $report_info->filters);
				$records = $this->sphinx->standalone_report($offset);
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

				$config['uri_segment'] = 4;
				$config['prev_link'] = '<i class="icon-chevron-left"></i>';
				$config['next_link'] = '<i class="icon-chevron-right"></i>';
				$config['use_page_numbers'] = FALSE;
				$config['first_link'] = FALSE;
				$config['last_link'] = FALSE;
				$config['display_pages'] = FALSE;
				$config['js_method'] = 'standalone_paginate';
				$config['postVar'] = 'page';
				$this->ajax_pagination->initialize($config);

				if (isAjax())
				{
					$data['isAjax'] = TRUE;
					echo $this->load->view('reports/standalone_report', $data, TRUE);
					exit_function();
				}
				$this->load->view('reports/standalone_report', $data);
			}
			else
			{
				show_error('Not a valid report url..');
			}
		}
		else
		{
			show_error('Not a valid report url.');
		}
	}

	public function standalone_datatable()
	{
		$columns = array('organization', 'instantiation_identifier', 'status', 'asset_title', 'generation', 'format_name',
			'dates', 'file_size', 'media_type', 'projected_duration', 'color', 'language',
		);
		$this->session->set_userdata('standalone_jscolumn', $this->input->get('iSortCol_0'));
		$this->session->set_userdata('standalone_column_order', $this->input->get('sSortDir_0'));
		$this->session->set_userdata('index_column', $columns[$this->input->get('iSortCol_0')]);


		$offset = isset($this->session->userdata['stand_offset']) ? $this->session->userdata['stand_offset'] : 0;
		$records = $this->sphinx->standalone_report($offset, 100, TRUE);
		$data['total'] = $records['total_count'];
		$record_ids = array_map(array($this, 'make_map_array'), $records['records']);
		$this->load->model('searchd_model', 'searchd');
		$records = $this->searchd->get_instantiation($record_ids);
//		$records = $records['records'];
		$data['count'] = count($records);
		$table_view = standalone_datatable_view($records);

		$dataTable = array(
			"sEcho" => intval($this->input->get('sEcho')),
			"iTotalRecords" => intval($data['count']),
			"iTotalDisplayRecords" => intval($data['count']),
			'aaData' => $table_view
		);
		echo json_encode($dataTable);
		exit_function();
	}

	public function generate_report()
	{
		$other = 0;
		$standalone = 0;
		$this->session->unset_userdata('stand_date_filter');
		$session_keys = array('date_range', 'custom_search', 'organization', 'states', 'nomination', 'media_type', 'physical_format',
			'digital_format', 'generation', 'digitized', 'migration_failed');
		foreach ($session_keys as $value)
		{
			if (isset($this->session->userdata[$value]))
			{
				if ($value == 'digitized' && $this->session->userdata[$value] == 1)
					$standalone = 1;
				else
				{
					if (isset($this->session->userdata[$value]) && $this->session->userdata[$value] != '')
						$other = 1;
				}
			}
		}
		if ($standalone == 1 && $other == 0)
		{
			$data['filters'] = $this->input->post('date');
			$data['user_id'] = $this->user_id;
			$data['report_type'] = 'standalone';
			if ( ! empty($data['filters']))
				$this->session->set_userdata('stand_date_filter', $data['filters']);
			$records = $this->sphinx->standalone_report();
			if (count($records['records']) > 0)
			{
				$report_id = $this->report_model->insert_report($data);
				$url = site_url() . "reports/standalone/" . base64_encode($report_id);

				echo json_encode(array('msg' => "<a href='$url' target='_blank'>$url</a>"));
			}
			else
			{

				echo json_encode(array('msg' => "No record available against " . $data['filters'] . '.'));
			}
		}
		else
		{

			echo json_encode(array('msg' => "Please apply Digitized filter from facet sidebar for standalone report."));
		}

		exit_function();
	}

}

// END Reports Controller

// End of file reports.php
/* Location: ./application/controllers/reports.php */