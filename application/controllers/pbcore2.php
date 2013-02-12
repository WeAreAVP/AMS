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

				function	import_assets($asset_children)
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
																				if(isset($pbcoredescription['attributes']['descriptiontype'])	&&	!	is_empty($pbcoredescription['attributes']['descriptiontype']))
																				{
																								$this->myLog('Asset Description Type: '	.	$pbcoredescription['attributes']['descriptiontype']);
																				}
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
								}
								// Asset Genre End  //
								// Asset Coverage Start  //
								if(isset($asset_children['pbcorecoverage']))
								{
												foreach($asset_children['pbcorecoverage']	as	$pbcore_coverage)
												{

																if(isset($pbcore_coverage['children']['coverage'][0]['text'])	&&	!	is_empty($pbcore_coverage['children']['coverage'][0]['text']))
																{
																				$this->myLog('Asset Coverage: '	.	$pbcore_coverage['children']['coverage'][0]['text']);
																				if(isset($pbcore_coverage['children']['coveragetype'][0]['text'])	&&	!	is_empty($pbcore_coverage['children']['coveragetype'][0]['text']))
																				{
																								$this->myLog('Asset Coverage Type: '	.	$pbcore_coverage['children']['coveragetype'][0]['text']);
																				}
																}
												}
								}
								// Asset Coverage End  //
								// Asset Audience Level Start //

								if(isset($asset_children['pbcoreaudiencelevel']))
								{
												foreach($asset_children['pbcoreaudiencelevel']	as	$pbcoreaudiencelevel)
												{

																if(isset($pbcoreaudiencelevel['text'])	&&	!	is_empty($pbcoreaudiencelevel['text']))
																{
																				$this->myLog('Asset Audience Level: '	.	$pbcoreaudiencelevel['text']);
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
								}
								// Asset Annotation End  //
								// Asset Relation Start  //
								if(isset($asset_children['pbcorerelation']))
								{
												foreach($asset_children['pbcorerelation']	as	$pbcorerelation)
												{

																if(isset($pbcorerelation['children']['pbcorerelationidentifier'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationidentifier'][0]['text']))
																{
																				$this->myLog('Asset Relation Identifier: '	.	$pbcorerelation['children']['pbcorerelationidentifier'][0]['text']);
																				if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['text']))
																				{
																								$this->myLog('Asset Relation Type: '	.	$pbcorerelation['children']['pbcorerelationtype'][0]['text']);
																								if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['source'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['attributes']['source']))
																								{
																												$this->myLog('Asset Relation Type Source: '	.	$pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['source']);
																								}
																								if(isset($pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['ref'])	&&	!	is_empty($pbcore_creator['children']['pbcorerelationtype'][0]['attributes']['ref']))
																								{
																												$this->myLog('Asset Relation Type Ref: '	.	$pbcorerelation['children']['pbcorerelationtype'][0]['attributes']['ref']);
																								}
																				}
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
																																								if(preg_match('/^historical value$|^risk of loss$|^local cultural value$|^potential to repurpose$/',	strtolower($map_extension['extensionvalue'][0]['text']),	$match_text))
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

}
