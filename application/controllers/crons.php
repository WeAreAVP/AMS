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
		$this->load->library('googlerefine');
		$this->load->model('email_template_model', 'email_template');
		$this->load->model('cron_model');
		$this->load->model('dx_auth/users', 'users');
		$this->load->model('assets_model');
		$this->load->model('instantiations_model', 'instant');
		$this->load->model('essence_track_model', 'essence');
		$this->load->model('station_model');
		$this->load->model('refine_modal');
		$this->assets_path = 'assets/export_pbcore/';
	}

	/**
	 * Process all pending email.
	 *  
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
				echo "Email Sent To " . $queue->email_to . " <br/>";
			}
		}
	}

	/**
	 * Process all pending csv exports. 
	 * 
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
			$this->myLog('CSV Job Started.');
			$filename = 'csv_export_' . time() . '.csv';
			$fp = fopen("uploads/$filename", 'a');
			$line = "GUID,Unique ID,Title,Format,Duration,Priority\n";
			fputs($fp, $line);
			fclose($fp);
			$this->myLog('Header File Saved.');
			for ($i = 0; $i < $job->query_loop; $i ++ )
			{
				$this->myLog('Query Loop ' . $i);
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
				$this->myLog($mem . ' GB');
				$this->myLog('Sleeping for 5 seconds');
				sleep(5);
			}
			$url = site_url() . "uploads/$filename";
			$this->csv_job->update_job($job->id, array('status' => '1'));
			$user = $this->users->get_user_by_id($job->user_id)->row();
			$this->myLog('Sending Email to ' . $user->email);
			send_email($user->email, 'ssapienza@cpb.org', 'Limited CSV Export', $url);
			exit;
		}
		$this->myLog('No Record available for csv export.');
		exit;
	}

	/**
	 * Update Sphnix Indexes
	 *  
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
			$this->myLog("$index rotated successfully");
		}
		else
		{
			$this->myLog('No index available for rotation');
		}
		exit_function();
	}

	/**
	 * Update indexes after importing changes from google refine.
	 * 
	 */
	public function update_after_refine()
	{
		$record = $this->refine_modal->refine_update_records();
		if (count($record) > 0)
		{
			@exec("/usr/bin/indexer --all --rotate", $output);
			$email_output = implode('<br/>', $output);
			$this->refine_modal->update_job($record->id, array('is_active' => 0));
			send_email('nouman@avpreserve.com', $this->config->item('from_email'), 'Google Refine Index Rotation', $email_output);
			$this->myLog("All indexes rotated successfully.");
		}
		else
		{
			$this->myLog('No refine update available');
		}
		exit_function();
	}
