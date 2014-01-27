<?php

class Xml extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->layout = 'default.php';
		$this->load->library('pbcore');
		$this->load->model('pbcore_model');
		$this->load->model('export_csv_job_model', 'csv_job');
	}

	function export_pbcore()
	{
		$export_job = $this->csv_job->get_export_jobs('pbcore');
		if (count($export_job) > 0)
		{
//			myLog('Pbcore Export Job Started.');
			for ($i = 0; $i < $export_job->query_loop; $i ++ )
			{
//				myLog('Query Loop ' . $i);
				$query = $export_job->export_query;
				$query.=' LIMIT ' . ($i * 100000) . ', 100000';
				$records = $this->csv_job->get_csv_records($query);
				foreach ($records as $value)
				{
					$this->pbcore->asset_id = $value->id;
					$this->pbcore->make_xml();
//					Header('Content-type: text/xml');
					echo $this->pbcore->xml->asXML();
					exit;
				}

				exit;
			}
		}
	}

	function pbcore($guid)
	{

		if (isset($guid) && $guid !== 0)
		{

			$result = $this->pbcore_model->get_one_by($this->pbcore_model->_identifiers_table, array('identifier' => "cpb-aacip/{$guid}"));
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
