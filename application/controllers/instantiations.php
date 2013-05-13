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
//		debug($data['records']);
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
				$data['inst_relation'] = $this->manage_asset->get_relation_by_instantiation_id($instantiation_id);

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


			$detail = $data['instantiation_detail'] = $this->instantiation->get_by_id($instantiation_id);

			if (count($data['instantiation_detail']) > 0)
			{

				if ($this->input->post())
				{
					/* Instantiation Identifier Start */
					if ($this->input->post('instantiation_id_identifier'))
					{
						foreach ($this->input->post('instantiation_id_identifier') as $index => $ins_identifier)
						{
							$identifier['instantiation_identifier'] = $ins_identifier;
							$ins_identifer_id = $this->input->post('instantiation_id_identifier_id');
							$ins_source = $this->input->post('instantiation_id_source');
							if (isset($ins_source[$index]) && ! empty($ins_source[$index]))
								$identifier['instantiation_source'] = $ins_source[$index];

							if (isset($ins_identifer_id[$index]) && ! empty($ins_identifer_id[$index]))
							{

								$this->instantiation->update_instantiation_identifier_by_id($ins_identifer_id[$index], $identifier);
							}
							else
							{

								$identifier['instantiations_id'] = $instantiation_id;

								$this->instantiation->insert_instantiation_identifier($identifier);
							}
						}
					}
					else if ($this->input->post('instantiation_id_source'))
					{
						foreach ($this->input->post('instantiation_id_source') as $index => $identifier_src)
						{
							$ins_identifer_id = $this->input->post('instantiation_id_identifier_id');
							if ( ! empty($identifier_src))
							{
								$identifier['instantiation_source'] = $identifier_src;
								$this->instantiation->update_instantiation_identifier_by_id($ins_identifer_id[$index], $identifier);
							}
						}
					}

					/* Instantiation Identifier End */
					/* Nomination Start */

					$nomination = $this->input->post('nomination');
					$reason = $this->input->post('nomination_reason');
					$nomination_exist = $this->assets_model->get_nominations($instantiation_id);
					if ( ! empty($nomination))
					{
						$nomination_id = $this->assets_model->get_nomination_status_by_status($nomination)->id;

						$nomination_record = array('nomination_status_id' => $nomination_id, 'nomination_reason' => $reason, 'nominated_by' => $this->user_id, 'nominated_at' => date('Y-m-d H:i:s'));
						if ($nomination_exist)
						{
							$nomination_record['updated'] = date('Y-m-d H:i:s');
							$this->assets_model->update_nominations($instantiation_id, $nomination_record);
						}
						else
						{
							$nomination_record['instantiations_id'] = $instantiation_id;
							$nomination_record['created'] = date('Y-m-d H:i:s');
							$this->assets_model->insert_nominations($nomination_record);
						}
						$this->sphinx->update_indexes('instantiations_list', array('nomination_status_id'), array($instantiation_id => array((int) $nomination_id)));
						$this->sphinx->update_indexes('assets_list', array('nomination_status_id'), array($detail->assets_id => array((int) $nomination_id)));
					}
					else
					{
						if ($nomination_exist)
						{
							$this->manage_asset->delete_row($instantiation_id, 'nominations', 'instantiations_id');
							$this->sphinx->update_indexes('instantiations_list', array('nomination_status_id'), array($instantiation_id => array((int) 0)));
							$this->sphinx->update_indexes('assets_list', array('nomination_status_id'), array($detail->assets_id => array((int) 0)));
						}
					}
					/* Nomination End */
					/* Media Type Start */
					$media_type = $this->input->post('media_type');
					$db_media_type = $this->instantiation->get_instantiation_media_types_by_media_type($media_type);
					if ($db_media_type)
					{
						$update_instantiation['instantiation_media_type_id'] = $db_media_type->id;
					}
					else
					{
						$update_instantiation['instantiation_media_type_id'] = $this->instantiation->insert_instantiation_media_types(array('media_type' => $media_type));
					}
					/* Media Type End */
					/* Generation Start */
					if ($this->input->post('generation'))
					{

						$this->manage_asset->delete_row($instantiation_id, 'instantiation_generations', 'instantiations_id');
						foreach ($this->input->post('generation') as $row)
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
							$this->instantiation->insert_instantiation_generations(array('instantiations_id' => $instantiation_id, 'generations_id' => $db_gen_id));
						}
					}
					/* Generation End */
					if ($this->input->post('instantiation_id_identifier'))
					{
						/* Date Start */
						if ($this->input->post('inst_date'))
						{
							$this->manage_asset->delete_row($instantiation_id, 'instantiation_dates', 'instantiations_id');
							foreach ($this->input->post('inst_date') as $index => $value)
							{
								$inst_date_types = $this->input->post('inst_date_type');
								if ( ! empty($value))
								{
									$date_type = $this->instantiation->get_date_types_by_type($inst_date_types[$index]);
									if (isset($date_type) && isset($date_type->id))
										$instantiation_dates_d['date_types_id'] = $date_type->id;
									else
										$instantiation_dates_d['date_types_id'] = $this->instantiation->insert_date_types(array('date_type' => $inst_date_types[$index]));
									$instantiation_dates_d['instantiation_date'] = $value;
									$instantiation_dates_d['instantiations_id'] = $instantiation_id;
									$this->instantiation->insert_instantiation_dates($instantiation_dates_d);
								}
							}
						}
						/* Date End */
						/* Demension Start */
						if ($this->input->post('asset_dimension'))
						{

							$this->manage_asset->delete_row($instantiation_id, 'instantiation_dimensions', 'instantiations_id');
							foreach ($this->input->post('asset_dimension') as $index => $value)
							{
								$unit_measure = $this->input->post('dimension_unit');
								$instantiation_dimension_d['instantiations_id'] = $instantiation_id;
								$instantiation_dimension_d['instantiation_dimension'] = $value;
								$instantiation_dimension_d['unit_of_measure'] = $unit_measure[$index];
								$this->instantiation->insert_instantiation_dimensions($instantiation_dimension_d);
							}
						}
						/* Demension End */
						/* Physical Format Start */

						$physical_format = $this->instantiation->get_format_by_instantiation_id($instantiation_id);

						$instantiation_format_physical_d['format_name'] = $this->input->post('physical_format');
						$instantiation_format_physical_d['format_type'] = 'physical';

						if (count($physical_format) > 0)
						{
							$instantiation_format_physical_id = $this->instantiation->update_instantiation_formats($physical_format->id, $instantiation_format_physical_d);
						}
						else
						{
							$instantiation_format_physical_d['instantiations_id'] = $instantiation_id;
							$instantiation_format_physical_id = $this->instantiation->insert_instantiation_formats($instantiation_format_physical_d);
						}

						/* Physical Format End */
						/* Standard Start */
						if ($this->input->post('standard'))
						{
							$update_instantiation['standard'] = $this->input->post('standard');
						}
						/* Standard End */
						/* Location Start */
						if ($this->input->post('location'))
						{
							$update_instantiation['location'] = $this->input->post('location');
						}
						/* Location End */
						/* Time Start Start */
						if ($this->input->post('time_start'))
						{
							$update_instantiation['time_start'] = $this->input->post('time_start');
						}
						/* Time Start End */
						/* Porjected Duration Start */
						if ($this->input->post('projected_duration'))
						{
							$update_instantiation['projected_duration'] = $this->input->post('projected_duration');
						}
						/* Porjected Duration End */
						/* Porjected Alernative Modes Start */
						if ($this->input->post('alternative_modes'))
						{
							$update_instantiation['alternative_modes'] = $this->input->post('alternative_modes');
						}
						/* Porjected Alernative Modes End */
						/* Color Start */
						if ($this->input->post('color'))
						{

							$inst_color_d = $this->instantiation->get_instantiation_colors_by_color($this->input->post('color'));
							if (isset($inst_color_d) && ! is_empty($inst_color_d))
							{
								$update_instantiation['instantiation_colors_id'] = $inst_color_d->id;
							}
							else
							{
								$update_instantiation['instantiation_colors_id'] = $this->instantiation->insert_instantiation_colors(array('color' => $this->input->post('color')));
							}
						}
						/* Color End */
						/* Tracks Start */
						if ($this->input->post('tracks'))
						{
							$update_instantiation['tracks'] = $this->input->post('tracks');
						}
						/* Tracks End */
						/* Channel Configuration Start */
						if ($this->input->post('channel_configuration'))
						{
							$update_instantiation['channel_configuration'] = $this->input->post('channel_configuration');
						}
						/* Channel Configuration End */
					}
					/* Language Configuration Start */
					if ($this->input->post('language'))
					{
						$update_instantiation['language'] = $this->input->post('language');
					}
					/* Language Configuration End */
					/* Update Instantiation */
					$this->instantiation->update_instantiations($instantiation_id, $update_instantiation);
					if ($this->input->post('instantiation_id_identifier'))
					{
						/* Annotation Start */
						if ($this->input->post('annotation'))
						{

							$this->manage_asset->delete_row($instantiation_id, 'instantiation_annotations', 'instantiations_id');
							foreach ($this->input->post('annotation') as $index => $value)
							{
								if ( ! empty($value))
								{
									$annotation_type = $this->input->post('annotation_type');
									$instantiation_annotation_d['instantiations_id'] = $instantiation_id;
									$instantiation_annotation_d['annotation'] = $value;
									$instantiation_annotation_d['annotation_type'] = $annotation_type[$index];
									$this->instantiation->insert_instantiation_annotations($instantiation_annotation_d);
								}
							}
						}
						/* Annotation End */
						/* Relation Start */
						if ($this->input->post('relation'))
						{
							$this->manage_asset->delete_row($instantiation_id, 'instantiation_relations', 'instantiations_id');
							$relation_src = $this->input->post('relation_source');
							$relation_ref = $this->input->post('relation_ref');
							$relation_type = $this->input->post('relation_type');
							foreach ($this->input->post('relation') as $index => $value)
							{
								if ( ! empty($value))
								{
									$relation['instantiations_id'] = $instantiation_id;
									$relation['relation_identifier'] = $value;
									$relation_types['relation_type'] = $relation_type[$index];
									if ( ! empty($relation_src[$index]))
										$relation_types['relation_type_source'] = $relation_src[$index];
									if ( ! empty($relation_ref[$index]))
										$relation_types['relation_type_ref'] = $relation_ref[$index];
									$db_relations = $this->assets_model->get_relation_types_all($relation_types);
									if (isset($db_relations) && isset($db_relations->id))
									{
										$relation['relation_types_id'] = $db_relations->id;
									}
									else
									{
										$relation['relation_types_id'] = $this->assets_model->insert_relation_types($relation_types);
									}
									$this->instantiation->insert_instantiation_relation($relation);
								}
							}
						}
						/* Relation End */

						/* Essence Track Frame Size Start */
						$db_essence_track = FALSE;
						if ($this->input->post('width') && $this->input->post('height'))
						{
							$width = $this->input->post('width');
							$height = $this->input->post('height');
							if ( ! empty($width) && ! empty($height))
							{
								$db_essence_track = TRUE;
								$track_frame_size_d = $this->essence_track->get_essence_track_frame_sizes_by_width_height(trim($this->input->post('width')), trim($this->input->post('height')));
								if ($track_frame_size_d)
								{
									$essence_tracks_d['essence_track_frame_sizes_id'] = $track_frame_size_d->id;
								}
								else
								{
									$essence_tracks_d['essence_track_frame_sizes_id'] = $this->essence_track->insert_essence_track_frame_sizes(array("width" => $this->input->post('width'), "height" => $this->input->post('height')));
								}
							}
						}
						/* Essence Track Frame Size End */
						/* Essence Track Frame Rate Start */
						if ($frame_rate = $this->input->post('frame_rate'))
						{
							if ( ! empty($frame_rate))
							{
								$db_essence_track = TRUE;
								$essence_tracks_d['frame_rate'] = $this->input->post('frame_rate');
							}
						}
						/* Essence Track Frame Rate End */
						/* Essence Track Playback Speed Start */
						if ($playback_speed = $this->input->post('playback_speed'))
						{
							if ( ! empty($playback_speed))
							{
								$db_essence_track = TRUE;
								$essence_tracks_d['playback_speed'] = $this->input->post('playback_speed');
							}
						}
						/* Essence Track Playback Speed End */
						/* Essence Track Sampling Rate Start */
						if ($sampling_rate = $this->input->post('sampling_rate'))
						{
							if ( ! empty($sampling_rate))
							{
								$db_essence_track = TRUE;
								$essence_tracks_d['sampling_rate'] = $this->input->post('sampling_rate');
							}
						}
						/* Essence Track Sampling Rate End */
						/* Essence Track Aspect Ratio Start */
						if ($aspect_ratio = $this->input->post('aspect_ratio'))
						{
							if ( ! empty($aspect_ratio))
							{
								$db_essence_track = TRUE;
								$essence_tracks_d['aspect_ratio'] = $this->input->post('aspect_ratio');
							}
						}
						/* Essence Track Aspect Ratio End */
						/* Essence Track Type Start */
						$essence_track_type_d = $this->essence_track->get_essence_track_by_type('General');
						if (isset($essence_track_type_d) && isset($essence_track_type_d->id))
						{
							$essence_tracks_d['essence_track_types_id'] = $essence_track_type_d->id;
						}
						else
						{
							$essence_tracks_d['essence_track_types_id'] = $this->essence_track->insert_essence_track_types(array('essence_track_type' => 'General'));
						}
						/* Essence Track Type End */


						/* Essence Track Start */
						if ($db_essence_track)
						{
							$essence_track = $this->manage_asset->get_single_essence_tracks_by_instantiations_id($instantiation_id);
							if ($essence_track)
							{
								$this->essence_track->update_essence_track($essence_track->id, $essence_tracks_d);
							}
							else
							{
								$essence_tracks_d['instantiations_id'] = $instantiation_id;
								$this->essence_track->insert_essence_tracks($essence_tracks_d);
							}
						}
						/* Essence Track End */
					}
					redirect('instantiations/detail/' . $instantiation_id);
				}
				$data['asset_id'] = $detail->assets_id;
				$data['inst_id'] = $instantiation_id;
				$data['list_assets'] = $this->instantiation->get_instantiations_by_asset_id($detail->assets_id);
				$data['ins_nomination'] = $this->instantiation->get_nomination_by_instantiation_id($instantiation_id);
				$data['inst_identifier'] = $this->manage_asset->get_identifier_by_instantiation_id($instantiation_id);
				$data['date'] = $this->manage_asset->get_dates_by_instantiation_id($instantiation_id);
				$data['inst_demension'] = $this->manage_asset->get_demension_by_instantiation_id($instantiation_id);
				$data['inst_format'] = $this->instantiation->get_format_by_instantiation_id($instantiation_id);
				$data['inst_media_type'] = $this->instantiation->get_media_type_by_instantiation_media_id($detail->instantiation_media_type_id);
				$data['inst_generation'] = $this->instantiation->get_generation_by_instantiation_id($instantiation_id);
				$data['inst_data_rate_unit'] = $this->instantiation->get_data_rate_unit_by_data_id($detail->data_rate_units_id);
				$data['inst_color'] = $this->instantiation->get_color_by_instantiation_colors_id($detail->instantiation_colors_id);
				$data['inst_annotation'] = $this->manage_asset->get_annotation_by_instantiation_id($instantiation_id);
				$data['inst_relation'] = $this->manage_asset->get_relation_by_instantiation_id($instantiation_id);
				$data['asset_details'] = $this->assets_model->get_asset_by_asset_id($detail->assets_id);
				$data['essence_track'] = $this->manage_asset->get_single_essence_tracks_by_instantiations_id($instantiation_id);

				$data['pbcore_asset_date_types'] = $this->manage_asset->get_picklist_values(2);
				$data['pbcore_media_types'] = $this->manage_asset->get_picklist_values(11);
				$data['pbcore_generations'] = $this->manage_asset->get_picklist_values(12);
				$data['pbcore_relation_types'] = $this->manage_asset->get_picklist_values(7);
				$data['pbcore_standards'] = $this->manage_asset->get_picklist_values(14);
				$data['pbcore_colors'] = $this->manage_asset->get_picklist_values(15);
				$data['pbcore_physical_formats'] = $this->manage_asset->get_picklist_values(13);
				$data['nominations'] = $this->instantiation->get_nomination_status();
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

	public function add()
	{
		$asset_id = $data['asset_id'] = $this->uri->segment(3);
		if ($this->input->post())
		{
			/* Media Type Start */
			$media_type = $this->input->post('media_type');
			$db_media_type = $this->instantiation->get_instantiation_media_types_by_media_type($media_type);
			if ($db_media_type)
			{
				$update_instantiation['instantiation_media_type_id'] = $db_media_type->id;
			}
			else
			{
				$update_instantiation['instantiation_media_type_id'] = $this->instantiation->insert_instantiation_media_types(array('media_type' => $media_type));
			}
			/* Media Type End */
			/* Standard Start */
			if ($this->input->post('standard'))
			{
				$update_instantiation['standard'] = $this->input->post('standard');
			}
			/* Standard End */
			/* Location Start */
			if ($this->input->post('location'))
			{
				$update_instantiation['location'] = $this->input->post('location');
			}
			/* Location End */
			/* Time Start Start */
			if ($this->input->post('time_start'))
			{
				$update_instantiation['time_start'] = $this->input->post('time_start');
			}
			/* Time Start End */
			/* Porjected Duration Start */
			if ($this->input->post('projected_duration'))
			{
				$update_instantiation['projected_duration'] = $this->input->post('projected_duration');
			}
			/* Porjected Duration End */
			/* Porjected Alernative Modes Start */
			if ($this->input->post('alternative_modes'))
			{
				$update_instantiation['alternative_modes'] = $this->input->post('alternative_modes');
			}
			/* Porjected Alernative Modes End */
			/* Color Start */
			if ($this->input->post('color'))
			{

				$inst_color_d = $this->instantiation->get_instantiation_colors_by_color($this->input->post('color'));
				if (isset($inst_color_d) && ! is_empty($inst_color_d))
				{
					$update_instantiation['instantiation_colors_id'] = $inst_color_d->id;
				}
				else
				{
					$update_instantiation['instantiation_colors_id'] = $this->instantiation->insert_instantiation_colors(array('color' => $this->input->post('color')));
				}
			}
			/* Color End */
			/* Tracks Start */
			if ($this->input->post('tracks'))
			{
				$update_instantiation['tracks'] = $this->input->post('tracks');
			}
			/* Tracks End */
			/* Channel Configuration Start */
			if ($this->input->post('channel_configuration'))
			{
				$update_instantiation['channel_configuration'] = $this->input->post('channel_configuration');
			}
			/* Channel Configuration End */

			/* Language Configuration Start */
			if ($this->input->post('language'))
			{
				$update_instantiation['language'] = $this->input->post('language');
			}
			/* Language Configuration End */
			/* Insert Instantiation Start */
			$update_instantiation['assets_id'] = $asset_id;
			$instantiation_id = $this->instantiation->insert_instantiations($update_instantiation);
			/* Insert Instantiation End */
			/* Instantiation Identifier Start */
			if ($this->input->post('instantiation_id_identifier'))
			{
				foreach ($this->input->post('instantiation_id_identifier') as $index => $ins_identifier)
				{
					$identifier['instantiation_identifier'] = $ins_identifier;

					$ins_source = $this->input->post('instantiation_id_source');
					if (isset($ins_source[$index]) && ! empty($ins_source[$index]))
						$identifier['instantiation_source'] = $ins_source[$index];
					$identifier['instantiations_id'] = $instantiation_id;

					$this->instantiation->insert_instantiation_identifier($identifier);
				}
			}
			/* Instantiation Identifier End */
			/* Nomination Start */

			$nomination = $this->input->post('nomination');
			$reason = $this->input->post('nomination_reason');

			if ( ! empty($nomination))
			{
				$nomination_id = $this->assets_model->get_nomination_status_by_status($nomination)->id;

				$nomination_record = array('nomination_status_id' => $nomination_id, 'nomination_reason' => $reason, 'nominated_by' => $this->user_id, 'nominated_at' => date('Y-m-d H:i:s'));

				$nomination_record['instantiations_id'] = $instantiation_id;
				$nomination_record['created'] = date('Y-m-d H:i:s');
				$this->assets_model->insert_nominations($nomination_record);
			}

			/* Nomination End */
			/* Generation Start */
			if ($this->input->post('generation'))
			{
				foreach ($this->input->post('generation') as $row)
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
					$this->instantiation->insert_instantiation_generations(array('instantiations_id' => $instantiation_id, 'generations_id' => $db_gen_id));
				}
			}
			/* Generation End */

			/* Date Start */
			if ($this->input->post('inst_date'))
			{
				foreach ($this->input->post('inst_date') as $index => $value)
				{
					$inst_date_types = $this->input->post('inst_date_type');
					if ( ! empty($value))
					{
						$date_type = $this->instantiation->get_date_types_by_type($inst_date_types[$index]);
						if (isset($date_type) && isset($date_type->id))
							$instantiation_dates_d['date_types_id'] = $date_type->id;
						else
							$instantiation_dates_d['date_types_id'] = $this->instantiation->insert_date_types(array('date_type' => $inst_date_types[$index]));
						$instantiation_dates_d['instantiation_date'] = $value;
						$instantiation_dates_d['instantiations_id'] = $instantiation_id;
						$this->instantiation->insert_instantiation_dates($instantiation_dates_d);
					}
				}
			}
			/* Date End */
			/* Demension Start */
			if ($this->input->post('asset_dimension'))
			{
				foreach ($this->input->post('asset_dimension') as $index => $value)
				{
					$unit_measure = $this->input->post('dimension_unit');
					$instantiation_dimension_d['instantiations_id'] = $instantiation_id;
					$instantiation_dimension_d['instantiation_dimension'] = $value;
					$instantiation_dimension_d['unit_of_measure'] = $unit_measure[$index];
					$this->instantiation->insert_instantiation_dimensions($instantiation_dimension_d);
				}
			}
			/* Demension End */
			/* Physical Format Start */
			if ($this->input->post('physical_format'))
			{
				$instantiation_format_physical_d['format_name'] = $this->input->post('physical_format');
				$instantiation_format_physical_d['format_type'] = 'physical';
				$instantiation_format_physical_d['instantiations_id'] = $instantiation_id;
				$instantiation_format_physical_id = $this->instantiation->insert_instantiation_formats($instantiation_format_physical_d);
			}

			/* Physical Format End */
			/* Annotation Start */
			if ($this->input->post('annotation'))
			{
				foreach ($this->input->post('annotation') as $index => $value)
				{
					if ( ! empty($value))
					{
						$annotation_type = $this->input->post('annotation_type');
						$instantiation_annotation_d['instantiations_id'] = $instantiation_id;
						$instantiation_annotation_d['annotation'] = $value;
						$instantiation_annotation_d['annotation_type'] = $annotation_type[$index];
						$this->instantiation->insert_instantiation_annotations($instantiation_annotation_d);
					}
				}
			}
			/* Annotation End */
			/* Relation Start */
			if ($this->input->post('relation'))
			{
				$relation_src = $this->input->post('relation_source');
				$relation_ref = $this->input->post('relation_ref');
				$relation_type = $this->input->post('relation_type');
				foreach ($this->input->post('relation') as $index => $value)
				{
					if ( ! empty($value))
					{
						$relation['instantiations_id'] = $instantiation_id;
						$relation['relation_identifier'] = $value;
						$relation_types['relation_type'] = $relation_type[$index];
						if ( ! empty($relation_src[$index]))
							$relation_types['relation_type_source'] = $relation_src[$index];
						if ( ! empty($relation_ref[$index]))
							$relation_types['relation_type_ref'] = $relation_ref[$index];
						$db_relations = $this->assets_model->get_relation_types_all($relation_types);
						if (isset($db_relations) && isset($db_relations->id))
						{
							$relation['relation_types_id'] = $db_relations->id;
						}
						else
						{
							$relation['relation_types_id'] = $this->assets_model->insert_relation_types($relation_types);
						}
						$this->instantiation->insert_instantiation_relation($relation);
					}
				}
			}
			/* Relation End */
			/* Essence Track Frame Size Start */
			$db_essence_track = FALSE;
			if ($this->input->post('width') && $this->input->post('height'))
			{
				$width = $this->input->post('width');
				$height = $this->input->post('height');
				if ( ! empty($width) && ! empty($height))
				{
					$db_essence_track = TRUE;
					$track_frame_size_d = $this->essence_track->get_essence_track_frame_sizes_by_width_height(trim($this->input->post('width')), trim($this->input->post('height')));
					if ($track_frame_size_d)
					{
						$essence_tracks_d['essence_track_frame_sizes_id'] = $track_frame_size_d->id;
					}
					else
					{
						$essence_tracks_d['essence_track_frame_sizes_id'] = $this->essence_track->insert_essence_track_frame_sizes(array("width" => $this->input->post('width'), "height" => $this->input->post('height')));
					}
				}
			}
			/* Essence Track Frame Size End */
			/* Essence Track Frame Rate Start */
			if ($frame_rate = $this->input->post('frame_rate'))
			{
				if ( ! empty($frame_rate))
				{
					$db_essence_track = TRUE;
					$essence_tracks_d['frame_rate'] = $this->input->post('frame_rate');
				}
			}
			/* Essence Track Frame Rate End */
			/* Essence Track Playback Speed Start */
			if ($playback_speed = $this->input->post('playback_speed'))
			{
				if ( ! empty($playback_speed))
				{
					$db_essence_track = TRUE;
					$essence_tracks_d['playback_speed'] = $this->input->post('playback_speed');
				}
			}
			/* Essence Track Playback Speed End */
			/* Essence Track Sampling Rate Start */
			if ($sampling_rate = $this->input->post('sampling_rate'))
			{
				if ( ! empty($sampling_rate))
				{
					$db_essence_track = TRUE;
					$essence_tracks_d['sampling_rate'] = $this->input->post('sampling_rate');
				}
			}
			/* Essence Track Sampling Rate End */
			/* Essence Track Aspect Ratio Start */
			if ($aspect_ratio = $this->input->post('aspect_ratio'))
			{
				if ( ! empty($aspect_ratio))
				{
					$db_essence_track = TRUE;
					$essence_tracks_d['aspect_ratio'] = $this->input->post('aspect_ratio');
				}
			}
			/* Essence Track Aspect Ratio End */
			/* Essence Track Type Start */
			$essence_track_type_d = $this->essence_track->get_essence_track_by_type('General');
			if (isset($essence_track_type_d) && isset($essence_track_type_d->id))
			{
				$essence_tracks_d['essence_track_types_id'] = $essence_track_type_d->id;
			}
			else
			{
				$essence_tracks_d['essence_track_types_id'] = $this->essence_track->insert_essence_track_types(array('essence_track_type' => 'General'));
			}
			/* Essence Track Type End */


			/* Essence Track Start */
			if ($db_essence_track)
			{
				$essence_tracks_d['instantiations_id'] = $instantiation_id;
				$this->essence_track->insert_essence_tracks($essence_tracks_d);
			}
			/* Essence Track End */
			if ($this->input->post('add_another'))
			{
				redirect('instantiations/add/' . $asset_id);
			}
			else
			{
				redirect('records/details/' . $asset_id);
			}
		}
		$data['asset_id'] = $asset_id;
		$data['pbcore_asset_date_types'] = $this->manage_asset->get_picklist_values(2);
		$data['pbcore_media_types'] = $this->manage_asset->get_picklist_values(11);
		$data['pbcore_generations'] = $this->manage_asset->get_picklist_values(12);
		$data['pbcore_relation_types'] = $this->manage_asset->get_picklist_values(7);
		$data['pbcore_standards'] = $this->manage_asset->get_picklist_values(14);
		$data['pbcore_colors'] = $this->manage_asset->get_picklist_values(15);
		$data['pbcore_physical_formats'] = $this->manage_asset->get_picklist_values(13);
		$data['nominations'] = $this->instantiation->get_nomination_status();
		$this->load->view('instantiations/add', $data);
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
			'Nomination' => 'nomination_status_id',
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
		$this->session->set_userdata('jscolumn', $this->input->post('iSortCol_0'));
		$this->session->set_userdata('column', $column[$this->column_order[$this->input->post('iSortCol_0')]['title']]);
		$this->session->set_userdata('column_order', $this->input->post('sSortDir_0'));


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
				$nomination = $this->sphinx->facet_index('nomination_status_id', $index);

				$nomination_status = sortByOneKey($nomination['records'], 'nomination_status_id');
				$data['nomination_status'] = array();
				foreach ($nomination_status as $key => $status)
				{
					if ($status['nomination_status_id'] != 0)
					{
						$data['nomination_status'][$key]['status'] = $this->sphinx->get_nomination_status($status['nomination_status_id'])->status;
						$data['nomination_status'][$key]['@count'] = $status['@count'];
					}
				}

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

				$nomination_status = json_decode($this->memcached_library->get($key_name . '_status'), TRUE);
				$data['nomination_status'] = array();
				foreach ($nomination_status as $key => $status)
				{
					if ($status['nomination_status_id'] != 0)
					{
						$data['nomination_status'][$key]['status'] = $this->sphinx->get_nomination_status($status['nomination_status_id'])->status;
						$data['nomination_status'][$key]['@count'] = $status['@count'];
					}
				}
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
