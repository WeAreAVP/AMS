<?php

/**
 * Dashboard Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Dashboard Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AVPS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Searchd extends MY_Controller
{

	/**
	 * Constructor.
	 * 
	 * Load the layout for the dashboard.
	 *  
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->library('sphnixrt');
		$this->load->model('searchd_model');
	}

	/**
	 * Dashboard Functionality
	 * 
	 * @return view dashboard/index
	 */
	function station_insert()
	{


		$stations = $this->station_model->get_all();
		foreach ($stations as $key => $row)
		{
			if ($row->type == 0)
				$type = 'Radio';
			else if ($row->type == 1)
				$type = 'TV';
			else if ($row->type == 2)
				$type = 'JOINT';

			$record = array(
				's_station_name' => $row->station_name,
				'station_name' => $row->station_name,
				's_type' => $type,
				'type' => $type,
				's_address_primary' => $row->address_primary,
				'address_primary' => $row->address_primary,
				's_address_secondary' => ! empty($row->address_secondary) ? $row->address_secondary : '',
				'address_secondary' => ! empty($row->address_secondary) ? $row->address_secondary : '',
				's_city' => $row->city,
				'city' => $row->city,
				's_state' => $row->state,
				'state' => $row->state,
				's_zip' => $row->zip,
				'zip' => $row->zip,
				's_cpb_id' => $row->cpb_id,
				'cpb_id' => $row->cpb_id,
				'allocated_hours' => (int) $row->allocated_hours,
				'allocated_buffer' => (int) $row->allocated_buffer,
				'total_allocated' => (int) $row->total_allocated,
				'is_certified' => (int) $row->is_certified,
				'is_agreed' => (int) $row->is_agreed,
				'start_date' => (int) strtotime($row->start_date),
				'end_date' => (int) strtotime($row->end_date)
			);
			$this->sphnixrt->insert('stations', $record, $row->id);
		}
		$data = $this->sphnixrt->select('stations', array('start' => 0, 'limit' => 1000));
		debug($data, FALSE);
		echo count($data['records']);
		exit;
	}
	
	function index()
	{
		$db_count = 0;
		$offset = 0;
		while ($db_count == 0)
		{

			$records = $this->searchd_model->get_ins_index($ids);
			foreach ($records as $row)
			{
				$data = array(
					'assets_id' => (int) $row->asset_id,
					'organization' => ! empty($row->organization) ? $row->organization : '',
					'state' => ! empty($row->state) ? $row->state : '',
					'standard' => ! empty($row->standard) ? $row->standard : '',
					'data_rate' => ! empty($row->data_rate) ? $row->data_rate : '',
					'location' => ! empty($row->location) ? $row->location : '',
					'tracks' => ! empty($row->tracks) ? $row->tracks : '',
					'digitized' => ! empty($row->digitized) ? $row->digitized : '',
					'language' => ! empty($row->language) ? $row->language : '',
					'actual_duration' => ! empty($row->actual_duration) ? (int) $row->actual_duration : 0,
					'projected_duration' => ! empty($row->projected_duration) ? $row->projected_duration : '',
					'file_size_unit_of_measure' => ! empty($row->file_size_unit_of_measure) ? $row->file_size_unit_of_measure : '',
					'file_size' => ! empty($row->file_size) ? $row->file_size : '',
					'channel_configuration' => ! empty($row->channel_configuration) ? $row->channel_configuration : '',
					'alternative_modes' => ! empty($row->alternative_modes) ? $row->alternative_modes : '',
					'data_rate_unit_of_measure' => ! empty($row->data_rate_unit_of_measure) ? $row->data_rate_unit_of_measure : '',
					'dates' => ! empty($row->dates) ? (int) $row->dates : 0,
					'date_type' => ! empty($row->date_type) ? $row->date_type : '',
					'media_type' => ! empty($row->media_type) ? $row->media_type : '',
					'format_type' => ! empty($row->format_type) ? $row->format_type : '',
					'format_name' => ! empty($row->format_name) ? $row->format_name : '',
					'color' => ! empty($row->color) ? $row->color : '',
					'generation' => ! empty($row->generation) ? $row->generation : '',
					'facet_generation' => ! empty($row->facet_generation) ? $row->facet_generation : '',
					'status' => ! empty($row->status) ? $row->status : '',
					'outcome_event' => ! empty($row->outcome_event) ? $row->outcome_event : '',
					'event_type' => ! empty($row->event_type) ? $row->event_type : '',
					'event_date' => ! empty($row->event_date) ? $row->event_date : '',
					'instantiation_identifier' => ! empty($row->instantiation_identifier) ? $row->instantiation_identifier : '',
					'instantiation_source' => ! empty($row->instantiation_source) ? $row->instantiation_source : '',
					'instantiation_dimension' => ! empty($row->instantiation_dimension) ? $row->instantiation_dimension : '',
					'unit_of_measure' => ! empty($row->unit_of_measure) ? $row->unit_of_measure : '',
					'track_standard' => ! empty($row->track_standard) ? $row->track_standard : '',
					'track_duration' => ! empty($row->track_duration) ? $row->track_duration : '',
					'track_language' => ! empty($row->track_language) ? $row->track_language : '',
					'track_frame_rate' => ! empty($row->track_frame_rate) ? $row->track_frame_rate : '',
					'track_playback_speed' => ! empty($row->track_playback_speed) ? $row->track_playback_speed : '',
					'track_sampling_rate' => ! empty($row->track_sampling_rate) ? $row->track_sampling_rate : '',
					'track_bit_depth' => ! empty($row->track_bit_depth) ? $row->track_bit_depth : '',
					'track_aspect_ratio' => ! empty($row->track_aspect_ratio) ? $row->track_aspect_ratio : '',
					'track_data_rate' => ! empty($row->track_data_rate) ? $row->track_data_rate : '',
					'track_unit_of_measure' => ! empty($row->track_unit_of_measure) ? $row->track_unit_of_measure : '',
					'track_essence_track_type' => ! empty($row->track_essence_track_type) ? $row->track_essence_track_type : '',
					'track_width' => ! empty($row->track_width) ? $row->track_width : '',
					'track_height' => ! empty($row->track_height) ? $row->track_height : '',
					'track_encoding' => ! empty($row->track_encoding) ? $row->track_encoding : '',
					'track_annotation' => ! empty($row->track_annotation) ? $row->track_annotation : '',
					'track_annotation_type' => ! empty($row->track_annotation_type) ? $row->track_annotation_type : '',
					'ins_annotation' => ! empty($row->ins_annotation) ? $row->ins_annotation : '',
					'guid_identifier' => ! empty($row->guid_identifier) ? $row->guid_identifier : '',
					'asset_title' => ! empty($row->asset_title) ? $row->asset_title : '',
					'asset_subject' => ! empty($row->asset_subject) ? $row->asset_subject : '',
					'asset_coverage' => ! empty($row->asset_coverage) ? $row->asset_coverage : '',
					'asset_genre' => ! empty($row->asset_genre) ? $row->asset_genre : '',
					'asset_publisher_name' => ! empty($row->asset_publisher_name) ? $row->asset_publisher_name : '',
					'asset_description' => ! empty($row->asset_description) ? $row->asset_description : '',
					'asset_creator_name' => ! empty($row->asset_creator_name) ? $row->asset_creator_name : '',
					'asset_creator_affiliation' => ! empty($row->asset_creator_affiliation) ? $row->asset_creator_affiliation : '',
					'asset_contributor_name' => ! empty($row->asset_contributor_name) ? $row->asset_contributor_name : '',
					'asset_contributor_affiliation' => ! empty($row->asset_contributor_affiliation) ? $row->asset_contributor_affiliation : '',
					'asset_rights' => ! empty($row->asset_rights) ? $row->asset_rights : '',
				);
				$this->sphnixrt->insert('instantiations_list', $data, $row->id);
			}

			$offset = $offset + 15000;

			if (count($records) < 15000)
				$db_count ++;
		}
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */