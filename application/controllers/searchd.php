<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Searchd Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Searchd extends CI_Controller
{

	/**
	 * Constructor.
	 * 
	 * Load Model,Helper and Library.
	 *  
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->library('sphnixrt');
		$this->load->model('station_model');
		$this->load->model('searchd_model');
		$this->load->helper('sphnixdata');
		$this->load->model('assets_model');
	}

	function test()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}

	/**
	 * Insert Stations information to Sphnix Realtime Index.
	 * 
	 * @return none
	 */
	function insert_station_sphnix()
	{
		$stations = $this->station_model->get_all();
		foreach ($stations as $key => $row)
		{
			$record = make_station_sphnix_array($row);
			$this->sphnixrt->insert($this->config->item('station_index'), $record, $row->id);
		}
		exit_function();
	}

	function make_map_array($value)
	{

		return $value->id;
	}

	/**
	 * Insert Instantiations information to Sphnix Realtime Index.
	 * 
	 * @return none
	 */
	function insert_instantiations_sphnix()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999);
		$db_count = 0;
		$offset = 0;
		while ($db_count == 0)
		{
			$inst = $this->searchd_model->get_ins($offset, 1000);
			$ids = array_map(array($this, 'make_map_array'), $inst);
			$records = $this->searchd_model->get_ins_index($ids);
			foreach ($records as $row)
			{
				$data = make_instantiation_sphnix_array($row);
				$this->sphnixrt->insert($this->config->item('instantiatiion_index'), $data, $row->id);
			}
			$offset = $offset + 1000;
			if (count($inst) < 1000)
				$db_count ++;
		}

		exit_function();
	}

	function insert_assets()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "4000M"); # 1GB
		@ini_set("max_execution_time", 999999999999);
		$assets = $this->searchd_model->run_query('SELECT id from assets WHERE id>4851194')->result();
		foreach ($assets as $asset)
		{
			myLog('Start Inserting ID =>' . $asset->id);
			$records = $this->searchd_model->get_asset_index(array($asset->id));
			myLog('Start inserting to sphinx');
			foreach ($records as $row)
			{
				$data = make_assets_sphnix_array($row);
				$this->sphnixrt->insert($this->config->item('asset_index'), $data, $row->id);
				myLog('Inserted ID =>' . $row->id);
			}
		}
	}

	/**
	 * Insert Assets information to Sphnix Realtime Index.
	 * 
	 * @return none
	 */
	function insert_assets_sphnix()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "4000M"); # 1GB
		@ini_set("max_execution_time", 999999999999);

		$db_count = 0;
		$offset = 0;
		while ($db_count == 0)
		{
			$inst = $this->searchd_model->get_asset($offset, 1000);
			myLog('Get 1000 records');
			$ids = array_map(array($this, 'make_map_array'), $inst);
			mylog($ids);
			$records = $this->searchd_model->get_asset_index($ids);
			myLog('Start inserting to sphinx');
			foreach ($records as $row)
			{
				$data = make_assets_sphnix_array($row);
				$this->sphnixrt->insert($this->config->item('asset_index'), $data, $row->id);
				myLog('Inserted ID =>' . $row->id);
			}
			myLog('Inserted 1000 records');
			$offset = $offset + 1000;
			if (count($inst) < 1000)
				$db_count ++;
		}

		exit_function();
	}

}

// END Searchd Controller

// End of file searchd.php 
/* Location: ./application/controllers/searchd.php */