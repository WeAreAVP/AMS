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

	/**
	 * Map ID from 2D arrary
	 * 
	 * @param array $value
	 * @return string/integer
	 */
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

	/**
	 * Use this function if you want to insert assets one by one in assets sphinx index.
	 */
	function insert_assets()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "4000M"); # 1GB
		@ini_set("max_execution_time", 999999999999);
		//4847801 then 4850341 also found this id installed in the next line: 4902235
		// 4849209
		$assets = $this->searchd_model->run_query('SELECT id from assets WHERE id > 4911756')->result();
		//$assets = $this->searchd_model->run_query('SELECT id from assets')->result();
		foreach ($assets as $asset)
		{
			myLog('using searchd.php function insert_assets');
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
		@ini_set("memory_limit", "8000M"); # 1GB
		@ini_set("max_execution_time", 999999999999);

		$db_count = 0;
		$offset = 0;
		while ($db_count == 0)
		{
			$inst = $this->searchd_model->get_asset($offset, 1000);
			myLog('Get 1000 records');
			$ids = array_map(array($this, 'make_map_array'), $inst);
			//mylog($ids);
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

	/**
	 * You need to add assets.id in an array that you want to update. and then run this script.
	 * 
	 */
	function update_assets_index()
	{
		/**
		* experiment to read values from a file
		$fh = fopen('/root/kcarter/assets_id2delete.csv','r');
		$asset_ids = fread($fh, filesize('/root/kcarter/assets_id2delete.csv'));
		*/

		$asset_ids = array(235089,235089,236450,236450,236450,236450,237065,237065,237234,237234,237419,237419,234970,234970,237005,237005,237322,237322,235251,235251,235251,235251,234950,234950,237092,237092,235228,235228,235228,235228,235070,235070,237172,237172,237440,237440,236490,236490,236490,236490,237396,237396,236961,236961,235015,235015,235269,235269,235269,235269,235108,235108,237298,237298,237191,237191,237357,237357,235032,235032,234890,234890,237377,237377,235313,235313,235313,235313,235289,235289,235289,235289,239214,235052,235052,234910,234910,237150,237150,237211,237211,237111,237111,237134,237134,236989,237026,237026,237255,237255,237811,237811,237278,237278,236410,236410,236410,236410,236984,236984,237337,237337,237047,237047);
	/**
	* modify what is commented here to control what becomes $asset_ids
	*
		$asset_ids = array(1, 2, 3, 4);
	*
	*/
		foreach ($asset_ids as $_id)
		{
			$asset = $this->searchd_model->run_query("SELECT id from assets WHERE id = {$_id}")->row();
			$asset_list = $this->searchd_model->get_asset_index(array($asset->id));
			$updated_asset_info = make_assets_sphnix_array($asset_list[0], FALSE);
			$this->sphnixrt->update($this->config->item('asset_index'), $updated_asset_info);
			myLog('Asset successfully update with id=> ' . $_id);
		}
	}

	/**
	 * You need to add instantiations.id in an array that you want to update. and then run this script.
	 * 
	 */
	function update_instantiations_index()
	{
		$instantiation_ids = array(2355135);
		foreach ($instantiation_ids as $_id)
		{
			$instantiation = $this->searchd_model->run_query("SELECT id from instantiations WHERE id = {$_id}")->row();
			$instantiation_list = $this->searchd_model->get_ins_index(array($instantiation->id));
			$new_list_info = make_instantiation_sphnix_array($instantiation_list[0], FALSE);
			$this->sphnixrt->update($this->config->item('instantiatiion_index'), $new_list_info);
			myLog('Instantiation successfully update with id=> ' . $_id);
		}
	}

}

// END Searchd Controller

// End of file searchd.php 
/* Location: ./application/controllers/searchd.php */
