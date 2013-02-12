<?php

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
	* Pbcore2 Class
	*
	* @category   AMS
	* @package    CI
	* @subpackage Controller
	* @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    CPB http://ams.avpreserve.com
	* @link       http://ams.avpreserve.com
	*/
class	Pbcore2	extends	CI_Controller
{

				/**
					*
					* Constructor.
					* 
					*/
				public	$pbcore_path;

				function	__construct()
				{
								parent::__construct();
								$this->load->model('cron_model');
								$this->load->model('assets_model');
								$this->load->model('instantiations_model',	'instant');
								$this->load->model('essence_track_model',	'essence');
								$this->load->model('station_model');
								$this->pbcore_path	=	'assets/export_pbcore2/';
				}

				function	process_dir()
				{
								set_time_limit(0);
								$this->myLog("Calculating Number of Directories...");
								$this->cron_model->scan_directory($this->pbcore_path,	$dir_files);
								$count	=	count($dir_files);
								$this->myLog("Total Directories: $count");
								if(isset($count)	&&	$count	>	0)
								{
												$this->myLog("Total Number of process: "	.	$count);
												$loop_counter	=	0;
												$maxProcess	=	5;
												foreach($dir_files	as	$dir)
												{
																$cmd	=	escapeshellcmd('/usr/bin/php '	.	$this->config->item('path')	.	'index.php pbcore2 pbcore2_dir_child '	.	base64_encode($dir));
																$this->config->item('path')	.	"cronlog/pbcore2_dir_child.log";
																$pidFile	=	$this->config->item('path')	.	"PIDs/pbcore2_dir_child/"	.	$loop_counter	.	".txt";
																@exec('touch '	.	$pidFile);
																$this->runProcess($cmd,	$pidFile,	$this->config->item('path')	.	"cronlog/pbcore2_dir_child.log");
																$file_text	=	file_get_contents($pidFile);
																$this->arrPIDs[$file_text]	=	$loop_counter;
																$proc_cnt	=	$this->procCounter();
																$loop_counter	++;
																while	($proc_cnt	==	$maxProcess)
																{
																				$this->myLog('Number of Processes running: '	.	$loop_counter	.	'/.'	.	$count	.	' Sleeping ...');
																				sleep(30);
																				$proc_cnt	=	$this->procCounter();
																}
												}
												$this->myLog("Waiting for all process to complete.");
												$proc_cnt	=	$this->procCounter();
												while	($proc_cnt	>	0)
												{
																echo	"Sleeping for 10 second...\n";
																sleep(10);
																echo	"\010\010\010\010\010\010\010\010\010\010\010\010";
																echo	"\n";
																$proc_cnt	=	$this->procCounter();
																echo	"Number of Processes running: $proc_cnt/$maxProcess\n";
												}
								}
								echo	"All Data Path Under {$this->pbcore_path} Directory Stored ";
								exit_function();
				}

				function	pbcore2_dir_child($path)
				{

								$type	=	'assets';
								$file	=	'manifest-md5.txt';
								$directory	=	base64_decode($path);
								$folder_status	=	'complete';
								if(	!	$data_folder_id	=	$this->cron_model->get_data_folder_id_by_path($directory))
								{
												$data_folder_id	=	$this->cron_model->insert_data_folder(array("folder_path"	=>	$directory,	"created_at"		=>	date('Y-m-d H:i:s'),	"data_type"			=>	$type));
								}
								if(isset($data_folder_id)	&&	$data_folder_id	>	0)
								{
												$data_result	=	file($directory	.	$file);
												if(isset($data_result)	&&	!	is_empty($data_result))
												{
																foreach($data_result	as	$value)
																{
																				$data_file	=	(explode(" ",	$value));
																				$data_file_path	=	trim(str_replace(array('\r\n',	'\n',	'<br>'),	'',	trim($data_file[1])));
																				$this->myLog('Checking File '	.	$data_file_path);
																				if(isset($data_file_path)	&&	!	is_empty($data_file_path))
																				{
																								$file_path	=	trim($directory	.	$data_file_path);
																								if(strpos($data_file_path,	'organization.xml')	===	false)
																								{
																												if(file_exists($file_path))
																												{
																																if(	!	$this->cron_model->is_pbcore_file_by_path($data_file_path))
																																{
																																				$this->cron_model->insert_prcoess_data(array('file_type'						=>	$type,	'file_path'						=>	($data_file_path),	'is_processed'			=>	0,	'created_at'					=>	date('Y-m-d H:i:s'),	"data_folder_id"	=>	$data_folder_id));
																																}
																												}
																												else
																												{
																																if(	!	$this->cron_model->is_pbcore_file_by_path($data_file_path))
																																{
																																				$this->cron_model->insert_prcoess_data(array('file_type'						=>	$type,	'file_path'						=>	($data_file_path),	'is_processed'			=>	0,	'created_at'					=>	date('Y-m-d H:i:s'),	"data_folder_id"	=>	$data_folder_id,	'status_reason'		=>	'file_not_found'));
																																}
																																$folder_status	=	'incomplete';
																												}
																								}
																				}
																}
												}
												$this->myLog('folder Id '	.	$data_folder_id	.	' => folder_status '	.	$folder_status);
												$this->cron_model->update_data_folder(array('updated_at'				=>	date('Y-m-d H:i:s'),	'folder_status'	=>	$folder_status),	$data_folder_id);
								}
				}

				function	process_xml()
				{
								error_reporting(E_ALL);
								ini_set('display_errors',	1);

								$sample	=	array('will_pbcore.xml',	'wnyc_pbcore.xml',	'scetv_pbcore.xml',	'mpr_pbcore.xml');
								foreach($sample	as	$value)
								{
												$file_path	=	$this->pbcore_path	.	'sample/'	.	$value;
												$this->myLog('<b>File Name: '	.	$value	.	'</b>');
												$file_content	=	file_get_contents($file_path);
												$xml	=	@simplexml_load_string($file_content);
												$xml_to_array	=	xmlObjToArr($xml);
//								debug($xml_to_array,	FALSE);
												$this->import_assets($xml_to_array['children']);
												$this->import_instantiations($xml_to_array['children']);
												echo	'<br/><hr/>';
//												exit;
								}
				}

