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
				$this->config->item('path') . "cronlog/media_info.log";
				$pidFile = $this->config->item('path') . "PIDs/media_info/" . $loop_counter . ".txt";
				@exec('touch ' . $pidFile);
				$this->runProcess($cmd, $pidFile, $this->config->item('path') . "cronlog/media_info.log");
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
					$maxProcess = 1;
					$limit = 1;
					$loop_end = ceil($count / $limit);
					$this->myLog("Run $loop_end times  $maxProcess at a time");
					for ($loop_counter = 0; $loop_end > $loop_counter; $loop_counter ++ )
					{
						$offset = $loop_counter * $limit;
						myLog("Started $offset~$limit of $count");
						$cmd = escapeshellcmd('/usr/bin/php ' . $this->config->item('path') . 'index.php mediainfo process_xml_file_child ' . $folder->id . ' ' . $offset . ' ' . $limit);
						$pidFile = $this->config->item('path') . "PIDs/media_info/" . $loop_counter . ".txt";
						@exec('touch ' . $pidFile);
						$this->runProcess($cmd, $pidFile, $this->config->item('path') . "cronlog/mediainfo.log");
						$file_text = file_get_contents($pidFile);
						$this->arrPIDs[$file_text] = $loop_counter;
						$proc_cnt = $this->procCounter();
						while ($proc_cnt == $maxProcess)
						{
							$this->myLog("Sleeping ...");
							sleep(5);
							$proc_cnt = $this->procCounter();
							echo "Number of Processes running : $proc_cnt/$maxProcess\n";
						}
					}
					$this->myLog("Waiting for all process to complete");
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
							$this->myLog(" Is File Check Issues " . $file_path);
							$this->cron_model->update_prcoess_data(array('status_reason' => 'file_not_found'), $d_file->id);
						}
					}
				}
				unset($data_files);
			}
			else
			{
				$this->myLog(" Data files not found " . $file_path);
			}
		}
		else
		{
			$this->myLog(" folders Data not found " . $file_path);
		}
	}

	/**
	 * Its a sample.
	 *  
	 */
	function import_media_files($file_path)
	{ //data/cpb-aacip-331-15bcc3x8.wav.mediainfo.xml
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		if (file_exists($file_path))
		{
			$data = file_get_contents($file_path);
			$x = @simplexml_load_string($data);
			$data = xmlObjToArr($x);

			$tracks_data = $data['children']['file'][0]['children']['track'];
			$db_asset_id = NULL;
			$db_instantiation_id = NULL;
			$db_essence_track_id = NULL;
			if (isset($tracks_data) && count($tracks_data) > 0)
			{
				$instantiation = array();
				$instantiation['digitized'] = 0;
//			echo '<br/>Digitized= ' . $instantiation['digitized'];
				$instantiation['location'] = 'N/A';
//			echo '<br/>Location= ';
				$essence_track = array();
				$dessence_track = array();
				$dessence_track_counter = 0;

				foreach ($tracks_data as $index => $track)
				{
					if (isset($track['attributes']['type']) && $track['attributes']['type'] === 'General')
					{
						$general_track = $track['children'];
						/* Media Type Start */
						$media_type = '';
						if (isset($general_track['videocount']) && isset($general_track['videocount'][0]))
						{

							if ( ! empty($general_track['videocount'][0]['text']) || $general_track['videocount'][0]['text'] != NULL || $general_track['videocount'][0]['text'] > 0)
							{
								$media_type = 'Moving Image';
							}
							else if ($general_track['videocount'][0]['text'] == 0)
							{
								if (isset($general_track['audiocount']) && isset($general_track['audiocount'][0]))
								{
									if ( ! empty($general_track['audiocount'][0]['text']) || $general_track['audiocount'][0]['text'] != NULL)
									{
										$media_type = 'Sound';
									}
								}
							}
							if ($media_type != '')
							{
//							echo '<br/>Media Type = ' . $media_type;
								$inst_media_type = $this->instant->get_instantiation_media_types_by_media_type($media_type);
								if ( ! is_empty($inst_media_type))
									$instantiation['instantiation_media_type_id'] = $inst_media_type->id;
								else
									$instantiation['instantiation_media_type_id'] = $this->instant->insert_instantiation_media_types(array('media_type' => $media_type));
							}
						}
						else if (isset($general_track['audiocount']) && isset($general_track['audiocount'][0]))
						{
							if ( ! empty($general_track['audiocount'][0]['text']) || $general_track['audiocount'][0]['text'] != NULL)
							{
								$media_type = 'Sound';
//							echo '<br/>Media Type = ' . $media_type;
								$inst_media_type = $this->instant->get_instantiation_media_types_by_media_type($media_type);
								if ( ! is_empty($inst_media_type))
									$instantiation['instantiation_media_type_id'] = $inst_media_type->id;
								else
									$instantiation['instantiation_media_type_id'] = $this->instant->insert_instantiation_media_types(array('media_type' => $media_type));
							}
						}
						/* Media Type End */
						/* Actual Duration Start */
						if (isset($general_track['duration_string3']) && isset($general_track['duration_string3'][0]))
						{
							if ( ! empty($general_track['duration_string3'][0]['text']))
							{
								$instantiation['actual_duration'] = date('H:i:s', strtotime($general_track['duration_string3'][0]['text']));
							}
						}
						/* Actual Duration End */
						/* Standard Start */
						if (isset($general_track['format_profile']) && isset($general_track['format_profile'][0]))
						{

							if ( ! empty($general_track['format_profile'][0]['text']) || $general_track['format_profile'][0]['text'] != NULL)
							{
								$instantiation['standard'] = $general_track['format_profile'][0]['text'];
							}
							else if (isset($general_track['format']) && isset($general_track['format'][0]))
							{
								if ( ! empty($general_track['format'][0]['text']) || $general_track['format'][0]['text'] != NULL)
								{
									$instantiation['standard'] = $general_track['format'][0]['text'];
								}
							}
						}
						else if (isset($general_track['format']) && isset($general_track['format'][0]))
						{
							if ( ! empty($general_track['format'][0]['text']) || $general_track['format'][0]['text'] != NULL)
							{
								$instantiation['standard'] = $general_track['format'][0]['text'];
							}
						}
						/* Standard End */
						/* Tracks Start */
						$instantiation['tracks'] = '';
						if (isset($general_track['videocount']) && isset($general_track['videocount'][0]))
						{
							if ( ! empty($general_track['videocount'][0]['text']))
							{
								$add_comma = '';
								if ($instantiation['tracks'] !== '')
									$add_comma = ', ';

								$instantiation['tracks'].=$add_comma . $general_track['videocount'][0]['text'] . ' video';
							}
						}
						if (isset($general_track['audiocount']) && isset($general_track['audiocount'][0]))
						{
							if ( ! empty($general_track['audiocount'][0]['text']))
							{
								$add_comma = '';
								if ($instantiation['tracks'] !== '')
									$add_comma = ', ';
								$instantiation['tracks'].=$add_comma . $general_track['audiocount'][0]['text'] . ' audio';
							}
						}
						if (isset($general_track['menucount']) && isset($general_track['menucount'][0]))
						{
							if ( ! empty($general_track['menucount'][0]['text']))
							{
								$add_comma = '';
								if ($instantiation['tracks'] !== '')
									$add_comma = ', ';
								$instantiation['tracks'].=$add_comma . $general_track['menucount'][0]['text'] . ' menu';
							}
						}
						if (isset($general_track['textcount']) && isset($general_track['textcount'][0]))
						{
							if ( ! empty($general_track['textcount'][0]['text']))
							{
								$add_comma = '';
								if ($instantiation['tracks'] !== '')
									$add_comma = ', ';
								$instantiation['tracks'].=$add_comma . $general_track['textcount'][0]['text'] . ' text';
							}
						}
//					echo '<br/>Tracks = ' . $instantiation['tracks'];
						/* Tracks End */
						/* Data Rate Start */
						if (isset($general_track['overallbitrate_string']) && isset($general_track['overallbitrate_string'][0]))
						{
							if ( ! empty($general_track['overallbitrate_string'][0]['text']))
							{
								$datarate = explode(' ', $general_track['overallbitrate_string'][0]['text']);
								$instantiation['data_rate'] = (isset($datarate[0])) ? $datarate[0] : '';
//							echo '<br/>Data Rate = ' . $instantiation['data_rate'];
								$data_rate_unit = (isset($datarate[1])) ? $datarate[1] : '';
//							echo '<br/>Data Rate Unit = ' . $data_rate_unit;
								if ($data_rate_unit != '')
								{
									$inst_media_type = $this->instant->get_data_rate_units_by_unit($data_rate_unit);
									if ( ! is_empty($inst_media_type))
										$instantiation['data_rate_units_id'] = $inst_media_type->id;
									else
										$instantiation['data_rate_units_id'] = $this->instant->insert_data_rate_units(array('unit_of_measure' => $data_rate_unit));
								}
							}
						}
						/* Data Rate End */
						/* File Size Start */
						if (isset($general_track['filesize_string4']) && isset($general_track['filesize_string4'][0]))
						{
							if ( ! empty($general_track['filesize_string4'][0]['text']))
							{
								$filesize = explode(' ', $general_track['filesize_string4'][0]['text']);
								$instantiation['file_size'] = (isset($filesize[0])) ? $filesize[0] : '';
								$instantiation['file_size_unit_of_measure'] = (isset($filesize[1])) ? $filesize[1] : '';
							}
						}
						/* File Size End */
						/* Identifier and Generation Start */
						if (isset($general_track['filename']) && isset($general_track['filename'][0]))
						{

							if (isset($general_track['fileextension']) && isset($general_track['fileextension'][0]))
							{
								$identifier['instantiation_identifier'] = $general_track['filename'][0]['text'];


								$db_asset_id = $this->get_asset_id_for_media_import($identifier['instantiation_identifier']);
								$parent_instantiations = $this->instant->get_instantiation_by_asset_id($db_asset_id);
								if (count($parent_instantiations) == 1)
								{
									$this->instant->update_instantiations($parent_instantiations[0]->id, array('digitized' => 1));
									$this->update_ins_asset_index($parent_instantiations[0]->id);
								}
								else
								{
									$parent_instantiations = $this->instant->get_instantiation_with_event_by_asset_id($db_asset_id);
									if (count($parent_instantiations) > 0)
									{
										$this->instant->update_instantiations($parent_instantiations->id, array('digitized' => 1));
										$this->update_ins_asset_index($parent_instantiations->id);
									}
								}

								$identifier['instantiation_identifier'] = $general_track['filename'][0]['text'] . '.' . $general_track['fileextension'][0]['text'];

								if ($db_asset_id)
								{
									$instantiation['assets_id'] = $db_asset_id;
								}

								$identifier['instantiation_source'] = 'mediainfo';

								$db_instantiation_id = $this->instant->insert_instantiations($instantiation);
								$identifier['instantiations_id'] = $db_instantiation_id;
								$this->instant->insert_instantiation_identifier($identifier);
								$filename = $identifier['instantiation_identifier'];
								$generation = '';
								if (strstr($filename, '.j2k.mxf'))
								{
									$generation = 'Preservation Master';
								}
								else if (strstr($filename, '.mpeg2.mxf'))
								{
									$generation = 'Mezzanine';
								}
								else if (strstr($filename, '.h264.mov'))
								{
									$generation = 'Proxy';
								}
								else if (strstr($filename, '.wav'))
								{
									$generation = 'Preservation Master';
								}
								else if (strstr($filename, '.mp3'))
								{
									$generation = 'Proxy';
								}
								if ($generation != '')
								{
//								echo '<br/>Generation = ' . $generation;
									$generations_d = $this->instant->get_generations_by_generation($generation);
									if (isset($generations_d) && isset($generations_d->id))
									{
										$generations['generations_id'] = $generations_d->id;
									}
									else
									{
										$generations['generations_id'] = $this->instant->insert_generations(array("generation" => $generation));
									}
									$generations['instantiations_id'] = $db_instantiation_id;
									$this->instant->insert_instantiation_generations($generations);
								}
							}
						}

						/* Identifier and Generation End */
						/* Instantiation Date Start */
						if (isset($general_track['encoded_date']) && isset($general_track['encoded_date'][0]))
						{

							if ( ! empty($general_track['encoded_date'][0]['text']) || $general_track['encoded_date'][0]['text'] != NULL)
							{
								$date['instantiation_date'] = date('Y-m-d', strtotime($general_track['encoded_date'][0]['text']));
//							echo '<br/>Instantitation Date = ' . $date['instantiation_date'];
//							echo '<br/>Instantitation Date Type = encoded';
							}
							else if (isset($general_track['file_modified_date']) && isset($general_track['file_modified_date'][0]))
							{
								$date['instantiation_date'] = date('Y-m-d', strtotime($general_track['file_modified_date'][0]['text']));
//							echo '<br/>Instantitation Date = ' . $date['instantiation_date'];
//							echo '<br/>Instantitation Date Type = encoded';
							}
							if (isset($date['instantiation_date']) && $date['instantiation_date'] != '')
							{
								$date_type = $this->instant->get_date_types_by_type('encoded');
								if (isset($date_type) && isset($date_type->id))
								{
									$date['date_types_id'] = $date_type->id;
								}
								else
								{
									$date['date_types_id'] = $this->instant->insert_date_types(array('date_type' => 'encoded'));
								}
								$date['instantiations_id'] = $db_instantiation_id;
								$this->instant->insert_instantiation_dates($date);
							}
						}
						else if (isset($general_track['file_modified_date']) && isset($general_track['file_modified_date'][0]))
						{


							$date['instantiation_date'] = date('Y-m-d', strtotime($general_track['file_modified_date'][0]['text']));
//						echo '<br/>Instantitation Date = ' . $date['instantiation_date'];
//						echo '<br/>Instantitation Date Type = encoded';
							if (isset($date['instantiation_date']) && $date['instantiation_date'] != '')
							{
								$date_type = $this->instant->get_date_types_by_type('encoded');
								if (isset($date_type) && isset($date_type->id))
								{
									$date['date_types_id'] = $date_type->id;
								}
								else
								{
									$date['date_types_id'] = $this->instant->insert_date_types(array('date_type' => 'encoded'));
								}
								$date['instantiations_id'] = $db_instantiation_id;
								$this->instant->insert_instantiation_dates($date);
							}
						}
						/* Instantiation Date End */
						/* Instantiation Format Start */
						if (isset($general_track['internetmediatype']) && isset($general_track['internetmediatype'][0]))
						{

							$format['format_name'] = $general_track['internetmediatype'][0]['text'];
							$format['format_type'] = 'digital';
//						echo '<br/>Instantitation Format = ' . $format['format_name'];
//						echo '<br/>Instantitation Format Type = digital';
							$format['instantiations_id'] = $db_instantiation_id;
							$this->instant->insert_instantiation_formats($format);
						}
						/* Instantiation Format End */
						/* Instantiation Annotation Start */
						if (isset($general_track['encoded_library_string']) && isset($general_track['encoded_library_string'][0]))
						{
							if ( ! empty($general_track['encoded_library_string'][0]['text']))
							{
								$annotation['annotation'] = $general_track['encoded_library_string'][0]['text'];
								$annotation['annotation_type'] = 'encoded by';
//							echo '<br/>Instantitation annotation = ' . $annotation['annotation'];
//							echo '<br/>Instantitation annotation Type = ' . $annotation['annotation_type'];
								$annotation['instantiations_id'] = $db_instantiation_id;
								$this->instant->insert_instantiation_annotations($annotation);
							}
						}
						else if (isset($general_track['encodedby']) && isset($general_track['encodedby'][0]))
						{
							if ( ! empty($general_track['encodedby'][0]['text']))
							{
								$annotation['annotation'] = $general_track['encodedby'][0]['text'];
								$annotation['annotation_type'] = 'encoded by';
//							echo '<br/>Instantitation annotation = ' . $annotation['annotation'];
//							echo '<br/>Instantitation annotation Type = ' . $annotation['annotation_type'];
								$annotation['instantiations_id'] = $db_instantiation_id;
								$this->instant->insert_instantiation_annotations($annotation);
							}
						}
						/* Instantiation Annotation End */
					}
					else
					{
						unset($essence_track);

						if (isset($track['attributes']['type']) && $track['attributes']['type'] === 'Audio')
						{

							$audio_track = $track['children'];

							if (isset($audio_track['channel_s__string']) && isset($audio_track['channel_s__string'][0]))
							{
								if (isset($audio_track['channel_s__string'][0]['text']))
								{
									$channel = substr_replace($audio_track['channel_s__string'][0]['text'], "", -1);
//								echo '<br/>Channel Configuration = ' . $channel;

									$this->instant->update_instantiations($db_instantiation_id, array('channel_configuration' => $channel));
								}
							}
							if (isset($audio_track['samplingrate_string']) && isset($audio_track['samplingrate_string'][0]))
							{
								if (isset($audio_track['samplingrate_string'][0]['text']))
								{
									$dessence_track[$dessence_track_counter]['sampling_rate'] = $essence_track['sampling_rate'] = $audio_track['samplingrate_string'][0]['text'];
								}
							}
						}

						$track_type = '';
						if (isset($track['attributes']['type']))
						{
							$track_type = strtolower($track['attributes']['type']);
						}
						if ($track_type != '')
						{
							$dessence_track[$dessence_track_counter]['track_type'] = $track_type;

							$essence_track_type = $this->pbcore_model->get_one_by($this->pbcore_model->table_essence_track_types, array('essence_track_type' => $track_type), TRUE);
							if (isset($essence_track_type) && isset($essence_track_type->id))
							{
								$essence_track['essence_track_types_id'] = $essence_track_type->id;
							}
							else
							{
								$essence_track['essence_track_types_id'] = $this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_types, array('essence_track_type' => $track_type));
							}
						}
//					}
						/* Essence Track type End */



						/* Essence Track Standard Start */
						if (isset($track['children']['standard']) && isset($track['children']['standard'][0]) && isset($track['children']['standard'][0]['text']) && ! empty($track['children']['standard'][0]['text']))
						{

							$dessence_track[$dessence_track_counter]['standard'] = $essence_track['standard'] = $track['children']['standard'][0]['text'];
						}
						/* Essence Track Standard End */
						/* Essence Track Data Rate Start */
						if (isset($track['children']['bitrate_string']) && isset($track['children']['bitrate_string'][0]) && isset($track['children']['bitrate_string'][0]['text']) && ! empty($track['children']['bitrate_string'][0]['text']))
						{
							$bitrate = explode(' ', $track['children']['bitrate_string'][0]['text']);
							$dessence_track[$dessence_track_counter]['data_rate'] = $essence_track['data_rate'] = (isset($bitrate[0])) ? $bitrate[0] : '';
							$dessence_track[$dessence_track_counter]['data_rate_unit'] = $data_rate_unit = (isset($bitrate[1])) ? $bitrate[1] : '';
							if ($data_rate_unit != '')
							{
								$data_rate = $this->instant->get_data_rate_units_by_unit($data_rate_unit);
								if ( ! is_empty($data_rate))
									$essence_track['data_rate_units_id'] = $data_rate->id;
								else
									$essence_track['data_rate_units_id'] = $this->instant->insert_data_rate_units(array('unit_of_measure' => $data_rate_unit));
							}
						}
						/* Essence Track Date Rate End */
						/* Essence Track Bitdepth Start */
						if (isset($track['children']['bitdepth']) && isset($track['children']['bitdepth'][0]) && isset($track['children']['bitdepth'][0]['text']) && ! empty($track['children']['bitdepth'][0]['text']))
						{

							$dessence_track[$dessence_track_counter]['bit_depth'] = $essence_track['bit_depth'] = $track['children']['bitdepth'][0]['text'] . ' bits';
						}
						/* Essence Track Bitdepth End */
						/* Essence Track Duration Start */
						if (isset($track['children']['duration_string3']) && isset($track['children']['duration_string3'][0]) && isset($track['children']['duration_string3'][0]['text']) && ! empty($track['children']['duration_string3'][0]['text']))
						{
							$dessence_track[$dessence_track_counter]['duration'] = $essence_track['duration'] = date('H:i:s', strtotime($track['children']['duration_string3'][0]['text']));
						}
						/* Essence Track Duration End */
						/* Essence Track Language Start */
						if (isset($track['children']['language_string3']) && isset($track['children']['language_string3'][0]) && isset($track['children']['language_string3'][0]['text']) && ! empty($track['children']['language_string3'][0]['text']))
						{

							$dessence_track[$dessence_track_counter]['language'] = $essence_track['language'] = $track['children']['language_string3'][0]['text'];
						}
						/* Essence Track Language End */


						/* Insert Essence Track Start */
						$essence_track['instantiations_id'] = $db_instantiation_id;

						$db_essence_track_id = $this->pbcore_model->insert_record($this->pbcore_model->table_essence_tracks, $essence_track);

						/* Insert Essence Track End */

						/* Essence Track Encoding Start */
						$essence_track_encodeing = array();


						if (isset($track['children']['codec_string']) && isset($track['children']['codec_string'][0]) && isset($track['children']['codec_string'][0]['text']) && ! empty($track['children']['codec_string'][0]['text']))
						{
							$dessence_track[$dessence_track_counter]['encoding'] = $essence_track_encodeing['encoding'] = $track['children']['codec_string'][0]['text'];
						}
						else if (isset($track['children']['format']) && isset($track['children']['format'][0]) && isset($track['children']['format'][0]['text']) && ! empty($track['children']['format'][0]['text']))
						{
							$dessence_track[$dessence_track_counter]['encoding'] = $essence_track_encodeing['encoding'] = $track['children']['format'][0]['text'];
						}

						if (isset($track['children']['codec_url']) && isset($track['children']['codec_url'][0]) && isset($track['children']['codec_url'][0]['text']) && ! empty($track['children']['codec_url'][0]['text']))
						{
							$dessence_track[$dessence_track_counter]['encoding_ref'] = $essence_track_encodeing['encoding_ref'] = $track['children']['codec_url'][0]['text'];
						}
						else if (isset($track['children']['format_url']) && isset($track['children']['format_url'][0]) && isset($track['children']['format_url'][0]['text']) && ! empty($track['children']['format_url'][0]['text']))
						{
							$dessence_track[$dessence_track_counter]['encoding_ref'] = $essence_track_encodeing['encoding_ref'] = $track['children']['format_url'][0]['text'];
						}
						if (isset($essence_track_encodeing['encoding']))
						{
							$essence_track_encodeing['essence_tracks_id'] = $db_essence_track_id;
							$dessence_track[$dessence_track_counter]['encoding_source'] = $essence_track_encodeing['encoding_source'] = 'mediainfo';
							$this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_encodings, $essence_track_encodeing);
						}
						unset($essence_track_encodeing);
						/* Essence Track Encoding End */
						/* Essence Track Identifier Start */
						$essence_track_identifier = array();
						if (isset($track['children']['id']) && isset($track['children']['id'][0]) && isset($track['children']['id'][0]['text']) && ! empty($track['children']['id'][0]['text']))
						{
							$dessence_track[$dessence_track_counter]['identifier'] = $essence_track_identifier['essence_track_identifiers'] = $track['children']['id'][0]['text'];
							$dessence_track[$dessence_track_counter]['identifier_source'] = $essence_track_identifier['essence_track_identifier_source'] = 'mediainfo';
						}
						else if (isset($track['children']['streamkindid']) && isset($track['children']['streamkindid'][0]) && isset($track['children']['streamkindid'][0]['text']))
						{
							$dessence_track[$dessence_track_counter]['identifier'] = $essence_track_identifier['essence_track_identifiers'] = $track['children']['streamkindid'][0]['text'];
							$dessence_track[$dessence_track_counter]['identifier_source'] = $essence_track_identifier['essence_track_identifier_source'] = 'mediainfo';
						}
						if (isset($essence_track_identifier['essence_track_identifiers']))
						{
							$essence_track_identifier['essence_tracks_id'] = $db_essence_track_id;
							$this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_identifiers, $essence_track_identifier);
						}

						unset($essence_track_identifier);
						/* Essence Track Identifier End */
						if (isset($track['attributes']['type']) && $track['attributes']['type'] === 'Video')
						{
							unset($essence_track);
							$video_track = $track['children'];
							/* Essence Track Frame Rate Start */
							if (isset($video_track['framerate']) && isset($video_track['framerate'][0]))
							{
								if (isset($video_track['framerate'][0]['text']))
								{
									$dessence_track[$dessence_track_counter]['frame_rate'] = $essence_track['frame_rate'] = $video_track['framerate'][0]['text'];
								}
							}
							/* Essence Track Frame Rate End */
							/* Essence Track Aspect Ratio Start */
							if (isset($video_track['displayaspectratio_string']) && isset($video_track['displayaspectratio_string'][0]))
							{
								if (isset($video_track['displayaspectratio_string'][0]['text']))
								{
									$dessence_track[$dessence_track_counter]['aspect_ratio'] = $essence_track['aspect_ratio'] = $video_track['displayaspectratio_string'][0]['text'];
								}
							}
							/* Essence Track Aspect Ratio End */

							/* Essence Track Frame Size Start */
							$frame = array();
							if (isset($video_track['width']) && isset($video_track['width'][0]))
							{
								if (isset($video_track['width'][0]['text']))
								{
									$dessence_track[$dessence_track_counter]['width'] = $frame['width'] = $video_track['width'][0]['text'];
								}
							}
							if (isset($video_track['height']) && isset($video_track['height'][0]))
							{
								if (isset($video_track['height'][0]['text']))
								{
									$dessence_track[$dessence_track_counter]['height'] = $frame['height'] = $video_track['height'][0]['text'];
								}
							}
							if (isset($frame['width']) || isset($frame['height']))
							{
								$track_frame_size = $this->pbcore_model->get_one_by($this->pbcore_model->table_essence_track_frame_sizes, array('width' => trim($frame['width']), 'height' => trim($frame['height'])));
								if ($track_frame_size)
								{
									$essence_track['essence_track_frame_sizes_id'] = $track_frame_size->id;
								}
								else
								{
									$essence_track['essence_track_frame_sizes_id'] = $this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_frame_sizes, $frame);
								}
							}
							unset($frame);
							/* Essence Track Frame Size End */
							/* Update Essence Track Start */
							$this->pbcore_model->update_essence_track($db_essence_track_id, $essence_track);
							/* Update Essence Track End */

							/* Essence Track Annotation Start */
							$essence_annotation = array();

							if (isset($video_track['colorspace']) && isset($video_track['colorspace'][0]))
							{
								if (isset($video_track['colorspace'][0]['text']))
								{
									$dessence_track[$dessence_track_counter]['annotation'][] = $essence_annotation[] = array('annotation' => $video_track['colorspace'][0]['text'], 'annotation_type' => 'colorspace');
								}
							}
							if (isset($video_track['chromasubsampling']) && isset($video_track['chromasubsampling'][0]))
							{
								if (isset($video_track['chromasubsampling'][0]['text']))
								{
									$dessence_track[$dessence_track_counter]['annotation'][] = $essence_annotation[] = array('annotation' => $video_track['chromasubsampling'][0]['text'], 'annotation_type' => 'subsampling');
								}
							}
							if (count($essence_annotation) > 0)
							{
								foreach ($essence_annotation as $annotation)
								{
									$annotation['essence_tracks_id'] = $db_essence_track_id;
									$this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_annotations, $annotation);
								}
							}
							unset($essence_annotation);
							/* Essence Track Annotation End */
						}
						$dessence_track_counter ++;
					}
					$this->insert_ins_asset_index($db_instantiation_id);
					unset($db_instantiation_id);
				}
			}
			else
			{
				$this->myLog('Error while importing the mediainfo file: ' . $file_path);
			}
			unset($instantiation);
			unset($essence_track);
		}
	}

	function insert_ins_asset_index($db_instantiation_id)
	{
		$this->load->library('sphnixrt');
		$this->load->model('searchd_model');
		$this->load->helper('sphnixdata');
		$instantiation_list = $this->searchd_model->get_ins_index(array($db_instantiation_id));
		$new_list_info = make_instantiation_sphnix_array($instantiation_list[0]);
		myLog('Instantiation Inserted');
		$this->sphnixrt->insert('instantiations_list', $new_list_info, $db_instantiation_id);
		$asset_list = $this->searchd_model->get_asset_index(array($instantiation_list[0]->assets_id));
		$new_asset_info = make_assets_sphnix_array($asset_list[0], FALSE);
		$this->sphnixrt->update('assets_list', $new_asset_info);
	}

	function update_ins_asset_index($db_instantiation_id)
	{
		$this->load->library('sphnixrt');
		$this->load->model('searchd_model');
		$this->load->helper('sphnixdata');
		$instantiation_list = $this->searchd_model->get_ins_index(array($db_instantiation_id));
		$new_list_info = make_instantiation_sphnix_array($instantiation_list[0], FALSE);
		myLog('Instantiation Updated');
		$this->sphnixrt->update('instantiations_list', $new_list_info);

		$asset_list = $this->searchd_model->get_asset_index(array($instantiation_list[0]->assets_id));
		$new_asset_info = make_assets_sphnix_array($asset_list[0], FALSE);
		$this->sphnixrt->update('assets_list', $new_asset_info);
	}

	function get_asset_id_for_media_import($guid)
	{
		$asset_guid = explode('.', $guid);

		if (count($asset_guid) > 0)
		{
			$asset_guid = $asset_guid[0];
			$make_db_name = explode('cpb-aacip-', $asset_guid);

			if (count($make_db_name) > 1)
			{
				$make_db_name = explode('-', $make_db_name[1]);
				$guid_db = trim('cpb-aacip/' . $make_db_name[0] . '-' . $make_db_name[1]);
				$asset_id = $this->assets_model->get_asset_id_by_guid($guid_db);
				if ($asset_id && ! empty($asset_id))
				{
					return $asset_id->assets_id;
				}
			}
			return FALSE;
		}
		return FALSE;
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

	/**
	 * Check the date format
	 * 
	 * @param type $value
	 * @return boolean 
	 */
	function is_valid_date($value)
	{
		$date = date_parse($value);
		if ($date['error_count'] == 0 && $date['warning_count'] == 0)
		{
			return date("Y-m-d", strtotime($value));
		}
		return FALSE;
	}

}
