<?php

/**
 * AMS Archive Management System
 * 
 * To manage the instantiations
 * 
 * PHP version 5
 * 
 * @category AMS
 * @package  CI
 * @author   Nouman Tayyab <nouman@geekschicago.com>
 * @license  CPB http://nouman.com
 * @version  GIT: <$Id>
 * @link     http://amsqa.avpreserve.com

 */

/**
 * Instantiations Class
 *
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    http://amsqa.avpreserve.com CPB
 * @link       http://amsqa.avpreserve.com
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
		$this->load->model('instantiations_model', 'instantiation');
$this->load->model('manage_asset_model', 'manage_asset');
		$this->load->model('export_csv_job_model', 'csv_job');
		$this->load->model('assets_model');
		$this->load->model('essence_track_model', 'essence_track');
		$this->load->model('sphinx_model', 'sphinx');
		$this->load->library('pagination');
		$this->load->library('Ajax_pagination');
		$this->load->library('memcached_library');
		$this->load->helper('datatable');
		$this->load->model('refine_modal');
		$this->load->model('cron_model');
	}

	/**
	 * List all the instantiation records with pagination and filters. 
	 * 
	 * @return instantiations/index view
	 */
	public function index()
	{
		$offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->session->set_userdata('offset', $offset);
		$params = array('search' => '');

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

		$this->session->set_userdata('page_link', 'instantiations/index/' . $offset);
		$data['get_column_name'] = $this->make_array();


		$data['date_types'] = $this->instantiation->get_date_types();
		$data['is_refine'] = $this->refine_modal->get_active_refine();


		$data['current_tab'] = '';
		$is_hidden = array();
		$data['table_type'] = 'instantiation';
		foreach ($this->column_order as $index => $value)
		{
			if ($value['hidden'] === '1')
				$is_hidden[] = $index;
		}
		$data['hidden_fields'] = $is_hidden;
		$data['isAjax'] = FALSE;

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
			exit(0);
		}
		$this->load->view('instantiations/index', $data);
	}

	/**
	 * Show the detail of an instantiation
	 *  
	 * @return instantiations/detail view
	 */
	public function detail()
	{
		$instantiation_id = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : FALSE;
		if ($instantiation_id)
		{
			$detail = $data['detail_instantiation'] = $this->instantiation->get_by_id($instantiation_id);

			if (count($detail) > 0)
			{
				$data['asset_id'] = $detail->assets_id;
				$data['inst_id'] = $instantiation_id;
				$data['list_assets'] = $this->instantiation->get_instantiations_by_asset_id($detail->assets_id);
				$data['asset_guid'] = $this->assets_model->get_guid_by_asset_id($data['asset_id']);
				$data['ins_nomination'] = $this->instantiation->get_nomination_by_instantiation_id($instantiation_id);
				$data['inst_identifier'] = $this->instantiation->get_identifier_by_instantiation_id($instantiation_id);
				$data['inst_dates'] = $this->instantiation->get_dates_by_instantiation_id($instantiation_id);
				$data['inst_media_type'] = $this->instantiation->get_media_type_by_instantiation_media_id($detail->instantiation_media_type_id);
				$data['inst_format'] = $this->instantiation->get_format_by_instantiation_id($instantiation_id);
				$data['inst_generation'] = $this->instantiation->get_generation_by_instantiation_id($instantiation_id);
				$data['inst_demension'] = $this->instantiation->get_demension_by_instantiation_id($instantiation_id);
				$data['inst_data_rate_unit'] = $this->instantiation->get_data_rate_unit_by_data_id($detail->data_rate_units_id);
				$data['inst_color'] = $this->instantiation->get_color_by_instantiation_colors_id($detail->instantiation_colors_id);
				$data['inst_annotation'] = $this->instantiation->get_annotation_by_instantiation_id($instantiation_id);
				$data['essence_track'] = $this->essence_track->get_essence_tracks_by_instantiations_id($instantiation_id);
//				debug($data['essence_track']);

				$data['instantiation_events'] = $this->instantiation->get_events_by_instantiation_id($instantiation_id);

				$data['asset_details'] = $this->assets_model->get_asset_by_asset_id($detail->assets_id);
				$search_results_data = $this->sphinx->instantiations_list(array('index' => 'assets_list'), 0, 1000);
				$data['nominations'] = $this->instantiation->get_nomination_status();

				$data['media'] = $this->proxy_files($data['asset_guid']->guid_identifier);
				$data['next_result_id'] = FALSE;
				$data['prev_result_id'] = FALSE;
				if (isset($search_results_data['records']) && ! is_empty($search_results_data['records']))
				{
					$search_results = $search_results_data['records'];
					$search_results_array = array();
					$num_search_results = 0;
					if ($search_results)
					{
						foreach ($search_results as $search_result)
						{
							$search_results_array[]['id'] = $search_result->id;
						}
						$num_search_results = count($search_results);
					}
					# Get result number of current asset
					$search_result_pointer = 0;
					foreach ($search_results_array as $search_res)
					{
						if ($search_res['id'] == $instantiation_id)
							break;
						$search_result_pointer ++;
					}
					$data['cur_result'] = $search_result_pointer + 1;

					# Get number of results
					$data['num_results'] = $num_search_results;

					# Get result number of next listings
					if ($search_result_pointer >= ($num_search_results - 1))
						$data['next_result_id'] = FALSE;
					else
						$data['next_result_id'] = $search_results_array[$search_result_pointer + 1]['id'];

					# Get result number of previous listings
					if ($search_result_pointer <= 0 || $num_search_results == 1)
						$data['prev_result_id'] = FALSE;
					else
						$data['prev_result_id'] = $search_results_array[$search_result_pointer - 1]['id'];
				}
				$data['last_page'] = '';
				if (isset($this->session->userdata['page_link']) && ! is_empty($this->session->userdata['page_link']))
				{
					$data['last_page'] = $this->session->userdata['page_link'];
				}

				$this->load->view('instantiations/detail', $data);
			}
			else
			{
				show_404();
			}
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
		return FALSE;
	}

	/**
	 * Set last state of table view
	 *  
	 * @return json
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
			exit(0);
		}
		show_404();
	}

	

	

	public function edit()
	{
		$instantiation_id = $this->uri->segment(3);

		if ( ! empty($instantiation_id))
		{

			if ($this->input->post())
			{
				
			}
			$detail = $data['instantiation_detail'] = $this->instantiation->get_by_id($instantiation_id);
			if (count($data['instantiation_detail']) > 0)
			{
				$data['asset_id'] = $detail->assets_id;
				$data['inst_id'] = $instantiation_id;
				$data['list_assets'] = $this->instantiation->get_instantiations_by_asset_id($detail->assets_id);
				$data['ins_nomination'] = $this->instantiation->get_nomination_by_instantiation_id($instantiation_id);
				$data['inst_identifier'] = $this->manage_asset->get_identifier_by_instantiation_id($instantiation_id);
				$data['inst_dates'] = $this->manage_asset->get_dates_by_instantiation_id($instantiation_id);
				$data['inst_demension'] = $this->manage_asset->get_demension_by_instantiation_id($instantiation_id);
				$data['inst_format'] = $this->instantiation->get_format_by_instantiation_id($instantiation_id);
				$data['inst_media_type'] = $this->instantiation->get_media_type_by_instantiation_media_id($detail->instantiation_media_type_id);
				$data['inst_generation'] = $this->instantiation->get_generation_by_instantiation_id($instantiation_id);
				$data['inst_data_rate_unit'] = $this->instantiation->get_data_rate_unit_by_data_id($detail->data_rate_units_id);
				$data['inst_color'] = $this->instantiation->get_color_by_instantiation_colors_id($detail->instantiation_colors_id);
				$data['inst_annotation'] = $this->manage_asset->get_annotation_by_instantiation_id($instantiation_id);
				$data['inst_relation'] = $this->manage_asset->get_relation_by_instantiation_id($instantiation_id);
				$data['asset_details'] = $this->assets_model->get_asset_by_asset_id($detail->assets_id);
				$data['pbcore_asset_date_types'] = $this->manage_asset->get_picklist_values(2);
				$data['pbcore_media_types'] = $this->manage_asset->get_picklist_values(11);
				$data['pbcore_generations'] = $this->manage_asset->get_picklist_values(12);
				$data['pbcore_relation_types'] = $this->manage_asset->get_picklist_values(7);
				$this->load->view('instantiations/edit', $data);
			}
			else
			{
				show_error('Not a valid instantiation id');
			}
		}
		else
		{
			show_error('Instantiation ID is required for editing.');
		}
	}

	public function edit_old()
	{
		$ins_id = $this->input->post('ins_id');
		$nomination = $this->input->post('nomination');
		$reason = $this->input->post('nomination_reason');
		$source = $this->input->post('ins_id_source');
		$media_type = $this->input->post('media_type');
		$generation = $this->input->post('generation');
		$language = $this->input->post('language');
		$gen_array = '';
		if ($source != '')
		{
			$this->instantiation->update_instantiation_identifier($ins_id, array('instantiation_source' => $source));
		}
		$nomination_exist = $this->assets_model->get_nominations($ins_id);
		if ($nomination != '')
		{
			$nomination_id = $this->assets_model->get_nomination_status_by_status($nomination)->id;

			$nomination_record = array('nomination_status_id' => $nomination_id, 'nomination_reason' => $reason, 'nominated_by' => $this->user_id, 'nominated_at' => date('Y-m-d H:i:s'));
			if ($nomination_exist)
			{
				$nomination_record['updated'] = date('Y-m-d H:i:s');
				$this->assets_model->update_nominations($ins_id, $nomination_record);
			}
			else
			{
				$nomination_record['instantiations_id'] = $ins_id;
				$nomination_record['created'] = date('Y-m-d H:i:s');
				$this->assets_model->insert_nominations($nomination_record);
			}
		}
		else
		{
			if ($nomination_exist)
			{

				$this->instantiation->delete_nominations_by_instantiation_id($ins_id);
			}
		}
		$db_media_type = $this->instantiation->get_instantiation_media_types_by_media_type($media_type);
		if ($db_media_type)
		{
			$media_type_id = $db_media_type->id;
		}
		else
		{
			$media_type_id = $this->instantiation->insert_instantiation_media_types(array('media_type' => $media_type));
		}
		if ($generation)
		{
			$this->instantiation->delete_generation_by_instantiation_id($ins_id);
			foreach ($generation as $row)
			{
				$db_generation = $this->instantiation->get_generations_by_generation($row);
				if ($db_generation)
				{
					$db_gen_id = $db_generation->id;
				}
				else
				{
					$db_gen_id = $this->instantiation->insert_generations(array('generation' => $row));
				}
				$this->instantiation->insert_instantiation_generations(array('instantiations_id' => $ins_id, 'generations_id' => $db_gen_id));
			}
			$gen_array = implode('|', $generation);
		}
		$this->instantiation->update_instantiations($ins_id, array('instantiation_media_type_id' => $media_type_id, 'language' => $language));
		$this->cron_model->update_rotate_indexes(2, array('status' => 0));
		$this->cron_model->update_rotate_indexes(1, array('status' => 0));
		redirect('instantiations/detail/' . $ins_id);
	}

	public function export_csv()
	{
//								if(isAjax())
//								{
		@ini_set("memory_limit", "3000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB
		$params = array('search' => '');
		$records = $this->sphinx->instantiations_list($params);
		if ($records['total_count'] <= 10000)
		{
			$records = $this->instantiation->export_limited_csv();

			if (count($records) > 0)
			{
				$this->load->library('excel');
				$this->excel->getActiveSheetIndex();
				$this->excel->getActiveSheet()->setTitle('Limited CSV');
				$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
				$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
				$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
				$this->excel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
				$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(0, 1, 'GUID');
				$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(1, 1, 'Unique ID');
				$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(2, 1, 'Title');
				$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, 1, 'Format');
				$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(4, 1, 'Duration');
				$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(5, 1, 'Priority');
				$row = 2;
				foreach ($records as $value)
				{
					$col = 0;
					foreach ($value as $field)
					{

						$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $field);

						$col ++;
					}

					$row ++;
				}
				$filename = 'csv_export_' . time() . '.csv';
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
				$objWriter->save("uploads/$filename");
				$this->excel->disconnectWorksheets();
				unset($this->excel);
				echo json_encode(array('link' => 'true', 'msg' => site_url() . "uploads/$filename"));
				exit_function();
			}
			else
			{
				echo json_encode(array('link' => 'false', 'msg' => 'No Record available for limited csv export'));
				exit_function();
			}
		}
		else
		{
			$query = $this->instantiation->export_limited_csv(TRUE);
			$record = array('user_id' => $this->user_id, 'status' => 0, 'export_query' => $query, 'query_loop' => ceil($records['total_count'] / 100000));
			$this->csv_job->insert_job($record);
			echo json_encode(array('link' => 'false', 'msg' => 'Email will be sent to you with the link of limited csv export.'));
			exit_function();
		}
//								}
//								show_404();
	}

	public function instantiation_table()
	{
		$params = array('search' => '');
		$column = array(
			'Organization' => 'organization',
			'Instantiation_ID' => 'instantiation_identifier',
			'Nomination' => 'status',
			'Instantiation\'s_Asset_Title' => 'asset_title',
			'Media_Type' => 'media_type',
			'Generation' => 'generation',
			'Format' => 'format_name',
			'Duration' => 'projected_duration',
			'Date' => 'dates',
			'File_size' => 'file_size',
			'Colors' => 'color',
			'Language' => 'language',
		);


		$this->session->unset_userdata('column');
		$this->session->unset_userdata('jscolumn');
		$this->session->unset_userdata('column_order');
		$this->session->set_userdata('jscolumn', $this->input->get('iSortCol_0'));
		$this->session->set_userdata('column', $column[$this->column_order[$this->input->get('iSortCol_0')]['title']]);
		$this->session->set_userdata('column_order', $this->input->get('sSortDir_0'));


		$offset = isset($this->session->userdata['offset']) ? $this->session->userdata['offset'] : 0;
		$records = $this->sphinx->instantiations_list($params, $offset);
		$data['total'] = $records['total_count'];
		$records = $records['records'];
		$data['count'] = count($records);
		$table_view = instantiations_datatable_view($records, $this->column_order);

		$dataTable = array(
			"sEcho" => intval($this->input->get('sEcho')),
			"iTotalRecords" => intval($data['count']),
			"iTotalDisplayRecords" => intval($data['count']),
			'aaData' => $table_view
		);
		echo json_encode($dataTable);
		exit_function();
	}

	function load_facet_columns()
	{
		if (isAjax())
		{
			$is_all_facet = $this->input->post('issearch');
			$index = $this->input->post('index');
			if ($is_all_facet > 0)
			{
				$states = $this->sphinx->facet_index('state', $index);
				$data['org_states'] = sortByOneKey($states['records'], 'state');
				unset($states);

				$stations = $this->sphinx->facet_index('organization', $index);

				$data['stations'] = sortByOneKey($stations['records'], 'organization');
				unset($stations);
				$nomination = $this->sphinx->facet_index('status', $index);

				$data['nomination_status'] = sortByOneKey($nomination['records'], 'status');
				unset($nomination);
				$media_type = $this->sphinx->facet_index('media_type', $index);

				$data['media_types'] = sortByOneKey($media_type['records'], 'media_type', TRUE);

				unset($media_type);
				$p_format = $this->sphinx->facet_index('format_name', $index, 'physical');

				$data['physical_formats'] = sortByOneKey($p_format['records'], 'format_name', TRUE);
				unset($p_format);
				$d_format = $this->sphinx->facet_index('format_name', $index, 'digital');

				$data['digital_formats'] = sortByOneKey($d_format['records'], 'format_name', TRUE);
				unset($d_format);
				$generation = $this->sphinx->facet_index('facet_generation', $index);

				$data['generations'] = sortByOneKey($generation['records'], 'facet_generation', TRUE);
				unset($generation);

				$digitized = $this->sphinx->facet_index('digitized', $index, 'digitized');
				$data['digitized'] = $digitized['records'];

				$migration = $this->sphinx->facet_index('migration', $index, 'migration');
				$data['migration'] = $migration['records'];
			}
			else
			{
				if ($index == 'assets_list')
				{
					$key_name = 'asset';
				}
				else
				{
					$key_name = 'ins';
				}
				$data['org_states'] = json_decode($this->memcached_library->get($key_name . '_state'), TRUE);

				$data['stations'] = json_decode($this->memcached_library->get($key_name . '_stations'), TRUE);

				$data['nomination_status'] = json_decode($this->memcached_library->get($key_name . '_status'), TRUE);
				$data['media_types'] = json_decode($this->memcached_library->get($key_name . '_media_type'), TRUE);
				$data['physical_formats'] = json_decode($this->memcached_library->get($key_name . '_physical'), TRUE);
				$data['digital_formats'] = json_decode($this->memcached_library->get($key_name . '_digital'), TRUE);
				$data['generations'] = json_decode($this->memcached_library->get($key_name . '_generations'), TRUE);


				$data['digitized'] = json_decode($this->memcached_library->get($key_name . '_digitized'), TRUE);

				$data['migration'] = json_decode($this->memcached_library->get($key_name . '_migration'), TRUE);
			}

			echo $this->load->view('instantiations/_facet_columns', $data, TRUE);
			exit_function();
		}
		show_404();
	}

}

// END Instantiations Controller

// End of file instantiations.php 
/* Location: ./application/controllers/instantiations.php */
