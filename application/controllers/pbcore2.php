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
								$file_path	=	$this->pbcore_path	.	'data/cpb-aacip-16-00ns1t8b/pbcore';
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
								// 
								// Logic will be written  //
								// 
								// Asset Identifier End //
								// Asset Title Start //

								if(isset($asset_children['pbcoretitle']))
								{
												foreach($asset_children['pbcoretitle']	as	$pbcoretitle)
												{
																
																if(isset($pbcoretitle['pbcoretitle']['text'])	&&	!	is_empty($pbcoretitle['pbcoretitle']['text']))
																{
																				$this->myLog('Asset Title: '	.	$pbcoretitle['pbcoretitle']['text']);
																}
																if(isset($pbcoretitle['pbcoretitle']['attributes']['titletype'])	&&	!	is_empty($pbcoretitle['pbcoretitle']['attributes']['titletype']))
																{
																				$this->myLog('Asset Title Type: '	.	$pbcoretitle['pbcoretitle']['attributes']['titletype']);
																}
																if(isset($pbcoretitle['pbcoretitle']['attributes']['ref'])	&&	!	is_empty($pbcoretitle['pbcoretitle']['attributes']['ref']))
																{
																				$this->myLog('Asset Title Ref: '	.	$pbcoretitle['pbcoretitle']['attributes']['ref']);
																}
																if(isset($pbcoretitle['pbcoretitle']['attributes']['source'])	&&	!	is_empty($pbcoretitle['pbcoretitle']['attributes']['source']))
																{
																				$this->myLog('Asset Title Ref: '	.	$pbcoretitle['pbcoretitle']['attributes']['source']);
																}
												}
								}
								// Asset Title End  //
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