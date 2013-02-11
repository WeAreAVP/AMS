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

				function	process_xml()
				{
								error_reporting(E_ALL);
								ini_set('display_errors',	1);
								//will_pbcore.xml
								//wnyc_pbcore.xml
								//scetv_pbcore.xml
								//mpr_pbcore.xml
								$file_path	=	$this->pbcore_path	.	'sample/mpr_pbcore.xml';
								$file_content	=	file_get_contents($file_path);
								$xml	=	@simplexml_load_string($file_content);
								$xml_to_array	=	xmlObjToArr($xml);
								debug($xml_to_array,	FALSE);
								$this->import_assets($xml_to_array['children']);
				}

				function	import_assets($asset_children)
				{
								// Asset Type Start //
								if(isset($asset_children['pbcoreassettype']))
								{
												foreach($asset_children['pbcoreassettype']	as	$pbcoreassettype)
												{

																if(isset($pbcoreassettype['text'])	&&	!	is_empty($pbcoreassettype['text']))
																{
																				$this->myLog('Asset Type: '	.	$pbcoreassettype['text']);
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


																if(isset($pbcoreidentifier['text'])	&&	!	is_empty($pbcoreidentifier['text']))
																{

																				$this->myLog('Asset Identifier: '	.	trim($pbcoreidentifier['text']));

																				if(isset($pbcoreidentifier['attributes']['source'])	&&	!	is_empty($pbcoreidentifier['attributes']['source']))
																				{
																								$this->myLog('Asset Identifier Source: '	.	trim($pbcoreidentifier['attributes']['source']));
																				}
																				if(isset($pbcoreidentifier['attributes']['ref'])	&&	!	is_empty($pbcoreidentifier['attributes']['ref']))
																				{
																								$this->myLog('Asset Identifier Ref: '	.	trim($pbcoreidentifier['attributes']['ref']));
																				}
																}
												}
								}


								// Asset Identifier End //
								// Asset Title Start //

								if(isset($asset_children['pbcoretitle']))
								{
												foreach($asset_children['pbcoretitle']	as	$pbcoretitle)
												{

																if(isset($pbcoretitle['text'])	&&	!	is_empty($pbcoretitle['text']))
																{
																				$this->myLog('Asset Title: '	.	$pbcoretitle['text']);
																}
																if(isset($pbcoretitle['attributes']['titletype'])	&&	!	is_empty($pbcoretitle['attributes']['titletype']))
																{
																				$this->myLog('Asset Title Type: '	.	$pbcoretitle['attributes']['titletype']);
																}
																if(isset($pbcoretitle['attributes']['ref'])	&&	!	is_empty($pbcoretitle['attributes']['ref']))
																{
																				$this->myLog('Asset Title Ref: '	.	$pbcoretitle['attributes']['ref']);
																}
																if(isset($pbcoretitle['attributes']['source'])	&&	!	is_empty($pbcoretitle['attributes']['source']))
																{
																				$this->myLog('Asset Title Source: '	.	$pbcoretitle['attributes']['source']);
																}
												}
								}
								// Asset Title End  //
								// Asset Subject Start //

								if(isset($asset_children['pbcoresubject']))
								{
												foreach($asset_children['pbcoresubject']	as	$pbcoresubject)
												{

																if(isset($pbcoresubject['text'])	&&	!	is_empty($pbcoresubject['text']))
																{
																				$this->myLog('Asset Subject: '	.	$pbcoresubject['text']);
																}
																if(isset($pbcoresubject['attributes']['subjecttype'])	&&	!	is_empty($pbcoresubject['attributes']['subjecttype']))
																{
																				$this->myLog('Asset Subject Type: '	.	$pbcoresubject['attributes']['subjecttype']);
																}
																if(isset($pbcoresubject['attributes']['ref'])	&&	!	is_empty($pbcoresubject['attributes']['ref']))
																{
																				$this->myLog('Asset Subject Ref: '	.	$pbcoresubject['attributes']['ref']);
																}
																if(isset($pbcoresubject['attributes']['source'])	&&	!	is_empty($pbcoresubject['attributes']['source']))
																{
																				$this->myLog('Asset Subject Source: '	.	$pbcoresubject['attributes']['source']);
																}
												}
								}
								// Asset Subject End  //
								// Asset Description Start //

								if(isset($asset_children['pbcoredescription']))
								{
												foreach($asset_children['pbcoredescription']	as	$pbcoredescription)
												{

																if(isset($pbcoredescription['text'])	&&	!	is_empty($pbcoredescription['text']))
																{
																				$this->myLog('Asset Description: '	.	$pbcoredescription['text']);
																}
																else
																{
																				$this->myLog('Asset Description: ');
																}
																if(isset($pbcoredescription['attributes']['descriptiontype'])	&&	!	is_empty($pbcoredescription['attributes']['descriptiontype']))
																{
																				$this->myLog('Asset Description Type: '	.	$pbcoredescription['attributes']['descriptiontype']);
																}
												}
								}
								// Asset Description End  //
								// Asset Genre Start //

								if(isset($asset_children['pbcoregenre']))
								{
												foreach($asset_children['pbcoregenre']	as	$pbcoregenre)
												{

																if(isset($pbcoregenre['text'])	&&	!	is_empty($pbcoregenre['text']))
																{
																				$this->myLog('Asset Genre: '	.	$pbcoregenre['text']);
																}

																if(isset($pbcoregenre['attributes']['source'])	&&	!	is_empty($pbcoregenre['attributes']['source']))
																{
																				$this->myLog('Asset Genre Source: '	.	$pbcoregenre['attributes']['source']);
																}
																if(isset($pbcoregenre['attributes']['ref'])	&&	!	is_empty($pbcoregenre['attributes']['ref']))
																{
																				$this->myLog('Asset Genre Ref: '	.	$pbcoregenre['attributes']['ref']);
																}
												}
								}
								// Asset Genre End  //
								// Asset Coverage Start  //
								// 
								// Logics will be written when I got sample
								// 
								// Asset Coverage End  //
								// Asset Audience Level Start //

								if(isset($asset_children['pbcoreaudiencelevel']))
								{
												foreach($asset_children['pbcoreaudiencelevel']	as	$pbcoreaudiencelevel)
												{

																if(isset($pbcoreaudiencelevel['text'])	&&	!	is_empty($pbcoreaudiencelevel['text']))
																{
																				$this->myLog('Asset Audience Level: '	.	$pbcoreaudiencelevel['text']);
																}

																if(isset($pbcoreaudiencelevel['attributes']['source'])	&&	!	is_empty($pbcoreaudiencelevel['attributes']['source']))
																{
																				$this->myLog('Asset Audience Level Source: '	.	$pbcoreaudiencelevel['attributes']['source']);
																}
																if(isset($pbcoreaudiencelevel['attributes']['ref'])	&&	!	is_empty($pbcoreaudiencelevel['attributes']['ref']))
																{
																				$this->myLog('Asset Audience Level Ref: '	.	$pbcoreaudiencelevel['attributes']['ref']);
																}
												}
								}
								// Asset Audience Level End  //
								// Asset Audience Rating Start //

								if(isset($asset_children['pbcoreaudiencerating']))
								{
												foreach($asset_children['pbcoreaudiencerating']	as	$pbcoreaudiencerating)
												{

																if(isset($pbcoreaudiencerating['text'])	&&	!	is_empty($pbcoreaudiencerating['text']))
																{
																				$this->myLog('Asset Audience Rating: '	.	$pbcoreaudiencerating['text']);
																}

																if(isset($pbcoreaudiencerating['attributes']['source'])	&&	!	is_empty($pbcoreaudiencerating['attributes']['source']))
																{
																				$this->myLog('Asset Audience Rating Source: '	.	$pbcoreaudiencerating['attributes']['source']);
																}
																if(isset($pbcoreaudiencerating['attributes']['ref'])	&&	!	is_empty($pbcoreaudiencerating['attributes']['ref']))
																{
																				$this->myLog('Asset Audience Rating Ref: '	.	$pbcoreaudiencerating['attributes']['ref']);
																}
												}
								}
								// Asset Audience Rating End  //
								// Asset Annotation Start //

								if(isset($asset_children['pbcoreannotation']))
								{
												foreach($asset_children['pbcoreannotation']	as	$pbcoreannotation)
												{

																if(isset($pbcoreannotation['text'])	&&	!	is_empty($pbcoreannotation['text']))
																{
																				$this->myLog('Asset Annotation: '	.	$pbcoreannotation['text']);
																}

																if(isset($pbcoreannotation['attributes']['annotationtype'])	&&	!	is_empty($pbcoreannotation['attributes']['annotationtype']))
																{
																				$this->myLog('Asset Annotation Type: '	.	$pbcoreannotation['attributes']['annotationtype']);
																}
																if(isset($pbcoreannotation['attributes']['ref'])	&&	!	is_empty($pbcoreannotation['attributes']['ref']))
																{
																				$this->myLog('Asset Annotation Ref: '	.	$pbcoreannotation['attributes']['ref']);
																}
												}
								}
								// Asset Annotation End  //
								// Asset Relation Start  //
								// 
								// Logics will be written when I got sample
								// 
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

}

// END Pbcore2 Controller

// End of file pbcore2.php 
/* Location: ./application/controllers/pbcore2.php */