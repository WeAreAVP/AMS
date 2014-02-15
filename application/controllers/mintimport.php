<?php

// @codingStandardsIgnoreFile
/**
 * Mint Import Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AMS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Mintimport Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Mintimport extends CI_Controller
{

	public $mint_path;

	/**
	 * Constructor
	 * 
	 * Load Models, Set values for global variables.
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('assets_model');
		$this->load->model('instantiations_model', 'instant');
		$this->load->model('cron_model');
		$this->load->model('station_model');
		$this->load->model('mint_model', 'mint');
		$this->load->model('dx_auth/user_profile', 'user_profile');
		$this->load->model('dx_auth/users', 'users');
		$this->mint_path = 'assets/mint_import/';
		$this->load->model('pbcore_model');
	}

	public function visit()
	{
		$this->layout = 'main_layout.php';
		$user_id = $this->uri->segment(3);
		$user_info = $this->users->get_user_detail($user_id)->row();
		$username = explode('@', $user_info->email);
		$data['user_id'] = $user_id;
		$data['email'] = $user_info->email;
		$data['first_name'] = $user_info->first_name;
		$data['last_name'] = $user_info->last_name;
		if ($user_info->role_id == 1 || $user_info->role_id == 2)
			$data['rights'] = 7;
		else
			$data['rights'] = 4;
		/* Already we have mint user */
		if ( ! empty($user_info->mint_user_id) && $user_info->mint_user_id != NULL)
		{
			$data['mint_id'] = $user_info->mint_user_id;
		}
		else /* Need to Create a new mint user */
		{
			$data['mint_id'] = NULL;
		}
		$this->load->view('mint_login', $data);
	}

	/**
	 * Manage transformation info of mint user.
	 * 
	 */
	public function save_transformed_info()
	{
		$record['mint_user_id'] = $this->uri->segment(3);
		$record['transformed_id'] = $this->uri->segment(4);
		$record['is_approved'] = 0;
		$record['is_downloaded '] = 0;
		$user_info = $this->user_profile->get_profile_by_mint_id($record['mint_user_id']);
		if ($user_info)
		{
			$record['user_id'] = $user_info->user_id;
			$already_exist = $this->mint->get_transformation($record['user_id'], $record['mint_user_id'], $record['transformed_id']);
			if ($already_exist)
			{
				$record['mint_id_approved_by'] = NULL;
				$record['user_id_approved_by'] = NULL;
				$this->mint->update_transformation($already_exist->id, $record);
			}
			else
			{
				$this->mint->insert_transformation($record);
			}
			$message = 'Hi<br/>';
			$message .=$user_info->first_name . ' ' . $user_info->last_name . ' completed importing and transformation. But need your approval to ingest data to AMS.</br>';
			$message .='<a href="' . base_url() . 'mintimport/visit/1' . '">Click here</a> to go to MINT.';

//			send_email($this->config->item('to_email'), $this->config->item('from_email'), 'Mint Transformation', $message);
			send_email('nouman@avpreserve.com', $this->config->item('from_email'), 'Mint Transformation', $message);
			echo 'Inserted new Transformation';
			exit;
		}
	}

	/**
	 * Update the transformation info.
	 * 
	 */
	public function update_transformed_info()
	{
		$transformed_id = $this->uri->segment(3);
		$record['is_approved'] = $this->uri->segment(4);
		$record['mint_id_approved_by'] = $this->uri->segment(5);
		$user_info = $this->user_profile->get_profile_by_mint_id($record['mint_id_approved_by']);
		if ($user_info)
		{
			$record['user_id_approved_by'] = $user_info->user_id;
			$record_exist = $this->mint->get_transformation_by_tID($transformed_id);
			if ($record_exist)
			{
				$this->mint->update_transformation($record_exist->id, $record);
				
				echo 'Updated Transformation';
				exit;
			}
		}
		send_email('nouman@avpreserve.com', $this->config->item('from_email'), 'Mint Transformation', 'testing');
	}

	public function download_transformed_zip()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		@set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB

		$not_downloaded = $this->mint->get_transformation_download(0);
		if ($not_downloaded)
		{
			$file_path = $this->mint_path . 'Transformation_' . $not_downloaded->transformed_id . '.zip';
			$url = $this->config->item('mint_url') . '/download?dbId=' . $not_downloaded->transformed_id . '&transformed=true';

			$fp = fopen($file_path, 'w');
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$data = curl_exec($ch);
			curl_close($ch);
			fclose($fp);
			$zip = new ZipArchive;
			$res = $zip->open($file_path);
			$stat = $zip->statIndex(0);
			if ($res === TRUE)
			{
				$zip->extractTo($this->mint_path . 'unzipped/');
				$zip->close();
				$this->mint->update_transformation($not_downloaded->id, array('is_downloaded' => 1, 'folder_name' => rtrim($stat['name'], '/')));
				myLog($stat['name']);
				$this->process_mint_dir($stat['name']);
			}
			else
			{
				myLog('Something went wrong  while extracting zip file.');
			}
		}
		else
		{
			myLog('All transformation files are already downloaded.');
		}
	}

	/**
	 * Unzip and store all the mint info imported files.
	 * 
	 */
	function process_mint_dir($folder_name)
	{
		set_time_limit(0);
		@ini_set("memory_limit", "2000M"); # 2GB
		@ini_set("max_execution_time", 999999999999);
		$this->load->helper('directory');
		myLog('Start Importing files for ' . $folder_name);
		$map = directory_map($this->mint_path . 'unzipped/' . $folder_name, 2);
		$count_files = 0;
		$station_id = 0;
		foreach ($map as $index => $file)
		{
			$station = $this->mint->get_station_by_transformed(rtrim($folder_name, '/'));

			if ($station && ! empty($station->station_id))
			{
				myLog("Station User/Admin import");
				$station_id = $station->station_id;
			}
			else
			{
				myLog("Admin/Crawford import");
				$station = $this->mint->get_last_import_by_user(rtrim($folder_name, '/'));
				if ($station)
					$station_id = $station->station_id;
			}

			myLog($station_id);
			if ($station_id !== 0)
			{
				$path = $folder_name . $file;
				$db_info = $this->mint->get_import_info_by_path($path);
				if ( ! $db_info)
				{
					$mint_info = array('folder' => $folder_name, 'path' => $path, 'is_processed' => 0, 'status_reason' => 'Not processed', 'station_id' => $station_id);

					$this->mint->insert_import_info($mint_info);
					$count_files ++;
				}
				else
					myLog('Already in db.');
			}
			else
			{
				myLog('Station Info is missing');
			}
		}
		myLog('All mint files info stored. Folder Path:' . $this->mint_path);
	}

	/**
	 * Import mint transformed files.
	 * 
	 */
	function import_mint_files()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		@ini_set("memory_limit", "2000M"); # 2GB
		@ini_set("max_execution_time", 999999999999);

		$result = $this->mint->get_files_to_import();
		if ($result)
		{
			foreach ($result as $row)
			{
				myLog('Start importing data.');
				$this->mint->update_mint_import_file($row->id, array('processed_at' => date('Y-m-d H:m:i'), 'status_reason' => 'Processing'));
				$this->parse_xml_file($row->path, $row->station_id);
				myLog('End importing data.');
				$this->mint->update_mint_import_file($row->id, array('is_processed' => 1, 'status_reason' => 'Complete'));
				myLog('Successfully finished.');
			}
		}
		else
		{
			myLog('No file available for importing.');
		}
	}

	/**
	 * Parse XML file for importing.
	 * 
	 * @param string $path file path
	 */
	function parse_xml_file($path, $station_id)
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		@ini_set("max_execution_time", 999999999999);
		myLog($path);
		$file_content = file_get_contents($this->mint_path . 'unzipped/' . $path);

		$xml_string = @simplexml_load_string($file_content);
		unset($file_content);
		$xmlArray = xmlObjToArr($xml_string);

		foreach ($xmlArray['children']['ams:pbcoredescriptiondocument'] as $document)
		{
			if (isset($document['children']))
			{
				$asset_id = $this->assets_model->insert_assets(array("stations_id" => $station_id, "created" => date("Y-m-d H:i:s")));
				myLog('Created Asset ID ' . $asset_id);
				$this->import_asset_info($asset_id, $station_id, $document['children']);
				myLog('Successfully inserted assets info.');
				$this->import_instantiation_info($asset_id, $document['children']);
				myLog('Successfully imported all the information to AMS');
			}
		}
	}

	/**
	 * Parse xml and save assets information to database.
	 * 
	 * @param integer $asset_id
	 * @param array $xmlArray
	 */
	function import_asset_info($asset_id, $station_id, $xmlArray)
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		// Asset Type Start //
		if (isset($xmlArray['ams:pbcoreassettype']))
		{
			foreach ($xmlArray['ams:pbcoreassettype'] as $pbcoreassettype)
			{

				if (isset($pbcoreassettype['text']) && ! is_empty($pbcoreassettype['text']))
				{
					$asset_type_detail = array();
					$asset_type_detail['assets_id'] = $asset_id;
					if ($asset_type = $this->assets_model->get_assets_type_by_type($pbcoreassettype['text']))
					{
						$asset_type_detail['asset_types_id'] = $asset_type->id;
					}
					else
					{
						$asset_type_detail['asset_types_id'] = $this->assets_model->insert_asset_types(array("asset_type" => $pbcoreassettype['text']));
					}

					$this->assets_model->insert_assets_asset_types($asset_type_detail);
					unset($asset_type_detail);
				}
			}
		}

		// Asset Type End //
		// Asset Date and Type Start //
		if (isset($xmlArray['ams:pbcoreassetdate']))
		{
			foreach ($xmlArray['ams:pbcoreassetdate'] as $pbcoreassetdate)
			{
				$asset_date_info = array();
				$asset_date_info['assets_id'] = $asset_id;
				if (isset($pbcoreassetdate['text']) && ! is_empty($pbcoreassetdate['text']))
				{
					$asset_date_info['asset_date'] = $pbcoreassetdate['text'];
					if (isset($pbcoreassetdate['attributes']['datetype']) && ! is_empty($pbcoreassetdate['attributes']['datetype']))
					{
						if ($asset_date_type = $this->instant->get_date_types_by_type($pbcoreassetdate['attributes']['datetype']))
						{
							$asset_date_info['date_types_id'] = $asset_date_type->id;
						}
						else
						{
							$asset_date_info['date_types_id'] = $this->instant->insert_date_types(array("date_type" => $pbcoreassetdate['attributes']['datetype']));
						}
					}
					$this->assets_model->insert_asset_date($asset_date_info);
				}
			}
		}

		// Asset Date and Type End //
		// Insert Identifier Start //

		if (isset($xmlArray['ams:pbcoreidentifier']) && ! empty($xmlArray['ams:pbcoreidentifier']))
		{
			$is_minted = TRUE;
			foreach ($xmlArray['ams:pbcoreidentifier'] as $row)
			{
				if (isset($row['text']) && ! empty($row['text']))
				{
					$identifier_detail = array();
					$identifier_detail['assets_id'] = $asset_id;
					$identifier_detail['identifier'] = trim($row['text']);
					$identifier_detail['identifier_source'] = '';
					$identifier_detail['identifier_ref'] = '';
					if (isset($row['attributes']['source']) && ! empty($row['attributes']['source']))
					{
						$identifier_detail['identifier_source'] = $row['attributes']['source'];
						if ($identifier_detail['identifier_source'] == 'http://americanarchiveinventory.org')
							$is_minted = FALSE;
					}

					if (isset($row['attributes']['ref']) && ! empty($row['attributes']['ref']))
						$identifier_detail['identifier_ref'] = $row['attributes']['ref'];

					$this->assets_model->insert_identifiers($identifier_detail);
					unset($identifier_detail);
				}
			}
			if ($is_minted)
			{
				$aacip_id = '';
				$station_info = $this->station_model->get_station_by_id($station_id);
				if ( ! empty($station_info->aacip_id))
				{
					$aacip_id = $station_info->aacip_id;
				}
				else
				{
					$records = file('aacip_cpb_stationid.csv');
					foreach ($records as $index => $line)
					{
						$explode_ids = explode(',', $line);
						if (isset($explode_ids[1]) && trim($explode_ids[1]) == trim($station_info->cpb_id))
							$aacip_id = $explode_ids[0];
					}
					if (empty($aacip_id))
					{
						// permanantly assign aacip_id  to station.
						$aacip_id = $this->station_model->assign_aacip_id()->id;
						// make increment in aacip_id for new station.
						$this->station_model->increment_aacip_id();
					}
					$this->station_model->update_station($station_id, array('aacip_id' => $aacip_id));
				}



				$guid_string = file_get_contents($this->config->item('base_url') . 'nd/noidu_kt5?mint+1');
				if ( ! empty($guid_string))
				{
					$explode_guid = explode('id:', $guid_string);
					if (count($explode_guid) > 1)
					{
						$guid = 'cpb-aacip/' . $aacip_id . '-' . trim($explode_guid[1]);
					}
				}
				if ( ! empty($guid))
				{
					$identifier_detail['assets_id'] = $asset_id;
					$identifier_detail['identifier'] = $guid;
					$identifier_detail['identifier_source'] = 'http://americanarchiveinventory.org';
					$this->assets_model->insert_identifiers($identifier_detail);
				}
			}
		}

		// Insert Identifier End //
		// Insert Asset Title Start //

		if (isset($xmlArray['ams:pbcoretitle']) && ! empty($xmlArray['ams:pbcoretitle']))
		{
			foreach ($xmlArray['ams:pbcoretitle'] as $row)
			{
				if (isset($row['text']) && ! empty($row['text']))
				{
					$title_detail = array();
					$title_detail['assets_id'] = $asset_id;
					$title_detail['title'] = trim($row['text']);

					if (isset($row['attributes']['titletype']) && ! empty($row['attributes']['titletype']))
					{
						$asset_title_types = $this->assets_model->get_asset_title_types_by_title_type(trim($row['attributes']['titletype']));
						if (isset($asset_title_types) && isset($asset_title_types->id))
						{
							$asset_title_types_id = $asset_title_types->id;
						}
						else
						{
							$asset_title_types_id = $this->assets_model->insert_asset_title_types(array("title_type" => trim($row['attributes']['titletype'])));
						}
						$title_detail['asset_title_types_id'] = $asset_title_types_id;
					}
					if (isset($row['attributes']['ref']) && ! is_empty($row['attributes']['ref']))
					{
						$title_detail['title_ref'] = $row['attributes']['ref'];
					}
					if (isset($row['attributes']['source']) && ! is_empty($row['attributes']['source']))
					{
						$title_detail['title_source'] = $row['attributes']['source'];
					}
					$title_detail['created'] = date('Y-m-d H:i:s');
					$this->assets_model->insert_asset_titles($title_detail);
					unset($title_detail);
				}
			}
		}

		// Insert Asset Title End //
		// Asset Subject Start //

		if (isset($xmlArray['ams:pbcoresubject']))
		{
			foreach ($xmlArray['ams:pbcoresubject'] as $pbcoresubject)
			{
				$subject_detail = array();
				if (isset($pbcoresubject['text']) && ! is_empty($pbcoresubject['text']))
				{
					$subject_detail['assets_id'] = $asset_id;
					$subject_d = array();
					$subject_d['subject'] = trim($pbcoresubject['text']);
					if (isset($pbcoresubject['attributes']['ref']) && ! is_empty($pbcoresubject['attributes']['ref']))
					{
						$subject_d['subject_ref'] = $pbcoresubject['attributes']['ref'];
					}
					if (isset($pbcoresubject['attributes']['source']) && ! is_empty($pbcoresubject['attributes']['source']))
					{
						$subject_d['subject_source'] = $pbcoresubject['attributes']['source'];
					}
					if (isset($pbcoresubject['attributes']['subjecttype']) && ! is_empty($pbcoresubject['attributes']['subjecttype']))
					{

						$subject_type = $pbcoresubject['attributes']['subjecttype'];
						$db_subject_type = $this->assets_model->get_subject_type_by_type($subject_type);
						if ($db_subject_type)
							$subject_d['subjects_types_id'] = $db_subject_type->id;
						else
							$subject_d['subjects_types_id'] = $this->assets_model->insert_subject_type(array('subject_type' => $subject_type));
					}
					$subjects = $this->assets_model->get_subjects_id_by_subject($subject_d['subject']);
					if (isset($subjects) && isset($subjects->id))
					{
						$subject_id = $subjects->id;
					}
					else
					{
						$subject_id = $this->assets_model->insert_subjects($subject_d);
					}
					$subject_detail['subjects_id'] = $subject_id;
					$assets_subject_id = $this->assets_model->insert_assets_subjects($subject_detail);
				}
			}
		}
		// Asset Subject End  //
		// Asset Description Start //

		if (isset($xmlArray['ams:pbcoredescription']))
		{
			foreach ($xmlArray['ams:pbcoredescription'] as $pbcoredescription)
			{
				$asset_descriptions_d = array();
				if (isset($pbcoredescription['text']) && ! is_empty($pbcoredescription['text']))
				{
					$asset_descriptions_d['assets_id'] = $asset_id;
					$asset_descriptions_d['description'] = $pbcoredescription['text'];
					if (isset($pbcoredescription['attributes']['descriptiontype']) && ! is_empty($pbcoredescription['attributes']['descriptiontype']))
					{
						$asset_description_type = $this->assets_model->get_description_by_type($pbcoredescription['attributes']['descriptiontype']);
						if (isset($asset_description_type) && isset($asset_description_type->id))
						{
							$asset_description_types_id = $asset_description_type->id;
						}
						else
						{
							$asset_description_types_id = $this->assets_model->insert_description_types(array("description_type" => $pbcoredescription['attributes']['descriptiontype']));
						}
						$asset_descriptions_d['description_types_id'] = $asset_description_types_id;
					}
					$this->assets_model->insert_asset_descriptions($asset_descriptions_d);
				}
			}
		}
		// Asset Description End  //
		// Asset Genre Start //

		if (isset($xmlArray['ams:pbcoregenre']))
		{
			foreach ($xmlArray['ams:pbcoregenre'] as $pbcoregenre)
			{
				$asset_genre_d = array();
				$asset_genre = array();
				$asset_genre['assets_id'] = $asset_id;
				if (isset($pbcoregenre['text']) && ! is_empty($pbcoregenre['text']))
				{
					$asset_genre_d['genre'] = $pbcoregenre['text'];
					$asset_genre_type = $this->assets_model->get_genre_type($pbcoregenre['text']);
					if (isset($asset_genre_type) && isset($asset_genre_type->id))
					{
						$asset_genre['genres_id'] = $asset_genre_type->id;
					}
					else
					{
						if (isset($pbcoregenre['attributes']['source']) && ! is_empty($pbcoregenre['attributes']['source']))
						{
							$asset_genre_d['genre_source'] = $pbcoregenre['attributes']['source'];
						}
						if (isset($pbcoregenre['attributes']['ref']) && ! is_empty($pbcoregenre['attributes']['ref']))
						{
							$asset_genre_d['genre_ref'] = $pbcoregenre['attributes']['ref'];
						}
						$asset_genre_id = $this->assets_model->insert_genre($asset_genre_d);
						$asset_genre['genres_id'] = $asset_genre_id;
					}
					$this->assets_model->insert_asset_genre($asset_genre);
				}
			}
		}
		// Asset Genre End  //
		// Asset Coverage Start  //
		if (isset($xmlArray['ams:pbcorecoverage']))
		{
			foreach ($xmlArray['ams:pbcorecoverage'] as $pbcore_coverage)
			{
				$coverage = array();
				$coverage['assets_id'] = $asset_id;
				if (isset($pbcore_coverage['children']['ams:coverage'][0]['text']) && ! is_empty($pbcore_coverage['children']['ams:coverage'][0]['text']))
				{
					$coverage['coverage'] = $pbcore_coverage['children']['ams:coverage'][0]['text'];
					if (isset($pbcore_coverage['children']['ams:coveragetype'][0]['text']) && ! is_empty($pbcore_coverage['children']['ams:coveragetype'][0]['text']))
					{
						$coverage['coverage_type'] = $pbcore_coverage['children']['ams:coveragetype'][0]['text'];
					}
					$asset_coverage = $this->assets_model->insert_coverage($coverage);
				}
			}
		}
		// Asset Coverage End  //
		// Asset Audience Level Start //

		if (isset($xmlArray['ams:pbcoreaudiencelevel']))
		{
			foreach ($xmlArray['ams:pbcoreaudiencelevel'] as $pbcoreaudiencelevel)
			{
				$audience_level = array();
				$asset_audience_level = array();
				$asset_audience_level['assets_id'] = $asset_id;
				if (isset($pbcoreaudiencelevel['text']) && ! is_empty($pbcoreaudiencelevel['text']))
				{
					$audience_level['audience_level'] = trim($pbcoreaudiencelevel['text']);
					if (isset($pbcoreaudiencelevel['attributes']['source']) && ! is_empty($pbcoreaudiencelevel['attributes']['source']))
					{
						$audience_level['audience_level_source'] = $pbcoreaudiencelevel['attributes']['source'];
					}
					if (isset($pbcoreaudiencelevel['attributes']['ref']) && ! is_empty($pbcoreaudiencelevel['attributes']['ref']))
					{
						$audience_level['audience_level_ref'] = $pbcoreaudiencelevel['attributes']['ref'];
					}
					$db_audience_level = $this->assets_model->get_audience_level($pbcoreaudiencelevel['text']);
					if (isset($db_audience_level) && isset($db_audience_level->id))
					{
						$asset_audience_level['audience_levels_id'] = $db_audience_level->id;
					}
					else
					{
						$asset_audience_level['audience_levels_id'] = $this->assets_model->insert_audience_level($audience_level);
					}
					$asset_audience = $this->assets_model->insert_asset_audience($asset_audience_level);
				}
			}
		}
		// Asset Audience Level End  //
		if (isset($xmlArray['ams:pbcoreaudiencerating']))
		{
			foreach ($xmlArray['ams:pbcoreaudiencerating'] as $pbcoreaudiencerating)
			{
				$audience_rating = array();
				$asset_audience_rating = array();
				$asset_audience_rating['assets_id'] = $asset_id;
				if (isset($pbcoreaudiencerating['text']) && ! is_empty($pbcoreaudiencerating['text']))
				{
					$db_audience_rating = $this->assets_model->get_audience_rating($pbcoreaudiencerating['text']);
					if (isset($db_audience_rating) && isset($db_audience_rating->id))
					{
						$asset_audience_rating['audience_ratings_id'] = $db_audience_rating->id;
					}
					else
					{
						$audience_rating['audience_rating'] = $pbcoreaudiencerating['text'];
						if (isset($pbcoreaudiencerating['attributes']['source']) && ! is_empty($pbcoreaudiencerating['attributes']['source']))
						{
							$audience_rating['audience_rating_source'] = $pbcoreaudiencerating['attributes']['source'];
						}
						if (isset($pbcoreaudiencerating['attributes']['ref']) && ! is_empty($pbcoreaudiencerating['attributes']['ref']))
						{
							$audience_rating['audience_rating_ref'] = $pbcoreaudiencerating['attributes']['ref'];
						}
						$asset_audience_rating['audience_ratings_id'] = $this->assets_model->insert_audience_rating($audience_rating);
					}
					$asset_audience_rate = $this->assets_model->insert_asset_audience_rating($asset_audience_rating);
				}
			}
		}
		// Asset Audience Rating End  //
		// Asset Annotation Start //

		if (isset($xmlArray['ams:pbcoreannotation']))
		{
			foreach ($xmlArray['ams:pbcoreannotation'] as $pbcoreannotation)
			{
				$annotation = array();
				$annotation['assets_id'] = $asset_id;
				if (isset($pbcoreannotation['text']) && ! is_empty($pbcoreannotation['text']))
				{
					$annotation['annotation'] = $pbcoreannotation['text'];
					if (isset($pbcoreannotation['attributes']['annotationtype']) && ! is_empty($pbcoreannotation['attributes']['annotationtype']))
					{
						$annotation['annotation_type'] = $pbcoreannotation['attributes']['annotationtype'];
					}
					if (isset($pbcoreannotation['attributes']['ref']) && ! is_empty($pbcoreannotation['attributes']['ref']))
					{
						$annotation['annotation_ref'] = $pbcoreannotation['attributes']['ref'];
					}

					$asset_annotation = $this->assets_model->insert_annotation($annotation);
				}
			}
		}
		// Asset Annotation End  //
		// Asset Relation Start  //
		if (isset($xmlArray['ams:pbcorerelation']))
		{
			foreach ($xmlArray['ams:pbcorerelation'] as $pbcorerelation)
			{
				$assets_relation = array();
				$assets_relation['assets_id'] = $asset_id;
				$relation_types = array();
				if (isset($pbcorerelation['children']['ams:pbcorerelationidentifier'][0]['text']) && ! is_empty($pbcorerelation['children']['ams:pbcorerelationidentifier'][0]['text']))
				{
					$assets_relation['relation_identifier'] = $pbcorerelation['children']['ams:pbcorerelationidentifier'][0]['text'];
					if (isset($pbcorerelation['children']['ams:pbcorerelationtype'][0]['text']) && ! is_empty($pbcorerelation['children']['ams:pbcorerelationtype'][0]['text']))
					{

						$relation_types['relation_type'] = $pbcorerelation['children']['ams:pbcorerelationtype'][0]['text'];
						if (isset($pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['source']) && ! is_empty($pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['source']))
						{
							$relation_types['relation_type_source'] = $pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['source'];
						}
						if (isset($pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['ref']) && ! is_empty($pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['ref']))
						{
							$relation_types['relation_type_ref'] = $pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['ref'];
						}
						$db_relations = $this->assets_model->get_relation_types($relation_types['relation_type']);
						if (isset($db_relations) && isset($db_relations->id))
						{
							$assets_relation['relation_types_id'] = $db_relations->id;
						}
						else
						{
							$assets_relation['relation_types_id'] = $this->assets_model->insert_relation_types($relation_types);
						}
					}
					$this->assets_model->insert_asset_relation($assets_relation);
				}
			}
		}
		// Asset Relation End  //
		// Asset Creator Start  //
		if (isset($xmlArray['ams:pbcorecreator']))
		{
			foreach ($xmlArray['ams:pbcorecreator'] as $pbcore_creator)
			{
				$assets_creators_roles_d = array();
				$assets_creators_roles_d['assets_id'] = $asset_id;

				if (isset($pbcore_creator['children']['ams:creator'][0]['text']) && ! is_empty($pbcore_creator['children']['ams:creator'][0]['text']))
				{

					$creater['creator_name'] = $pbcore_creator['children']['ams:creator'][0]['text'];
					if (isset($pbcore_creator['children']['ams:creator'][0]['attributes']['affiliation']) && ! is_empty($pbcore_creator['children']['ams:creator'][0]['attributes']['affiliation']))
					{
						$creater['creator_affiliation'] = $pbcore_creator['children']['ams:creator'][0]['attributes']['affiliation'];
					}
					if (isset($pbcore_creator['children']['ams:creator'][0]['attributes']['ref']) && ! is_empty($pbcore_creator['children']['ams:creator'][0]['attributes']['ref']))
					{
						$creater['creator_ref'] = $pbcore_creator['children']['ams:creator'][0]['attributes']['ref'];
					}
					$creator_d = $this->assets_model->get_creator_by_creator_name($creater['creator_name']);
					if (isset($creator_d) && isset($creator_d->id))
					{
						$assets_creators_roles_d['creators_id'] = $creator_d->id;
					}
					else
					{
						$assets_creators_roles_d['creators_id'] = $this->assets_model->insert_creators($creater);
					}
				}
				if (isset($pbcore_creator['children']['ams:creatorrole'][0]['text']) && ! is_empty($pbcore_creator['children']['ams:creatorrole'][0]['text']))
				{
					$role['creator_role'] = $pbcore_creator['children']['ams:creatorrole'][0]['text'];
					if (isset($pbcore_creator['children']['ams:creatorrole'][0]['attributes']['source']) && ! is_empty($pbcore_creator['children']['ams:creatorrole'][0]['attributes']['source']))
					{

						$role['creator_role_source'] = $pbcore_creator['children']['ams:creatorrole'][0]['attributes']['source'];
					}
					if (isset($pbcore_creator['children']['ams:creatorrole'][0]['attributes']['ref']) && ! is_empty($pbcore_creator['children']['ams:creatorrole'][0]['attributes']['ref']))
					{

						$role['creator_role_ref'] = $pbcore_creator['children']['ams:creatorrole'][0]['attributes']['ref'];
					}
					$creator_role = $this->assets_model->get_creator_role_by_role($pbcore_creator['children']['ams:creatorrole'][0]['text']);
					if (isset($creator_role) && isset($creator_role->id))
					{
						$assets_creators_roles_d['creator_roles_id'] = $creator_role->id;
					}
					else
					{
						$assets_creators_roles_d['creator_roles_id'] = $this->assets_model->insert_creator_roles($role);
					}
				}
				if ((isset($assets_creators_roles_d['creators_id']) && ! is_empty($assets_creators_roles_d['creators_id'])) || (isset($assets_creators_roles_d['creator_roles_id']) && ! is_empty($assets_creators_roles_d['creator_roles_id'])))
				{
					$assets_creators_roles_id = $this->assets_model->insert_assets_creators_roles($assets_creators_roles_d);
				}
			}
		}
		// Asset Creator End  //
		// Asset Contributor Start  //
		if (isset($xmlArray['ams:pbcorecontributor']))
		{
			foreach ($xmlArray['ams:pbcorecontributor'] as $pbcore_contributor)
			{
				$assets_contributors_d = array();
				$assets_contributors_d['assets_id'] = $asset_id;
				if (isset($pbcore_contributor['children']['ams:contributor'][0]['text']) && ! is_empty($pbcore_contributor['children']['ams:contributor'][0]['text']))
				{
					$contributor_info['contributor_name'] = $pbcore_contributor['children']['ams:contributor'][0]['text'];

					if (isset($pbcore_contributor['children']['ams:contributor'][0]['attributes']['affiliation']) && ! is_empty($pbcore_contributor['children']['ams:contributor'][0]['attributes']['affiliation']))
					{
						$contributor_info['contributor_affiliation'] = $pbcore_contributor['children']['ams:contributor'][0]['attributes']['affiliation'];
					}
					if (isset($pbcore_contributor['children']['ams:contributor'][0]['attributes']['ref']) && ! is_empty($pbcore_contributor['children']['ams:contributor'][0]['attributes']['ref']))
					{
						$contributor_info['contributor_ref'] = $pbcore_contributor['children']['ams:contributor'][0]['attributes']['ref'];
					}
					$contributor_d = $this->assets_model->get_contributor_by_contributor_name($contributor_info['contributor_name']);
					if (isset($contributor_d) && isset($contributor_d->id))
					{
						$assets_contributors_d['contributors_id'] = $contributor_d->id;
					}
					else
					{
						$last_insert_id = $this->assets_model->insert_contributors($contributor_info);
						if (isset($last_insert_id) && $last_insert_id > 0)
						{
							$assets_contributors_d['contributors_id'] = $last_insert_id;
						}
					}
				}
				if (isset($pbcore_contributor['children']['ams:contributorrole'][0]['text']) && ! is_empty($pbcore_contributor['children']['ams:contributorrole'][0]['text']))
				{
					$contributorrole_info['contributor_role'] = $pbcore_contributor['children']['ams:contributorrole'][0]['text'];

					if (isset($pbcore_contributor['children']['ams:contributorrole'][0]['attributes']['source']) && ! is_empty($pbcore_contributor['children']['ams:contributorrole'][0]['attributes']['source']))
					{

						$contributorrole_info['contributor_role_source'] = $pbcore_contributor['children']['ams:contributorrole'][0]['attributes']['source'];
					}
					if (isset($pbcore_contributor['children']['ams:contributorrole'][0]['attributes']['ref']) && ! is_empty($pbcore_contributor['children']['ams:contributorrole'][0]['attributes']['ref']))
					{
						$contributorrole_info['contributor_role_ref'] = $pbcore_contributor['children']['ams:contributorrole'][0]['attributes']['ref'];
					}
					$contributor_role = $this->assets_model->get_contributor_role_by_role($contributorrole_info['contributor_role']);
					if (isset($contributor_role) && isset($contributor_role->id))
					{
						$assets_contributors_d['contributor_roles_id'] = $contributor_role->id;
					}
					else
					{
						$last_insert_id = $this->assets_model->insert_contributor_roles($contributorrole_info);
						if (isset($last_insert_id) && $last_insert_id > 0)
						{
							$assets_contributors_d['contributor_roles_id'] = $last_insert_id;
						}
					}
				}
				if ((isset($assets_contributors_d['contributors_id']) && ! is_empty($assets_contributors_d['contributors_id'])) ||
				(isset($assets_contributors_d['contributor_roles_id']) && ! is_empty($assets_contributors_d['contributor_roles_id'])))
				{
					$assets_contributors_roles_id = $this->assets_model->insert_assets_contributors_roles($assets_contributors_d);
				}
			}
		}
		// Asset Contributor End  //
		// Asset Publisher Start  //
		if (isset($xmlArray['ams:pbcorepublisher']))
		{
			foreach ($xmlArray['ams:pbcorepublisher'] as $pbcorepublisher)
			{
				$assets_publisher_d = array();
				$assets_publisher_d['assets_id'] = $asset_id;
				if (isset($pbcorepublisher['children']['ams:publisher'][0]['text']) && ! is_empty($pbcorepublisher['children']['ams:publisher'][0]['text']))
				{
					$publisher_info['publisher'] = $pbcorepublisher['children']['ams:publisher'][0]['text'];
					if (isset($pbcorepublisher['children']['ams:publisher'][0]['attributes']['affiliation']) && ! is_empty($pbcorepublisher['children']['ams:publisher'][0]['attributes']['affiliation']))
					{

						$publisher_info['publisher_affiliation'] = $pbcorepublisher['children']['ams:publisher'][0]['attributes']['affiliation'];
					}
					if (isset($pbcorepublisher['children']['ams:publisher'][0]['attributes']['ref']) && ! is_empty($pbcorepublisher['children']['ams:publisher'][0]['attributes']['ref']))
					{

						$publisher_info['publisher_ref'] = $pbcorepublisher['children']['ams:publisher'][0]['attributes']['ref'];
					}
					$publisher_d = $this->assets_model->get_publishers_by_publisher($publisher_info['publisher']);
					if (isset($publisher_d) && isset($publisher_d->id))
					{
						$assets_publisher_d['publishers_id'] = $publisher_d->id;
					}
					else
					{
						$assets_publisher_d['publishers_id'] = $this->assets_model->insert_publishers($publisher_info);
					}
				}
				if (isset($pbcorepublisher['children']['ams:publisherrole'][0]['text']) && ! is_empty($pbcorepublisher['children']['ams:publisherrole'][0]['text']))
				{

					$publisher_role_info['publisher_role'] = $pbcorepublisher['children']['ams:publisherrole'][0]['text'];
					if (isset($pbcorepublisher['children']['ams:publisherrole'][0]['attributes']['source']) && ! is_empty($pbcorepublisher['children']['ams:publisherrole'][0]['attributes']['source']))
					{

						$publisher_role_info['publisher_role_source'] = $pbcorepublisher['children']['ams:publisherrole'][0]['attributes']['source'];
					}
					if (isset($pbcorepublisher['children']['ams:publisherrole'][0]['attributes']['ref']) && ! is_empty($pbcorepublisher['children']['ams:publisherrole'][0]['attributes']['ref']))
					{

						$publisher_role_info['publisher_role_ref'] = $pbcorepublisher['children']['ams:publisherrole'][0]['attributes']['ref'];
					}
					$publisher_role = $this->assets_model->get_publisher_role_by_role($publisher_role_info['publisher_role']);
					if (isset($publisher_role) && isset($publisher_role->id))
					{
						$assets_publisher_d['publisher_roles_id'] = $publisher_role->id;
					}
					else
					{
						$assets_publisher_d['publisher_roles_id'] = $this->assets_model->insert_publisher_roles($publisher_role_info);
					}
				}
				if ((isset($assets_publisher_d['publishers_id']) && ! is_empty($assets_publisher_d['publishers_id'])) || (isset($assets_publisher_d['publisher_roles_id']) && ! is_empty($assets_publisher_d['publisher_roles_id'])))
				{
					$assets_publishers_roles_id = $this->assets_model->insert_assets_publishers_role($assets_publisher_d);
				}
			}
		}
		// Asset Publisher End  //
		// Asset Right Summary Start  //
		if (isset($xmlArray['ams:pbcorerightssummary']))
		{
			foreach ($xmlArray['ams:pbcorerightssummary'] as $pbcore_rights)
			{
				$rights_summary_d = array();
				$rights_summary_d['assets_id'] = $asset_id;
				if (isset($pbcore_rights['children']['ams:rightssummary'][0]['text']) && ! is_empty($pbcore_rights['children']['ams:rightssummary'][0]['text']))
				{
					$rights_summary_d['rights'] = $pbcore_rights['children']['ams:rightssummary'][0]['text'];
					if (isset($pbcore_rights['children']['ams:rightslink'][0]['text']) && ! is_empty($pbcore_rights['children']['ams:rightslink'][0]['text']))
					{
						$rights_summary_d['rights_link'] = $pbcore_rights['children']['ams:rightslink'][0]['text'];
					}
					$this->assets_model->insert_rights_summaries($rights_summary_d);
				}
			}
		}
		// Asset Right Summary End  //
		// Asset Extension Start //

		if (isset($xmlArray['ams:pbcoreextension']) && ! is_empty($xmlArray['ams:pbcoreextension']))
		{
			foreach ($xmlArray['ams:pbcoreextension'] as $pbcore_extension)
			{
				$map_extension = $pbcore_extension['children']['ams:extensionwrap'][0]['children'];
				if (isset($map_extension['ams:extensionauthorityused'][0]['text']) && ! is_empty($map_extension['ams:extensionauthorityused'][0]['text']))
				{
					$extension_d = array();
					$extension_d['assets_id'] = $asset_id;
					if (strtolower($map_extension['ams:extensionauthorityused'][0]['text']) == strtolower('AACIP Record Tags'))
					{

						if (isset($map_extension['ams:extensionvalue'][0]['text']) && ! is_empty($map_extension['ams:extensionvalue'][0]['text']))
						{
							if ( ! preg_match('/historical value|risk of loss|local cultural value|potential to repurpose/', strtolower($map_extension['ams:extensionvalue'][0]['text']), $match_text))
							{
								$extension_d['extension_element'] = $map_extension['ams:extensionauthorityused'][0]['text'];
								$extension_d['extension_value'] = $map_extension['ams:extensionvalue'][0]['text'];
								$this->assets_model->insert_extensions($extension_d);
							}
						}
					}
					else if (strtolower($map_extension['ams:extensionauthorityused'][0]['text']) != strtolower('AACIP Record Nomination Status'))
					{

						$extension_d['extension_element'] = $map_extension['ams:extensionauthorityused'][0]['text'];
						if (isset($map_extension['ams:extensionvalue'][0]['text']) && ! is_empty($map_extension['ams:extensionvalue'][0]['text']))
						{
							$extension_d['extension_value'] = $map_extension['ams:extensionvalue'][0]['text'];
						}
						$this->assets_model->insert_extensions($extension_d);
					}
				}
			}
		}
		// Asset Extension End //
		$this->load->library('sphnixrt');
		$this->load->model('searchd_model');
		$this->load->helper('sphnixdata');
		$asset_list = $this->searchd_model->get_asset_index(array($asset_id));
		$new_asset_info = make_assets_sphnix_array($asset_list[0]);
		$this->sphnixrt->insert('assets_list', $new_asset_info, $asset_id);
	}

	/**
	 * Parse and save instantiation and essencetrack info.
	 * 
	 * @param string $asset_id
	 * @param array  $asset_children
	 */
	function import_instantiation_info($asset_id, $asset_children)
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		if (isset($asset_children['ams:pbcoreinstantiation']))
		{
			foreach ($asset_children['ams:pbcoreinstantiation'] as $pbcoreinstantiation)
			{
				if (isset($pbcoreinstantiation['children']) && ! is_empty($pbcoreinstantiation['children']))
				{
					$pbcoreinstantiation_child = $pbcoreinstantiation['children'];
					$instantiations_d = array();
					$instantiations_d['assets_id'] = $asset_id;
					// Instantiation Location Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationlocation'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationlocation'][0]['text']))
					{
						$instantiations_d['location'] = $pbcoreinstantiation_child['ams:instantiationlocation'][0]['text'];
					}
					// Instantiation Location End //
					// Instantiation Standard Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationstandard'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationstandard'][0]['text']))
					{
						$instantiations_d['standard'] = $pbcoreinstantiation_child['ams:instantiationstandard'][0]['text'];
					}
					// Instantiation Standard End //
					// Instantiation Media Type Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationmediatype'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationmediatype'][0]['text']))
					{
						$inst_media_type = $this->instant->get_instantiation_media_types_by_media_type($pbcoreinstantiation_child['ams:instantiationmediatype'][0]['text']);
						if ( ! is_empty($inst_media_type))
						{
							$instantiations_d['instantiation_media_type_id'] = $inst_media_type->id;
						}
						else
						{
							$instantiations_d['instantiation_media_type_id'] = $this->instant->insert_instantiation_media_types(array("media_type" => $pbcoreinstantiation_child['ams:instantiationmediatype'][0]['text']));
						}
					}
					// Instantiation Media Type End //
					// Instantiation File Size Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationfilesize'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationfilesize'][0]['text']))
					{
						$instantiations_d['file_size'] = $pbcoreinstantiation_child['ams:instantiationfilesize'][0]['text'];
						if (isset($pbcoreinstantiation_child['ams:instantiationfilesize'][0]['attributes']['unitsofmeasure']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationfilesize'][0]['attributes']['unitsofmeasure']))
						{
							$instantiations_d['file_size_unit_of_measure'] = $pbcoreinstantiation_child['ams:instantiationfilesize'][0]['attributes']['unitsofmeasure'];
						}
					}
					// Instantiation File Size End //
					// Instantiation Time Start Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationtimestart'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationtimestart'][0]['text']))
					{
						$instantiations_d['time_start'] = trim($pbcoreinstantiation_child['ams:instantiationtimestart'][0]['text']);
					}
					// Instantiation Time Start End //
					// Instantiation Projected Duration Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationduration'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationduration'][0]['text']))
					{
						$instantiations_d['projected_duration'] = trim($pbcoreinstantiation_child['ams:instantiationduration'][0]['text']);
					}
					// Instantiation Projected Duration End //
					// Instantiation Data Rate Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationdatarate'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationdatarate'][0]['text']))
					{
						$instantiations_d['data_rate'] = trim($pbcoreinstantiation_child['ams:instantiationdatarate'][0]['text']);
						if (isset($pbcoreinstantiation_child['ams:instantiationdatarate'][0]['attributes']['unitsofmeasure']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationdatarate'][0]['attributes']['unitsofmeasure']))
						{
							$data_rate_unit_d = $this->instant->get_data_rate_units_by_unit($pbcoreinstantiation_child['ams:instantiationdatarate'][0]['attributes']['unitsofmeasure']);
							if (isset($data_rate_unit_d) && isset($data_rate_unit_d->id))
							{
								$instantiations_d['data_rate_units_id'] = $data_rate_unit_d->id;
							}
							else
							{
								$instantiations_d['data_rate_units_id'] = $this->instant->insert_data_rate_units(array("unit_of_measure" => $pbcoreinstantiation_child['ams:instantiationdatarate'][0]['attributes']['unitsofmeasure']));
							}
						}
					}
					// Instantiation Data Rate End //
					// Instantiation Color Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationcolors'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationcolors'][0]['text']))
					{

						$inst_color_d = $this->instant->get_instantiation_colors_by_color($pbcoreinstantiation_child['ams:instantiationcolors'][0]['text']);
						if (isset($inst_color_d) && ! is_empty($inst_color_d))
						{
							$instantiations_d['instantiation_colors_id'] = $inst_color_d->id;
						}
						else
						{
							$instantiations_d['instantiation_colors_id'] = $this->instant->insert_instantiation_colors(array('color' => $pbcoreinstantiation_child['ams:instantiationcolors'][0]['text']));
						}
					}
					// Instantiation Color End //
					// Instantiation Tracks Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationtracks'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationtracks'][0]['text']))
					{
						$instantiations_d['tracks'] = $pbcoreinstantiation_child['ams:instantiationtracks'][0]['text'];
					}
					// Instantiation Tracks End //
					//Instantiation Channel Configuration Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationchannelconfiguration'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationchannelconfiguration'][0]['text']))
					{
						$instantiations_d['channel_configuration'] = $pbcoreinstantiation_child['ams:instantiationchannelconfiguration'][0]['text'];
					}
					//Instantiation Channel Configuration End //
					//Instantiation Language Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationlanguage'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationlanguage'][0]['text']))
					{
						$instantiations_d['language'] = $pbcoreinstantiation_child['ams:instantiationlanguage'][0]['text'];
					}
					//Instantiation Language End //
					//Instantiation Alternative Mode Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationalternativemodes'][0]['text']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationalternativemodes'][0]['text']))
					{
						$instantiations_d['alternative_modes'] = $pbcoreinstantiation_child['ams:instantiationalternativemodes'][0]['text'];
					}
					//Instantiation Alternative Mode End //

					$insert_instantiation = TRUE;
					$instantiations_d['created'] = date("Y-m-d H:i:s");
					$instantiations_id = $this->instant->insert_instantiations($instantiations_d);
					// Instantiations Identifier Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationidentifier']))
					{

						foreach ($pbcoreinstantiation_child['ams:instantiationidentifier'] as $pbcore_identifier)
						{
							$instantiation_identifier_d = array();
							$instantiation_identifier_d['instantiations_id'] = $instantiations_id;
							if (isset($pbcore_identifier['text']) && ! is_empty($pbcore_identifier['text']))
							{
								$instantiation_identifier_d['instantiation_identifier'] = $pbcore_identifier['text'];
								if (isset($pbcore_identifier['attributes']['source']) && ! is_empty($pbcore_identifier['attributes']['source']))
								{
									$instantiation_identifier_d['instantiation_source'] = $pbcore_identifier['attributes']['source'];
								}
								$instantiation_identifier_id = $this->instant->insert_instantiation_identifier($instantiation_identifier_d);
							}
						}
					}
					// Instantiations Identifier End //
					// Instantiations Date Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationdate']))
					{

						foreach ($pbcoreinstantiation_child['ams:instantiationdate'] as $pbcore_date)
						{
							$instantiation_dates_d = array();
							$instantiation_dates_d['instantiations_id'] = $instantiations_id;
							if (isset($pbcore_date['text']) && ! is_empty($pbcore_date['text']))
							{
								$instantiation_dates_d['instantiation_date'] = str_replace(array('?', 'Unknown', 'unknown', '`', '[' . ']', 'N/A', 'N/A?', 'Jim Cooper', 'various', '.00', '.0', 'John Kelling', 'Roll in', 'interview'), '', $pbcore_date['text']);
								if (isset($instantiation_dates_d['instantiation_date']) && ! is_empty($instantiation_dates_d['instantiation_date']))
								{
									$date_check = $this->is_valid_date($instantiation_dates_d['instantiation_date']);
									if ($date_check === FALSE)
									{
										$instantiation_annotation_d = array();
										$instantiation_annotation_d['instantiations_id'] = $instantiations_id;
										$instantiation_annotation_d['annotation'] = $instantiation_dates_d['instantiation_date'];
										if (isset($pbcore_date['attributes']['datetype']) && ! is_empty($pbcore_date['attributes']['datetype']))
										{
											$instantiation_annotation_d['annotation_type'] = $pbcore_date['attributes']['datetype'];
										}

										$this->instant->insert_instantiation_annotations($instantiation_annotation_d);
									}
									else
									{
										if (isset($pbcore_date['attributes']['datetype']) && ! is_empty($pbcore_date['attributes']['datetype']))
										{
											$date_type = $this->instant->get_date_types_by_type($pbcore_date['attributes']['datetype']);
											if (isset($date_type) && isset($date_type->id))
											{
												$instantiation_dates_d['date_types_id'] = $date_type->id;
											}
											else
											{
												$instantiation_dates_d['date_types_id'] = $this->instant->insert_date_types(array('date_type' => $pbcore_date['attributes']['datetype']));
											}
										}
										$this->instant->insert_instantiation_dates($instantiation_dates_d);
									}
								}
							}
						}
					}
					// Instantiations Date End //
					// Instantiations Dimension Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationdimensions']))
					{

						foreach ($pbcoreinstantiation_child['ams:instantiationdimensions'] as $pbcore_dimension)
						{
							$instantiation_dimension_d = array();
							$instantiation_dimension_d['instantiations_id'] = $instantiations_id;
							if (isset($pbcore_dimension['text']) && ! is_empty($pbcore_dimension['text']))
							{
								$instantiation_dimension_d['instantiation_dimension'] = $pbcore_dimension['text'];
								$instantiation_dimension_d['unit_of_measure'] = '';
								if (isset($pbcore_dimension['attributes']['unitofmeasure']) && ! is_empty($pbcore_dimension['attributes']['unitofmeasure']))
								{
									$instantiation_dimension_d['unit_of_measure'] = $pbcore_dimension['attributes']['unitofmeasure'];
								}
								$this->instant->insert_instantiation_dimensions($instantiation_dimension_d);
							}
						}
					}
					// Instantiations Dimension End //
					// Instantiations Format Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationphysical']))
					{

						foreach ($pbcoreinstantiation_child['ams:instantiationphysical'] as $pbcore_physical)
						{
							if (isset($pbcore_physical['text']) && ! is_empty($pbcore_physical['text']))
							{
								$instantiation_format_physical_d = array();
								$instantiation_format_physical_d['instantiations_id'] = $instantiations_id;
								$instantiation_format_physical_d['format_name'] = $pbcore_physical['text'];
								$instantiation_format_physical_d['format_type'] = 'physical';
								$instantiation_format_physical_id = $this->instant->insert_instantiation_formats($instantiation_format_physical_d);
							}
						}
					}
					else if (isset($pbcoreinstantiation_child['ams:instantiationdigital']))
					{

						foreach ($pbcoreinstantiation_child['ams:instantiationdigital'] as $pbcore_digital)
						{
							if (isset($pbcore_digital['text']) && ! is_empty($pbcore_digital['text']))
							{
								$instantiation_format_digital_d = array();
								$instantiation_format_digital_d['instantiations_id'] = $instantiations_id;
								$instantiation_format_digital_d['format_name'] = $pbcore_digital['text'];
								$instantiation_format_digital_d['format_type'] = 'digital';
								$instantiation_format_digital_id = $this->instant->insert_instantiation_formats($instantiation_format_digital_d);
							}
						}
					}
					// Instantiations  Format End //
					// Instantiations  Generation Start //

					if (isset($pbcoreinstantiation_child['ams:instantiationgenerations']) && ! is_empty($pbcoreinstantiation_child['ams:instantiationgenerations']))
					{
						foreach ($pbcoreinstantiation_child['ams:instantiationgenerations'] as $instantiation_generations)
						{
							if (isset($instantiation_generations['text']) && ! is_empty($instantiation_generations['text']))
							{
								$instantiation_format_generations_d = array();
								$instantiation_format_generations_d['instantiations_id'] = $instantiations_id;
								$generations_d = $this->instant->get_generations_by_generation($instantiation_generations['text']);
								if (isset($generations_d) && isset($generations_d->id))
								{
									$instantiation_format_generations_d['generations_id'] = $generations_d->id;
								}
								else
								{
									$instantiation_format_generations_d['generations_id'] = $this->instant->insert_generations(array("generation" => $instantiation_generations['text']));
								}
								$this->instant->insert_instantiation_generations($instantiation_format_generations_d);
							}
						}
					}
					// Instantiations  Generation End //
					// Instantiations  Annotation Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationannotation']))
					{
						foreach ($pbcoreinstantiation_child['ams:instantiationannotation'] as $pbcore_annotation)
						{
							if (isset($pbcore_annotation['text']) && ! is_empty($pbcore_annotation['text']))
							{
								$instantiation_annotation_d = array();
								$instantiation_annotation_d['instantiations_id'] = $instantiations_id;
								$instantiation_annotation_d['annotation'] = $pbcore_annotation['text'];

								if (isset($pbcore_annotation['attributes']['annotationtype']) && ! is_empty($pbcore_annotation['attributes']['annotationtype']))
								{
									$instantiation_annotation_d['annotation_type'] = $pbcore_annotation['attributes']['annotationtype'];
								}
								$this->instant->insert_instantiation_annotations($instantiation_annotation_d);
							}
						}
					}
					// Instantiations  Annotation End //
					// Instantiations Relation Start  //
					if (isset($pbcoreinstantiation_child['ams:pbcorerelation']))
					{
						foreach ($pbcoreinstantiation_child['ams:pbcorerelation'] as $pbcorerelation)
						{

							if (isset($pbcorerelation['children']['ams:pbcorerelationidentifier'][0]['text']) && ! is_empty($pbcore_creator['children']['ams:pbcorerelationidentifier'][0]['text']))
							{
								$instantiation_relation_d = array();
								$instantiation_relation_d['instantiations_id'] = $instantiations_id;
								$instantiation_relation_d = $pbcorerelation['children']['ams:pbcorerelationidentifier'][0]['text'];
								if (isset($pbcorerelation['children']['pbcorerelationtype'][0]['text']) && ! is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['text']))
								{
									$relation_type_info['relation_type'] = $pbcorerelation['children']['pbcorerelationtype'][0]['text'];
									if (isset($pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['source']) && ! is_empty($pbcore_creator['children']['ams:pbcorerelationtype'][0]['attributes']['source']))
									{
										$relation_type_info['relation_type_source'] = $pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['source'];
									}
									if (isset($pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['ref']) && ! is_empty($pbcore_creator['children']['ams:pbcorerelationtype'][0]['attributes']['ref']))
									{
										$relation_type_info['relation_type_ref'] = $pbcorerelation['children']['ams:pbcorerelationtype'][0]['attributes']['ref'];
									}
									$db_relations = $this->assets_model->get_relation_types($relation_type_info['relation_type']);
									if (isset($db_relations) && isset($db_relations->id))
									{
										$instantiation_relation_d['relation_types_id'] = $db_relations->id;
									}
									else
									{
										$instantiation_relation_d['relation_types_id'] = $this->assets_model->insert_relation_types($relation_type_info);
									}
									$this->instant->insert_instantiation_relation($instantiation_relation_d);
								}
							}
						}
					}
					// Instantiations Relation End  //
					// Instantiations Essence Tracks Start //
					if (isset($pbcoreinstantiation_child['ams:instantiationessencetrack']))
					{
						foreach ($pbcoreinstantiation_child['ams:instantiationessencetrack'] as $pbcore_essence_track)
						{
							if (isset($pbcore_essence_track['children']) && ! is_empty($pbcore_essence_track['children']))
							{
								$pbcore_essence_child = $pbcore_essence_track['children'];
								$essence_tracks_d = array();
								$essence_tracks_d['instantiations_id'] = $instantiations_id;
								// Essence Track Standard Start //
								if (isset($pbcore_essence_child['ams:essencetrackstandard'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackstandard'][0]['text']))
								{
									$essence_tracks_d['standard'] = $pbcore_essence_child['ams:essencetrackstandard'][0]['text'];
								}
								// Essence Track Standard End //
								// Essence Track Data Rate Start //

								if (isset($pbcore_essence_child['ams:essencetrackdatarate'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackdatarate'][0]['text']))
								{
									$essence_tracks_d['data_rate'] = $pbcore_essence_child['essencetrackdatarate'][0]['text'];
									if (isset($pbcore_essence_child['ams:essencetrackdatarate'][0]['attributes']['unitsofmeasure']) && ! is_empty($pbcore_essence_child['ams:essencetrackdatarate'][0]['attributes']['unitsofmeasure']))
									{

										$data_rate_unit_d = $this->instant->get_data_rate_units_by_unit($pbcore_essence_child['ams:essencetrackdatarate'][0]['attributes']['unitsofmeasure']);
										if (isset($data_rate_unit_d) && isset($data_rate_unit_d->id))
										{
											$essence_tracks_d['data_rate_units_id'] = $data_rate_unit_d->id;
										}
										else
										{
											$essence_tracks_d['data_rate_units_id'] = $this->instant->insert_data_rate_units(array("unit_of_measure" => $pbcore_essence_child['ams:essencetrackdatarate'][0]['attributes']['unitsofmeasure']));
										}
									}
								}

								// Essence Track Data Rate End //
								// Essence Track Frame Rate Start //
								if (isset($pbcore_essence_child['ams:essencetrackframerate'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackframerate'][0]['text']))
								{
									$frame_rate = explode(" ", $pbcore_essence_child['ams:essencetrackframerate'][0]['text']);
									$essence_tracks_d['frame_rate'] = trim($frame_rate[0]);
								}
								// Essence Track Frame Rate End //
								// Essence Track Play Back Speed Start //
								if (isset($pbcore_essence_child['ams:essencetrackplaybackspeed'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackplaybackspeed'][0]['text']))
								{
									$essence_tracks_d['playback_speed'] = $pbcore_essence_child['ams:essencetrackplaybackspeed'][0]['text'];
								}
								// Essence Track Play Back Speed End //
								// Essence Track Sampling Rate Start //
								if (isset($pbcore_essence_child['ams:essencetracksamplingrate'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetracksamplingrate'][0]['text']))
								{
									$essence_tracks_d['sampling_rate'] = $pbcore_essence_child['ams:essencetracksamplingrate'][0]['text'];
								}
								// Essence Track Sampling Rate End //
								// Essence Track bit depth Start //
								if (isset($pbcore_essence_child['ams:essencetrackbitdepth'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackbitdepth'][0]['text']))
								{
									$essence_tracks_d['bit_depth'] = $pbcore_essence_child['ams:essencetrackbitdepth'][0]['text'];
								}
								// Essence Track bit depth End //
								// Essence Track Aspect Ratio Start //
								if (isset($pbcore_essence_child['ams:essencetrackaspectratio'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackaspectratio'][0]['text']))
								{
									$essence_tracks_d['aspect_ratio'] = $pbcore_essence_child['ams:essencetrackaspectratio'][0]['text'];
								}
								// Essence Track Aspect Ratio End //
								// Essence Track Time Start //
								if (isset($pbcore_essence_child['ams:essencetracktimestart'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetracktimestart'][0]['text']))
								{
									$essence_tracks_d['time_start'] = $pbcore_essence_child['ams:essencetracktimestart'][0]['text'];
								}
								// Essence Track Time End //
								// Essence Track Duration Start //

								if (isset($pbcore_essence_child['ams:essencetrackduration'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackduration'][0]['text']))
								{
									$essence_tracks_d['duration'] = $pbcore_essence_child['ams:essencetrackduration'][0]['text'];
								}
								// Essence Track Duration End //
								// Essence Track Language Start //

								if (isset($pbcore_essence_child['ams:essencetracklanguage'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetracklanguage'][0]['text']))
								{
									$essence_tracks_d['language'] = $pbcore_essence_child['ams:essencetracklanguage'][0]['text'];
								}
								// Essence Track Language Start //
								// Essence Track Type Start //
								if (isset($pbcore_essence_child['ams:essencetracktype'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetracktype'][0]['text']))
								{

									$essence_track_type_d = $this->pbcore_model->get_one_by($this->pbcore_model->table_essence_track_types, array('essence_track_type' => $pbcore_essence_child['ams:essencetracktype'][0]['text']), TRUE);
									if (isset($essence_track_type_d) && isset($essence_track_type_d->id))
									{
										$essence_tracks_d['essence_track_types_id'] = $essence_track_type_d->id;
									}
									else
									{
										$essence_tracks_d['essence_track_types_id'] = $this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_types, array('essence_track_type' => $pbcore_essence_child['ams:essencetracktype'][0]['text']));
									}
								}
								// Essence Track Type End //
								// Essence Track Frame Size Start //
								if (isset($pbcore_essence_child['ams:essencetrackframesize'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackframesize'][0]['text']))
								{
									$frame_sizes = explode("x", strtolower($pbcore_essence_child['ams:essencetrackframesize'][0]['text']));
									if (isset($frame_sizes[0]) && isset($frame_sizes[1]))
									{
										$track_frame_size_d = $this->pbcore_model->get_one_by($this->pbcore_model->table_essence_track_frame_sizes, array('width' => trim($frame_sizes['width']), 'height' => trim($frame_sizes['height'])));
										if ($track_frame_size_d)
										{
											$essence_tracks_d['essence_track_frame_sizes_id'] = $track_frame_size_d->id;
										}
										else
										{
											$essence_tracks_d['essence_track_frame_sizes_id'] = $this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_frame_sizes, array("width" => $frame_sizes[0], "height" => $frame_sizes[1]));
										}
									}
								}
								// Essence Track Frame Size End //
								if (isset($essence_tracks_d['essence_track_types_id']) && ! empty($essence_tracks_d['essence_track_types_id']) && $essence_tracks_d['essence_track_types_id'] != NULL)
								{
									$essence_tracks_id = $this->pbcore_model->insert_record($this->pbcore_model->table_essence_tracks, $essence_tracks_d);
									$insert_essence_track = TRUE;
									// Essence Track Identifier Start //
									if (isset($pbcore_essence_child['ams:essencetrackidentifier'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackidentifier'][0]['text']))
									{

										$essence_track_identifiers_d = array();
										$essence_track_identifiers_d['essence_tracks_id'] = $essence_tracks_id;
										$essence_track_identifiers_d['essence_track_identifiers'] = $pbcore_essence_child['ams:essencetrackidentifier'][0]['text'];
										if (isset($pbcore_essence_child['ams:essencetrackidentifier'][0]['attributes']['source']) && ! is_empty($pbcore_essence_child['ams:essencetrackidentifier'][0]['attributes']['source']))
										{
											$essence_track_identifiers_d['ams:essence_track_identifier_source'] = $pbcore_essence_child['ams:essencetrackidentifier'][0]['attributes']['source'];
										}
										$this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_identifiers, $essence_track_identifiers_d);
									}
									// Essence Track Identifier End //
									// Essence Track Encoding Start //
									if (isset($pbcore_essence_child['ams:essencetrackencoding'][0]['text']) && ! is_empty($pbcore_essence_child['ams:essencetrackencoding'][0]['text']))
									{

										$essence_track_standard_d = array();
										$essence_track_standard_d['essence_tracks_id'] = $essence_tracks_id;
										$essence_track_standard_d['encoding'] = $pbcore_essence_child['ams:essencetrackencoding'][0]['text'];
										if (isset($pbcore_essence_child['ams:essencetrackencoding'][0]['attributes']['ref']) && ! is_empty($pbcore_essence_child['ams:essencetrackencoding'][0]['attributes']['ref']))
										{
											$essence_track_standard_d['encoding_source'] = $pbcore_essence_child['ams:essencetrackencoding'][0]['attributes']['ref'];
										}
										$this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_encodings, $essence_track_standard_d);
									}
									// Essence Track Encoding End //
									// Essence Track Annotation Start //
									if (isset($pbcore_essence_child['ams:essencetrackannotation']) && ! is_empty($pbcore_essence_child['ams:essencetrackannotation']))
									{
										foreach ($pbcore_essence_child['ams:essencetrackannotation'] as $trackannotation)
										{
											if (isset($trackannotation['text']) && ! is_empty($trackannotation['text']))
											{
												$essencetrackannotation = array();
												$essencetrackannotation['essence_tracks_id'] = $essence_tracks_id;
												$essencetrackannotation['annotation'] = $trackannotation['text'];

												if (isset($trackannotation['attributes']['type']) && ! is_empty($trackannotation['attributes']['type']))
												{
													$essencetrackannotation['annotation_type'] = $trackannotation['attributes']['type'];
												}
												$this->pbcore_model->insert_record($this->pbcore_model->table_essence_track_annotations, $essencetrackannotation);
											}
										}
									}
									// Essence Track Annotation End //
								}
							}
						}
					}
					// Instantiations Essence Tracks End //
					// Asset Extension Start //

					if (isset($asset_children['ams:pbcoreextension']) && ! is_empty($asset_children['ams:pbcoreextension']))
					{
						foreach ($asset_children['ams:pbcoreextension'] as $pbcore_extension)
						{
							$map_extension = $pbcore_extension['children']['ams:extensionwrap'][0]['children'];
							if ((isset($map_extension['ams:extensionauthorityused'][0]['text']) && ! is_empty($map_extension['ams:extensionauthorityused'][0]['text'])))
							{
								$nomination_d = array();
								$nomination_d['instantiations_id'] = $instantiations_id;
								if ((strtolower($map_extension['ams:extensionauthorityused'][0]['text']) == strtolower('AACIP Record Nomination Status')))
								{
									if (isset($map_extension['ams:extensionvalue'][0]['text']) && ! is_empty($map_extension['ams:extensionvalue'][0]['text']))
									{
										$nomunation_status = $this->assets_model->get_nomination_status_by_status($map_extension['ams:extensionvalue'][0]['text']);
										if (isset($nomunation_status) && ! is_empty($nomunation_status))
										{
											$nomination_d['nomination_status_id'] = $nomunation_status->id;
										}
										else
										{
											$nomination_d['nomination_status_id'] = $this->assets_model->insert_nomination_status(array("status" => $map_extension['ams:extensionvalue'][0]['text']));
										}
									}
								}
								if ((strtolower($map_extension['ams:extensionauthorityused'][0]['text']) == strtolower('AACIP Record Tags')))
								{

									if (isset($map_extension['ams:extensionvalue'][0]['text']) && ! is_empty($map_extension['ams:extensionvalue'][0]['text']))
									{
										if (preg_match('/historical value/', strtolower($map_extension['ams:extensionvalue'][0]['text']), $match_text))
										{

											$nomination_d['nomination_reason'] = $map_extension['ams:extensionvalue'][0]['text'];
										}
										else if (preg_match('/risk of loss/', strtolower($map_extension['ams:extensionvalue'][0]['text']), $match_text))
										{

											$nomination_d['nomination_reason'] = $map_extension['ams:extensionvalue'][0]['text'];
										}
										else if (preg_match('/local cultural value/', strtolower($map_extension['ams:extensionvalue'][0]['text']), $match_text))
										{

											$nomination_d['nomination_reason'] = $map_extension['ams:extensionvalue'][0]['text'];
										}
										else if (preg_match('/potential to repurpose/', strtolower($map_extension['ams:extensionvalue'][0]['text']), $match_text))
										{

											$nomination_d['nomination_reason'] = $map_extension['ams:extensionvalue'][0]['text'];
										}
									}
								}
								if (isset($nomination_d['nomination_status_id']))
								{
									$nomination_d['created'] = date("Y-m-d H:i:s");
									$this->assets_model->insert_nominations($nomination_d);
									break;
								}
							}
						}
					}
					// Asset Extension End //
					$this->load->library('sphnixrt');
					$this->load->model('searchd_model');
					$this->load->helper('sphnixdata');
					$instantiation_list = $this->searchd_model->get_ins_index(array($instantiations_id));
					$new_list_info = make_instantiation_sphnix_array($instantiation_list[0], TRUE);
					$this->sphnixrt->insert('instantiations_list', $new_list_info, $instantiations_id);
					$asset_list = $this->searchd_model->get_asset_index(array($instantiation_list[0]->assets_id));
					$new_asset_info = make_assets_sphnix_array($asset_list[0], FALSE);
					$this->sphnixrt->update('assets_list', $new_asset_info);
				}
			}
		}
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
