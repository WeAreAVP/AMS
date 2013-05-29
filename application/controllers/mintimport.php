<?php

/**
 * Mint Import Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
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
 * @author     Nouman Tayyab <nouman@geekschicago.com>
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
		$this->load->model('cron_model');
		$this->load->model('mint_model', 'mint');
		$this->mint_path = 'assets/mint_import/';
	}

	/**
	 * Unzip and store all the mint info imported files.
	 * 
	 */
	function process_mint_dir()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "2000M"); # 2GB
		@ini_set("max_execution_time", 999999999999);
		$files = glob($this->mint_path . '*.zip');
		foreach ($files as $file)
		{
			$zip = new ZipArchive;
			$res = $zip->open($file);
			if ($res === TRUE)
			{
				$zip->extractTo($this->mint_path . 'unzipped/');
				$zip->close();
			}
			else
			{
				myLog('Something went wrong  while extracting zip file.');
			}
		}
		myLog('Succesfully unzipped all files.');
		$this->load->helper('directory');
		$map = directory_map($this->mint_path . 'unzipped/', 2);
		foreach ($map as $index => $directory)
		{
			foreach ($directory as $file)
			{
				$path = $index . '/' . $file;
				$db_info = $this->mint->get_import_info_by_path($path);
				if ( ! $db_info)
				{
					$mint_info = array('folder' => $index, 'path' => $path, 'is_processed' => 0, 'status_reason' => 'Not processed');
					$this->mint->insert_import_info($mint_info);
				}
				else
					myLog('Already in db.');
			}
		}
		myLog('All mint files info stored. Folder Path:' . $this->mint_path);
		$this->import_mint_files();
	}

	/**
	 * Import mint transformed files.
	 * 
	 */
	function import_mint_files()
	{
		$result = $this->mint->get_files_to_import();
		if ($result)
		{
			foreach ($result as $row)
			{
//				$this->mint->update_mint_import_file($row->id, array('processed_at' => date('Y-m-d H:m:i'), 'status_reason' => 'Processing'));
				$this->parse_xml_file($row->path);
//				$this->mint->update_mint_import_file($row->id, array('is_processed' => 1, 'status_reason' => 'Complete'));
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
	function parse_xml_file($path)
	{
		$file_content = file_get_contents($this->mint_path . 'unzipped/' . $path);
		$xml_string = @simplexml_load_string($file_content);
		unset($file_content);
		$xmlArray = xmlObjToArr($xml_string);
		$station_id = 1;
		$asset_id = $this->import_asset_info($station_id, $xmlArray['children']);
		if ($asset_id)
			$this->import_instantiation_info($asset_id, $xmlArray);
		log('Successfully imported all the information to AMS');
		debug($xmlArray);
	}

	function import_asset_info($station_id, $xmlArray)
	{
		debug($xmlArray);
		// Insert Asset
		$asset_id = 1;
		// Insert Asset Type Start //
//		if (isset($xmlArray['pbc:pbcoreassettype']) && ! empty($xmlArray['pbc:pbcoreassettype']))
//		{
//			foreach ($xmlArray['pbc:pbcoreassettype'] as $row)
//			{
//				if (isset($row['text']) && ! empty($row['text']))
//				{
//					$asset_type_detail = array();
//					$asset_type_detail['assets_id'] = $asset_id;
//					if ($asset_type = $this->assets_model->get_assets_type_by_type($row['text']))
//					{
//						$asset_type_detail['asset_types_id'] = $asset_type->id;
//					}
//					else
//					{
////						$asset_type_detail['asset_types_id'] = $this->assets_model->insert_asset_types(array("asset_type" => $row['text']));
//					}
////					$this->assets_model->insert_assets_asset_types($asset_type_detail);
//					unset($asset_type_detail);
//				}
//			}
//		}
		// Insert Asset Type End //
		// Insert Asset Date Start //
		// Insert Asset Date End //
		// Insert Identifier Start //
		if (isset($xmlArray['ams:pbcoreidentifier']) && ! empty($xmlArray['ams:pbcoreidentifier']))
		{
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
						$identifier_detail['identifier_source'] = $row['attributes']['source'];

//					$this->assets_model->insert_identifiers($identifier_detail);
					unset($identifier_detail);
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
//					$this->assets_model->insert_asset_titles($title_detail);
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
					if (isset($pbcoresubject['attributes']['subjecttype']) && ! is_empty($pbcoresubject['attributes']['subjecttype']))
					{
						$subject_d = array();
						$subject_d['subject'] = $pbcoresubject['attributes']['subjecttype'];
						if (isset($pbcoresubject['attributes']['ref']) && ! is_empty($pbcoresubject['attributes']['ref']))
						{
							$subject_d['subject_ref'] = $pbcoresubject['attributes']['ref'];
						}
						if (isset($pbcoresubject['attributes']['source']) && ! is_empty($pbcoresubject['attributes']['source']))
						{
							$subject_d['subject_source'] = $pbcoresubject['attributes']['source'];
						}

						$subjects = $this->assets_model->get_subjects_id_by_subject($pbcoresubject['attributes']['subjecttype']);
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
				if (isset($pbcore_coverage['children']['coverage'][0]['text']) && ! is_empty($pbcore_coverage['children']['coverage'][0]['text']))
				{
					$coverage['coverage'] = $pbcore_coverage['children']['coverage'][0]['text'];
					if (isset($pbcore_coverage['children']['coveragetype'][0]['text']) && ! is_empty($pbcore_coverage['children']['coveragetype'][0]['text']))
					{
						$coverage['coverage_type'] = $pbcore_coverage['children']['coveragetype'][0]['text'];
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
	}

	function import_instantiation_info($asset_id, $xmlArray)
	{
		
	}

}