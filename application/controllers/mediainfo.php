<?php

// @codingStandardsIgnoreFile
/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category AMS
 * @package  CI
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @license  CPB http://ams.avpreserve.com
 * @version  GIT: <$Id>
 * @link     http://ams.avpreserve.com

 */

/**
 * MediaInfo Class
 *
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    CPB http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Mediainfo extends CI_Controller
{

	/**
	 * constructor. Load layout,Model,Library and helpers
	 * 
	 */
	public $media_info_path;

	function __construct()
	{
		parent::__construct();

		$this->load->model('cron_model');
		$this->load->model('assets_model');
		$this->load->model('instantiations_model', 'instant');
		$this->load->model('station_model');
		$this->media_info_path = 'assets/mediainfo/';
		$this->load->model('pbcore_model');
	}

	/**
	 * Store all mediainfo directories and data files in the database.
	 *  
	 */
	function process_dir()
	{
		@set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB
		@error_reporting(E_ALL);
		@ini_set('display_errors', 1);
		$this->cron_model->scan_directory($this->media_info_path, $dir_files);
		$count = count($dir_files);
		if (isset($count) && $count > 0)
		{
			myLog("Total Number of process " . $count);
			$loop_counter = 0;
			$maxProcess = 10;
			foreach ($dir_files as $dir)
			{
				$cmd = escapeshellcmd('/usr/bin/php ' . $this->config->item('path') . 'index.php mediainfo process_dir_child ' . base64_encode($dir));
				$this->config->item('path') . "cronlog/mediainfo_processdir.log";
				$pidFile = $this->config->item('path') . "PIDs/media_info/" . $loop_counter . ".txt";
				@exec('touch ' . $pidFile);
				$this->runProcess($cmd, $pidFile, $this->config->item('path') . "cronlog/mediainfo_processdir.log");
				$file_text = file_get_contents($pidFile);
				$this->arrPIDs[$file_text] = $loop_counter;
				$proc_cnt = $this->procCounter();
				$loop_counter ++;
				while ($proc_cnt == $maxProcess)
				{
					myLog('Number of Processes running : ' . $loop_counter . '/.' . $count . ' Sleeping ...');
					sleep(30);
					$proc_cnt = $this->procCounter();
				}
			}
			myLog("Waiting for all process to complete");
			$proc_cnt = $this->procCounter();
			while ($proc_cnt > 0)
			{
				echo "Sleeping....\n";
				sleep(10);
				echo "\010\010\010\010\010\010\010\010\010\010\010\010";
				echo "\n";
				$proc_cnt = $this->procCounter();
				echo "Number of Processes running : $proc_cnt/$maxProcess\n";
			}
		}
		echo "All Data Path Under {$this->media_info_path} Directory Stored ";
		exit_function();
	}

	/**
	 * Store all PBCore 1.x sub files in the database.
	 * 
	 * @param type $path 
	 */
	function process_dir_child($path)
	{
		set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB
		@error_reporting(E_ALL);
		@ini_set('display_errors', 1);
		$type = 'mediainfo';
		$file = 'manifest-md5.txt';
		$directory = base64_decode($path);
		$folder_status = 'complete';
		if ( ! $data_folder_id = $this->cron_model->get_data_folder_id_by_path($directory))
		{
			$data_folder_id = $this->cron_model->insert_data_folder(array("folder_path" => $directory, "created_at" => date('Y-m-d H:i:s'), "data_type" => $type));
		}
		if (isset($data_folder_id) && $data_folder_id > 0)
		{
			$data_result = file($directory . $file);
			if (isset($data_result) && ! is_empty($data_result))
			{
				$db_error_counter = 0;
				foreach ($data_result as $value)
				{
					$data_file = (explode(" ", $value));
					$data_file_path = trim(str_replace(array('\r\n', '\n', '<br>'), '', trim($data_file[2])));
					unset($data_file);
					myLog('Checking File ' . $data_file_path);
					if (isset($data_file_path) && ! is_empty($data_file_path))
					{
						$file_path = trim($directory . $data_file_path);
//						
						if (file_exists($file_path))
						{
							if ( ! $this->cron_model->is_pbcore_file_by_path($data_file_path, $data_folder_id))
							{
								$this->cron_model->insert_prcoess_data(array('file_type' => $type, 'file_path' => ($data_file_path), 'is_processed' => 0, 'created_at' => date('Y-m-d H:i:s'), "data_folder_id" => $data_folder_id));
							}
						}
						else
						{
							if ( ! $this->cron_model->is_pbcore_file_by_path($data_file_path, $data_folder_id))
							{
								$this->cron_model->insert_prcoess_data(array('file_type' => $type, 'file_path' => ($data_file_path), 'is_processed' => 0, 'created_at' => date('Y-m-d H:i:s'), "data_folder_id" => $data_folder_id, 'status_reason' => 'file_not_found'));
							}
							$folder_status = 'incomplete';
						}
					}
					if ($db_error_counter == 20000)
					{
						$db_error_counter = 0;
						sleep(3);
					}
					$db_error_counter ++;
				}
			}
			myLog('folder Id ' . $data_folder_id . ' => folder_status ' . $folder_status);
			$this->cron_model->update_data_folder(array('updated_at' => date('Y-m-d H:i:s'), 'folder_status' => $folder_status), $data_folder_id);
		}
	}

	/**
	 * 
	 * Process all pending PBCore 2.x files.
	 *
	 */
	function process_xml_file()
	{
		$folders = $this->cron_model->get_all_mediainfo_folder();
		if (isset($folders) && ! empty($folders))
		{
			foreach ($folders as $folder)
			{
				$count = $this->cron_model->get_pbcore_file_count_by_folder_id($folder->id);
				if (isset($count) && $count > 0)
				{
					$maxProcess = 10;
					$limit = 10;
					$loop_end = ceil($count / $limit);
					myLog("Run $loop_end times  $maxProcess at a time");
					for ($loop_counter = 0; $loop_end > $loop_counter; $loop_counter ++ )
					{
						$offset = $loop_counter * $limit;
						myLog("Started $offset~$limit of $count");
						$cmd = escapeshellcmd('/usr/bin/php ' . $this->config->item('path') . 'index.php mediainfo process_xml_file_child ' . $folder->id . ' ' . $offset . ' ' . $limit);
						$pidFile = $this->config->item('path') . "PIDs/media_info/" . $loop_counter . ".txt";
						@exec('touch ' . $pidFile);
						$this->runProcess($cmd, $pidFile, $this->config->item('path') . "cronlog/mediainfo_xml.log");
						$file_text = file_get_contents($pidFile);
						$this->arrPIDs[$file_text] = $loop_counter;
						$proc_cnt = $this->procCounter();
						while ($proc_cnt == $maxProcess)
						{
							myLog("Sleeping ...");
							sleep(5);
							$proc_cnt = $this->procCounter();
							echo "Number of Processes running : $proc_cnt/$maxProcess\n";
						}
					}
					myLog("Waiting for all process to complete");
					$proc_cnt = $this->procCounter();
					while ($proc_cnt > 0)
					{
						echo "Sleeping....\n";
						sleep(5);
						echo "\010\010\010\010\010\010\010\010\010\010\010\010";
						echo "\n";
						$proc_cnt = $this->procCounter();
						echo "Number of Processes running : $proc_cnt/$maxProcess\n";
					}
				}

				unset($x);
				unset($data);
			}
		}
	}

	/**
	 * Process all pending PBCore 1.x files.
	 * @param type $folder_id
	 * @param type $station_cpb_id
	 * @param type $offset
	 * @param type $limit 
	 */
	function process_xml_file_child($folder_id, $offset = 0, $limit = 100)
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		$folder_data = $this->cron_model->get_data_folder_by_id($folder_id);
		if ($folder_data)
		{
			$data_files = $this->cron_model->get_pbcore_file_by_folder_id($folder_data->id, $offset, $limit);
			if (isset($data_files) && ! is_empty($data_files))
			{
				foreach ($data_files as $d_file)
				{
					if ($d_file->is_processed == 0)
					{
						$this->cron_model->update_prcoess_data(array("processed_start_at" => date('Y-m-d H:i:s')), $d_file->id);
						$file_path = '';
						$file_path = trim($folder_data->folder_path . $d_file->file_path);
						if (file_exists($file_path))
						{
							$this->import_media_files($file_path);
							$this->cron_model->update_prcoess_data(array('is_processed' => 1, "processed_at" => date('Y-m-d H:i:s'), 'status_reason' => 'Complete'), $d_file->id);
						}
						else
						{
							myLog(" Is File Check Issues " . $file_path);
							$this->cron_model->update_prcoess_data(array('status_reason' => 'file_not_found'), $d_file->id);
						}
					}
				}
				unset($data_files);
			}
			else
			{
				myLog(" Data files not found ");
			}
		}
		else
		{
			myLog(" folders Data not found " . $file_path);
		}
	}

	/**
	 * Its a sample.
	 *  
	 */
	function import_media_files($file_path)
	{
		$this->load->library('import_mediainfo');

		$this->import_mediainfo->initialize($file_path);
	}

	/**
	 * Check the process status
	 * 
	 * @param type $pid
	 * @return boolean 
	 */
	function checkProcessStatus($pid)
	{
		$proc_status = false;
		try
		{
			$result = shell_exec("/bin/ps $pid");
			if (count(preg_split("/\n/", $result)) > 2)
			{
				$proc_status = TRUE;
			}
		}
		catch (Exception $e)
		{
			
		}
		return $proc_status;
	}

	/**
	 * Check the process count.
	 * 
	 * @return type 
	 */
	function procCounter()
	{
		foreach ($this->arrPIDs as $pid => $cityKey)
		{
			if ( ! $this->checkProcessStatus($pid))
			{
				$t_pid = str_replace("\r", "", str_replace("\n", "", trim($pid)));
				unset($this->arrPIDs[$pid]);
			}
			else
			{
				
			}
		}
		return count($this->arrPIDs);
	}

	/**
	 * Run a new process
	 * 
	 * @param type $cmd
	 * @param type $pidFilePath
	 * @param type $outputfile 
	 */
	function runProcess($cmd, $pidFilePath, $outputfile = "/dev/null")
	{
		$cmd = escapeshellcmd($cmd);
		@exec(sprintf("%s >> %s 2>&1 & echo $! > %s", $cmd, $outputfile, $pidFilePath));
	}

}
