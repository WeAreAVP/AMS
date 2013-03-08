<?php

// @codingStandardsIgnoreFile
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
	* Automemcache Class
	*
	* @category   AMS
	* @package    CI
	* @subpackage Controller
	* @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    CPB http://ams.avpreserve.com
	* @link       http://ams.avpreserve.com
	*/
class	Automemcache	extends	CI_Controller
{

				/**
					*
					* constructor. Load layout,Model,Library and helpers
					* 
					*/
				function	__construct()
				{
								parent::__construct();
								$this->load->library('memcached_library');
								$this->load->model('sphinx_model',	'sphinx');
				}

				public	function	index()
				{
								$this->set_instantiation_facet();
								$this->set_asset_facet();
								$this->myLog('Succussfully Updated.');
								exit;
				}

				public	function	set_instantiation_facet()
				{
								$index	=	'instantiations_list';
								$states	=	$this->sphinx->facet_index('state',	$index);
								$stations	=	$this->sphinx->facet_index('organization',	$index);
								$nomination	=	$this->sphinx->facet_index('status',	$index);
								$media_type	=	$this->sphinx->facet_index('media_type',	$index);
								$p_format	=	$this->sphinx->facet_index('format_name',	$index,	'physical');
								$d_format	=	$this->sphinx->facet_index('format_name',	$index,	'digital');
								$generation	=	$this->sphinx->facet_index('facet_generation',	$index);
								
								$digitized	=	$this->sphinx->facet_index('digitized',	$index,	'digitized');
								$migration	=	$this->sphinx->facet_index('migration',	$index,	'migration');
								
								$this->memcached_library->set('ins_state',	json_encode(sortByOneKey($states['records'],	'state')),3600);
								
								
								
								$this->memcached_library->set('ins_stations',	json_encode(sortByOneKey($stations['records'],	'organization')),3600);
								$this->myLog('Station Count '.count($this->memcached_library->get('ins_stations')));
								
								$this->memcached_library->set('ins_status',	json_encode(sortByOneKey($nomination['records'],	'status')),3600);
								$this->myLog('Status Count '.count($this->memcached_library->get('ins_status')));
								
								$this->memcached_library->set('ins_media_type',	json_encode(sortByOneKey($media_type['records'],	'media_type',	TRUE)),3600);
								$this->myLog('Media Type Count '.count($this->memcached_library->get('ins_media_type')));
								
								$this->memcached_library->set('ins_physical',	json_encode(sortByOneKey($p_format['records'],	'format_name',	TRUE)),3600);
								$this->myLog('Physical Format Count '.count($this->memcached_library->get('ins_physical')));
								
								$this->memcached_library->set('ins_digital',	json_encode(sortByOneKey($d_format['records'],	'format_name',	TRUE)),3600);
								$this->myLog('Digital Format Count '.count($this->memcached_library->get('ins_digital')));
								
								$this->memcached_library->set('ins_generations',	json_encode(sortByOneKey($generation['records'],	'facet_generation',	TRUE)),3600);
								$this->myLog('Generation Count '.count($this->memcached_library->get('ins_generations')));
								
								$this->memcached_library->set('ins_digitized',	json_encode($digitized),3600);
								$this->myLog('Digitized Count '.count($this->memcached_library->get('ins_digitized')));
								
								$this->memcached_library->set('ins_migration',	json_encode($migration),3600);
								$this->myLog('Migration Count '.count($this->memcached_library->get('ins_migration')));
								
								$this->myLog('Succussfully Updated Instantiations Facet Search');
					
				}

				public	function	set_asset_facet()
				{
								$index	=	'assets_list';
								$states	=	$this->sphinx->facet_index('state',	$index);
								$stations	=	$this->sphinx->facet_index('organization',	$index);
								$nomination	=	$this->sphinx->facet_index('status',	$index);
								$media_type	=	$this->sphinx->facet_index('media_type',	$index);
								$p_format	=	$this->sphinx->facet_index('format_name',	$index,	'physical');
								$d_format	=	$this->sphinx->facet_index('format_name',	$index,	'digital');
								$generation	=	$this->sphinx->facet_index('facet_generation',	$index);
								$digitized	=	$this->sphinx->facet_index('digitized',	$index,	'digitized');
								$migration	=	$this->sphinx->facet_index('migration',	$index,	'migration');

								$this->memcached_library->set('asset_state',	sortByOneKey($states['records'],	'state'));
								$this->memcached_library->set('asset_stations',	sortByOneKey($stations['records'],	'organization'));
								$this->memcached_library->set('asset_status',	sortByOneKey($nomination['records'],	'status'));
								$this->memcached_library->set('asset_media_type',	sortByOneKey($media_type['records'],	'media_type',	TRUE));
								$this->memcached_library->set('asset_physical',	sortByOneKey($p_format['records'],	'format_name',	TRUE));
								$this->memcached_library->set('asset_digital',	sortByOneKey($d_format['records'],	'format_name',	TRUE));
								$this->memcached_library->set('asset_generations',	sortByOneKey($generation['records'],	'facet_generation',	TRUE));
								$this->memcached_library->set('asset_digitized',	$digitized);
								$this->memcached_library->set('asset_migration',	$migration);
								$this->myLog('Succussfully Updated Assets Facet Search');
								
				}
				/**
					* Display the Output
					* @global type $argc
					* @param type $s 
					*/
				function	myLog($s)
				{
								global	$argc;
								if($argc)
												$s.="\n";
								else
												$s.="<br>\n";
								echo	date('Y-m-d H:i:s')	.	' >> '	.	$s;
								flush();
				}

}