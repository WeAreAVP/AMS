<?php

/**
 * Tracking Model
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
 * Tracking Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Tracking_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();

		$this->_prefix = '';
		$this->_table = 'tracking_info';
		$this->_table_station = 'stations';
	}

	/**
	 * Get tracking info of a station.
	 * 
	 * @param type $station_id station id
	 * @return stdObject
	 */
	function get_all($station_id)
	{
		$this->db->where('station_id', $station_id);
		return $this->db->get($this->_table)->result();
	}

	/**
	 * insert the records in tracing_info
	 * 
	 * @param array $data record info
	 * @return boolean 
	 */
	function insert_record($data)
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	/**
	 * Update the tracking info
	 * 
	 * @param integer $tracking_id tracking db id
	 * @param array   $data        record info
	 * @return boolean 
	 */
	function update_record($tracking_id, $data)
	{
		$this->db->where('id', $tracking_id);
		return $this->db->update($this->_table, $data);
	}

	/**
	 * Get Tracking info by ID
	 * 
	 * @param integer $traking_id  tracking db id
	 * @return array 
	 */
	function get_by_id($traking_id)
	{
		$this->db->where('id', $traking_id);
		return $this->db->get($this->_table)->row();
	}

	/**
	 * Delete the tracking info
	 * 
	 * @param integer $tracking_id tracking db id
	 * @return interger 
	 */
	function delete_record($tracking_id)
	{
		$this->db->where('id', $tracking_id);
		$this->db->delete($this->_table);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Get the last inserted tracking info of specified station
	 * 
	 * @param integer $station_id station db id
	 * @return Object 
	 */
	function get_last_tracking_info($station_id)
	{
		$this->db->where('station_id', $station_id);
		$this->db->order_by("id", "desc");
		return $this->db->get($this->_table)->row();
	}

}

