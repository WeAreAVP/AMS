<?php

//require_once(dirname(dirname(__FILE__)) . '/third_party/BagIt/bagit.php');

class Xml extends CI_Controller
{

	private $temp_path = 'uploads/export_temp/';
	private $bagit_path = 'assets/bagit/';

	function __construct()
	{
		parent::__construct();
		$this->layout = 'default.php';
		$this->load->library('export_pbcore_premis');
		$this->load->library('bagit_lib');
		$this->load->model('pbcore_model');
		$this->load->model('export_csv_job_model', 'csv_job');
		$this->load->model('dx_auth/users', 'users');
	}

	function export_pbcore()
	{
		@ini_set("max_execution_time", 999999999999); # unlimited
		@ini_set("memory_limit", "1000M"); # 1GB
		$export_job = $this->csv_job->get_export_jobs('pbcore');
		if (count($export_job) > 0)
		{
			$bag_name = 'ams_export_' . time();
			$bagit_lib = new BagIt("{$this->bagit_path}{$bag_name}");
			for ($i = 0; $i < $export_job->query_loop; $i ++ )
			{
				$query = $export_job->export_query;
				$query.=' LIMIT ' . ($i * 100000) . ', 100000';
				$records = $this->csv_job->get_csv_records($query);
				$count = 0;
				foreach ($records as $value)
				{
					make_dir($this->temp_path);
					$this->export_pbcore_premis->asset_id = $value->id;
					$this->export_pbcore_premis->make_xml();
					$file_name = $this->export_pbcore_premis->make_file_name();
					$path = "{$this->temp_path}{$file_name}_pbcore.xml";
					$this->export_pbcore_premis->xml->saveXML($path);
					$bagit_lib->addFile($path, "{$file_name}/{$file_name}_pbcore.xml");
					$this->export_pbcore_premis->is_pbcore_export = FALSE;
					$this->export_pbcore_premis->make_xml();
					$file_name = $this->export_pbcore_premis->make_file_name();
					$path = "{$this->temp_path}{$file_name}_premis.xml";
					$this->export_pbcore_premis->xml->saveXML($path);
					$bagit_lib->addFile($path, "{$file_name}/{$file_name}_premis.xml");
					unset($this->export_pbcore_premis->xml);
				}
			}
			$bagit_lib->update();
			$bagit_lib->package("{$this->bagit_path}{$bag_name}", 'zip');
			rmdir($this->temp_path);
			rmdir("{$this->bagit_path}{$bag_name}");
			$this->csv_job->update_job($export_job->id, array('status' => '1', 'file_path' => "{$this->bagit_path}{$bag_name}.zip"));
			$user = $this->users->get_user_by_id($export_job->user_id)->row();
			myLog('Sending Email to ' . $user->email);
			send_email($user->email, $this->config->item('from'), 'AMS XML Export', "{$this->bagit_path}{$bag_name}.zip");
		}
	}

	function pbcore($guid)
	{

		if (isset($guid) && $guid !== 0)
		{

			$result = $this->pbcore_model->get_one_by($this->pbcore_model->table_identifers, array('identifier' => "cpb-aacip/{$guid}"));
			if ($result)
			{
				debug($result);
				for ($i = 1; $i <= 8; ++ $i)
				{
					$track = $xml->addChild('track');
					$track->addAttribute('source', 'Test');
					$track->addChild('path', "song$i.mp3");
					$track->addChild('title', "Track $i - Track Title");
				}

				Header('Content-type: text/xml');
				echo $xml->asXML();
				exit;
			}
			else
			{
				show_error('Invalid GUID. No record found.');
			}
		}
		else
		{
			show_error('GUID is required.');
		}
	}

}
