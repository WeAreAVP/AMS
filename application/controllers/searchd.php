<?php

/**
 * Searchd Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Searchd Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
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
	}

	function test()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		$sphinx['start']=0;
		$sphinx['limit']=1000;
		$sphinx['column_name']='id';
		$sphinx['where']='MATCH(@s_media_type("^Moving Image$" && ("^Moving Image |" | "| Moving Image$" | "| Moving Image |")))';
		$result=$this->sphnixrt->select('assets_list',$sphinx);
		debug($result);
		
	}

	/**
	 * Insert Stations in Sphnix Realtime Index.
	 * 
	 * @return none
	 */
	function insert_station_sphnix()
	{
		$stations = $this->station_model->get_all();
		foreach ($stations as $key => $row)
		{
			$record = make_station_sphnix_array($row);
			$this->sphnixrt->insert('stations', $record, $row->id);
		}
//		$data = $this->sphnixrt->select('stations', array('start' => 0, 'limit' => 1000));

		exit_function();
	}

	function make_map_array($value)
	{

		return $value->id;
	}

	/**
	 * Insert Stations in Sphnix Realtime Index.
	 * 
	 * @return none
	 */
	function insert_instantiations_sphnix()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB
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
				$this->sphnixrt->insert('instantiations_list', $data, $row->id);
			}
			$offset = $offset + 1000;
			if (count($inst) < 1000)
				$db_count ++;
		}

		exit_function();
	}

	function insert_assets_sphnix()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB

		$db_count = 0;
		$offset = 0;
		while ($db_count == 0)
		{
			$inst = $this->searchd_model->get_asset($offset, 1000);
			$ids = array_map(array($this, 'make_map_array'), $inst);
			$records = $this->searchd_model->get_asset_index($ids);
			foreach ($records as $row)
			{
				$data = make_assets_sphnix_array($row);
				$this->sphnixrt->insert('assets_list', $data, $row->id);
			}
			$offset = $offset + 1000;
			if (count($inst) < 1000)
				$db_count ++;
		}

		exit_function();
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */