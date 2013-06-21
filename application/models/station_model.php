<?php

/**
 * Station Model
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Station Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Station_Model extends CI_Model
{
 
	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
		$this->_table = 'stations';
		$this->_table_backup = 'stations_backup';
		$this->_table_messages = 'messages';
		$this->_assets_table = 'assets';
		$this->_instantiations_table = 'instantiations';
		$this->_table_audit_trail = 'audit_trail';
	}

	/**
	 * Get list of all the stations
	 * 
	 * @return array 
	 */
	function get_all()
	{
		$this->db->order_by("station_name");
		return $query = $this->db->get($this->_table)->result();
	}

	/**
	 * Get Assets count and name of stations that have records
	 * 
	 * @return type 
	 */
	function get_asset_facet_stations()
	{
		$this->db->select("COUNT($this->_assets_table.id) as total,$this->_table.station_name", FALSE);
		$this->db->join($this->_table, "$this->_table.id = $this->_assets_table.stations_id");
		$this->db->group_by("$this->_assets_table.stations_id");
		$query = $this->db->get($this->_assets_table)->result();
		echo $this->db->last_query();
	}

	/**
	 * Get Instantitations count and name of stations that have records
	 * 
	 * @return type 
	 */
	function get_inst_facet_stations()
	{
		$this->db->select("COUNT($this->_instantiations_table.id) as total,$this->_table.station_name", FALSE);
		$this->db->join($this->_assets_table, "$this->_assets_table.id = $this->_instantiations_table.assets_id");
		$this->db->join($this->_table, "$this->_table.id = $this->_assets_table.stations_id");
		$this->db->group_by("$this->_table.id");
		return $query = $this->db->get($this->_instantiations_table)->result();
	}

	/**
	 * search station by station_id
	 * 
	 * @param type $station_id
	 * @return array 
	 */
	function get_station_by_id($station_id)
	{
		$this->db->where('id', $station_id);
		return $this->db->get($this->_table)->row();
	}

	/**
	 * search station by station_id
	 * 
	 * @param type $station_id
	 * @return array 
	 */
	function get_station_by_cpb_id($cpb_id)
	{
		$this->db->where('cpb_id', $cpb_id);
		$result = $this->db->get($this->_table);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return false;
	}

	/**
	 * Let the list of stations by staion id
	 * 
	 * @param type $stations
	 * @return array 
	 */
	function get_stations_by_id($stations)
	{
		$this->db->select('id,station_name,start_date,end_date,is_certified,is_agreed');
		$this->db->where_in('id', $stations);
		return $this->db->get($this->_table)->result();
	}

	/**
	 * Insert Station Records
	 * 
	 * @param array $data
	 * @return type 
	 */
	function insert_station($data)
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	/**
	 * update the stations record
	 * 
	 * @param type $station_id
	 * @param array $data
	 * @return boolean 
	 */
	function update_station($station_ids, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $station_ids);
		return $this->db->update($this->_table, $data);
	}

	/**
	 * Get count stations
	 * 
	 * @return integer 
	 */
	function get_station_count()
	{
		$query = $this->db->get($this->_table);
		return $query->num_rows;
	}

	/**
	 *
	 * Filter stations
	 * 
	 * @param type $certified
	 * @param type $agreed
	 * @return type 
	 */
	function apply_filter($certified, $agreed)
	{
		if (trim($certified) != '')
			$this->db->where('is_certified', $certified);
		if (trim($certified) != '')
			$this->db->where('is_agreed', $agreed);

		return $query = $this->db->get($this->_table)->result();
	}

	/**
	 *
	 * Truncate the stations bachup table
	 * 
	 * @return integer 
	 */
	function delete_stations_backup()
	{
		$this->db->empty_table($this->_table_backup);
		return $this->db->affected_rows() > 0;
	}

	/**
	 *
	 * insert the records in stations backup table 
	 * 
	 * @param array $data
	 * @return boolean 
	 */
	function insert_station_backup($data)
	{
		return $this->db->insert($this->_table_backup, $data);
	}

	/**
	 *
	 * Get list of all backup stations
	 * 
	 * @return array 
	 */
	function get_all_backup_stations()
	{
		return $query = $this->db->get($this->_table_backup)->result();
	}

	function insert_log($data)
	{
		$data['changed_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->_table_audit_trail, $data);
		return $this->db->insert_id();
	}

}

?>