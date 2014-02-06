<?php

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
		$this->load->model('export_csv_job_model', 'export_job');
		$this->load->model('dx_auth/users', 'users');
		$this->load->model('dx_auth/user_profile', 'user_profile');
	}

	/**
	 * Export records from AMS for PBCore and PREMIS and save all the exported records in zip file using BagIt.
	 * 
	 * @return void
	 */
	function export_pbcore()
	{
		@ini_set("max_execution_time", 999999999999); # unlimited
		@ini_set("memory_limit", "1000M"); # 1GB
		$export_job = $this->export_job->get_export_jobs('pbcore');
		if (count($export_job) > 0)
		{
			myLog('Started export for ID =>' . $export_job->id);
			$bag_name = 'ams_export_' . time();
			$bagit_info = array('Source-Organization' => 'WGBH on behalf of the American Archive of Public Broadcasting',
				'Organization-Address' => 'Media Library & Archives, One Guest Street, Boston, MA 02135',
				'Contact-Name' => 'Casey E. Davis, Project Manager',
				'Contact-Phone' => '+1 617-300-5921',
				'Contact-Email' => 'info@americanarchive.org ',
				'External-Description' => 'The American Archive of Public Broadcasting, a collaboration between WGBH Boston and the Library of Congress, includes millions of records of public television and radio assets contributed by over 100 stations and organizations across the United States.',
//				'Bagging-Date' => date('Y-m-d'),
			);
			$bagit_lib = new BagIt("{$this->bagit_path}{$bag_name}", TRUE, TRUE, FALSE, $bagit_info);
			for ($i = 0; $i < $export_job->query_loop; $i ++ )
			{
				$query = $export_job->export_query;
				$query.=' LIMIT ' . ($i * 100000) . ', 100000';
				$records = $this->export_job->get_csv_records($query);
				foreach ($records as $value)
				{
					make_dir($this->temp_path);
					$this->export_pbcore_premis->asset_id = $value->id;
					$this->export_pbcore_premis->is_pbcore_export = TRUE;
					$this->export_pbcore_premis->make_xml();
					$file_name = $this->export_pbcore_premis->make_file_name();
					$path = "{$this->temp_path}{$file_name}_pbcore.xml";
					$this->export_pbcore_premis->format_xml($path);
					$bagit_lib->addFile($path, "{$file_name}/{$file_name}_pbcore.xml");
					$this->export_pbcore_premis->is_pbcore_export = FALSE;
					$result = $this->export_pbcore_premis->make_xml();
					if ($result)
					{
						$file_name = $this->export_pbcore_premis->make_file_name();
						$path = "{$this->temp_path}{$file_name}_premis.xml";
						$this->export_pbcore_premis->format_xml($path);
						$bagit_lib->addFile($path, "{$file_name}/{$file_name}_premis.xml");
					}

					unset($this->export_pbcore_premis->xml);
				}
			}

			$bagit_lib->update();
			$bagit_lib->package("{$this->bagit_path}{$bag_name}", 'zip');
			exec("rm -rf $this->temp_path");
			exec("rm -rf {$this->bagit_path}{$bag_name}");
			$this->export_job->update_job($export_job->id, array('status' => '1', 'file_path' => "{$this->bagit_path}{$bag_name}.zip"));
			$user = $this->users->get_user_by_id($export_job->user_id)->row();
			$data['user_profile'] = $this->user_profile->get_profile($export_job->user_id)->row();
			$data['user'] = $user;
			$data['export_id'] = $export_job->id;
			myLog('Sending Email to ' . $user->email);
			send_email($user->email, $this->config->item('from_email'), 'AMS XML Export', $this->load->view('email/export_pbcore', $data, TRUE));
			myLog('email sent successfully ' . $user->email);
		}
		else
		{
			myLog('No record availabe for export');
		}
		exit_function();
	}

	/**
	 * Download the zip file of exported record.
	 * 
	 * @return 
	 */
	function download()
	{
		$job_id = $this->uri->segment(3, 0);
		if ($job_id !== 0)
		{
			$job_id = base64_decode($job_id);
			$export_info = $this->export_job->get_job_by_id($job_id);
			if (count($export_info) > 0)
				download_file($export_info->file_path);
			else
				show_error('Invalid AMS export information.');
		}
		else
			show_error('No Export available.');
	}

	/**
	 * Export the premis xml against guid.
	 * 
	 * @return void
	 */
	function premis()
	{
		$default = array('guid', 'digitized', 'modified_date');
		$_uri = $this->uri->uri_to_assoc(3, $default);
		Header('Content-type: text/xml');
		if (isset($_uri['guid']) && ! empty($_uri['guid']))
		{

			$result = $this->pbcore_model->get_one_by($this->pbcore_model->table_identifers, array('identifier' => "cpb-aacip/{$_uri['guid']}"));
			if ($result)
			{
				$this->export_pbcore_premis->asset_id = $result->assets_id;
				$this->export_pbcore_premis->is_pbcore_export = FALSE;
				$result = $this->export_pbcore_premis->make_xml();
				if ($result)
					echo $this->export_pbcore_premis->xml->asXML();
				else
				{
					$response = $this->export_pbcore_premis->xml_error('No PREMIS info available.');
					echo $response->asXML();
				}
			}
			else
			{
				$result = $this->export_pbcore_premis->xml_error('Invalid GUID. No record found.');
				echo $result->asXML();
			}
		}
		else if ( ! empty($_uri['digitized']) || ! empty($_uri['modified_date']))
		{
			
		}
		else
		{
			$result = $this->export_pbcore_premis->xml_error('guid,digitized or modified_date. One of the parameter is required.');
			echo $result->asXML();
		}
		exit_function();
	}

	function pbcore()
	{
		$default = array('guid', 'digitized', 'modified_date');
		$_uri = $this->uri->uri_to_assoc(3, $default);

		Header('Content-type: text/xml');
		if (isset($_uri['guid']) && ! empty($_uri['guid']))
		{

			$result = $this->pbcore_model->get_one_by($this->pbcore_model->table_identifers, array('identifier' => "cpb-aacip/{$_uri['guid']}"));
			if ($result)
			{
				$this->export_pbcore_premis->asset_id = $result->assets_id;
				$this->export_pbcore_premis->is_pbcore_export = TRUE;
				$this->export_pbcore_premis->make_xml();

				echo $this->export_pbcore_premis->xml->asXML();
			}
			else
			{
				$result = $this->export_pbcore_premis->xml_error('Invalid GUID. No record found.');
				echo $result->asXML();
			}
		}
		else if ( ! empty($_uri['digitized']) || ! empty($_uri['modified_date']))
		{
			$result = check_web_service_params($_uri);
			if ($result === 'valid')
			{
				$records = $this->pbcore_model->get_by($this->pbcore_model->_assets_table, array('stations_id' => 100));
				$this->export_pbcore_premis->is_pbcore_export = TRUE;
				$this->export_pbcore_premis->make_collection_xml($records);
				echo $this->export_pbcore_premis->xml->asXML();
			}
			else
			{
				$response = $this->export_pbcore_premis->xml_error($result);
				echo $response->asXML();
			}
		}
		else
		{
			$result = $this->export_pbcore_premis->xml_error('guid,digitized or modified_date. One of the parameter is required.');
			echo $result->asXML();
		}
		exit_function();
	}

}
