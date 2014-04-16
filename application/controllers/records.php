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
 * Records Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Records extends MY_Records_Controller
{
	/*
	 *
	 * Constructor
	 * 
	 */

	function __construct()
	{
		parent::__construct();
		$this->load->model('assets_model');
		$this->load->model('sphinx_model', 'sphinx');
		$this->load->model('instantiations_model', 'instantiation');

		$this->load->library('pagination');
		$this->load->library('Ajax_pagination');
		$this->load->helper('datatable');
		$this->load->model('refine_modal');
	}

	/*
	 *
	 * To List All Assets
	 *
	 */

	function index()
	{
		$offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->session->set_userdata('offset', $offset);
		$data['station_records'] = $this->station_model->get_all();
		if (isAjax())
		{
			$this->unset_facet_search();
			$search['custom_search'] = json_decode($this->input->post('keyword_field_main_search'));
			$search['date_range'] = json_decode($this->input->post('date_field_main_search'));
			$search['organization'] = $this->input->post('organization_main_search');
			$search['states'] = $this->input->post('states_main_search');
			$search['nomination'] = $this->input->post('nomination_status_main_search');
			$search['media_type'] = $this->input->post('media_type_main_search');
			$search['physical_format'] = $this->input->post('physical_format_main_search');
			$search['digital_format'] = $this->input->post('digital_format_main_search');
			$search['generation'] = $this->input->post('generation_main_search');
			if ($this->input->post('digitized') && $this->input->post('digitized') === '1')
			{
				$search['digitized'] = $this->input->post('digitized');
			}
			if ($this->input->post('migration_failed') && $this->input->post('migration_failed') === '1')
			{
				$search['migration_failed'] = $this->input->post('migration_failed');
			}
			$this->set_facet_search($search);
		}

		$this->session->set_userdata('page_link', 'records/index');
		$data['facet_search_url'] = site_url('records/index');
		$data['current_tab'] = 'simple';
		if (isset($this->session->userdata['current_tab']) && ! empty($this->session->userdata['current_tab']))
		{
			$data['current_tab'] = $this->session->userdata['current_tab'];
		}
		$this->session->set_userdata('current_tab', $data['current_tab']);
		$data['get_column_name'] = $this->make_array();


		$data['date_types'] = $this->instantiation->get_date_types();
		$data['is_refine'] = $this->refine_modal->get_active_refine();
		$is_hidden = array();
		$data['table_type'] = 'assets';
		foreach ($this->column_order as $key => $value)
		{
			if ($value['hidden'] == 1)
				$is_hidden[] = $key;
		}
		$data['hidden_fields'] = $is_hidden;
		$data['isAjax'] = FALSE;

		$records = $this->sphinx->assets_listing($offset);
		$data['total'] = $records['total_count'];
		$config['total_rows'] = $data['total'];
		$config['per_page'] = 100;
		$data['records'] = $records['records'];
		$data['count'] = count($data['records']);
		if ($data['count'] > 0 && $offset == 0)
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
			$data['isAjax'] = TRUE;
			echo $this->load->view('records/index', $data, TRUE);
			exit;
		}
		$this->load->view('records/index', $data);
	}

	function set_current_tab($current_tab)
	{
		if (isAjax())
		{
			$this->session->set_userdata('current_tab', $current_tab);
			exit;
		}
	}

	/*
	 * To Display Assets details
	 *
	 */

	function details($asset_id)
	{
		if ($asset_id)
		{
			$data['asset_id'] = $asset_id;
			$data['asset_details'] = $this->assets_model->get_asset_by_asset_id($asset_id);

			if (empty($data['asset_details']->asset_id))
			{
				show_error('Invalid record id: ' . $asset_id);
				exit;
			}
			$data['list_assets'] = $this->instantiation->get_instantiations_by_asset_id($asset_id);

			$data['asset_guid'] = $this->assets_model->get_guid_by_asset_id($asset_id);

			$data['asset_localid'] = $this->assets_model->get_localid_by_asset_id($asset_id);

			$data['asset_description'] = $this->assets_model->get_description_by_asset_id($asset_id);

			$data['asset_subjects'] = $this->assets_model->get_subjects_by_assets_id($asset_id);
			$data['asset_dates'] = $this->assets_model->get_assets_dates_by_assets_id($asset_id);
			$data['asset_genres'] = $this->assets_model->get_assets_genres_by_assets_id($asset_id);
			$data['asset_creators_roles'] = $this->assets_model->get_assets_creators_roles_by_assets_id($asset_id);
			$data['asset_contributor_roles'] = $this->assets_model->get_assets_contributor_roles_by_assets_id($asset_id);
			$data['asset_publishers_roles'] = $this->assets_model->get_assets_publishers_role_by_assets_id($asset_id);
			$data['asset_coverages'] = $this->assets_model->get_coverages_by_asset_id($asset_id);
			$data['rights_summaries'] = $this->assets_model->get_rights_summaries_by_asset_id($asset_id);
			$data['asset_audience_levels'] = $this->assets_model->get_audience_level_by_asset_id($asset_id);
			$data['asset_audience_ratings'] = $this->assets_model->get_audience_rating_by_asset_id($asset_id);
			$data['annotations'] = $this->assets_model->get_annotations_by_asset_id($asset_id);
			$data['relation'] = $this->assets_model->get_relation_by_asset_id($asset_id);
			$search_results_data = $this->sphinx->assets_listing(0, 1000, TRUE);
			$data['next_result_id'] = FALSE;
			$data['prev_result_id'] = FALSE;
			$cur_key = NULL;
			$data['media'] = $this->proxy_files($data['asset_guid']->guid_identifier);
			if (isset($search_results_data['records']) && ! is_empty($search_results_data['records']))
			{
				$search_results = $search_results_data['records'];
				foreach ($search_results as $key => $value)
				{
					if ($value->id == $asset_id)
						$cur_key = $key;
				}
				if (isset($search_results[$cur_key - 1]))
					$data['prev_result_id'] = $search_results[$cur_key - 1]->id;
				if (isset($search_results[$cur_key + 1]))
					$data['next_result_id'] = $search_results[$cur_key + 1]->id;
			}
			$data['last_page'] = '';
			if (isset($this->session->userdata['page_link']) && ! is_empty($this->session->userdata['page_link']))
			{
				$data['last_page'] = $this->session->userdata['page_link'];
			}

			$this->load->view('records/assets_details', $data);
		}
		else
		{
			show_404();
		}
	}

	/**
	 * Save cron to export records in pbcore 2 xml format.
	 * 
	 * @return JsonArrayType
	 */
	function export_pbcore()
	{
		$this->load->model('pbcore_model');
		$this->load->model('export_csv_job_model', 'csv_job');
		$records = $this->sphinx->assets_listing(0,1000);
		$_ids = '';
		if ($records['total_count'] <= 1000)
		{
			foreach ($records['records'] as $record)
			{
				$_ids .="{$record->id},";
			}
			$query = rtrim($_ids, ',');
			$query_loop = 0;
		}
		else
		{
			$query = $this->pbcore_model->export_assets(TRUE);
			$query_loop = ceil($records['total_count'] / 100000);
		}

		$record = array('user_id' => $this->user_id, 'status' => 0, 'type' => 'pbcore', 'export_query' => $query, 'query_loop' => $query_loop);
		$this->csv_job->insert_job($record);
		echo json_encode(array('link' => 'false', 'msg' => 'Email will be sent to you with the link to download.'));
		exit_function();
	}

}
