<?php

// @codingStandardsIgnoreFile
/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category AMS
 * @package  CI
 * @author   Nouman Tayyab <nouman@geekschicago.com>
 * @license  CPB http://ams.avpreserve.com
 * @version  GIT: <$Id>
 * @link     http://ams.avpreserve.com

 */

/**
 * Crons Class
 *
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    CPB http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Crons extends CI_Controller
{

	/**
	 *
	 * constructor. Load layout,Model,Library and helpers
	 * 
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model('email_template_model', 'email_template');
		$this->load->model('cron_model');
		$this->load->model('dx_auth/users', 'users');
	}

	/**
	 * Process all pending email.
	 * 
	 * @return 
	 */
	function processemailqueues()
	{
		$email_queue = $this->email_template->get_all_pending_email();

		foreach ($email_queue as $queue)
		{
			$now_queue_body = $queue->email_body . '<img src="' . site_url('emailtracking/' . $queue->id . '.png') . '" height="1" width="1" />';
			if (send_email($queue->email_to, $queue->email_from, $queue->email_subject, $now_queue_body))
			{
				$this->email_template->update_email_queue_by_id($queue->id, array("is_sent" => 2, "sent_at" => date('Y-m-d H:i:s')));
				myLog("Email Sent To " . $queue->email_to);
			}
		}
	}

	/**
	 * Process all pending csv exports. 
	 * 
	 * @return 
	 */
	function csv_export_job()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB
		$this->load->model('export_csv_job_model', 'csv_job');
		$job = $this->csv_job->get_incomplete_jobs();
		if (count($job) > 0)
		{
			myLog('CSV Job Started.');
			$filename = 'csv_export_' . time() . '.csv';
			$fp = fopen("uploads/$filename", 'a');
			$line = "GUID,Unique ID,Title,Format,Duration,Priority\n";
			fputs($fp, $line);
			fclose($fp);
			myLog('Header File Saved.');
			for ($i = 0; $i < $job->query_loop; $i ++ )
			{
				myLog('Query Loop ' . $i);
				$query = $job->export_query;
				$query.=' LIMIT ' . ($i * 100000) . ', 100000';

				$records = $this->csv_job->get_csv_records($query);

				$fp = fopen("uploads/$filename", 'a');
				$line = '';
				foreach ($records as $value)
				{
					$line.='"' . str_replace('"', '""', $value->GUID) . '",';
					$line.='="' . str_replace('"', '""', $value->unique_id) . '",';
					$line.='"' . str_replace('"', '""', $value->titles) . '",';
					$line.='"' . str_replace('"', '""', $value->format_name) . '",';
					$line.='="' . str_replace('"', '""', $value->projected_duration) . '",';
					$line.='"' . str_replace('"', '""', $value->status) . '"';
					$line .= "\n";
				}
				fputs($fp, $line);
				fclose($fp);
				$mem = memory_get_usage() / 1024;
				$mem = $mem / 1024;
				$mem = $mem / 1024;
				myLog($mem . ' GB');
				myLog('Sleeping for 5 seconds');
				sleep(5);
			}
			$url = site_url() . "uploads/$filename";
			$this->csv_job->update_job($job->id, array('status' => '1'));
			$user = $this->users->get_user_by_id($job->user_id)->row();
			myLog('Sending Email to ' . $user->email);
			send_email($user->email, 'ssapienza@cpb.org', 'Limited CSV Export', $url);
			exit_function();
		}
		myLog('No Record available for csv export.');
		exit_function();
	}

	/**
	 * Update Sphnix Indexes
	 *  
	 * @return
	 */
	public function rotate_sphnix_indexes()
	{
		$record = $this->cron_model->get_sphnix_indexes();
		if ($record)
		{
			$index = $record->index_name;
			@exec("/usr/bin/indexer $index --rotate", $output);
			$email_output = implode('<br/>', $output);
			$db_output = implode("\n", $output);

			$this->cron_model->update_rotate_indexes($record->id, array('status' => 1, 'output' => $db_output));

			send_email('nouman@avpreserve.com', $this->config->item('from_email'), 'Index Rotation for ' . $index, $email_output);
			myLog("$index rotated successfully");
		}
		else
		{
			myLog('No index available for rotation');
		}
		exit_function();
	}

	/**
	 * Save Facet Search Values into memcahed.
	 * 
	 * @return 
	 */
	function auto_memcached_facets()
	{
		$this->load->library('memcached_library');
		$this->load->model('sphinx_model', 'sphinx');

		$memcached = new StdClass;
		$memcached->ins = 'instantiations_list';
		$memcached->asset = 'assets_list';

		$search_facet = new stdClass;
		$search_facet->state = 'state';
		$search_facet->stations = 'organization';
		$search_facet->status = 'nomination_status_id';
		$search_facet->media_type = 'media_type';
		$search_facet->physical = 'format_name';
		$search_facet->digital = 'format_name';
		$search_facet->generations = 'facet_generation';
		$search_facet->digitized = 'digitized';
		$search_facet->migration = 'migration';
		foreach ($memcached as $index => $index_name)
		{
			foreach ($search_facet as $columns => $facet)
			{
				$grouping = FALSE;
				if (in_array($facet, array('media_type', 'format_name', 'facet_generation')))
					$grouping = TRUE;
				if (in_array($columns, array('physical', 'digital', 'digitized', 'migration')))
				{
					$result = $this->sphinx->facet_index($facet, $index_name, $columns);
					$this->memcached_library->set($index . '_' . $columns, json_encode(sortByOneKey($result['records'], $facet, $grouping)), 3600);
				}
				else
				{
					$result = $this->sphinx->facet_index($facet, $index_name);
					$this->memcached_library->set($index . '_' . $columns, json_encode(sortByOneKey($result['records'], $facet, $grouping)), 3600);
				}
			}
			myLog("Succussfully Updated $index_name Facet Search");
		}
	}

	/**
	 * Memcached dashboard data
	 * 
	 */
	function dashboard_memcached()
	{
		$this->load->model('dashboard_model');
		$this->load->library('memcached_library');
		/* Start Graph Get Digitized Formats  */
		$total_digitized = $this->dashboard_model->get_digitized_formats();
		$data['digitized_format_name'] = NULL;
		$data['digitized_total'] = NULL;
		$dformat_array = array();
		foreach ($total_digitized as $digitized)
		{
			if ( ! isset($dformat_array[$digitized->format_name]))
				$dformat_array[$digitized->format_name] = 1;
			else
				$dformat_array[$digitized->format_name] = $dformat_array[$digitized->format_name] + 1;
		}
		foreach ($dformat_array as $index => $format)
		{
			$data['digitized_format_name'][] = $index;
			$data['digitized_total'][] = (int) $format;
		}
		$this->memcached_library->set('graph_digitized_format_name', json_encode($data['digitized_format_name']), 3600);
		$this->memcached_library->set('graph_digitized_total', json_encode($data['digitized_total']), 3600);
		/* End Graph Get Digitized Formats  */
		/* Start Graph Get Scheduled Formats  */
		$total_scheduled = $this->dashboard_model->get_scheduled_formats();
		$data['scheduled_format_name'] = NULL;
		$data['scheduled_total'] = NULL;

		$format_array = array();
		foreach ($total_scheduled as $scheduled)
		{

			if ( ! isset($format_array[$scheduled->format_name]))
				$format_array[$scheduled->format_name] = 1;
			else
				$format_array[$scheduled->format_name] = $format_array[$scheduled->format_name] + 1;
		}
		foreach ($format_array as $index => $format)
		{
			$data['scheduled_format_name'][] = $index;
			$data['scheduled_total'][] = (int) $format;
		}

		$this->memcached_library->set('graph_scheduled_format_name', json_encode($data['scheduled_format_name']), 3600);
		$this->memcached_library->set('graph_scheduled_total', json_encode($data['scheduled_total']), 3600);
		/* End Graph Get Scheduled Formats  */
		/* Start Meterial Goal  */
		$data['material_goal'] = $this->dashboard_model->get_digitized_hours();

		$this->memcached_library->set('material_goal', json_encode($data['material_goal']), 3600);
		/* End Meterial Goal  */


		/* Start Hours at crawford  */
		foreach ($this->config->item('messages_type') as $index => $msg_type)
		{
			if ($msg_type === 'Materials Received Digitization Vendor')
			{
				$data['msg_type'] = $index;
			}
		}

		$hours_at_craword = $this->dashboard_model->get_hours_at_crawford($data['msg_type']);

		$data['at_crawford'] = 0;
		foreach ($hours_at_craword as $hours)
		{
			$data['at_crawford'] = $data['at_crawford'] + $hours->total;
		}
		$this->memcached_library->set('at_crawford', json_encode($data['at_crawford']), 3600);
		/* End Hours at crawford  */

		/* Start goal hours  */
		$data['total_goal'] = $this->dashboard_model->get_material_goal();
		$digitized_hours = $this->dashboard_model->get_digitized_hours();
		$data['total_hours'] = $this->abbr_number((isset($data['total_goal']->total)) ? $data['total_goal']->total : 0);
		$data['percentage_hours'] = round(((isset($digitized_hours->total)) ? $digitized_hours->total : 0 * 100) / $data['total_hours']);
		$this->memcached_library->set('total_hours', json_encode($data['total_hours']), 3600);
		$this->memcached_library->set('percentage_hours', json_encode($data['percentage_hours']), 3600);
		/* End goal hours  */

		/* Start Total digitized assets by Region */
		$regions = array('other', 'midwest', 'northeast', 'south', 'west');
		foreach ($regions as $region)
		{
			$total_region_digitized[$region] = $this->dashboard_model->digitized_total_by_region($region)->total;
			if (empty($this->dashboard_model->digitized_hours_by_region($region)->time))
				$time = 0;
			else
				$time = $this->dashboard_model->digitized_hours_by_region($region)->time;
			$total_hours_region_digitized[$region] = $time;
		}
		$this->memcached_library->set('total_region_digitized', json_encode($total_region_digitized), 3600);
		$this->memcached_library->set('total_hours_region_digitized', json_encode($total_hours_region_digitized), 3600);
		/* End Total digitized assets by Region */

		/* Pie Chart for All Formats Start */
		$pie_total_completed = $this->dashboard_model->pie_total_completed();
		$pie_total_scheduled = $this->dashboard_model->pie_total_scheduled();
		$pie_total = $pie_total_completed->total + $pie_total_scheduled->total;
		$pie_total = ($pie_total == 0) ? 1 : $pie_total;
		$data['pie_total_completed'] = (int) round(($pie_total_completed->total * 100) / $pie_total);
		$data['pie_total_scheduled'] = (int) round(($pie_total_scheduled->total * 100) / $pie_total);
		$this->memcached_library->set('pie_total_completed', json_encode($data['pie_total_completed']), 3600);
		$this->memcached_library->set('pie_total_scheduled', json_encode($data['pie_total_scheduled']), 3600);
		/* Pie Chart for All Formats End */
		/* Pie Chart for Radio Formats Start */
		$pie_total_completed = $this->dashboard_model->pie_total_radio_completed();
		$pie_total_scheduled = $this->dashboard_model->pie_total_radio_scheduled();
		$pie_total = $pie_total_completed->total + $pie_total_scheduled->total;
		$pie_total = ($pie_total == 0) ? 1 : $pie_total;
		$data['pie_total_radio_completed'] = (int) round(($pie_total_completed->total * 100) / $pie_total);
		$data['pie_total_radio_scheduled'] = (int) round(($pie_total_scheduled->total * 100) / $pie_total);
		$this->memcached_library->set('pie_total_radio_completed', json_encode($data['pie_total_radio_completed']), 3600);
		$this->memcached_library->set('pie_total_radio_scheduled', json_encode($data['pie_total_radio_scheduled']), 3600);
		/* Pie Chart for Radio Formats End */
		/* Pie Chart for Radio Formats Start */
		$pie_total_completed = $this->dashboard_model->pie_total_tv_completed();
		$pie_total_scheduled = $this->dashboard_model->pie_total_tv_scheduled();
		$pie_total = $pie_total_completed->total + $pie_total_scheduled->total;
		$pie_total = ($pie_total == 0) ? 1 : $pie_total;
		$data['pie_total_tv_completed'] = (int) round(($pie_total_completed->total * 100) / $pie_total);
		$data['pie_total_tv_scheduled'] = (int) round(($pie_total_scheduled->total * 100) / $pie_total);
		$this->memcached_library->set('pie_total_tv_completed', json_encode($data['pie_total_tv_completed']), 3600);
		$this->memcached_library->set('pie_total_tv_scheduled', json_encode($data['pie_total_tv_scheduled']), 3600);
		/* Pie Chart for Radio Formats End */
	}

	function abbr_number($size)
	{
		$size = preg_replace('/[^0-9]/', '', $size);
		$sizes = array("", "K", "M");
		if ($size == 0)
		{
			return('n/a');
		}
		else
		{
			return (round($size / pow(1000, ($i = floor(log($size, 1000)))), 0) . $sizes[$i]);
		}
	}

	function test()
	{
		$this->load->model('searchd_model');
		$db_count = 0;
		$offset = 0;
		while ($db_count == 0)
		{
			$records = $this->searchd_model->get_ins_index($offset, 15000);
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
			log($offset);
			$offset = $offset + 15000;

			if (count($records) < 15000)
				$db_count ++;
		}
	}

}