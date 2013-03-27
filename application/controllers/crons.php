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
	function rotate_sphnix_indexes()
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
		$search_facet->status = 'status';
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
					$this->memcached_library->set($index .'_'. $columns, json_encode(sortByOneKey($result['records'], $facet, $grouping)), 3600);
				}
				else
				{
					$result = $this->sphinx->facet_index($facet, $index_name);
					$this->memcached_library->set($index .'_'. $columns, json_encode(sortByOneKey($result['records'], $facet, $grouping)), 3600);
				}
			}
			myLog("Succussfully Updated $index_name Facet Search");
		}
	}

}