				function	import_assets($asset_children,	$asset_id)
				{
//								debug($asset_children,	FALSE);
								// Asset Type Start //
								if(isset($asset_children['pbcoreassettype']))
								{
												foreach($asset_children['pbcoreassettype']	as	$pbcoreassettype)
												{

																if(isset($pbcoreassettype['text'])	&&	!	is_empty($pbcoreassettype['text']))
																{
																				$this->myLog('Asset Type: '	.	$pbcoreassettype['text']);
																				$asset_type_detail	=	array();
																				$asset_type_detail['assets_id']	=	$asset_id;
																				if($asset_type	=	$this->assets_model->get_assets_type_by_type($pbcoreassettype['text']))
																				{
																								$asset_type_detail['asset_types_id']	=	$asset_type->id;
																				}
																				else
																				{
																								$asset_type_detail['asset_types_id']	=	$this->assets_model->insert_asset_types(array("asset_type"	=>	$pbcoreassettype['text']));
																				}
																				$this->assets_model->insert_assets_asset_types($asset_type_detail);
																				unset($asset_type_detail);
																}
												}
								}
								// Asset Type End //
								// Asset Date and Type Start //
								if(isset($asset_children['pbcoreassetdate']))
								{
												foreach($asset_children['pbcoreassetdate']	as	$pbcoreassetdate)
												{

																if(isset($pbcoreassetdate['text'])	&&	!	is_empty($pbcoreassetdate['text']))
																{
																				$this->myLog('Asset Date: '	.	$pbcoreassetdate['text']);
																}
																if(isset($pbcoreassetdate['attributes']['datetype'])	&&	!	is_empty($pbcoreassetdate['attributes']['datetype']))
																{
																				$this->myLog('Asset Date Type: '	.	$pbcoreassetdate['attributes']['datetype']);
																}
												}
								}
								// Asset Date and Type End //
								// Asset Identifier Start //
								if(isset($asset_children['pbcoreidentifier']))
								{
												foreach($asset_children['pbcoreidentifier']	as	$pbcoreidentifier)
												{

																$identifier_detail	=	array();
																if(isset($pbcoreidentifier['text'])	&&	!	is_empty($pbcoreidentifier['text']))
																{

																				$this->myLog('Asset Identifier: '	.	trim($pbcoreidentifier['text']));
																				$identifier_detail['assets_id']	=	$asset_id;
																				$identifier_detail['identifier']	=	trim($pbcoreidentifier['children']['identifier'][0]['text']);
																				$identifier_detail['identifier_source']	=	'';
																				$identifier_detail['identifier_ref']	=	'';
																				if(isset($pbcoreidentifier['attributes']['source'])	&&	!	is_empty($pbcoreidentifier['attributes']['source']))
																				{
																								$this->myLog('Asset Identifier Source: '	.	trim($pbcoreidentifier['attributes']['source']));
																								$identifier_detail['identifier_source']	=	trim($pbcoreidentifier['attributes']['source']);
																				}
																				if(isset($pbcoreidentifier['attributes']['ref'])	&&	!	is_empty($pbcoreidentifier['attributes']['ref']))
																				{
																								$this->myLog('Asset Identifier Ref: '	.	trim($pbcoreidentifier['attributes']['ref']));
																								$identifier_detail['identifier_ref']	=	trim($pbcoreidentifier['attributes']['ref']);
																				}
																				$this->assets_model->insert_identifiers($identifier_detail);
																				unset($identifier_detail);
																}
												}
								}


								// Asset Identifier End //
								// Asset Title Start //

								if(isset($asset_children['pbcoretitle']))
								{
												foreach($asset_children['pbcoretitle']	as	$pbcoretitle)
												{
																$title_detail	=	array();
																if(isset($pbcoretitle['text'])	&&	!	is_empty($pbcoretitle['text']))
																{
																				$this->myLog('Asset Title: '	.	$pbcoretitle['text']);
																				$title_detail['assets_id']	=	$asset_id;
																				$title_detail['title']	=	$pbcoretitle['text'];
																				if(isset($pbcoretitle['attributes']['titletype'])	&&	!	is_empty($pbcoretitle['attributes']['titletype']))
																				{
																								$this->myLog('Asset Title Type: '	.	$pbcoretitle['attributes']['titletype']);
																								$asset_title_types	=	$this->assets_model->get_asset_title_types_by_title_type($pbcoretitle['attributes']['titletype']);
																								if(isset($asset_title_types)	&&	isset($asset_title_types->id))
																								{
																												$asset_title_types_id	=	$asset_title_types->id;
																								}
																								else
																								{
																												$asset_title_types_id	=	$this->assets_model->insert_asset_title_types(array("title_type"																										=>	$pbcoretitle['attributes']['titletype']));
																								}
																								$title_detail['asset_title_types_id']	=	$asset_title_types_id;
																				}
																				if(isset($pbcoretitle['attributes']['ref'])	&&	!	is_empty($pbcoretitle['attributes']['ref']))
																				{
																								$this->myLog('Asset Title Ref: '	.	$pbcoretitle['attributes']['ref']);
																								$title_detail['title_ref']	=	$pbcoretitle['attributes']['ref'];
																				}
																				if(isset($pbcoretitle['attributes']['source'])	&&	!	is_empty($pbcoretitle['attributes']['source']))
																				{
																								$this->myLog('Asset Title Source: '	.	$pbcoretitle['attributes']['source']);
																								$title_detail['title_source']	=	$pbcoretitle['attributes']['source'];
																				}
																				$title_detail['created']	=	date('Y-m-d H:i:s');
																				$this->assets_model->insert_asset_titles($title_detail);
																				unset($title_detail);
																}
												}
								}
								// Asset Title End  //
								// Asset Subject Start //

								if(isset($asset_children['pbcoresubject']))
								{
												foreach($asset_children['pbcoresubject']	as	$pbcoresubject)
												{
																$subject_detail	=	array();
																if(isset($pbcoresubject['text'])	&&	!	is_empty($pbcoresubject['text']))
																{
																				$subject_detail['assets_id']	=	$asset_id;
																				$this->myLog('Asset Subject: '	.	$pbcoresubject['text']);
																				if(isset($pbcoresubject['attributes']['subjecttype'])	&&	!	is_empty($pbcoresubject['attributes']['subjecttype']))
																				{
																								$subject_d	=	array();
																								$this->myLog('Asset Subject Type: '	.	$pbcoresubject['attributes']['subjecttype']);
																								$subject_d['subject']	=	$pbcoresubject['attributes']['subjecttype'];
																								if(isset($pbcoresubject['attributes']['ref'])	&&	!	is_empty($pbcoresubject['attributes']['ref']))
																								{
																												$this->myLog('Asset Subject Ref: '	.	$pbcoresubject['attributes']['ref']);
																												$subject_d['subject_ref']	=	$pbcoresubject['attributes']['ref'];
																								}
																								if(isset($pbcoresubject['attributes']['source'])	&&	!	is_empty($pbcoresubject['attributes']['source']))
																								{
																												$this->myLog('Asset Subject Source: '	.	$pbcoresubject['attributes']['source']);
																												$subject_d['subject_source']	=	$pbcoresubject['attributes']['source'];
																								}

																								$subjects	=	$this->assets_model->get_subjects_id_by_subject($pbcoresubject['attributes']['subjecttype']);
																								if(isset($subjects)	&&	isset($subjects->id))
																								{
																												$subject_id	=	$subjects->id;
																								}
																								else
																								{
																												$subject_id	=	$this->assets_model->insert_subjects($subject_d);
																								}
																								$subject_detail['subjects_id']	=	$subject_id;
																								$assets_subject_id	=	$this->assets_model->insert_assets_subjects($pbcoreSubject_d);
																				}
																}
												}
								}
								// Asset Subject End  //
								// Asset Description Start //

								if(isset($asset_children['pbcoredescription']))
								{
												foreach($asset_children['pbcoredescription']	as	$pbcoredescription)
												{
																$asset_descriptions_d	=	array();
																if(isset($pbcoredescription['text'])	&&	!	is_empty($pbcoredescription['text']))
																{
																				$asset_descriptions_d['assets_id']	=	$asset_id;
																				$asset_descriptions_d['description']	=	$pbcoredescription['text'];
																				$this->myLog('Asset Description: '	.	$pbcoredescription['text']);
																				if(isset($pbcoredescription['attributes']['descriptiontype'])	&&	!	is_empty($pbcoredescription['attributes']['descriptiontype']))
																				{
																								$this->myLog('Asset Description Type: '	.	$pbcoredescription['attributes']['descriptiontype']);
																								$asset_description_type	=	$this->assets_model->get_description_by_type($pbcoredescription['attributes']['descriptiontype']);
																								if(isset($asset_description_type)	&&	isset($asset_description_type->id))
																								{
																												$asset_description_types_id	=	$asset_description_type->id;
																								}
																								else
																								{
																												$asset_description_types_id	=	$this->assets_model->insert_asset_title_types(array("description_type"																												=>	$pbcoredescription['attributes']['descriptiontype']));
																								}
																								$asset_descriptions_d['description_types_id']	=	$asset_title_types_id;
																				}
																				$this->assets_model->insert_asset_descriptions($asset_descriptions_d);
																}
												}
								}
								// Asset Description End  //
								// Asset Genre Start //

								if(isset($asset_children['pbcoregenre']))
								{
												foreach($asset_children['pbcoregenre']	as	$pbcoregenre)
												{
																$asset_genre_d	=	array();
																$asset_genre	=	array();
																$asset_genre['assets_id']	=	$asset_id;
																if(isset($pbcoregenre['text'])	&&	!	is_empty($pbcoregenre['text']))
																{
																				$this->myLog('Asset Genre: '	.	$pbcoregenre['text']);
																				$asset_genre_d['genre']	=	$pbcoregenre['text'];
																				$asset_genre_type	=	$this->assets_model->get_genre_type($pbcoregenre['text']);
																				if(isset($asset_genre_type)	&&	isset($asset_genre_type->id))
																				{
																								$asset_genre['genres_id']	=	$asset_genre_type->id;
																				}
																				else
																				{
																								if(isset($pbcoregenre['attributes']['source'])	&&	!	is_empty($pbcoregenre['attributes']['source']))
																								{
																												$this->myLog('Asset Genre Source: '	.	$pbcoregenre['attributes']['source']);
																												$asset_genre_d['genre_source']	=	$pbcoregenre['attributes']['source'];
																								}
																								if(isset($pbcoregenre['attributes']['ref'])	&&	!	is_empty($pbcoregenre['attributes']['ref']))
																								{
																												$this->myLog('Asset Genre Ref: '	.	$pbcoregenre['attributes']['ref']);
																												$asset_genre_d['genre_ref']	=	$pbcoregenre['attributes']['ref'];
																								}
																								$asset_genre_id	=	$this->assets_model->insert_genre($asset_genre_d);
																								$asset_genre['genres_id']	=	$asset_genre_id;
																				}
																				$this->assets_model->insert_asset_genre($asset_genre);
																}
												}
								}
								// Asset Genre End  //
								// Asset Coverage Start  //
								if(isset($asset_children['pbcorecoverage']))
								{
												foreach($asset_children['pbcorecoverage']	as	$pbcore_coverage)
												{
																$coverage	=	array();
																$coverage['assets_id']	=	$asset_id;
																if(isset($pbcore_coverage['children']['coverage'][0]['text'])	&&	!	is_empty($pbcore_coverage['children']['coverage'][0]['text']))
																{
																				$this->myLog('Asset Coverage: '	.	$pbcore_coverage['children']['coverage'][0]['text']);
																				$coverage['coverage']	=	$pbcore_coverage['children']['coverage'][0]['text'];
																				if(isset($pbcore_coverage['children']['coveragetype'][0]['text'])	&&	!	is_empty($pbcore_coverage['children']['coveragetype'][0]['text']))
																				{
																								$this->myLog('Asset Coverage Type: '	.	$pbcore_coverage['children']['coveragetype'][0]['text']);
																								$coverage['coverage_type']	=	$pbcore_coverage['children']['coveragetype'][0]['text'];
																				}
																				$asset_coverage	=	$this->assets_model->insert_coverage($coverage);
																}
												}
								}
								// Asset Coverage End  //
								// Asset Audience Level Start //

								if(isset($asset_children['pbcoreaudiencelevel']))
								{
												foreach($asset_children['pbcoreaudiencelevel']	as	$pbcoreaudiencelevel)
												{
																$audience_level	=	array();
																$asset_audience_level	=	array();
																$asset_audience_level['assets_id']	=	$asset_id;
																if(isset($pbcoreaudiencelevel['text'])	&&	!	is_empty($pbcoreaudiencelevel['text']))
																{
																				$this->myLog('Asset Audience Level: '	.	$pbcoreaudiencelevel['text']);
																				$audience_level['audience_level']	=	trim($pbcoreaudiencelevel['text']);
																				if(isset($pbcoreaudiencelevel['attributes']['source'])	&&	!	is_empty($pbcoreaudiencelevel['attributes']['source']))
																				{
																								$this->myLog('Asset Audience Level Source: '	.	$pbcoreaudiencelevel['attributes']['source']);
																								$audience_level['audience_level_source']	=	$pbcoreaudiencelevel['attributes']['source'];
																				}
																				if(isset($pbcoreaudiencelevel['attributes']['ref'])	&&	!	is_empty($pbcoreaudiencelevel['attributes']['ref']))
																				{
																								$this->myLog('Asset Audience Level Ref: '	.	$pbcoreaudiencelevel['attributes']['ref']);
																								$audience_level['audience_level_ref']	=	$pbcoreaudiencelevel['attributes']['ref'];
																				}
																				$db_audience_level	=	$this->assets_model->get_audience_level($pbcoreaudiencelevel['text']);
																				if(isset($db_audience_level)	&&	isset($db_audience_level->id))
																				{
																								$asset_audience_level['audience_levels_id']	=	$db_audience_level->id;
																				}
																				else
																				{
																								$asset_audience_level['audience_levels_id']	=	$this->assets_model->insert_audience_level($audience_level);
																				}
																				$asset_audience	=	$this->assets_model->insert_asset_audience($asset_audience_level);
																}
												}
								}
								// Asset Audience Level End  //
								// Asset Audience Rating Start //

								if(isset($asset_children['pbcoreaudiencerating']))
								{
												foreach($asset_children['pbcoreaudiencerating']	as	$pbcoreaudiencerating)
												{
																$audience_rating	=	array();
																$asset_audience_rating	=	array();
																$asset_audience_rating['assets_id']	=	$asset_id;
																if(isset($pbcoreaudiencerating['text'])	&&	!	is_empty($pbcoreaudiencerating['text']))
																{
																				$this->myLog('Asset Audience Rating: '	.	$pbcoreaudiencerating['text']);
																				$db_audience_rating	=	$this->assets_model->get_audience_rating($pbcoreaudiencerating['text']);
																				if(isset($db_audience_rating)	&&	isset($db_audience_rating->id))
																				{
																								$asset_audience_rating['audience_ratings_id']	=	$db_audience_rating->id;
																				}
																				else
																				{
																								$audience_rating['audience_rating']	=	$pbcoreaudiencerating['text'];
																								if(isset($pbcoreaudiencerating['attributes']['source'])	&&	!	is_empty($pbcoreaudiencerating['attributes']['source']))
																								{
																												$this->myLog('Asset Audience Rating Source: '	.	$pbcoreaudiencerating['attributes']['source']);
																												$audience_rating['audience_rating_source']	=	$pbcoreaudiencerating['attributes']['source'];
																								}
																								if(isset($pbcoreaudiencerating['attributes']['ref'])	&&	!	is_empty($pbcoreaudiencerating['attributes']['ref']))
																								{
																												$this->myLog('Asset Audience Rating Ref: '	.	$pbcoreaudiencerating['attributes']['ref']);
																												$audience_rating['audience_rating_ref']	=	$pbcoreaudiencerating['attributes']['ref'];
																								}
																								$asset_audience_rating['audience_ratings_id']	=	$this->assets_model->insert_audience_rating($audience_rating);
																				}
																				$asset_audience_rate	=	$this->assets_model->insert_asset_audience_rating($asset_audience_rating);
																}
												}
								}
								// Asset Audience Rating End  //
								// Asset Annotation Start //

								if(isset($asset_children['pbcoreannotation']))
								{
												foreach($asset_children['pbcoreannotation']	as	$pbcoreannotation)
												{
																$annotation	=	array();
																$annotation['assets_id']	=	$asset_id;
																if(isset($pbcoreannotation['text'])	&&	!	is_empty($pbcoreannotation['text']))
																{
																				$this->myLog('Asset Annotation: '	.	$pbcoreannotation['text']);
																				$annotation['annotation']	=	$pbcoreannotation['text'];
																				if(isset($pbcoreannotation['attributes']['annotationtype'])	&&	!	is_empty($pbcoreannotation['attributes']['annotationtype']))
																				{
																								$this->myLog('Asset Annotation Type: '	.	$pbcoreannotation['attributes']['annotationtype']);
																								$annotation['annotation_type']	=	$pbcoreannotation['attributes']['annotationtype'];
																				}
																				if(isset($pbcoreannotation['attributes']['ref'])	&&	!	is_empty($pbcoreannotation['attributes']['ref']))
																				{
																								$this->myLog('Asset Annotation Ref: '	.	$pbcoreannotation['attributes']['ref']);
																								$annotation['annotation_ref']	=	$pbcoreannotation['attributes']['ref'];
																				}

																				$asset_annotation	=	$this->assets_model->insert_annotation($annotation);
																}
												}
								}
								// Asset Annotation End  //
								// Asset Relation Start  //
								if(isset($asset_children['pbcorerelation']))
								{
												foreach($asset_children['pbcorerelation']	as	$pbcorerelation)
												{
																$assets_relation	=	array();
																$assets_relation['assets_id']	=	$asset_id;
																$relation_types	=	array();
																if(isset($pbcorerelation['children']['pbcorerelationidentifier'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationidentifier'][0]['text']))
																{
																				$assets_relation['relation_identifier']	=	$pbcorerelation['children']['pbcorerelationidentifier'][0]['text'];
																				if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['text']))
																				{

																								$relation_types['relation_type']	=	$pbcorerelation['children']['pbcorerelationtype'][0]['text'];
																								if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['source'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['attributes']['source']))
																								{
																												$relation_types['relation_type_source']	=	$pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['source'];
																								}
																								if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['ref'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['attributes']['ref']))
																								{
																												$relation_types['relation_type_ref']	=	$pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['ref'];
																								}
																								$db_relations	=	$this->assets_model->get_relation_types($relation_types['relation_type']);
																								if(isset($db_relations)	&&	isset($db_relations->id))
																								{
																												$assets_relation['relation_types_id']	=	$db_relations->id;
																								}
																								else
																								{
																												$assets_relation['relation_types_id']	=	$this->assets_model->insert_relation_types($relation_types);
																								}
																				}
																				$this->assets_model->insert_asset_relation($assets_relation);
																}
												}
								}
								// Asset Relation End  //
								// Asset Creator Start  //
								if(isset($asset_children['pbcorecreator']))
								{
												foreach($asset_children['pbcorecreator']	as	$pbcore_creator)
												{

																if(isset($pbcore_creator['children']['creator'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['creator'][0]['text']))
																{
																				$this->myLog('Asset Creator: '	.	$pbcore_creator['children']['creator'][0]['text']);
																				if(isset($pbcore_creator['children']['creator'][0]['attributes']['affiliation'])	&&	!	is_empty($pbcore_creator['children']['creator'][0]['attributes']['affiliation']))
																				{
																								$this->myLog('Asset Creator affiliation: '	.	$pbcore_creator['children']['creator'][0]['attributes']['affiliation']);
																				}
																				if(isset($pbcore_creator['children']['creator'][0]['attributes']['ref'])	&&	!	is_empty($pbcore_creator['children']['creator'][0]['attributes']['ref']))
																				{
																								$this->myLog('Asset Creator Ref: '	.	$pbcore_creator['children']['creator'][0]['attributes']['ref']);
																				}
																}
																if(isset($pbcore_creator['children']['creatorrole'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['creatorrole'][0]['text']))
																{
																				$this->myLog('Asset Creator Role: '	.	$pbcore_creator['children']['creatorrole'][0]['text']);
																				if(isset($pbcore_creator['children']['creatorrole'][0]['attributes']['source'])	&&	!	is_empty($pbcore_creator['children']['creatorrole'][0]['attributes']['source']))
																				{
																								$this->myLog('Asset Creator Role Source: '	.	$pbcore_creator['children']['creatorrole'][0]['attributes']['source']);
																				}
																				if(isset($pbcore_creator['children']['creatorrole'][0]['attributes']['ref'])	&&	!	is_empty($pbcore_creator['children']['creatorrole'][0]['attributes']['ref']))
																				{
																								$this->myLog('Asset Creator Role Ref: '	.	$pbcore_creator['children']['creatorrole'][0]['attributes']['ref']);
																				}
																}
												}
								}
								// Asset Creator End  //
								// Asset Contributor Start  //
								if(isset($asset_children['pbcorecontributor']))
								{
												foreach($asset_children['pbcorecontributor']	as	$pbcore_contributor)
												{

																if(isset($pbcore_contributor['children']['contributor'][0]['text'])	&&	!	is_empty($pbcore_contributor['children']['contributor'][0]['text']))
																{
																				$this->myLog('Asset Contributor: '	.	$pbcore_contributor['children']['contributor'][0]['text']);
																				if(isset($pbcore_contributor['children']['contributor'][0]['attributes']['affiliation'])	&&	!	is_empty($pbcore_contributor['children']['contributor'][0]['attributes']['affiliation']))
																				{
																								$this->myLog('Asset Contributor affiliation: '	.	$pbcore_contributor['children']['contributor'][0]['attributes']['affiliation']);
																				}
																				if(isset($pbcore_contributor['children']['contributor'][0]['attributes']['ref'])	&&	!	is_empty($pbcore_contributor['children']['contributor'][0]['attributes']['ref']))
																				{
																								$this->myLog('Asset Contributor Ref: '	.	$pbcore_contributor['children']['contributor'][0]['attributes']['ref']);
																				}
																}
																if(isset($pbcore_contributor['children']['contributorrole'][0]['text'])	&&	!	is_empty($pbcore_contributor['children']['contributorrole'][0]['text']))
																{
																				$this->myLog('Asset Contributor Role: '	.	$pbcore_contributor['children']['contributorrole'][0]['text']);
																				if(isset($pbcore_contributor['children']['contributorrole'][0]['attributes']['source'])	&&	!	is_empty($pbcore_contributor['children']['contributorrole'][0]['attributes']['source']))
																				{
																								$this->myLog('Asset Contributor Role Source: '	.	$pbcore_contributor['children']['contributorrole'][0]['attributes']['source']);
																				}
																				if(isset($pbcore_contributor['children']['contributorrole'][0]['attributes']['ref'])	&&	!	is_empty($pbcore_contributor['children']['contributorrole'][0]['attributes']['ref']))
																				{
																								$this->myLog('Asset Contributor Role Ref: '	.	$pbcore_contributor['children']['contributorrole'][0]['attributes']['ref']);
																				}
																}
												}
								}
								// Asset Contributor End  //
								// Asset Publisher Start  //
								if(isset($asset_children['pbcorepublisher']))
								{
												foreach($asset_children['pbcorepublisher']	as	$pbcorepublisher)
												{

																if(isset($pbcorepublisher['children']['publisher'][0]['text'])	&&	!	is_empty($pbcorepublisher['children']['publisher'][0]['text']))
																{
																				$this->myLog('Asset Publisher: '	.	$pbcorepublisher['children']['publisher'][0]['text']);
																				if(isset($pbcorepublisher['children']['publisher'][0]['attributes']['affiliation'])	&&	!	is_empty($pbcorepublisher['children']['publisher'][0]['attributes']['affiliation']))
																				{
																								$this->myLog('Asset Publisher affiliation: '	.	$pbcorepublisher['children']['publisher'][0]['attributes']['affiliation']);
																				}
																				if(isset($pbcorepublisher['children']['publisher'][0]['attributes']['ref'])	&&	!	is_empty($pbcorepublisher['children']['publisher'][0]['attributes']['ref']))
																				{
																								$this->myLog('Asset Publisher Ref: '	.	$pbcorepublisher['children']['publisher'][0]['attributes']['ref']);
																				}
																}
																if(isset($pbcorepublisher['children']['publisherrole'][0]['text'])	&&	!	is_empty($pbcorepublisher['children']['publisherrole'][0]['text']))
																{
																				$this->myLog('Asset Publisher Role: '	.	$pbcorepublisher['children']['publisherrole'][0]['text']);
																				if(isset($pbcorepublisher['children']['publisherrole'][0]['attributes']['source'])	&&	!	is_empty($pbcorepublisher['children']['publisherrole'][0]['attributes']['source']))
																				{
																								$this->myLog('Asset Publisher Role Source: '	.	$pbcorepublisher['children']['publisherrole'][0]['attributes']['source']);
																				}
																				if(isset($pbcorepublisher['children']['publisherrole'][0]['attributes']['ref'])	&&	!	is_empty($pbcorepublisher['children']['publisherrole'][0]['attributes']['ref']))
																				{
																								$this->myLog('Asset Publisher Role Ref: '	.	$pbcorepublisher['children']['publisherrole'][0]['attributes']['ref']);
																				}
																}
												}
								}
								// Asset Publisher End  //
								// Asset Right Summary Start  //
								if(isset($asset_children['pbcorerightssummary']))
								{
												foreach($asset_children['pbcorerightssummary']	as	$pbcore_rights)
												{
																if(isset($pbcore_rights['children']['rightssummary'][0]['text'])	&&	!	is_empty($pbcore_rights['children']['rightssummary'][0]['text']))
																{
																				$this->myLog('Asset Right Summary: '	.	$pbcore_rights['children']['rightssummary'][0]['text']);
																}
																if(isset($pbcore_rights['children']['rightslink'][0]['text'])	&&	!	is_empty($pbcore_rights['children']['rightslink'][0]['text']))
																{
																				$this->myLog('Asset Right Summary Link: '	.	$pbcore_rights['children']['rightslink'][0]['text']);
																}
												}
								}
								// Asset Right Summary End  //
								// Asset Extension Start //

								if(isset($asset_children['pbcoreextension'])	&&	!	is_empty($asset_children['pbcoreextension']))
								{
												foreach($asset_children['pbcoreextension']	as	$pbcore_extension)
												{
																$map_extension	=	$pbcore_extension['children']['extensionwrap'][0]['children'];
																if(isset($map_extension['extensionauthorityused'][0]['text'])	&&	!	is_empty($map_extension['extensionauthorityused'][0]['text']))
																{
																				if(strtolower($map_extension['extensionauthorityused'][0]['text'])	==	strtolower('AACIP Record Tags'))
																				{

																								if(isset($map_extension['extensionvalue'][0]['text'])	&&	!	is_empty($map_extension['extensionvalue'][0]['text']))
																								{
																												if(	!	preg_match('/historical value|risk of loss|local cultural value|potential to repurpose/',	strtolower($map_extension['extensionvalue'][0]['text']),	$match_text))
																												{
																																$this->myLog('Asset Extension Element: '	.	$map_extension['extensionauthorityused'][0]['text']);
																																$this->myLog('Asset Extension Value: '	.	$map_extension['extensionvalue'][0]['text']);
																												}
																								}
																				}
																				else	if(strtolower($map_extension['extensionauthorityused'][0]['text'])	!=	strtolower('AACIP Record Nomination Status'))
																				{

																								$this->myLog('Asset Extension Element: '	.	$map_extension['extensionauthorityused'][0]['text']);
																								if(isset($map_extension['extensionvalue'][0]['text'])	&&	!	is_empty($map_extension['extensionvalue'][0]['text']))
																								{
																												$this->myLog('Asset Extension Value: '	.	$map_extension['extensionvalue'][0]['text']);
																								}
																				}
																}
												}
								}
								// Asset Extension End //
				}

				function	import_instantiations($asset_children)
				{
								if(isset($asset_children['pbcoreinstantiation']))
								{
												foreach($asset_children['pbcoreinstantiation']	as	$pbcoreinstantiation)
												{
																if(isset($pbcoreinstantiation['children'])	&&	!	is_empty($pbcoreinstantiation['children']))
																{
																				$pbcoreinstantiation_child	=	$pbcoreinstantiation['children'];
//																				debug($pbcoreinstantiation_child,	FALSE);
																				// Instantiation Location Start //
																				if(isset($pbcoreinstantiation_child['instantiationlocation'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationlocation'][0]['text']))
																				{
																								$this->myLog('Instantiation Location: '	.	$pbcoreinstantiation_child['instantiationlocation'][0]['text']);
																				}
																				// Instantiation Location End //
																				// Instantiation Standard Start //
																				if(isset($pbcoreinstantiation_child['instantiationstandard'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationstandard'][0]['text']))
																				{
																								$this->myLog('Instantiation Standard: '	.	$pbcoreinstantiation_child['instantiationstandard'][0]['text']);
																				}
																				// Instantiation Standard End //
																				// Instantiation Media Type Start //
																				if(isset($pbcoreinstantiation_child['instantiationmediatype'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationmediatype'][0]['text']))
																				{
																								$this->myLog('Instantiation Media Type: '	.	$pbcoreinstantiation_child['instantiationmediatype'][0]['text']);
																				}
																				// Instantiation Media Type End //
																				// Instantiation File Size Start //
																				if(isset($pbcoreinstantiation_child['instantiationfilesize'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationfilesize'][0]['text']))
																				{
																								$this->myLog('Instantiation File Size: '	.	$pbcoreinstantiation_child['instantiationfilesize'][0]['text']);
																								if(isset($pbcoreinstantiation_child['instantiationfilesize'][0]['attributes']['unitsofmeasure'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationfilesize'][0]['attributes']['unitsofmeasure']))
																								{
																												$this->myLog('Instantiation File Size Type: '	.	$pbcoreinstantiation_child['instantiationfilesize'][0]['attributes']['unitsofmeasure']);
																								}
																				}
																				// Instantiation File Size End //
																				// Instantiation Time Start Start //
																				if(isset($pbcoreinstantiation_child['instantiationtimestart'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationtimestart'][0]['text']))
																				{
																								$this->myLog('Instantiation Time Start: '	.	trim($pbcoreinstantiation_child['instantiationtimestart'][0]['text']));
																				}
																				// Instantiation Time Start End //
																				// Instantiation Projected Duration Start //
																				if(isset($pbcoreinstantiation_child['instantiationduration'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationduration'][0]['text']))
																				{
																								$this->myLog('Instantiation Projected Duration: '	.	trim($pbcoreinstantiation_child['instantiationduration'][0]['text']));
																				}
																				// Instantiation Projected Duration End //
																				// Instantiation Data Rate Start //
																				if(isset($pbcoreinstantiation_child['instantiationdatarate'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationdatarate'][0]['text']))
																				{
																								$this->myLog('Instantiation Data Rate : '	.	trim($pbcoreinstantiation_child['instantiationdatarate'][0]['text']));
																								if(isset($pbcoreinstantiation_child['instantiationdatarate'][0]['attributes']['unitsofmeasure'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationdatarate'][0]['attributes']['unitsofmeasure']))
																								{
																												$this->myLog('Instantiation Data Rate Unit: '	.	$pbcoreinstantiation_child['instantiationdatarate'][0]['attributes']['unitsofmeasure']);
																								}
																				}
																				// Instantiation Data Rate End //
																				// Instantiation Color Start //
																				if(isset($pbcoreinstantiation_child['instantiationcolors'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationcolors'][0]['text']))
																				{
																								$this->myLog('Instantiation Color: '	.	$pbcoreinstantiation_child['instantiationcolors'][0]['attributes']['unitsofmeasure']);
																				}
																				// Instantiation Color End //
																				// Instantiation Tracks Start //
																				if(isset($pbcoreinstantiation_child['instantiationtracks'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationtracks'][0]['text']))
																				{
																								$this->myLog('Instantiation Tracks: '	.	$pbcoreinstantiation_child['instantiationtracks'][0]['text']);
																				}
																				// Instantiation Tracks End //
																				//Instantiation Channel Configuration Start //
																				if(isset($pbcoreinstantiation_child['instantiationchannelconfiguration'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationchannelconfiguration'][0]['text']))
																				{
																								$this->myLog('Instantiation Cannel Configuration: '	.	$pbcoreinstantiation_child['instantiationchannelconfiguration'][0]['text']);
																				}
																				//Instantiation Channel Configuration End //
																				//Instantiation Language Start //
																				if(isset($pbcoreinstantiation_child['instantiationlanguage'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationlanguage'][0]['text']))
																				{
																								$this->myLog('Instantiation Language: '	.	$pbcoreinstantiation_child['instantiationlanguage'][0]['text']);
																				}
																				//Instantiation Language End //
																				//Instantiation Alternative Mode Start //
																				if(isset($pbcoreinstantiation_child['instantiationalternativemodes'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationalternativemodes'][0]['text']))
																				{
																								$this->myLog('Instantiation Alternative Modes: '	.	$pbcoreinstantiation_child['instantiationalternativemodes'][0]['text']);
																				}
																				//Instantiation Alternative Mode End //

																				$insert_instantiation	=	TRUE;
																				// Instantiations Identifier Start //
																				if(isset($pbcoreinstantiation_child['instantiationidentifier']))
																				{

																								foreach($pbcoreinstantiation_child['instantiationidentifier']	as	$pbcore_identifier)
																								{
																												if(isset($pbcore_identifier['text'])	&&	!	is_empty($pbcore_identifier['text']))
																												{
																																$this->myLog('Instantiation Identifer: '	.	$pbcore_identifier['text']);
																																if(isset($pbcore_identifier['attributes']['source'])	&&	!	is_empty($pbcore_identifier['attributes']['source']))
																																{
																																				$this->myLog('Instantiation Identifer Source: '	.	$pbcore_identifier['attributes']['source']);
																																}
																												}
																								}
																				}
																				// Instantiations Identifier End //
																				// Instantiations Date Start //
																				if(isset($pbcoreinstantiation_child['instantiationdate']))
																				{

																								foreach($pbcoreinstantiation_child['instantiationdate']	as	$pbcore_date)
																								{
																												if(isset($pbcore_date['text'])	&&	!	is_empty($pbcore_date['text']))
																												{
																																$this->myLog('Instantiation Date: '	.	$pbcore_date['text']);
																																if(isset($pbcore_date['attributes']['datetype'])	&&	!	is_empty($pbcore_date['attributes']['datetype']))
																																{
																																				$this->myLog('Instantiation Date Type: '	.	$pbcore_date['attributes']['datetype']);
																																}
																												}
																								}
																				}
																				// Instantiations Date End //
																				// Instantiations Dimension Start //
																				if(isset($pbcoreinstantiation_child['instantiationdimensions']))
																				{

																								foreach($pbcoreinstantiation_child['instantiationdimensions']	as	$pbcore_dimension)
																								{
																												if(isset($pbcore_dimension['text'])	&&	!	is_empty($pbcore_dimension['text']))
																												{
																																$this->myLog('Instantiation Dimension: '	.	$pbcore_dimension['text']);
																																if(isset($pbcore_dimension['attributes']['unitofmeasure'])	&&	!	is_empty($pbcore_dimension['attributes']['unitofmeasure']))
																																{
																																				$this->myLog('Instantiation Dimension Unit: '	.	$pbcore_dimension['attributes']['unitofmeasure']);
																																}
																												}
																								}
																				}
																				// Instantiations Dimension End //
																				// Instantiations Format Start //
																				if(isset($pbcoreinstantiation_child['instantiationphysical']))
																				{

																								foreach($pbcoreinstantiation_child['instantiationphysical']	as	$pbcore_physical)
																								{
																												if(isset($pbcore_physical['text'])	&&	!	is_empty($pbcore_physical['text']))
																												{
																																$this->myLog('Instantiation Format Name: '	.	$pbcore_physical['text']);
																																$this->myLog('Instantiation Format Type: physical');
																												}
																								}
																				}
																				else	if(isset($pbcoreinstantiation_child['instantiationdigital']))
																				{

																								foreach($pbcoreinstantiation_child['instantiationdigital']	as	$pbcore_digital)
																								{
																												if(isset($pbcore_digital['text'])	&&	!	is_empty($pbcore_digital['text']))
																												{
																																$this->myLog('Instantiation Format Name: '	.	$pbcore_physical['text']);
																																$this->myLog('Instantiation Format Type: digital');
																												}
																								}
																				}
																				// Instantiations  Format End //
																				// Instantiations  Generation Start //

																				if(isset($pbcoreinstantiation_child['instantiationgenerations'])	&&	!	is_empty($pbcoreinstantiation_child['instantiationgenerations']))
																				{
																								foreach($pbcoreinstantiation_child['instantiationgenerations']	as	$instantiation_generations)
																								{
																												if(isset($instantiation_generations['text'])	&&	!	is_empty($instantiation_generations['text']))
																												{
																																$this->myLog('Instantiation Generation: '	.	$instantiation_generations['text']);
																												}
																								}
																				}
																				// Instantiations  Generation End //
																				// Instantiations  Annotation Start //
																				if(isset($pbcoreinstantiation_child['instantiationannotation']))
																				{
																								foreach($pbcoreinstantiation_child['instantiationannotation']	as	$pbcore_annotation)
																								{
																												if(isset($pbcore_annotation['text'])	&&	!	is_empty($pbcore_annotation['text']))
																												{
																																$this->myLog('Instantiation Annotation: '	.	$pbcore_annotation['text']);
																																if(isset($pbcore_annotation['attributes']['annotationtype'])	&&	!	is_empty($pbcore_annotation['attributes']['annotationtype']))
																																{
																																				$this->myLog('Instantiation Annotation Type: '	.	$pbcore_annotation['attributes']['annotationtype']);
																																}
																												}
																								}
																				}
																				// Instantiations  Annotation End //
																				// Instantiations Relation Start  //
																				if(isset($pbcoreinstantiation_child['pbcorerelation']))
																				{
																								foreach($pbcoreinstantiation_child['pbcorerelation']	as	$pbcorerelation)
																								{

																												if(isset($pbcorerelation['children']['pbcorerelationidentifier'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationidentifier'][0]['text']))
																												{
																																$this->myLog('Instantiation Relation Identifier: '	.	$pbcorerelation['children']['pbcorerelationidentifier'][0]['text']);
																												}
																												if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['text']))
																												{
																																$this->myLog('Instantiation Relation Type: '	.	$pbcorerelation['children']['pbcorerelationtype'][0]['text']);
																																if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['source'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['attributes']['source']))
																																{
																																				$this->myLog('Instantiation Relation Type Source: '	.	$pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['source']);
																																}
																																if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['ref'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['attributes']['ref']))
																																{
																																				$this->myLog('Instantiation Relation Type Ref: '	.	$pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['ref']);
																																}
																												}
																								}
																				}
																				// Instantiations Relation End  //
																				// Instantiations Essence Tracks Start //
																				if(isset($pbcoreinstantiation_child['instantiationessencetrack']))
																				{
																								foreach($pbcoreinstantiation_child['instantiationessencetrack']	as	$pbcore_essence_track)
																								{
																												if(isset($pbcore_essence_track['children'])	&&	!	is_empty($pbcore_essence_track['children']))
																												{
																																$pbcore_essence_child	=	$pbcore_essence_track['children'];
																																// Essence Track Standard Start //
																																if(isset($pbcore_essence_child['essencetrackstandard'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackstandard'][0]['text']))
																																{
																																				$this->myLog('Essence Standard: '	.	$pbcore_essence_child['essencetrackstandard'][0]['text']);
																																}
																																// Essence Track Standard End //
																																// Essence Track Data Rate Start //

																																if(isset($pbcore_essence_child['essencetrackdatarate'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackdatarate'][0]['text']))
																																{
																																				$this->myLog('Essence Track Data Rate: '	.	$pbcore_essence_child['essencetrackdatarate'][0]['text']);
																																				if(isset($pbcore_essence_child['essencetrackdatarate'][0]['attributes']['unitsofmeasure'])	&&	!	is_empty($pbcore_essence_child['essencetrackdatarate'][0]['attributes']['unitsofmeasure']))
																																				{
																																								$this->myLog('Essence Track Date Rate Unit: '	.	$pbcore_essence_child['essencetrackdatarate'][0]['attributes']['unitsofmeasure']);
																																				}
																																}

																																// Essence Track Data Rate End //
																																// Essence Track Frame Rate Start //
																																if(isset($pbcore_essence_child['essencetrackframerate'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackframerate'][0]['text']))
																																{
																																				$frame_rate	=	explode(" ",	$pbcore_essence_child['essencetrackframerate'][0]['text']);
																																				$this->myLog('Essence Track Frame Rate: '	.	trim($frame_rate[0]));
																																}
																																// Essence Track Frame Rate End //
																																// Essence Track Play Back Speed Start //
																																if(isset($pbcore_essence_child['essencetrackplaybackspeed'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackplaybackspeed'][0]['text']))
																																{
																																				$this->myLog('Essence Track Playback Speed: '	.	$pbcore_essence_child['essencetrackplaybackspeed'][0]['text']);
																																}
																																// Essence Track Play Back Speed End //
																																// Essence Track Sampling Rate Start //
																																if(isset($pbcore_essence_child['essencetracksamplingrate'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetracksamplingrate'][0]['text']))
																																{
																																				$this->myLog('Essence Track Sampling Rate: '	.	$pbcore_essence_child['essencetracksamplingrate'][0]['text']);
																																}
																																// Essence Track Sampling Rate End //
																																// Essence Track bit depth Start //
																																if(isset($pbcore_essence_child['essencetrackbitdepth'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackbitdepth'][0]['text']))
																																{
																																				$this->myLog('Essence Bit Depth: '	.	$pbcore_essence_child['essencetrackbitdepth'][0]['text']);
																																}
																																// Essence Track bit depth End //
																																// Essence Track Aspect Ratio Start //
																																if(isset($pbcore_essence_child['essencetrackaspectratio'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackaspectratio'][0]['text']))
																																{
																																				$this->myLog('Essence Aspect Ratio: '	.	$pbcore_essence_child['essencetrackaspectratio'][0]['text']);
																																}
																																// Essence Track Aspect Ratio End //
																																// Essence Track Time Start //
																																if(isset($pbcore_essence_child['essencetracktimestart'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetracktimestart'][0]['text']))
																																{
																																				$this->myLog('Essence Time Start: '	.	$pbcore_essence_child['essencetracktimestart'][0]['text']);
																																}
																																// Essence Track Time End //
																																// Essence Track Duration Start //

																																if(isset($pbcore_essence_child['essencetrackduration'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackduration'][0]['text']))
																																{
																																				$this->myLog('Essence Duration: '	.	$pbcore_essence_child['essencetrackduration'][0]['text']);
																																}
																																// Essence Track Duration End //
																																// Essence Track Language Start //

																																if(isset($pbcore_essence_child['essencetracklanguage'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetracklanguage'][0]['text']))
																																{
																																				$this->myLog('Essence Language: '	.	$pbcore_essence_child['essencetracklanguage'][0]['text']);
																																}
																																// Essence Track Language Start //
																																// Essence Track Type Start //
																																if(isset($pbcore_essence_child['essencetracktype'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetracktype'][0]['text']))
																																{
																																				$this->myLog('Essence Track Type: '	.	$pbcore_essence_child['essencetracktype'][0]['text']);
																																}
																																// Essence Track Type End //
																																$insert_essence_track	=	TRUE;
																																// Essence Track Identifier Start //
																																if(isset($pbcore_essence_child['essencetrackidentifier'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackidentifier'][0]['text']))
																																{
																																				$this->myLog('Essence Track Identifier: '	.	$pbcore_essence_child['essencetrackidentifier'][0]['text']);
																																				if(isset($pbcore_essence_child['essencetrackidentifier'][0]['attributes']['source'])	&&	!	is_empty($pbcore_essence_child['essencetrackidentifier'][0]['attributes']['source']))
																																				{
																																								$this->myLog('Essence Track Identifier Source: '	.	$pbcore_essence_child['essencetrackidentifier'][0]['attributes']['source']);
																																				}
																																}
																																// Essence Track Identifier End //
																																// Essence Track Encoding Start //
																																if(isset($pbcore_essence_child['essencetrackencoding'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackencoding'][0]['text']))
																																{
																																				$this->myLog('Essence Track Encoding: '	.	$pbcore_essence_child['essencetrackencoding'][0]['text']);
																																				if(isset($pbcore_essence_child['essencetrackencoding'][0]['attributes']['ref'])	&&	!	is_empty($pbcore_essence_child['essencetrackencoding'][0]['attributes']['ref']))
																																				{
																																								$this->myLog('Essence Track Encoding Ref: '	.	$pbcore_essence_child['essencetrackencoding'][0]['attributes']['ref']);
																																				}
																																}
																																// Essence Track Encoding End //
																																// Essence Track Annotation Start //
																																if(isset($pbcore_essence_child['essencetrackannotation'])	&&	!	is_empty($pbcore_essence_child['essencetrackannotation']))
																																{
																																				foreach($pbcore_essence_child['essencetrackannotation']	as	$trackannotation)
																																				{
																																								if(isset($trackannotation['text'])	&&	!	is_empty($trackannotation['text']))
																																								{
																																												$this->myLog('Essence Track Annotation: '	.	$trackannotation['text']);
																																												if(isset($trackannotation['attributes']['type'])	&&	!	is_empty($trackannotation['attributes']['type']))
																																												{
																																																$this->myLog('Essence Track Annotation Type: '	.	$trackannotation['attributes']['type']);
																																												}
																																								}
																																				}
																																}
																																// Essence Track Annotation End //
																																// Essence Track Frame Size Start //
																																if(isset($pbcore_essence_child['essencetrackframesize'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackframesize'][0]['text']))
																																{
																																				$frame_sizes	=	explode("x",	strtolower($pbcore_essence_child['essencetrackframesize'][0]['text']));
																																				$this->myLog('Essence Track Frame Size Width: '	.	$frame_sizes[0]);
																																				$this->myLog('Essence Track Frame Size Height: '	.	$frame_sizes[1]);
																																}
																																// Essence Track Frame Size End //
																												}
																								}
																				}
																				// Instantiations Essence Tracks End //
																				// Asset Extension Start //

																				if(isset($asset_children['pbcoreextension'])	&&	!	is_empty($asset_children['pbcoreextension']))
																				{
																								foreach($asset_children['pbcoreextension']	as	$pbcore_extension)
																								{
																												$map_extension	=	$pbcore_extension['children']['extensionwrap'][0]['children'];
																												if(isset($map_extension['extensionauthorityused'][0]['text'])	&&	!	is_empty($map_extension['extensionauthorityused'][0]['text']))
																												{
																																if(strtolower($map_extension['extensionauthorityused'][0]['text'])	==	strtolower('AACIP Record Nomination Status'))
																																{
																																				if(isset($map_extension['extensionvalue'][0]['text'])	&&	!	is_empty($map_extension['extensionvalue'][0]['text']))
																																				{
																																								$this->myLog('<b>Nomination Status:</b> '	.	$map_extension['extensionvalue'][0]['text']);
																																				}
																																}
																																if(strtolower($map_extension['extensionauthorityused'][0]['text'])	==	strtolower('AACIP Record Tags'))
																																{

																																				if(isset($map_extension['extensionvalue'][0]['text'])	&&	!	is_empty($map_extension['extensionvalue'][0]['text']))
																																				{
																																								if(preg_match('/historical value/',	strtolower($map_extension['extensionvalue'][0]['text']),	$match_text))
																																								{

																																												$this->myLog('<b>Nomination Reason:</b> '	.	$map_extension['extensionvalue'][0]['text']);
																																								}
																																								else	if(preg_match('/risk of loss/',	strtolower($map_extension['extensionvalue'][0]['text']),	$match_text))
																																								{

																																												$this->myLog('<b>Nomination Reason:</b> '	.	$map_extension['extensionvalue'][0]['text']);
																																								}
																																								else	if(preg_match('/local cultural value/',	strtolower($map_extension['extensionvalue'][0]['text']),	$match_text))
																																								{

																																												$this->myLog('<b>Nomination Reason:</b> '	.	$map_extension['extensionvalue'][0]['text']);
																																								}
																																								else	if(preg_match('/potential to repurpose/',	strtolower($map_extension['extensionvalue'][0]['text']),	$match_text))
																																								{

																																												$this->myLog('<b>Nomination Reason:</b> '	.	$map_extension['extensionvalue'][0]['text']);
																																								}
																																				}
																																}
																												}
																								}
																				}
																				// Asset Extension End //
																}
												}
								}
				}

				function	myLog($string)
				{
								global	$argc;
								if($argc)
												$string.="\n";
								else
												$string.="<br>\n";
								echo	date('Y-m-d H:i:s')	.	' >> '	.	$string;
								flush();
				}

				function	checkProcessStatus($pid)
				{
								$proc_status	=	false;
								try
								{
												$result	=	shell_exec("/bin/ps $pid");
												if(count(preg_split("/\n/",	$result))	>	2)
												{
																$proc_status	=	TRUE;
												}
								}
								catch	(Exception	$e)
								{
												
								}
								return	$proc_status;
				}

				function	procCounter()
				{
								foreach($this->arrPIDs	as	$pid	=>	$cityKey)
								{
												if(	!	$this->checkProcessStatus($pid))
												{
																$t_pid	=	str_replace("\r",	"",	str_replace("\n",	"",	trim($pid)));
																unset($this->arrPIDs[$pid]);
												}
												else
												{
																
												}
								}
								return	count($this->arrPIDs);
				}

				function	runProcess($cmd,	$pidFilePath,	$outputfile	=	"/dev/null")
				{
								$cmd	=	escapeshellcmd($cmd);
								@exec(sprintf("%s >> %s 2>&1 & echo $! > %s",	$cmd,	$outputfile,	$pidFilePath));
				}

}