function create($path, $filename, $job_id)
	{

		$project_name = $filename;
		$file_path = $path;
		$data = $this->googlerefine->create_project($project_name, $file_path);
		if ($data)
		{
			$data['is_active'] = 1;
			$data['project_name'] = $filename;
			$this->refine_modal->update_job($job_id, $data);
			return $data['project_url'];
		}
		return FALSE;
	}
	/**
	 * Make CSV File for google refinement
	 * 
	 */
	public function make_refine_csv()
	{
		$record = $this->refine_modal->get_job_for_refine();
		if (count($record) > 0)
		{
			if ($record->refine_type == 'instantiation')
			{
				$filename = 'google_refine_' . time() . '.csv';
				$fp = fopen("uploads/google_refine/$filename", 'a');

				$line = "Organization,Asset Title,Description,Instantiation ID,Instantiation ID Source,Generation,Nomination,Nomination Reason,Media Type,Language,__Ins_id,__identifier_id,__gen_id\n";
				fputs($fp, $line);
				fclose($fp);
				$db_count = 0;
				$offset = 0;
				while ($db_count == 0)
				{
					$custom_query = $record->export_query;
					$custom_query.=' LIMIT ' . ($offset * 15000) . ', 15000';

					$records = $this->refine_modal->get_csv_records($custom_query);

					$fp = fopen("uploads/google_refine/$filename", 'a');
					$line = '';
					foreach ($records as $value)
					{
						$line.='"' . str_replace('"', '""', $value->organization) . '",';
						$line.='"' . str_replace('"', '""', $value->asset_title) . '",';
						$line.='"' . str_replace('"', '""', $value->description) . '",';
						$line.='"' . str_replace('"', '""', $value->instantiation_identifier) . '",';
						$line.='"' . str_replace('"', '""', $value->instantiation_source) . '",';
						$line.='"' . str_replace('"', '""', $value->generation) . '",';
						$line.='"' . str_replace('"', '""', $value->status) . '",';
						$line.='"' . str_replace('"', '""', $value->nomination_reason) . '",';
						$line.='"' . str_replace('"', '""', $value->media_type) . '",';
						$line.='"' . str_replace('"', '""', $value->language) . '",';
						$line.='"' . str_replace('"', '""', $value->ins_id) . '",';
						$line.='"' . str_replace('"', '""', $value->identifier_id) . '",';
						$line.='"' . str_replace('"', '""', $value->gen_id) . '"';
						$line .= "\n";
					}
					fputs($fp, $line);
					fclose($fp);
					$offset ++;
					if (count($records) < 15000)
						$db_count ++;
				}

				$path = $this->config->item('path') . "uploads/google_refine/$filename";
				$data = array('export_csv_path' => $path);
				$this->refine_modal->update_job($record->id, $data);
				$project_url = $this->create($path, $filename, $record->id);
				$user = $this->users->get_user_by_id($record->user_id)->row();
				$this->myLog('Sending Email to ' . $user->email);
				
				send_email($user->email, $this->config->item('from_email'), 'AMS Refine', $project_url);
			}
			else
			{
				$filename = 'google_refine_' . time() . '.csv';
				$fp = fopen("uploads/google_refine/$filename", 'a');
				$line = "Organization,Asset Title,Description,Subject,Subject Source,Subject Ref,Genre,Genre Source,Genre Ref,Creator Name,Creator Affiliation,Creator Source,Creator Ref,";
				$line .="Contributors Name,Contributors Affiliation,Contributors Source,Contributors Ref,Publisher,Publisher Affiliation,Publisher Ref,Coverage,Coverage Type,";
				$line .="Audience Level,Audience Level Source,Audience Level Ref,";
				$line .="Audience Rating,Audience Rating Source,Audience Rating Ref,";
				$line .="Annotation,Annotation Type,Annotation Ref,";
				$line .="Rights,Rights Link,Asset Type,Identifier,Identifier Source,Identifier Ref,Asset Date,";
				$line .="__subject_id,__genre_id,__creator_id,__contributor_id,__publisher_id,__coverage_id,__audience_levels_id,__audience_ratings_id,__annotation_id,__right_id,__asset_types_id,__identifier_id,__asset_date_id,__asset_id\n";
				fputs($fp, $line);
				fclose($fp);
				$db_count = 0;
				$offset = 0;
				while ($db_count == 0)
				{

					$custom_query = $record->export_query;
					$custom_query.=' LIMIT ' . ($offset * 15000) . ', 15000';

					$records = $this->refine_modal->get_csv_records($custom_query);

					$fp = fopen("uploads/google_refine/$filename", 'a');
					$line = '';
					foreach ($records as $value)
					{
						$count = 1;
						foreach ($value as $index => $column)
						{
							if ($index == 'asset_id')
								$line.='"' . str_replace('"', '""', $column) . '"';
							else
								$line.='"' . str_replace('"', '""', $column) . '",';
						}

						$line .= "\n";
					}

					fputs($fp, $line);
					fclose($fp);
					$offset ++;
					if (count($records) < 15000)
						$db_count ++;
				}

				$path = $this->config->item('path') . "uploads/google_refine/$filename";
				$data = array('export_csv_path' => $path);
				$this->refine_modal->update_job($record->id, $data);
				$project_url = $this->create($path, $filename, $record->id);
				$user = $this->users->get_user_by_id($record->user_id)->row();
				$this->myLog('Sending Email to ' . $user->email);
				send_email($user->email, $this->config->item('from_email'), 'AMS Refine', $project_url);
			}
		}
		else
		{
			send_default_email();
			$this->myLog('No job available for refinement.');
		}
		exit_function();
	}

	/**
	 * Display the Output
	 * @global type $argc
	 * @param type $s 
	 */
	function myLog($s)
	{
		global $argc;
		if ($argc)
			$s.="\n";
		else
			$s.="<br>\n";
		echo date('Y-m-d H:i:s') . ' >> ' . $s;
		flush();
	}

}