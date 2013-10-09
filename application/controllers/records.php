<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category AMS
 * @package  CI
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @license  CPB http://nouman.com
 * @version  GIT: <$Id>
 * @link     http://ams.avpreserve.com

 */

/**
 * Records Class
 *
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    http://ams.avpreserve.com CPB
 * @link       http://ams.avpreserve.com
 */
class Records extends MY_Controller
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
			$this->session->set_userdata('offset', $offset);
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
		$param = array('index' => 'assets_list');
		$records = $this->sphinx->assets_listing($param, $offset);
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
	 *
	 * To List All flagged
	 *
	 */

	function flagged()
	{
		show_404();
		exit();
//			$this->load->view('records/flagged');
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
			$search_results_data = $this->sphinx->assets_listing(array('index' => 'assets_list'), 0, 1000, TRUE);
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
//				$search_results_array = array();
//				$num_search_results = 0;
//				if ($search_results)
//				{
//					foreach ($search_results as $search_result)
//					{
//						$search_results_array[]['id'] = $search_result->id;
//					}
//					$num_search_results = count($search_results);
//				}
//# Get result number of current asset
//				$search_result_pointer = 0;
//				foreach ($search_results_array as $search_res)
//				{
//					if ($search_res['id'] == $asset_id)
//						break;
//					$search_result_pointer ++;
//				}
//				$data['cur_result'] = $search_result_pointer + 1;
//
//# Get number of results
//				$data['num_results'] = $num_search_results;
//
//# Get result number of next listings
//				if ($search_result_pointer >= ($num_search_results - 1))
//					$data['next_result_id'] = FALSE;
//				else
//					$data['next_result_id'] = $search_results_array[$search_result_pointer + 1]['id'];
//
//# Get result number of previous listings
//				if ($search_result_pointer <= 0 || $num_search_results == 1)
//					$data['prev_result_id'] = FALSE;
//				else
//					$data['prev_result_id'] = $search_results_array[$search_result_pointer - 1]['id'];
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

	function proxy_files($guid)
	{
		$proxy_guid = str_replace('/', '-', $guid);
		$proxy_response = file_get_contents("http://cpbproxy.crawfordmedia.com/xml.php?GUID=$proxy_guid");
		$x = @simplexml_load_string($proxy_response);
		if (is_object($x))
		{
			$data = xmlObjToArr($x);

			$child = $data['children'];
			if (isset($data['name']) && $data['name'] == 'error')
			{
				return FALSE;
			}
			else
			{
				if (isset($child['mediaurl'][0]))
				{
					$media['url'] = $child['mediaurl'][0]['text'];
				}
				if (isset($child['format'][0]))
				{
					$media['format'] = $child['format'][0]['text'];
				}
				return $media;
			}
		}
		return FALSE;
	}

	public function sort_simple_table()
	{
		$columns = array('flag', 'organization', 'guid_identifier', 'local_identifier', 'asset_title', 'description');
		$this->session->unset_userdata('column');
		$this->session->unset_userdata('jscolumn');
		$this->session->unset_userdata('column_order');

		$this->session->set_userdata('jscolumn', $this->input->get('iSortCol_0'));
		$this->session->set_userdata('column', $columns[$this->input->get('iSortCol_0')]);

		$this->session->set_userdata('column_order', $this->input->get('sSortDir_0'));

		$offset = isset($this->session->userdata['offset']) ? $this->session->userdata['offset'] : 0;
		$param = array('index' => 'assets_list');
		$records = $this->sphinx->assets_listing($param, $offset, 100, TRUE);
		$data['total'] = $records['total_count'];
		$record_ids = array_map(array($this, 'make_map_array'), $records['records']);
		$this->load->model('searchd_model', 'searchd');
		$records = $this->searchd->get_assets($record_ids);
//		
//		$records = $records['records'];
		$data['count'] = count($records);
		$table_view = simple_simple_datatable_view($records);


		$dataTable = array(
			"sEcho" => $this->input->get('sEcho') + 1,
			"iTotalRecords" => $data['count'],
			"iTotalDisplayRecords" => $data['count'],
			'aaData' => $table_view
		);
		echo json_encode($dataTable);
		exit_function();
	}

	public function sort_full_table()
	{
		$column = array(
			'Organization' => 'organization',
			'Titles' => 'asset_title',
			'AA_GUID' => 'guid_identifier',
			'Local_ID' => 'local_identifier',
			'Description' => 'description',
			'Subjects' => 'asset_subject',
			'Genre' => 'asset_genre',
			'Assets_Date' => 'dates',
			'Creator' => 'asset_creator_name',
			'Contributor' => 'asset_contributor_name',
			'Publisher' => 'asset_publisher_name',
			'Coverage' => 'asset_coverage',
			'Audience_Level' => 'asset_audience_level',
			'Audience_Rating' => 'asset_audience_rating',
			'Annotation' => 'asset_annotation',
			'Rights' => 'asset_rights');


		$this->session->unset_userdata('column');
		$this->session->unset_userdata('jscolumn');
		$this->session->unset_userdata('column_order');
		$this->session->set_userdata('jscolumn', $this->input->get('iSortCol_0'));
		$this->session->set_userdata('column', $column[$this->column_order[$this->input->get('iSortCol_0')]['title']]);
		$this->session->set_userdata('column_order', $this->input->get('sSortDir_0'));
		$offset = isset($this->session->userdata['offset']) ? $this->session->userdata['offset'] : 0;
		$param = array('index' => 'assets_list');
		$records = $this->sphinx->assets_listing($param, $offset, 100, TRUE);
		$data['total'] = $records['total_count'];
		$record_ids = array_map(array($this, 'make_map_array'), $records['records']);
		$this->load->model('searchd_model', 'searchd');
		$records = $this->searchd->get_assets($record_ids);
//		
//		$records = $records['records'];
		$data['count'] = count($records);
		$table_view = full_assets_datatable_view($records, $this->column_order);

		$dataTable = array(
			"sEcho" => $this->input->get('sEcho') + 1,
			"iTotalRecords" => $data['count'],
			"iTotalDisplayRecords" => $data['count'],
			'aaData' => $table_view
		);
		echo json_encode($dataTable);
		exit_function();
	}

}
