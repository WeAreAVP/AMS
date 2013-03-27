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
 * Automemcache Class
 *
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    CPB http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Automemcache extends CI_Controller
{

	/**
	 *
	 * constructor. Load layout,Model,Library and helpers
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->model('sphinx_model', 'sphinx');
	}

	public function index()
	{


		$this->set_instantiation_facet();
		$this->set_asset_facet();
		myLog('Succussfully Updated.');
		exit_function();
	}

	function test()
	{
		$memcached = new StdClass;
		$memcached->ins = 'instantiations_list';
		$memcached->asset = 'assets_list';
		$search_facet = new stdClass;
		$search_facet->state = 'state';
		$search_facet->stations = 'organization';
		$search_facet->status = 'status';
		$search_facet->media_type = 'media_type';
		$search_facet->physical = 'format_name';
		$search_facet->digital = 'format_name';
		$search_facet->generations = 'facet_generation';
		$search_facet->digitized = 'digitized';
		$search_facet->migration = 'migration';
		foreach ($memcached as $index => $index_name)
		{
			foreach ($search_facet as $columns => $facet)
			{
				$grouping = FALSE;
				if (in_array($facet, array('media_type', 'format_name', 'facet_generation')))
					$grouping = TRUE;
				if (in_array($columns, array('physical', 'digital', 'digitized', 'migration')))
				{
					$result = $this->sphinx->facet_index($facet, $index_name, $columns);
					$this->memcached_library->set($index . $columns, json_encode(sortByOneKey($result['records'], $facet,$grouping)), 3600);
				}
				else
				{
					$result = $this->sphinx->facet_index($facet, $index_name);
					$this->memcached_library->set($index . $columns, json_encode(sortByOneKey($result['records'], $facet,$grouping)), 3600);
				}
			}
			myLog("Succussfully Updated $index_name Facet Search");
		}
	}

	public function set_instantiation_facet()
	{
		$index = 'instantiations_list';
		$states = $this->sphinx->facet_index('state', $index);
		$stations = $this->sphinx->facet_index('organization', $index);
		$nomination = $this->sphinx->facet_index('status', $index);
		$media_type = $this->sphinx->facet_index('media_type', $index);
		$p_format = $this->sphinx->facet_index('format_name', $index, 'physical');
		$d_format = $this->sphinx->facet_index('format_name', $index, 'digital');
		$generation = $this->sphinx->facet_index('facet_generation', $index);

		$digitized = $this->sphinx->facet_index('digitized', $index, 'digitized');
		$migration = $this->sphinx->facet_index('migration', $index, 'migration');

		$this->memcached_library->set('ins_state', json_encode(sortByOneKey($states['records'], 'state')), 3600);
		$this->memcached_library->set('ins_stations', json_encode(sortByOneKey($stations['records'], 'organization')), 3600);
		$this->memcached_library->set('ins_status', json_encode(sortByOneKey($nomination['records'], 'status')), 3600);
		$this->memcached_library->set('ins_media_type', json_encode(sortByOneKey($media_type['records'], 'media_type', TRUE)), 3600);
		$this->memcached_library->set('ins_physical', json_encode(sortByOneKey($p_format['records'], 'format_name', TRUE)), 3600);
		$this->memcached_library->set('ins_digital', json_encode(sortByOneKey($d_format['records'], 'format_name', TRUE)), 3600);
		$this->memcached_library->set('ins_generations', json_encode(sortByOneKey($generation['records'], 'facet_generation', TRUE)), 3600);
		$this->memcached_library->set('ins_digitized', json_encode($digitized), 3600);
		$this->memcached_library->set('ins_migration', json_encode($migration), 3600);


		myLog('Succussfully Updated Instantiations Facet Search');
	}

	public function set_asset_facet()
	{
		$index = 'assets_list';
		$states = $this->sphinx->facet_index('state', $index);
		$stations = $this->sphinx->facet_index('organization', $index);
		$nomination = $this->sphinx->facet_index('status', $index);
		$media_type = $this->sphinx->facet_index('media_type', $index);
		$p_format = $this->sphinx->facet_index('format_name', $index, 'physical');
		$d_format = $this->sphinx->facet_index('format_name', $index, 'digital');
		$generation = $this->sphinx->facet_index('facet_generation', $index);
		$digitized = $this->sphinx->facet_index('digitized', $index, 'digitized');
		$migration = $this->sphinx->facet_index('migration', $index, 'migration');

		$this->memcached_library->set('asset_state', json_encode(sortByOneKey($states['records'], 'state')), 3600);
		$this->memcached_library->set('asset_stations', json_encode(sortByOneKey($stations['records'], 'organization')), 3600);
		$this->memcached_library->set('asset_status', json_encode(sortByOneKey($nomination['records'], 'status')), 3600);
		$this->memcached_library->set('asset_media_type', json_encode(sortByOneKey($media_type['records'], 'media_type', TRUE)), 3600);
		$this->memcached_library->set('asset_physical', json_encode(sortByOneKey($p_format['records'], 'format_name', TRUE)), 3600);
		$this->memcached_library->set('asset_digital', json_encode(sortByOneKey($d_format['records'], 'format_name', TRUE)), 3600);
		$this->memcached_library->set('asset_generations', json_encode(sortByOneKey($generation['records'], 'facet_generation', TRUE)), 3600);
		$this->memcached_library->set('asset_digitized', json_encode($digitized), 3600);
		$this->memcached_library->set('asset_migration', json_encode($migration), 3600);
		myLog('Succussfully Updated Assets Facet Search');
	}

}